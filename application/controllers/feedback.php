<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-5
 * Time: 下午4:51
 * To change this template use File | Settings | File Templates.
 */
class feedback extends MY_Controller
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
        $this->load->model('model_feedback', 'feedback');

        $uname = $this->input->get_post('uname');
        $time = $this->input->get_post('time');
        $categoryId = $this->input->get_post('category_id');
        $processStatus = $this->input->get_post('process_status');
        $uId = intval($this->input->get_post('uid'));

        $where = array();
        $uname && $where['uname'] = $uname;
        //$time && $where['create_time'] = $time;
        $categoryId && $where['category_id'] = $categoryId;
        ($processStatus || $processStatus === '0') && $where['process_status'] = $processStatus;
        $uId && $where['uid'] = $uId;

        if ($time) {
            $eTime = explode('-', $time);
            $where['create_time >='] = date('Y-m-d H:i:s', strtotime($eTime[0]));
            $where['create_time <='] = date('Y-m-d ', strtotime($eTime[1])).'23:59:59';
        }

        $totalNum = $this->feedback->getFeedbackCount($where);
        $feedbackInfo = $this->feedback->getFeedback($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/feedback/index/');
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

        $data = array(
            'categoryId' => $categoryId,
            'time' => $time,
            'processStatus' => $processStatus,
            'uName' => $uname,
            'pageHtml' => $pageHtml,
            'feedback' => $feedbackInfo,
            'feedback_category' => config_item('feedback_category'),
            'userData' => $userData,
            'user_type' => config_item('user_type'),
            'process_status' => config_item('process_status'),
            'totalNum' => $totalNum,
        );
        $this->load->view('feedback/index', $data);
    }
}
