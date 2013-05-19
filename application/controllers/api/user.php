 <?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-13
 * Time: 下午9:23
 * To change this template use File | Settings | File Templates.
 */
class user extends MY_Controller
{
    public function login()
    {
        $phone = trim($this->input->get_post('phone'));

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($phone)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserByPhone($phone, '*');
            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if (!checkMobile($phone)) {
                $response = error(10019);//手机号码格式错误
                break;
            }

            $code = rand(1000, 9999);
            $data = array('password' => $code);

            $s = $this->user->save($data, $uData['uid']);

            if (!$s) {
                $response = error(10020);//保存验证码错误
                break;
            }
            $message = '欢迎使用'.APP_NAME.'，本次登陆验证码：'.$code;
            $this->sendMessage($phone, $message);
        } while (false);

        $this->json_output($response);
    }
    /**
     * 登陆验证
     */
    public function login_verify()
    {
        $this->load->helper('validation');
        $phone = trim($this->input->get_post('phone'));
        $code = $this->input->get_post('code');

        $response = array('code' => '0', 'msg' => '验证成功');

        do {
            if (empty ($phone) || empty ($code)) {
                $response = error(10001);//参数不全
                break;
            }

            if (!checkMobile($phone)) {
                $response = error(10019);//手机号码格式错误
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserByPhone($phone, '*');

            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if ($uData['is_del'] == '1') {
                $response = error(10010);//用户已禁用
                break;
            }

            if (strtolower($uData['password']) != strtolower($code)) {
                $response = error(10022);//验证失败
                break;
            }

            $token = $this->generaToken($uData['uid'], $uData['uname'], $uData['password']);
            $response['data'] = $token;
        } while (false);

        $this->json_output($response);
    }

    /*

    public function login()
    {
        $this->load->helper('validation');
        $userName = trim($this->input->get_post('username'));
        $passWord = trim($this->input->get_post('password'));

        $response = array('code' => '0', 'msg' => '登陆成功');

        do {
            if (empty ($userName) || empty ($passWord)) {
                $response = error(10001);//参数不全
                break;
            }

            if (!is_username($userName)) {
                $response = error(10002);//用户名格式错误
                break;
            }

            if (!length_limit($passWord, 6, 32)) {
                $response = error(10003);//密码格式错误
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserByName($userName, '*');

            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if ($uData['password'] != md5($passWord)) {
                $response = error(10008);//密码错误
                break;
            }

            if ($uData['is_del'] == '1') {
                $response = error(10010);//用户已禁用
                break;
            }

            $token = $this->generaToken($uData['uid'], $uData['uname'], $uData['password']);
            $response['data'] = $token;
        } while (false);

        $this->json_output($response);
    }
     */

    /**
     * 注册
     */
    public function register()
    {
        $this->load->helper('validation');
        $response = array('code' => '0', 'msg' => '获取成功');

        $phone = trim($this->input->get_post('phone'));
        $userName = trim($this->input->get_post('username'));
        $recommend = $this->input->get_post('recommend');

        do {
            if ( empty ($phone)) {
                $response = error(10001);//参数不全
                break;
            }

            if (!checkMobile($phone)) {
                $response = error(10004);//手机号码格式错误
                break;
            }

            $this->load->model('model_user', 'user');
            $tmp = $this->user->getUserByPhone($phone);
            if (!empty ($tmp)) {
                $response = error(10045);//手机号码已注册
                break;
            }

            $code = rand(1000, 9999);
            $data = array(
                'uname' => empty ($userName) ? $phone : $userName,
                'password' => $code,
                'phone' => $phone,
                'is_del' => '1',
		'realname' => $phone,
            );

            $uId = $this->user->save($data);
            if (!$uId) {
                $response = error(10005);//用户注册失败
                break;
            }

            $message = '欢迎注册使用'.APP_NAME.'，您本次注册验证码：'.$code;
            $this->sendMessage($phone, $message);

            //$token = $this->generaToken($uId, $userName, $code);
            //$response['data'] = $token;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 注册验证
     */
    public function register_verify()
    {
        $phone = trim($this->input->get_post('phone'));
        $code = $this->input->get_post('code');

        $response = array('code' => '0', 'msg' => '验证成功');

        do {
            if (empty ($phone) || empty ($code)) {
                $response = error(10001);//参数不全
                break;
            }

            if (!checkMobile($phone)) {
                $response = error(10019);//手机号码格式错误
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserByPhone($phone);

            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if (strtolower($uData['password']) != strtolower($code)) {
                $response = error(10022);//验证失败
                break;
            }

            $s = $this->user->save(array('is_del' => '0'), $uData['uid']);
            if (!$s) {
                $response = error(10022);//验证失败
                break;
            }

            $token = $this->generaToken($uData['uid'], $uData['uname'], $uData['password']);
            $response['data'] = $token;
        } while (false);

        $this->json_output($response);
    }

    /*
    public function register()
    {
        $this->load->helper('validation');
        $response = array('code' => '0', 'msg' => '注册成功');

        $userName = trim($this->input->get_post('username'));
        $passWord = trim($this->input->get_post('password'));
        $phone = trim($this->input->get_post('phone'));

        do {
            if ( empty ($userName) || empty ($passWord) || empty ($phone) ) {
                $response = error(10001);//参数不全
                break;
            }

            if (!is_username($userName)) {
                $response = error(10002);//用户名格式错误
                break;
            }

            if (!length_limit($passWord, 6, 32)) {
                $response = error(10003);//密码格式错误
                break;
            }

            if (!checkMobile($phone)) {
                $response = error(10004);//手机号码格式错误
                break;
            }

            $this->load->model('model_user', 'user');
            $tmp = $this->user->getUserByPhone($phone);
            if (!empty ($tmp)) {
                $response = error(10045);//手机号码已注册
                break;
            }

            $uData = $this->user->getUserByName($userName);
            if ($uData) {
                $response = error(10006);//用户已存在
                break;
            }

            $passWord = md5($passWord);
            $data = array(
                'uname' => $userName,
                'password' => $passWord,
                'phone' => $phone,
            );

            $uId = $this->user->save($data);
            if (!$uId) {
                $response = error(10005);//用户注册失败
                break;
            }

            $token = $this->generaToken($uId, $userName, $passWord);
            $response['data'] = $token;
        } while (false);

        $this->json_output($response);
    }
     */
    /**
     * 重置密码
     */
    public function resetPassword()
    {

    }

    /**
     * 用户详情
     */
    public function info()
    {
        $token = trim($this->input->get_post('token'));
        $response = array('code' => '0', 'msg' => '获取成功');
        do {
            if (empty ($token)) {
                $response = error(10001);//参数不全
                break;
            }

            $uInfo = $this->analyzeToken($token);
            if (!$uInfo) {
                $response = error(10011);//用户未登陆
                break;
            }

            $uId = $uInfo[0];
            if (!$uId) {
                $response = error(10009);//错误的token
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserById($uId, 'uid, uname, password, realname, amount, sex, phone,binding_type, is_del, create_time');
            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if ($uData['password'] != $uInfo[2]) {
                $response = error(10008);//密码错误
                break;
            }

            if ($uData['is_del'] == '1') {
                $response = error(10010);//用户已禁用
                break;
            }

            unset($uData['password'], $uData['is_del']);

            //$this->load->model('model_invoice', 'user');
            $invoice = $this->user->getInvoiceByUid($uId);

            $this->load->model('model_card', 'card');
            $card = $this->card->getCardByuId($uId);

            $uData['invoice'] = $invoice;
            $uData['card'] = $card;
            $response['data'] = $uData;
        } while (false);

        $this->json_output($response);
    }



    /**11
     * 验证码
     */
    public function verificationCode()
    {

    }
}
