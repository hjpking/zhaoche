<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-3
 * Time: 上午12:22
 * To change this template use File | Settings | File Templates.
 */
class chauffeur extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $isDelStatus = $this->uri->segment(3);

        $Limit = 20;
        $currentPage = $this->uri->segment(4, 1);
        $offset = ($currentPage - 1) * $Limit;

        $this->load->helper('url');
        $this->load->model('model_city', 'city');
        $this->load->model('model_car', 'car');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));
        $car = $this->car->getCar(10000);

        $where = array('is_del' => $isDelStatus);
        $cname = $this->input->get_post('cname');
        $cityId = $this->input->get_post('city_id');
        $car_model = $this->input->get_post('car_id');
        $status = $this->input->get_post('status');

        $cname && $where['cname'] = $cname;
        $cityId && $where['city_id'] = $cityId;
        $car_model && $where['car_id'] = $car_model;
        ($status || $status === '0')  && $where['status'] = $status;

        $this->load->model('model_order', 'order');
        $this->load->model('model_chauffeur', 'cf');
        $totalNum = $this->cf->getChauffeurCount($where);
        $carInfo = $this->cf->getChauffeur($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/chauffeur/index/'.$isDelStatus);
            $where && $config['suffix'] = ('?' . http_build_query($where));
            $config['total_rows'] = $totalNum;
            $config['per_page'] = $Limit;
            $config['num_links'] = 10;
            $config['uri_segment'] = 4;
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


        foreach ($carInfo as $k=>$v) {
            $carInfo[$k]['recent_order'] = $this->order->getChauffeurOrderCount($v['chauffeur_id'], 1);
        }

        $chauffeur = $this->cf->getChauffeur(100000, 0, '*', array('is_del' => $isDelStatus));
        $data = array(
            'city'=> $city,
            'car' => $car,
            'car_info' => $carInfo,
            'username' => $cname,
            'city_id' => $cityId,
            'car_model' => $car_model,
            'status' => $status,
            'pageHtml' => $pageHtml,
            'is_del_status' => $isDelStatus,
            'chauffeur' => $chauffeur,
        );
        $this->load->view('chauffeur/index', $data);
    }

    public function create()
    {
        //$this->load->model('model_city');echo 'aq';exit;
        $this->load->model('model_city', 'city');
        $this->load->model('model_car', 'car');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));
        $car = $this->car->getCar(10000);

        $this->load->view('chauffeur/create', array('city'=> $city, 'car' => $car));
    }

    public function save()
    {
        $isDeleteStatus = $this->uri->segment(3, 0);
        $userName = $this->input->get_post('username');
        $password = $this->input->get_post('password');
        $realname = $this->input->get_post('realname');
        $usersex = $this->input->get_post('usersex');
        $phone = $this->input->get_post('phone');
        $id_card = $this->input->get_post('id_card');
        $city = $this->input->get_post('city');
        $car_type = $this->input->get_post('car_type');
        $car_no = $this->input->get_post('car_no');
        $status = $this->input->get_post('status');
        $descr = $this->input->get_post('descr');
        $chauffeur_id = $this->input->get_post('chauffeur_id');

        $data = array(
            'cname' => $userName,
            'realname' => $realname,
            'sex' => $usersex,
            'phone' => $phone,
            'id_card' => $id_card,
            'city_id' => $city,
            'car_id' => $car_type,
            'car_no' => $car_no,
            'status' => $status,
            'descr' => $descr,
        );
        $password && $data['password'] = md5($password);

        $this->load->model('model_chauffeur', 'cf');
        $this->cf->save($data, $chauffeur_id);

        $this->load->helper('url');
        redirect('chauffeur/index/'.$isDeleteStatus, 'refresh');
    }

    public function edit()
    {
        $isDeleteStatus = $this->uri->segment(3);
        $chauffeurId = $this->uri->segment(4);

        $this->load->model('model_chauffeur', 'cf');
        $chauffeurData = $this->cf->getChauffeurById($chauffeurId);
        //echo '<pre>';print_r($chauffeurData);exit;

        $this->load->model('model_city', 'city');
        $this->load->model('model_car', 'car');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));
        $car = $this->car->getCar(10000);

        $data = array(
            'city'=> $city,
            'car' => $car,
            'data' => $chauffeurData,
            'isDeleteStatus' => $isDeleteStatus,
        );
        $this->load->view('chauffeur/create', $data);
    }

    public function detail()
    {
        $chauffeurId = $this->uri->segment(3, 1);

        $this->load->model('model_chauffeur', 'cf');
        $chauffeurData = $this->cf->getChauffeurById($chauffeurId);
        //echo '<pre>';print_r($chauffeurData);exit;

        $this->load->model('model_city', 'city');
        $this->load->model('model_car', 'car');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));
        $car = $this->car->getCar(10000);

        $this->load->model('model_order', 'order');
        $order = $this->order->getChauffeurOrder($chauffeurId);

        $data = array(
            'city'=> $city,
            'car' => $car,
            'data' => $chauffeurData,
            'order' => $order,
        );

        $this->load->view('chauffeur/detail', $data);
    }

    public function delete()
    {
        $chauffeurId = $this->uri->segment(3, 1);

        $this->load->model('model_chauffeur', 'cf');

        $this->cf->delete($chauffeurId, 1);

        $this->load->helper('url');
        redirect('/chauffeur/index/0');
    }

    public function recycle_delete()
    {
        $chauffeurId = $this->uri->segment(3, 1);

        $this->load->model('model_chauffeur', 'cf');
        $this->cf->delete($chauffeurId, 0);

        $this->load->helper('url');
        redirect('/chauffeur/index/1');
    }

    public function restore()
    {
        $chauffeurId = $this->uri->segment(3, 1);

        $this->load->model('model_chauffeur', 'cf');
        $this->cf->restore($chauffeurId);

        $this->load->helper('url');
        redirect('/chauffeur/index/1');
    }

    public function recycle_list()
    {
        $this->load->view('chauffeur/recycle_list');
    }
}

