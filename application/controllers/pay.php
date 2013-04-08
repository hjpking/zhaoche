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
        $pay_channel = $this->input->get_post('pay_channel');
        $isExport = $this->input->get_post('is_export');
        $uId = intval($this->input->get_post('uid'));

        $where = array();
        $orderSn && $where['pay_id'] = $orderSn;
        //$time && $where['create_time'] = $time;
        $pay_channel && $where['pay_channel'] = $pay_channel;
        $uname && $where['uname'] = $uname;
        $uId && $where['uid'] = $uId;

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

        $payType = config_item('pay_type');
        $payStatus = config_item('post_status');
        $isPost = config_item('is_post');
        $postMode = config_item('post_mode');
        $postStatus = config_item('pay_status');
        $pay = $this->pay->getPay(100000);
        $data = array(
            'orderSn' => $orderSn,
            'time' => $time,
            'pay_channel' => $pay_channel,
            'uname' => $uname,
            'pageHtml' => $pageHtml,
            'pay' => $payInfo,
            'pay_data' => $pay,
            'post_status' => $payStatus,
            'post_mode' => $postMode,
            'is_post' => $isPost,
            'pay_type' => $payType,
            'pay_status' => $postStatus,
            'userData' => $userData,
            'url' => '/pay/index?'.http_build_query($_REQUEST),
            //'orderData' => $orderData,
        );

        if ($isExport) {
            $str = "订单号,用户名,充值金额(元),充值来源,充值方式,充值状态,充值时间,操作人,寄送发票,寄送方式,发票抬头,邮寄地址,寄送状态;\n";
            foreach ($payInfo as $v) {
                $str .= $v['pay_id'].','.$v['uname'].','.fPrice($v['pay_amount']).','.($v['source'] ? '其他':'客户端').','.$payType[$v['pay_type']].','.$payStatus[$v['pay_status']]
                    .','.$v['create_time'].','.$v['opera_people'].','.$isPost[$v['is_post']].','.$postMode[$v['post_mode']].','.$v['invoice'].','.$v['post_address'].','.$postStatus[$v['post_status']].";\n";
            }
            $fileName = 'pay_'.date('Y-m-d', TIMESTAMP) .'.csv';
            exportCsv($fileName, $str);
            return;
        }

        $this->load->view('pay/index', $data);
    }

    public function beUserPay()
    {
        $Limit = 20;
        $currentPage = $this->uri->segment(3, 1);
        $offset = ($currentPage - 1) * $Limit;

        $where = array('is_del' => '0');

        $uname = $this->input->get_post('uname');
        $phone = $this->input->get_post('phone');
        $time = $this->input->get_post('create_time');
        $status = $this->input->get_post('status');

        if ($time) {
            $eTime = explode('-', $time);
            $where['create_time >='] = date('Y-m-d H:i:s', strtotime($eTime[0]));
            $where['create_time <='] = date('Y-m-d ', strtotime($eTime[1])).'23:59:59';
        }

        $uname && $where['uname'] = $uname;
        $phone && $where['phone'] = $phone;
        ($status || $status === '0') && $where['status'] = $status;

        $this->load->model('model_user', 'user');

        $totalNum = $this->user->getUserCount($where);
        $userInfo = $this->user->getUser($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/user/index');
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

        $userData = $this->user->getUser(100000, 0, 'uname, phone', array('is_del' => '0'));

        $binding_status = config_item('binding_type');
        $data = array(
            'user_info' => $userInfo,
            'user_data' => $userData,
            'pageHtml' => $pageHtml,
            'binding_status' => $binding_status,
            'time' => $time,
            'uname' => $uname,
            'phone' => $phone,
            'status' => $status,
        );

        $this->load->view('pay/be_user_pay', $data);
    }

    /**
     * 用户充值
     */
    public function userPay()
    {
        $uId = $this->uri->segment(3);
        if (empty ($uId)) {
            show_error('用户ID为空!');
        }

        $this->load->model('model_user', 'user');
        $userInfo = $this->user->getUserById($uId);
        if (empty ($userInfo)) {
            show_error('用户不存在！');
        }

        $data = array(
            'user_info' => $userInfo,
        );
        $this->load->view('pay/user_pay', $data);
    }

    /**
     * 批量充值
     */
    public function batchUserPay()
    {
        $uId = $this->input->get_post('uid');
        if (empty ($uId) || !is_array($uId)) {
            show_error('用户ID为空!');
        }

        $uInfo = array();
        $this->load->model('model_user', 'user');
        foreach ($uId as $v) {
            $userInfo = $this->user->getUserById($v);
            if (empty ($userInfo)) {
                show_error('用户不存在！');
            }

            $uInfo[] = $userInfo;
        }

        $this->load->view('pay/batch_user_pay', array('user_info' => $uInfo));
    }

    public function toPay()
    {
        $uId = $this->input->get_post('uid');
        $payAmount = intval($this->input->get_post('pay_amount'));

        if (empty ($uId) || !is_array($uId) || empty ($payAmount)) {
            show_error('用户ID或充值金额为空！');
        }

        $payAmount = fPrice($payAmount, 4);

        foreach ($uId as $v) {
            $this->load->model('model_user', 'user');
            $userInfo = $this->user->getUserById($v);
            if (empty ($userInfo)) {
                show_error('用户不存在！');
            }

            if ($payAmount > 2000000) {
                show_error('充值金额超过2000元！');
            }

            $s = $this->db->set(array('amount' => 'amount+'.$payAmount), '', false)->where('uid', $userInfo['uid'])->update('user');
            if (!$s) {
                show_error('充值失败');
            }

            if (!empty ($userInfo['phone'])) {
                $msg = '您的账号收到通过 '.APP_NAME.' 官网的充值：'.$payAmount.'元,当前余额为：'.($userInfo['amount']+$payAmount).'元。';
                $this->sendMessage($userInfo['phone'], $msg);
            }

            $logData = array(
                'uid' => $userInfo['uid'],
                'uname' => $userInfo['uname'],
                'pay_amount' => $payAmount,
                'opera_people' => $this->amInfo['staff_id'],
                'opera_name' => $this->amInfo['login_name'],
                'status' => '1',
            );
            $this->load->model('model_pay', 'pay');
            $this->pay->saveBeUserPayLog($logData);
        }

        $this->load->helper('url');
        redirect('pay/payLog');
    }

    public function payLog()
    {
        $Limit = 20;
        $currentPage = $this->uri->segment(3, 1);
        $offset = ($currentPage - 1) * $Limit;

        $where = array();

        $uname = $this->input->get_post('uname');
        $staffName = $this->input->get_post('staff_name');
        $time = $this->input->get_post('create_time');

        if ($time) {
            $eTime = explode('-', $time);
            $where['create_time >='] = date('Y-m-d H:i:s', strtotime($eTime[0]));
            $where['create_time <='] = date('Y-m-d ', strtotime($eTime[1])).'23:59:59';
        }

        $uname && $where['uname'] = $uname;
        $staffName && $where['opera_name'] = $staffName;

        $this->load->model('model_pay', 'pay');

        $totalNum = $this->pay->getBeUserPayLogCount($where);
        $logInfo = $this->pay->getBeUserPayLog($Limit, $offset, '*', $where, 'create_time desc');

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/pay/payLog');
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

        $this->load->model('model_staff', 'staff');
        $staffData = $this->staff->getStaff(100000);

        $data = array(
            'log_info' => $logInfo,
            'user_data' => $userData,
            'pageHtml' => $pageHtml,
            'time' => $time,
            'staff_data' => $staffData,
            'uname' => $uname,
            'staff_name' => $staffName,
        );

        $this->load->view('pay/pay_log', $data);
    }

    public function cancelBeUserPay()
    {
        $pId = $this->uri->segment(3);
        if (empty ($pId)) {
            show_error('充值记录ID为空!');
        }

        $this->load->model('model_pay', 'pay');
        $logInfo = $this->pay->getBeUserPayLogById($pId);
        if (empty ($logInfo)) {
            show_error('给用户充值记录不存在!');
        }

        $s = $this->db->set(array('amount' => 'amount-'.$logInfo['pay_amount']), '', false)->where('uid', $logInfo['uid'])->update('user');
        if (!$s) {
            show_error('取消用户充值失败！');
        }

        $this->pay->saveBeUserPayLog(array('status' => '0'), $logInfo['id']);

        $this->load->helper('url');
        redirect('pay/payLog');
    }
}
