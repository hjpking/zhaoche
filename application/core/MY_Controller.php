<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 12-6-4
 * Time: 下午3:49
 * To change this template use File | Settings | File Templates.
 */
class MY_Controller extends CI_Controller
{
    public $competenceList = array();

    function __construct()
    {
        parent::__construct();

        $urlApi = $this->uri->segment(1);
        if ($urlApi !== 'api') {
            if (!$this->AdminIsLogin()) {
                $this->load->helper('url');
                redirect('/login/index');
            }

            //*权限检测开始/
            $this->load->model('model_competence_correspond', 'cc');
            $userCcData = $this->cc->getUserCompetence($this->amInfo['staff_id'], '*', null, 'competence_id');

            $functionModule = config_item('view_nav');
            foreach ($functionModule as $k=>$v) {
                if (!array_key_exists($k, $userCcData)) {
                    unset ($functionModule[$k]);
                    continue;
                }

                foreach ($v['links'] as $lk=>$lv) {
                    if (!array_key_exists($lk, $userCcData)) {
                        //p($functionModule[$k]['links'][$lk]);
                        unset ($functionModule[$k]['links'][$lk]);//echo $functionModule[$k]['links'][$lk].'<br>';
                    }
                }
            }

            $this->competenceList = $functionModule;//p($functionModule);exit;
            //权限检测结束*/
        }
    }

    /**
     * 减少重复载入相同库步骤;
     * @param $name
     * @param null $params
     */
    protected function lib($name, $params = NULL)
    {
        static $lib;
        if (!isset($lib[$name])) {
            $lib[$name] = true;
            $this->load->library($name, $params);
        }
    }

    /**
     * 是否为POST请求
     *
     * @return bool
     */
    protected function isPOST()
    {
        return $this->input->server('REQUEST_METHOD') === 'POST';
    }

    /**
     * 从cookie中获取用户登录信息
     * @return array | NULL
     */
    protected function getUserinfoForCookie()
    {
        $this->load->helper('cookie');
        $auth =  get_cookie('auth');
        if($auth)
            return explode("\t", authcode($auth, 'DECODE'));
    }

    /**
     * 用户是否登陆
     *
     * @access    public
     * @return    void
     */
    protected function isLogin()
    {
        $userInfo = $this->getUserinfoForCookie();
        if(! $userInfo)
            return false;

        $uid = $userInfo[0];
        $password = $userInfo[2];

        if (empty ($uid) || empty ($password)) {
            return false;
        }

        $this->load->model('user/Model_User', 'user');
        $uInfo = $this->user->getUserById($uid);

        if (! $uInfo || $uInfo['password'] != $password) {
            return false;
        }
        $this->uInfo = $uInfo;
        return true;
    }

    /**
     * 检测后台用户是否登陆
     *
     * @return bool
     */
    protected function AdminIsLogin()
    {
        //$prefix = config_item('cookie_prefix');
        //$cUserName = $this->input->cookie($prefix . 'username');
        $this->load->helper('cookie');
        $auth =  get_cookie('admin_auth');
        if($auth)
        {
            $am = explode("\t", authcode($auth, 'DECODE'));
            $this->load->model('model_staff', 'staff');
            $uinfo = $this->staff->getStaffById($am[0]);
            if (isset($uinfo) && $uinfo['password'] === $am[2]) {
                $this->amInfo = $uinfo;
                return true;
            }
        }
        return false;
    }

    /**
     * 输出JSON数据
     *
     * @static
     * @param $data
     * @param bool $jsonp
     * @return mixed
     */
    static protected function json_output($data, $jsonp = false)
    {
        if($jsonp && isset($_GET['callback']))
        {
            $callback = $_GET['callback'];
            echo "{$callback}(",json_encode($data),")";
            return ;
        }
        echo json_encode($data);
        return;
    }

    protected function cache_view($match='')
    {
        $key = "{$this->uri->rsegment(1)}@{$this->uri->rsegment(2)}";
        $life = isset($this->config->config['cache_view'][$key]) ? $this->config->config['cache_view'][$key] : 0;
        if($life > 0  && ! $match)
            return false;
        if (preg_match('#^' . $match . '$#', $this->uri->uri_string())) {
            $this->output->cache($life);
            return true;
        }
        return false;
    }

