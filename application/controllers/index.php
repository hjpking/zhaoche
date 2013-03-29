<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-1
 * Time: 下午5:07
 * To change this template use File | Settings | File Templates.
 */
class index extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function index()
    {
        $this->load->model('model_order', 'order');
        $orderInfo = $this->order->getOrder(5, 0, '*', null, 'create_time desc');

        $this->load->model('model_city', 'city');
        $city = $this->city->getCity(10000, 0, '*', array('is_del' => '0'));

        $this->load->model('model_pay', 'pay');
        $pay = $this->pay->getPay(5, 0, '*', null, 'create_time desc');

        $this->load->model('model_feedback', 'feedback');
        $feedback = $this->feedback->getFeedback(5, 0, '*', null, 'create_time desc');

        $this->load->model('model_user', 'user');
        $user = $this->user->getUser(5, 0, '*', null, 'create_time desc');

        $data = array(
            'order' => $orderInfo,
            'cityInfo' => $city,
            'pay' => $pay,
            'feedback' => $feedback,
            'user' => $user,
            'order_status' => config_item('order_status'),
            'pay_status' => config_item('pay_status'),
            'is_post' => config_item('is_post'),
            'user_type' => config_item('user_type'),
            'user_status' => config_item('user_status'),
            'process_status' => config_item('process_status'),
        );
        $this->load->view('index', $data);
    }
}
