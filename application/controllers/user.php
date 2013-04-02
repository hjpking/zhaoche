<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-2
 * Time: 下午11:33
 * To change this template use File | Settings | File Templates.
 */
class user extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $isDelStatus = $this->uri->segment(3, 1);
        $isExport = $this->input->get_post('is_export');

        $Limit = 20;
        $currentPage = $this->uri->segment(4, 1);
        $offset = ($currentPage - 1) * $Limit;

        $where = array('is_del' => $isDelStatus);

        $uname = $this->input->get_post('uname');
        $phone = $this->input->get_post('phone');
        $time = $this->input->get_post('create_time');
        $status = $this->input->get_post('status');

        if ($time) {
            $eTime = explode('-', $time);//echo $eTime[0].'<br>';p(date('Y-m-d H:i:s', strtotime($eTime[0])));
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
            $config['base_url'] = site_url('/user/index/'.$isDelStatus);
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

        $userData = $this->user->getUser(100000, 0, 'uname, phone', array('is_del' => $isDelStatus));

        $binding_status = config_item('binding_type');
        $data = array(
            'user_info' => $userInfo,
            'user_data' => $userData,
            'pageHtml' => $pageHtml,
            'binding_status' => $binding_status,
            'isDelStatus' => $isDelStatus,
            'time' => $time,
            'uname' => $uname,
            'phone' => $phone,
            'status' => $status,
            'url' => '/user/index/'.$isDelStatus.'?'.http_build_query($_REQUEST),
        );

        if ($isExport) {
            $str = "用户ID,用户名,真实姓名,手机号,绑定类型,余额(元),用户状态,注册时间;\n";
            foreach ($userInfo as $v) {
                $str .= $v['uid'].','.$v['uname'].','.$v['realname'].','.$v['phone'].','.$binding_status[$v['binding_type']].','.fPrice($v['amount']).','.($v['status'] ? '白名单' : '黑名单').','.$v['create_time'].";\n";
            }
            $fileName = 'user_'.date('Y-m-d', TIMESTAMP) .'.csv';
            exportCsv($fileName, $str);
            return;
        }

        $this->load->view('user/index', $data);
    }

    public function create()
    {
        $binding_status = config_item('binding_type');

        $data = array(
            'binding_status' => $binding_status,
        );

        $this->load->view('user/create', $data);
    }

    public function edit()
    {
        $isDeleteStatus = $this->uri->segment(3);
        $uId = $this->uri->segment(4);

        $this->load->model('model_user', 'user');
        $userInfo = $this->user->getUserById($uId);

        $binding_status = config_item('binding_type');

        $data = array(
            'data' => $userInfo,
            'binding_status' => $binding_status,
            'isDeleteStatus' => $isDeleteStatus,
        );
        $this->load->view('user/create', $data);
    }

    public function save()
    {
        $isDeleteStatus = $this->uri->segment(3);

        $username = $this->input->get_post('username');
        $password = $this->input->get_post('password');
        $realname = $this->input->get_post('realname');
        $sex = $this->input->get_post('sex');
        $phone = $this->input->get_post('phone');
        $binding_type = $this->input->get_post('binding_type');
        $status = $this->input->get_post('status');
        $descr = $this->input->get_post('descr');
        $uid = $this->input->get_post('uid');

        $data = array(
            'uname' => $username,
            'realname' => $realname,
            'sex' => $sex,
            'phone' => $phone,
            'amount' => 0,
            'binding_type' => $binding_type,
            'descr' => $descr,
            'status' => $status,
        );
        $password && $data['password'] = md5($password);

        $this->load->model('model_user', 'user');
        $this->user->save($data, $uid);

        $this->load->helper('url');
        redirect('user/index/'.$isDeleteStatus, 'refresh');
    }

    public function detail()
    {
        $uId = $this->uri->segment(3, 1);

        $this->load->model('model_user', 'user');
        $userInfo = $this->user->getUserById($uId);

        $this->load->model('model_order', 'order');
        $orderNum = $this->order->getOrderCount(array('uid' => $uId, 'status' => 1));

        $this->load->model('model_pay', 'pay');
        $payNum = $this->pay->getPayCount(array('uid' => $uId, 'post_status' => 1));

        $this->load->model('model_feedback', 'feedback');
        $feedbackNum = $this->feedback->getFeedbackCount(array('uid' => $uId));


        $binding_type = config_item('binding_type');
        $user_status = config_item('user_status');

        $data = array(
            'data' => $userInfo,
            'binding_type' => $binding_type,
            'user_status' => $user_status,
            'order_number' => $orderNum,
            'pay_number' => $payNum,
            'feedback_number' => $feedbackNum
        );

        $this->load->view('user/detail', $data);
    }

    public function delete()
    {
        $uId = $this->uri->segment(3, 1);

        $this->load->model('model_user', 'user');
        $this->user->delete($uId, 1);

        $this->load->helper('url');
        redirect('user/index/0');
    }

    public function recycle_delete()
    {
        $uId = $this->uri->segment(3, 1);

        $this->load->model('model_user', 'user');
        $this->user->delete($uId, 0);

        $this->load->helper('url');
        redirect('/user/index/1');
    }

    public function restore()
    {
        $uId = $this->uri->segment(3, 1);

        $this->load->model('model_user', 'user');
        $this->user->restore($uId);

        $this->load->helper('url');
        redirect('/user/index/1');
    }


    public function recycle_list()
    {
        $this->load->view('user/recycle_list');
    }

    public function invoice_index()
    {
        $Limit = 20;
        $currentPage = $this->uri->segment(4, 1);
        $offset = ($currentPage - 1) * $Limit;

        $where = array();

        $uname = $this->input->get_post('uname');

        $uname && $where['uname'] = $uname;

        $this->load->model('model_user', 'user');

        $totalNum = $this->user->getInvoiceCount($where);
        $invoiceInfo = $this->user->getInvoice($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/user/invoice_index');
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

        $data = array(
            'invoice' => $invoiceInfo,
            'pageHtml' => $pageHtml,
            'uname' => $uname,
        );

        $this->load->view('user/invoice_index', $data);
    }

    public function invoiceDelete()
    {
        $invoiceId = $this->uri->segment(3);

        $this->load->model('model_user', 'user');
        $this->user->invoiceDelete($invoiceId, 0);

        $this->load->helper('url');
        redirect('user/invoice_index');
    }
}
