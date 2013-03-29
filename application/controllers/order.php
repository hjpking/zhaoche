<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-4
 * Time: 下午7:10
 * To change this template use File | Settings | File Templates.
 */
class order extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $Limit = 20;
        $currentPage = $this->uri->segment(3, 1);
        $offset = ($currentPage - 1) * $Limit;

        $this->load->helper('url');
        $this->load->model('model_order', 'order');

        $orderSn = $this->input->get_post('order_sn');
        $time = $this->input->get_post('time');
        $status = $this->input->get_post('status');

        $where = array();
        $orderSn && $where['order_sn'] = $orderSn;
        //$time && $where['city_id'] = $time;
        ($status || $status === '0') && $where['status'] = $status;

        if ($time) {
            $eTime = explode('-', $time);//echo $eTime[0].'<br>';p(date('Y-m-d H:i:s', strtotime($eTime[0])));
            $where['create_time >='] = date('Y-m-d H:i:s', strtotime($eTime[0]));
            $where['create_time <='] = date('Y-m-d ', strtotime($eTime[1])).'23:59:59';
        }

        $totalNum = $this->order->getOrderCount($where);
        $orderInfo = $this->order->getOrder($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/order/index/');
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

        $this->load->model('model_service_type', 'st');
        $sfInfo = $this->st->getServiceType(1000);

        $this->load->model('model_car', 'car');
        $carLevelInfo = $this->car->getCarLevel(1000);

        $this->load->model('model_city', 'city');
        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array(
            'order_sn' => $orderSn,
            'time' => $time,
            'status' => $status,
            'pageHtml' => $pageHtml,
            'order' => $orderInfo,
            'order_status' => config_item('order_status'),
            'sf_info' => $sfInfo,
            'carLevelInfo' => $carLevelInfo,
            'cityInfo' => $city,
        );

        $this->load->view('order/index', $data);
    }

    public function detail()
    {
        $orderSn = $this->uri->segment(3);
        $this->load->model('model_order', 'order');
        $orderInfo = $this->order->getOrderById($orderSn);

        $this->load->model('model_service_type', 'st');
        $sfInfo = $this->st->getServiceType(1000);

        $this->load->model('model_car', 'car');
        $carLevelInfo = $this->car->getCarLevel(1000);

        $this->load->model('model_city', 'city');
        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $data = array(
            'data' => $orderInfo,
            'order_status' => config_item('order_status'),
            'user_sex' => config_item('user_sex'),
            'sf_info' => $sfInfo,
            'carLevelInfo' => $carLevelInfo,
            'cityInfo' => $city,
        );

        $this->load->view('order/detail', $data);
    }
}
