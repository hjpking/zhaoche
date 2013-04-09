<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-5
 * Time: 下午1:53
 * To change this template use File | Settings | File Templates.
 */
class rule extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->helper('url');
        $Limit = 20;
        $currentPage = $this->uri->segment(3, 1);
        $offset = ($currentPage - 1) * $Limit;

        $city_id = $this->input->get_post('city_id');
        $sId = $this->input->get_post('sid');
        $lId = $this->input->get_post('lid');

        $where = array('is_del' => '0');
        $city_id && $where['city_id'] = $city_id;
        $sId && $where['sid'] = $sId;
        $lId && $where['lid'] = $lId;

        $this->load->model('model_rule', 'rule');
        $totalNum = $this->rule->getRuleCount($where);
        $ruleInfo = $this->rule->getRule($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/rule/index');
            $where && $config['suffix'] = ('?' . http_build_query($where));
            $config['total_rows'] = $totalNum;
            $config['per_page'] = $Limit;
            $config['num_links'] = 10;
            $config['uri_segment'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config['anchor_class'] = 'class="number"';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';

            $config['full_tag_open'] = '<li>';
            $config['full_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';

            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            $config['cur_tag_open'] = '<li class="active"><a>';
            $config['cur_tag_close'] = '</a></li>';

            $this->pagination->initialize($config);
            $pageHtml = $this->pagination->create_links();
        }




        //$ruleInfo = $this->rule->getRule(10000);

        $this->load->model('model_service_type', 'st');
        $sfInfo = $this->st->getServiceType(1000);

        $this->load->model('model_car', 'car');
        $carLevelInfo = $this->car->getCarLevel(1000);

        $this->load->model('model_city', 'city');
        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array(
            'rule_info' => $ruleInfo,
            'sf_info' => $sfInfo,
            'carLevelInfo' => $carLevelInfo,
            'cityInfo' => $city,
            'pageHtml' => $pageHtml,
            'city_id' => $city_id,
            'sId' => $sId,
            'lId' => $lId,
            //'ruleData' => $ruleData,
        );
        $this->load->view('rule/index', $data);
    }

    public function create()
    {
        $this->load->model('model_service_type', 'st');
        $sfInfo = $this->st->getServiceType(1000);

        $this->load->model('model_car', 'car');
        $carLevelInfo = $this->car->getCarLevel(1000);

        $this->load->model('model_city', 'city');
        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array(
            'sf_info' => $sfInfo,
            'carLevelInfo' => $carLevelInfo,
            'cityInfo' => $city,
            //'ruleData' => $ruleData,
        );

        $this->load->view('rule/create', $data);
    }

    public function edit()
    {
        $ruleId = $this->uri->segment(3);

        if (empty ($ruleId)) {
            show_error('计费规则ID为空!');
        }

        $this->load->model('model_rule', 'rule');
        $ruleInfo = $this->rule->getRuleById($ruleId);

        $this->load->model('model_service_type', 'st');
        $sfInfo = $this->st->getServiceType(1000);

        $this->load->model('model_car', 'car');
        $carLevelInfo = $this->car->getCarLevel(1000);

        $this->load->model('model_city', 'city');
        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array(
            'sf_info' => $sfInfo,
            'carLevelInfo' => $carLevelInfo,
            'cityInfo' => $city,
            'data' => $ruleInfo,
        );

        $this->load->view('rule/create', $data);
    }

    public function save()
    {
        $cityId = intval($this->input->get_post('city_id'));
        $sId = intval($this->input->get_post('sid'));
        $lId = intval($this->input->get_post('lid'));
        $basePrice = intval($this->input->get_post('base_price'));
        $basePrice = fPrice($basePrice, 4);
        $kmPrice = intval($this->input->get_post('km_price'));
        $kmPrice = fPrice($kmPrice, 4);
        $timePrice = intval($this->input->get_post('time_price'));
        $timePrice = fPrice($timePrice, 4);
        $timeInt = intval($this->input->get_post('time_int'));
        $nightServiceCharge = intval($this->input->get_post('night_service_charge'));
        $nightServiceCharge = fPrice($nightServiceCharge, 4);
        $kongshiFee = intval($this->input->get_post('kongshi_fee'));
        $kongshiFee = fPrice($kongshiFee, 4);
        $descr = $this->input->get_post('descr');
        $ruleId = intval($this->input->get_post('rule_id'));

        if (empty ($cityId) || empty ($sId) || empty ($lId) || empty ($basePrice) || empty ($kmPrice) || empty ($timePrice) || empty ($timeInt) ) {
            show_error('参数不全!');
        }

        $data = array(
            'city_id' => $cityId,
            'sid' => $sId,
            'lid' => $lId,
            'base_price' => $basePrice,
            'km_price' => $kmPrice,
            'time_price' => $timePrice,
            'time' => $timeInt,
            'night_service_charge' => $nightServiceCharge,
            'kongshi_fee' => $kongshiFee,
            'descr' => $descr,
        );

        $this->load->model('model_rule', 'rule');

        if (!$ruleId) {
            $ruleData = $this->rule->getRule(1, 0, '*', array('sid' => $sId, 'lid' => $lId, 'city_id' => $cityId));
            if (!empty ($ruleData)) {
                show_error('一个城市、一个服务类别、一个车辆级别只能拥有一条计费规则！');
            }
        }
        //$ruleData = $this->rule->getRuleById($carType, '*', array('sid' => $serviceType));

        $this->rule->save($data, $ruleId);

        $this->load->helper('url');
        redirect('rule/index');
    }

    public function delete()
    {
        $ruleId = $this->uri->segment(3);

        if (empty ($ruleId)) {
            show_error('计费规则ID为空!');
        }

        $this->load->model('model_rule', 'rule');
        //$status = $this->cf->isAlone($ruleId);

        $this->rule->delete($ruleId, 1);

        $this->load->helper('url');
        redirect('rule/index/');
    }
}
