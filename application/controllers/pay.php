<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-5
 * Time: 下午1:09
 * To change this template use File | Settings | File Templates.
 */
class pay extends MY_Controller
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
        $this->load->model('model_pay', 'pay');

        $orderSn = $this->input->get_post('order_sn');
        $uname = $this->input->get_post('uname');
        $time = $this->input->get_post('time');
        $pay_status = $this->input->get_post('pay_status');

        $where = array();
        $orderSn && $where['pay_id'] = $orderSn;
        //$time && $where['create_time'] = $time;
        $pay_status && $where['pay_status'] = $pay_status;
        $uname && $where['uname'] = $uname;

        if ($time) {
            $eTime = explode('-', $time);
            $where['create_time >='] = date('Y-m-d H:i:s', strtotime($eTime[0]));
            $where['create_time <='] = date('Y-m-d ', strtotime($eTime[1])).'23:59:59';
        }

        $totalNum = $this->pay->getPayCount($where);
        $payInfo = $this->pay->getPay($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/pay/index/');
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

        $this->load->model('model_user', 'user');
        $userData = $this->user->getUser(100000, 0, 'uname, phone', array('is_del' => '0'));

        //$this->load->model('model_order', 'order');
        //$payData = $this->pay->getPay(10000);

        $pay = $this->pay->getPay(100000);
        $data = array(
            'orderSn' => $orderSn,
            'time' => $time,
            'status' => $pay_status,
            'uname' => $uname,
            'pageHtml' => $pageHtml,
            'pay' => $payInfo,
            'pay_data' => $pay,
            'post_status' => config_item('post_status'),
            'post_mode' => config_item('post_mode'),
            'is_post' => config_item('is_post'),
            'pay_type' => config_item('pay_type'),
            'pay_status' => config_item('pay_status'),
            'userData' => $userData,
            //'orderData' => $orderData,
        );
        $this->load->view('pay/index', $data);
    }
}
