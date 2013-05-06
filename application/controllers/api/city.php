<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-13
 * Time: 下午9:10
 * To change this template use File | Settings | File Templates.
 */
class city extends MY_Controller
{
    /**2
     * 获取可选城市
     */
    public function getCity()
    {
        $msg = array('code' => '0', 'msg' => '获取成功', 'data' => '');

        $limit = 20;
        $offset = 0;
        $number = $this->input->get_post('limit');
        $start  = $this->input->get_post('offset');
        $cityId = $this->input->get_post('city_id');

        $where = array('is_del' => '0', 'is_city' => '1');
        $cityId && $where['city_id'] = $cityId;
        $number && $limit = $number;
        $start && $offset = $start;

        $this->load->model('model_city', 'city');
        $cityData = $this->city->getCity($limit, $offset, 'city_id, city_name', $where, null, false);
        $msg['data'] = $cityData;

        $this->json_output($msg);
    }

    /**
     * 获取城市机场信息
     */
    public function getCityAirport()
    {
        $cityId = $this->input->get_post('city_id');
        //$date = $this->input->get_post('date');

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($cityId)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_city', 'city');
            $cityData = $this->city->getCityById($cityId);
            if (!$cityData) {
                $response = error(10014);//城市不存在
                break;
            }

            $data = $this->city->getAirport(100, 0, '*', array('city_id' => $cityId));
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 获取航班信息
     */
    public function getFlight()
    {
        $flightNo = $this->input->get_post('flight_no');
        $date = $this->input->get_post('date');

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($flightNo) || empty ($date)) {
                $response = error(10001);//参数不全
                break;
            }

            $date = date('Y-m-d', strtotime($date));

            $url = 'http://fd2.tripnew.com/Test/FlightNo.aspx?FlightNo='.$flightNo.'&BeginDate='.$date.'&Key=5BD566E2C39A1909';
            $xmlData = file_get_contents($url);

            $this->load->library('xml');
            $flightData = $this->xml->createArray($xmlData);

            $data = isset ($flightData['avlist']['FlightInfo']) ? $flightData['avlist']['FlightInfo'] : array();
            if (!empty($data)) {
				//$data['longitude'] = '40.077415';
				//$data['latitude'] = '116.591549';
			}

            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 推荐下车地址
     */
    public function recommendGetOffAddress()
    {
        $limit = 20;
        $offset = 0;
        $cityId = $this->input->get_post('city_id');
        $number = $this->input->get_post('limit');
        $start = $this->input->get_post('offset');

        $number && $limit = $number;
        $start && $offset = $start;

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($cityId)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_city', 'city');
            $data = $this->city->getUsefulByCityId($cityId, $limit, $offset);
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 推荐下车地址搜索
     */
    public function recommendAddressSearch()
    {
        $limit = 20;
        $offset = 0;

        $cityId = intval($this->input->get_post('city_id'));
        $keyWord = $this->input->get_post('keyword');
        $number = $this->input->get_post('limit');
        $start = $this->input->get_post('offset');

        $number && $limit = $number;
        $start && $offset = $start;

        $response = array('code' => '0', 'msg' => '搜索成功');

        do {
            if (empty ($cityId)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_city', 'city');
            $data = $this->city->searchUseFul($cityId, $keyWord, $limit, $offset);
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }
}
