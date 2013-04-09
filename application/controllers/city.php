<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-5
 * Time: 下午2:11
 * To change this template use File | Settings | File Templates.
 */
class city extends MY_Controller
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

        $cityCode = $this->input->get_post('city_code');

        $where = array('is_del' => '0');
        $cityCode && $where['city_code'] = $cityCode;

        $this->load->model('model_city', 'city');

        $totalNum = $this->city->getCityCount($where);
        if (isset ($where['city_code'])) {
            $cityData = $this->city->getCity($Limit, $offset, '*', $where, null, false);
        } else {
            $cityData = $this->city->getCity($Limit, $offset, '*', $where);
        }

        //echo '<pre>';print_r($cityData);exit;
        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/city/index');
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

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array(
            'city_data' => $cityData,
            'pageHtml' => $pageHtml,
            'city_code' => $cityCode,
            'city' => $city,
        );
        $this->load->view('city/index', $data);
    }

    public function create()
    {
        $this->load->model('model_city', 'city');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array('city' => $city);
        $this->load->view('city/create', $data);
    }

    public function save()
    {
        $cityName = $this->input->get_post('city_name');
        $cityCode = $this->input->get_post('city_code');
        $parentId = $this->input->get_post('parent_id');
        $isCity = $this->input->get_post('is_city');
        $descr = $this->input->get_post('descr');
        $cityId = $this->input->get_post('city_id');

        $data = array(
            'city_name' => $cityName,
            'city_code' => $cityCode,
            'parent_id' => $parentId,
            'is_city' => $isCity,
            'descr' => $descr,
        );

        $this->load->model('model_city', 'city');
        $this->city->save($data, $cityId);

        $this->load->helper('url');
        redirect('city/index', 'refresh');
    }

    public function edit()
    {
        $cityId = $this->uri->segment(3);

        $this->load->model('model_city', 'city');
        $cityData = $this->city->getCityById($cityId);

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));
//echo '<pre>';print_r($cityData);exit;

        $data = array('data' => $cityData, 'city' => $city);
        $this->load->view('city/create', $data);
    }

    public function delete()
    {
        $cityId = $this->uri->segment(3, 1);

        $this->load->model('model_city', 'city');
        $status = $this->city->isAlone($cityId);

        if ($status) {
            $this->city->delete($cityId, 1);

            $this->load->helper('url');
            redirect('/city/index/');
        }

        show_error('还有下级城市!');
    }

    public function useful_index()
    {
        $this->load->helper('url');

        $Limit = 20;
        $currentPage = $this->uri->segment(3, 1);
        $offset = ($currentPage - 1) * $Limit;

        $cityId = $this->input->get_post('city_id');

        $where = array();
        $cityId && $where['city_id'] = $cityId;

        $this->load->model('model_city', 'city');

        $totalNum = $this->city->getUsefulCount($where);
        $usefulData = $this->city->getUseful($Limit, $offset, '*', $where);

        //echo '<pre>';print_r($cityData);exit;
        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/city/useful_index');
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

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array(
            'useful_data' => $usefulData,
            'pageHtml' => $pageHtml,
            'city_id' => $cityId,
            'city' => $city,
        );
        $this->load->view('city/useful_index', $data);
    }

    public function useful_create()
    {
        $this->load->model('model_city', 'city');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array('city' => $city);
        $this->load->view('city/useful_create', $data);
    }

    public function useful_edit()
    {
        $usefulId = $this->uri->segment(3);

        $this->load->model('model_city', 'city');
        $cityData = $this->city->getUsefulById($usefulId);

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array('data' => $cityData, 'city' => $city);
        $this->load->view('city/useful_create', $data);
    }

    public function useful_save()
    {
        $name = $this->input->get_post('name');
        $cityId = $this->input->get_post('city_id');
        $descr = $this->input->get_post('descr');
        $longitude = $this->input->get_post('longitude');
        //$latitude = $this->input->get_post('latitude');
        $uaId = $this->input->get_post('ua_id');

        $tmp = explode(",", $longitude);
        $longitude = $tmp[0];
        $latitude = $tmp[1];

        $data = array(
            'name' => $name,
            'city_id' => $cityId,
            'descr' => $descr,
            'longitude' => $longitude,
            'latitude' => $latitude,
        );

        $this->load->model('model_city', 'city');
        $this->city->usefulSave($data, $uaId);

        $this->load->helper('url');
        redirect('city/useful_index');
    }

    public function useful_delete()
    {
        $uaId = $this->uri->segment(3, 1);

        $this->load->model('model_city', 'city');

        $this->city->usefulDelete($uaId, 0);

        $this->load->helper('url');
        redirect('city/useful_index');
    }

    public function airport_index()
    {
        $this->load->helper('url');

        $Limit = 20;
        $currentPage = $this->uri->segment(3, 1);
        $offset = ($currentPage - 1) * $Limit;

        $cityId = $this->input->get_post('city_id');

        $where = array();
        $cityId && $where['city_id'] = $cityId;

        $this->load->model('model_city', 'city');

        $totalNum = $this->city->getAirportCount($where);
        $data = $this->city->getAirport($Limit, $offset, '*', $where);

        //echo '<pre>';print_r($cityData);exit;
        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/city/airport_index');
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

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array(
            'useful_data' => $data,
            'pageHtml' => $pageHtml,
            'city_id' => $cityId,
            'city' => $city,
        );
        $this->load->view('city/airport_index', $data);
    }

    public function airport_create()
    {
        $this->load->model('model_city', 'city');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array('city' => $city);
        $this->load->view('city/airport_create', $data);
    }

    public function airport_edit()
    {
        $id = $this->uri->segment(3);

        $this->load->model('model_city', 'city');
        $data = $this->city->getAirportById($id);

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array('data' => $data, 'city' => $city);
        $this->load->view('city/airport_create', $data);
    }

    public function airport_save()
    {
        $name = $this->input->get_post('name');
        $cityId = $this->input->get_post('city_id');
        $longitude = $this->input->get_post('longitude');
        $latitude = $this->input->get_post('latitude');
        $id = $this->input->get_post('id');

        $data = array(
            'airport_name' => $name,
            'city_id' => $cityId,
            'longitude' => $longitude,
            'latitude' => $latitude,
        );

        $this->load->model('model_city', 'city');
        $this->city->airportSave($data, $id);

        $this->load->helper('url');
        redirect('city/airport_index');
    }

    public function airport_delete()
    {
        $id = $this->uri->segment(3, 1);

        $this->load->model('model_city', 'city');

        $this->city->airportDelete($id, 0);

        $this->load->helper('url');
        redirect('city/airport_index');
    }
}