    /**
     * 检查请求是否过期
     * @return mixed
     */
    protected function HTTPLastModified()
    {
        $IF_MODIFIED_SINCE = $this->input->server('HTTP_IF_MODIFIED_SINCE');
        if($IF_MODIFIED_SINCE !== false
            && (TIMESTAMP - (strtotime($IF_MODIFIED_SINCE) ) < config_item('http_expires'))) //(当前时间减去最后修改时间) < 不满过期周期
        {
            $this->output->set_status_header(304);
            die;
        }
        else
        {
            $Last_Modified = gmdate('D, d M Y H:i:s', TIMESTAMP) . ' GMT'; //修改时间
            $Expires = gmdate('D, d M Y H:i:s', TIMESTAMP + config_item('http_expires')) . ' GMT'; //过期时间
            $this->output->set_header('Last-Modified: ' . $Last_Modified);
            $this->output->set_header('Expires: ' . $Expires);
        }
        return ;
    }

    /**
     * 生成token
     *
     * @param $uId
     * @param $userName
     * @param $passWord
     * @return string
     */
    protected function generaToken($uId, $userName, $passWord)
    {
        $token = authcode("{$uId}\t{$userName}\t{$passWord}", 'ENCODE');

        $tokenKey = md5($uId.$userName.$passWord);
        $data = array(
            'token_key' => $tokenKey,
            'token' => $token,
        );

        $this->load->model('model_other', 'other');
        $status = $this->other->saveToken($data);
        if (!$status) {
            return false ;
        }

        return $tokenKey;
    }

    /**
     * 解析token
     *
     * @param $tokenLKey
     * @return array
     */
    protected function analyzeToken($tokenLKey)
    {
        $this->load->model('model_other', 'other');
        $tokenData = $this->other->getTokenByTokenKey($tokenLKey);
        if (empty ($tokenData)) {
            return false;
        }

        $tokenInfo = explode("\t", authcode($tokenData['token'], 'DECODE'));

        return $tokenInfo;
    }

    /**
     * 发送短信
     *
     * @param $phoneNumber
     * @param $content
     * @return mixed|string
     */
    public function sendMessage($phoneNumber, $content)
    {
        $arr = array(
            'PHONE' => $phoneNumber,
            'MOID' => '0',
            'MSG' => $content,
            'PRODUCTID' => '',
            'AUTHCODE' => '0000',
        );
        return vPost('', http_build_query($arr));
    }
    /*/
    public function sendMessage($phoneNumber, $content)
    {
        $content = '【'.$content.'】';
        $gwUrl = 'http://sdkhttp.eucp.b2m.cn/sdk/SDKService?wsdl';
        $serialNumber = '0SDK-EBB-0130-NETLK';
        $password = '119165';
        $sessionKey = '123456';
        $connectTimeOut = 2;
        $readTimeOut = 10;

        $proxyhost = false;
        $proxyport = false;
        $proxyusername = false;
        $proxypassword = false;

        $this->load->library('client');
        $this->client->clients($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
        $this->client->setOutgoingEncoding("utf-8");
        return $statusCode = $this->client->sendSMS(array($phoneNumber), $content);
    }
    //*/

    /*/发送短信
    function sendMessage($phoneNumber, $content, $taskName = '')
    {
        $content = '['.$content.']';

        $data = array(
            'userid' => '272',
            'account' => 'PF091',
            'password' => 'abc12345',
            'mobile' => $phoneNumber,
            'content' => $content. APP_NAME,
            'sendTime' => date('Y-m-d H:i:s', TIMESTAMP),
            'checkcontent' => '0',
            'taskName' => $taskName,
            'countnumber' => 1,
            'mobilenumber' => 1,
            'telephonenumber' => 0,
        );
    //echo 'http://p1.ipyy.com:8888/sms.aspx?action=send'.http_build_query($data);exit;
        return vPost('http://p1.ipyy.com:8888/sms.aspx?action=send', http_build_query($data));



        $data = array(
            'action' => 'send',
            'userid' => M_ID,
            'account' => M_ACCOUNT,
            'password' => M_PASSWORD,
            'mobile' => $phoneNumber,
            'content' => $content,
            'sendtime' => date('Y-m-d H:i:s'),
            'checkcontent' => '0',
            'taskname' => $taskName,
            'countnumber' => '1',
            'mobilenumber' => '1',
            'telephonenumber' => '',
        );

        return vPost(M_URL, http_build_query($data));

    }
    //*/
}
