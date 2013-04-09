<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-15
 * Time: 下午10:02
 * To change this template use File | Settings | File Templates.
 */
class chauffeur extends MY_Controller
{
    /**
     * 获取司机当前位置
     */
    public function chauffeurCurrentLocation()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($chauffeurId)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $locationData = $this->chauffeur->isCurrentLocation($chauffeurId);
            $response['data'] = $locationData;
        } while (false);

        $this->json_output($response);
    }


    /**
     * 回报当前所在位置
     */
    public function reportCurrentLocation()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $longitude = $this->input->get_post('longitude');
        $latitude = $this->input->get_post('latitude');

        $response = array('code' => '0', 'msg' => '回报成功');

        do {
            if (empty ($chauffeurId) || empty ($longitude) || empty ($latitude)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $chauffeurData = $this->chauffeur->getChauffeurById($chauffeurId);
            if (empty ($chauffeurData)) {
                $response = error(10012);//司机不存在
                break;
            }

            $data = array(
                'chauffeur_id' => $chauffeurId,
                'city_id' => $chauffeurData['city_id'],
                'longitude' => $longitude,
                'latitude' => $latitude,
            );
            $status = $this->chauffeur->saveCurrentLocation($data);

            if (!$status) {
                $response = error(10013);//回报位置失败
                break;
            }
        } while (false);

        $this->json_output($response);
    }

    /**
     * 修改司机信息
     */
    public function modifyChauffeur()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));
        $realname = $this->input->get_post('realname');
        $sex = intval($this->input->get_post('sex'));
        $idCard = intval($this->input->get_post('id_card'));
        $cityId = intval($this->input->get_post('city_id'));
        $carId = intval($this->input->get_post('car_id'));
        $carNo = $this->input->get_post('car_no');
        $status = intval($this->input->get_post('status'));
        $descr = $this->input->get_post('descr');

        $response = array('code' => '0', 'msg' => '修改成功');

        do {
            if (empty ($chauffeurId)) {
                $response = error(10001);//参数不全
                break;
            }

            $where = array();
            $realname && $where['realname'] = $realname;
            $sex && $where['sex'] = $sex;
            $idCard && $where['id_card'] = $idCard;
            $cityId && $where['city_id'] = $cityId;
            $carId && $where['car_id'] = $carId;
            $carNo && $where['car_no'] = $carNo;
            ($status || $status == '0') && $where['status'] = $status;
            $descr && $where['descr'] = $descr;

            if ($where) {
                $this->load->model('model_chauffeur', 'chauffeur');
                $this->chauffeur->save($where, $chauffeurId);
            }

        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机登陆
     */
    public function login()
    {
        $phone = $this->input->get_post('phone');

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($phone)) {
                $response = error(10001);//参数不全
                break;
            }

            if (!checkMobile($phone)) {
                $response = error(10019);//手机号码格式错误
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $field = 'chauffeur_id, cname, realname, sex, phone, id_card, car_id, city_id, car_no, descr, status';
            $data = $this->chauffeur->getChauffeurByPhone($phone, $field);
            if (empty ($data)) {
                $response = error(10012);//司机不存在
                break;
            }

            $code = rand(100000, 999999);
            $data = array(
                'phone' => $phone,
                'verify_code' => $code,
            );

            $s = $this->chauffeur->verifySave($data);

            if (!$s) {
                $response = error(10020);//保存验证码错误
                break;
            }
            $message = '欢迎使用'.APP_NAME.'，您本次登陆验证码：'.$code;
            $this->sendMessage($phone, $message);
        } while (false);

        $this->json_output($response);
    }

    /**
     * 司机登陆验证
     */
    public function login_verify()
    {
        $phone = $this->input->get_post('phone');
        $code = $this->input->get_post('code');

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($phone) || empty ($code)) {
                $response = error(10001);//参数不全
                break;
            }

            if (!checkMobile($phone)) {
                $response = error(10019);//手机号码格式错误
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $s = $this->chauffeur->getVerify($phone);

            if (!$s) {
                $response = error(10021);//验证信息不存在
                break;
            }

            if (strtolower($s['verify_code']) != strtolower($code)) {
                $response = error(10022);//验证失败
                break;
            }

            $field = 'chauffeur_id, cname, realname, sex, phone, id_card, car_id, city_id, car_no, descr, status';
            $data = $this->chauffeur->getChauffeurByPhone($phone, $field);
            if (empty ($data)) {
                $response = error(10012);//司机不存在
                break;
            }
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }
}
