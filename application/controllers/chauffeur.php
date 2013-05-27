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
        $isExport = $this->input->get_post('is_export');

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
            'totalNum' => $totalNum,
            'offset' => $offset,
            'url' => '/chauffeur/index/'.$isDelStatus.'?'.http_build_query($_REQUEST),
            'color' => config_item('color'),
        );

        if ($isExport) {
            $str = "司机ID,用户名,真实姓名,车型,所在城市,手机号,服务状态,接单量;\n";
            foreach ($carInfo as $v) {
                $str .= $v['chauffeur_id'].','.$v['cname'].','.$v['realname'].','.$car[$v['car_id']]['name'].','.$city[$v['city_id']]['city_name'].','.$v['phone'].','.($v['status'] ? '正常服务' : '暂时服务').','.$v['recent_order'].";\n";
            }
            $fileName = 'chauffeur_'.date('Y-m-d', TIMESTAMP) .'.csv';
            exportCsv($fileName, $str);
            return;
        }

        $this->load->view('chauffeur/index', $data);
    }

    public function create()
    {
        //$this->load->model('model_city');echo 'aq';exit;
        $this->load->model('model_city', 'city');
        $this->load->model('model_car', 'car');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));
        $car = $this->car->getCar(10000);

        $this->load->view('chauffeur/create', array('city'=> $city, 'car' => $car,'color' => config_item('color'),));
    }

    public function save()
    {
        $isDeleteStatus = $this->uri->segment(3, 0);
        $userName = trim($this->input->get_post('username'));
        $password = trim($this->input->get_post('password'));
        $realname = trim($this->input->get_post('realname'));
        $usersex = intval($this->input->get_post('usersex'));
        $phone = trim($this->input->get_post('phone'));
        $id_card = trim($this->input->get_post('id_card'));
        $city = intval($this->input->get_post('city'));
        $car_type = intval($this->input->get_post('car_type'));
        $car_no = trim($this->input->get_post('car_no'));
        $status = intval($this->input->get_post('status'));
        $descr = trim($this->input->get_post('descr'));
        $chauffeur_id = intval($this->input->get_post('chauffeur_id'));
        $colorId = intval($this->input->get_post('color_id'));
        $url = $this->input->get_post('url');

        if (empty ($userName) || empty ($phone) || empty ($city) || empty ($car_type) || empty ($car_no) ) {
            show_error('登陆名、手机号、城市、车型、车牌号为空!');
        }

        $data = array(
            'cname' => $userName,
            'realname' => $realname,
            'sex' => $usersex,
            'phone' => $phone,
            'id_card' => $id_card,
            'city_id' => $city,
            'car_id' => $car_type,
            'color_id' => $colorId,
            'car_no' => $car_no,
            'status' => $status,
            'descr' => $descr,
        );
        $password && $data['password'] = md5($password);

        $this->load->model('model_chauffeur', 'cf');
        if (!$chauffeur_id) {
            $cInfo = $this->cf->getChauffeurByPhone($phone);
            if (!empty ($cInfo)) {
                show_error('此手机号码已存在!');
            }
        }

        $this->cf->save($data, $chauffeur_id);

        $url = empty ($url) ? 'chauffeur/index/'.$isDeleteStatus : $url;
        $this->load->helper('url');
        redirect($url, 'refresh');
    }

    public function edit()
    {
        $isDeleteStatus = $this->uri->segment(3);
        $chauffeurId = $this->uri->segment(4);
        $url = $this->input->get_post('url');

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
            'url' => $url,
            'color' => config_item('color'),
            'isEdit' => 1
        );
        $this->load->view('chauffeur/create', $data);
    }

    public function detail()
    {
        $chauffeurId = $this->uri->segment(3, 1);

        $time = $this->input->get_post('time');
        $isExport = $this->input->get_post('is_export');

        $this->load->model('model_chauffeur', 'cf');
        $chauffeurData = $this->cf->getChauffeurById($chauffeurId);
        //echo '<pre>';print_r($chauffeurData);exit;

        $this->load->model('model_city', 'city');
        $this->load->model('model_service_type', 'st');
        $this->load->model('model_car', 'car');

        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));
        $sfInfo = $this->st->getServiceType(1000);
        $carLevelInfo = $this->car->getCarLevel(1000);
        $car = $this->car->getCar(1000);


        $this->load->model('model_order', 'order');
        $order = $this->order->getChauffeurOrder($chauffeurId);
        if ($time) {
            $eTime = explode('-', $time);//echo $eTime[0].'<br>';p(date('Y-m-d H:i:s', strtotime($eTime[0])));
            $where['create_time >='] = date('Y-m-d H:i:s', strtotime($eTime[0]));
            $where['create_time <='] = date('Y-m-d ', strtotime($eTime[1])).'23:59:59';
            $order = $this->order->getChauffeurOrder($chauffeurId, 1000, 0, $where);
        }
        $orderStatus = config_item('order_status');
        $data = array(
            'city'=> $city,
            'sf_info' => $sfInfo,
            'carLevelInfo' => $carLevelInfo,
            'data' => $chauffeurData,
            'car' => $car,
            'order' => $order,
            'time' => $time,
            'order_status' => $orderStatus,
            'url' => '/chauffeur/detail?'.http_build_query($_REQUEST),
            'color' => config_item('color'),
        );

        if ($isExport) {
            $str = "订单号,用户手机号,用户姓名,订车时间,上车时间,下车时间,订单状态,租金(元),司机用户名,车型,操作;\n";
            foreach ($order as $v) {
                $str .= $v['order_sn'].','.$v['user_phone'].','.$v['uname'].','.$v['create_time'].','.$v['train_time'].','.$v['getoff_time'].','.$orderStatus[$v['status']].','.fPrice($v['amount']).','.fPrice($v['amount']).','.$v['chauffeur_login_name'].','.$car[$v['car_id']]['name'].";\n";
            }
            $fileName = 'chauffeur_order_'.date('Y-m-d', TIMESTAMP) .'.csv';
            exportCsv($fileName, $str);
            return;
        }

        $this->load->view('chauffeur/detail', $data);
    }

    public function delete()
    {
        $chauffeurId = $this->uri->segment(3);
        $url = $this->input->get_post('url');

        $this->load->model('model_chauffeur', 'cf');

        $this->cf->delete($chauffeurId, 1);

        $url = empty ($url) ? '/chauffeur/index/0' : $url;
        $this->load->helper('url');
        redirect($url);
    }

    public function recycle_delete()
    {
        $chauffeurId = $this->uri->segment(3, 1);
        $url = $this->input->get_post('url');

        $this->load->model('model_chauffeur', 'cf');

        $this->cf->delete($chauffeurId, 0);

        $url = empty ($url) ? '/chauffeur/index/1' : $url;
        $this->load->helper('url');
        redirect($url);
    }

    public function batch_delete()
    {
        $deleteStatus = $this->uri->segment(3);
        $chauffeurId = $this->input->get_post('chauffeur_id');
        $url = $this->input->get_post('url');

        if (empty ($chauffeurId) || !is_array($chauffeurId)) {
            show_error('未选择要删除的司机！');
        }

        $s = $deleteStatus ? 0 : 1;
        $this->load->model('model_chauffeur', 'cf');

        foreach ($chauffeurId as $v) {
            $this->cf->delete($v, $s);
        }

        $url = empty ($url) ? '/chauffeur/index/'.$deleteStatus : $url;
        $this->load->helper('url');
        redirect($url);
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

