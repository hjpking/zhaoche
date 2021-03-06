<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-13
 * Time: 下午9:03
 * To change this template use File | Settings | File Templates.
 */
class car extends MY_Controller
{
    public function test()
    {
        $a = array('0' => '0', '1' => '1');
        $b = array('0' => '1', '2' => '2');

        p($a+$b);
        p(array_merge($a,$b));


		//$status = array(0,1,4,7);
		//p($status);
		//echo json_encode($status);
        //echo $this->sendMessage('13520740166', '赵磊收');
        //echo '<br>'.date('Y-m-d H:i:s');
        //$this->sendMessage('13811508022', '王振生收');
        /*/
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


        //$client = new Client();
        //$client->setOutgoingEncoding("utf-8");
        //$statusCode = $client->login();
//echo $statusCode;exit;

        $this->load->library('client');
        $this->client->clients($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
        $this->client->setOutgoingEncoding("utf-8");
        echo $statusCode = $this->client->sendSMS(array('13811508022'), "老郭收");
        echo '<br>'.date('Y-m-d H:i:s', TIMESTAMP);
        //echo $statusCode = $this->client->login();
        //echo sendMessage('13811508022', '王振生收');
        //*/
    }
    /**39.903021,116.440487
     * 获取周围车辆 -- 通过GPS
     */
    public function getAroundCar()
    {
        $longitude = trim($this->input->get_post('longitude'));//经度
        $latitude = trim($this->input->get_post('latitude'));//纬度
        $cityId = intval($this->input->get_post('city_id'));//城市ID

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($longitude) || empty ($latitude) || empty ($cityId)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_city', 'city');
            $cityData = $this->city->getCityById($cityId);
            if (!$cityData) {
                $response = error(10014);//城市不存在
                break;
            }

            $this->load->model('model_chauffeur', 'chauffeur');
            $currData = $this->chauffeur->getCurrentLocationByCityId(1000, 0, '*', array('city_id' => $cityId), 'update_time desc');

            $recentDistance = 1000000;//默认给一个非常远的距离
            foreach ($currData as &$v) {
                $v['distance'] = getDistance($latitude,$longitude,$v['latitude'],$v['longitude']);

                $recentDistance = ($recentDistance > $v['distance']) ? $v['distance'] : $recentDistance;
            }
            //echo $recentDistance;p($currData);
            $data = array(
                'recent_distance' => $recentDistance,
                'arrival_time' => ceil($recentDistance / 500),
                'car_total' => count($currData),
                'success_rate' => (count($currData) > 20) ? 100 : rand(50, 99),
                'location_list' => $currData,
            );
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }

    /**3
     * 获取可选车辆
     */
    public function getOptionalCar()
    {
        $cityId = $this->input->get_post('city_id');
        $serviceType = $this->input->get_post('service_type');


        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($serviceType) || empty ($cityId)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_city', 'city');
            $cityData = $this->city->getCityById($cityId);
            if (!$cityData) {
                $response = error(10014);//城市不存在
                break;
            }

            $this->load->model('model_service_type', 'st');
            $sfData = $this->st->getServiceTypeById($serviceType);
            if (!$sfData) {
                $response = error(10015);//服务类别不存在
                break;
            }

            $this->load->model('model_car', 'car');
            $carLevelData = $this->car->getCarLevel();

            $this->load->model('model_rule', 'rule');
            $data = $this->rule->getRule(20, 0, '*', array('sid' => $serviceType, 'city_id' => $cityId), 'base_price');
            foreach ($data as &$v) {
                $v['car_model_name'] = isset($carLevelData[$v['lid']]['name']) ? $carLevelData[$v['lid']]['name'] : '';
            }
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 获取车车型
     */
    public function getCar()
    {
        $response = array('code' => '0', 'msg' => '获取成功');

        $this->load->model('model_car', 'car');
        $data = $this->car->getCar(10000, 0, 'car_id, lid, name, descr, create_time', array('is_car_model' => '1'), null, false);
        $response['data'] = $data;

        $this->json_output($response);
    }

    /**
     * 获取服务
     */
    public function getService()
    {
        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            $this->load->model('model_service_type', 'st');
            $data = $this->st->getServiceType();
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 获取车辆级别
     */
    public function getCarLevel()
    {
        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            $this->load->model('model_car', 'car');
            $data = $this->car->getCarLevel(1000);
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);

    }
}
