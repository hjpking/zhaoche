<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-5
 * Time: 下午6:19
 * To change this template use File | Settings | File Templates.
 */
class message extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $isDelStatus = $this->uri->segment(3, 0);
        $Limit = 20;
        $currentPage = $this->uri->segment(4, 1);
        $offset = ($currentPage - 1) * $Limit;

        $this->load->helper('url');

        $title = $this->input->get_post('title');
        $categoryId = $this->input->get_post('category_id');

        $where = array('is_del' => $isDelStatus);
        $title && $where['title'] = $title;
        $categoryId && $where['cid'] = $categoryId;

        $this->load->model('model_message', 'message');
        $totalNum = $this->message->getMessageCount($where);
        $messageInfo = $this->message->getMessage($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/message/index/'.$isDelStatus);
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

        $this->load->model('model_staff', 'staff');
        $staffData = $this->staff->getStaff(100000);
        $categoryData = $this->message->getMessageCategory(1000);
        $messageData = $this->message->getMessage(10000);

        $data = array(
            'message_info' => $messageInfo,
            'messageData' => $messageData,
            'pageHtml' => $pageHtml,
            'category_data' => $categoryData,
            'title' => $title,
            'cid' => $categoryId,
            'isDelStatus' => $isDelStatus,
            'staffData' => $staffData,
            'totalNum' => $totalNum,
        );

        $this->load->view('message/index', $data);
    }

    public function create()
    {
        $this->load->model('model_message', 'message');
        $categoryData = $this->message->getMessageCategory(1000);

        $data = array(
            'category_data' => $categoryData,
        );
        $this->load->view('message/create', $data);
    }

    public function edit()
    {
        $mId = $this->uri->segment(3);

        if (!$mId) {
            show_error('消息ID为空！');
        }

        $this->load->model('model_message', 'message');
        $messageData = $this->message->getMessageById($mId);

        $categoryInfo = $this->message->getMessageCategory(1000);

        $data = array(
            'category_data' => $categoryInfo,
            'data' => $messageData,
        );

        $this->load->view('message/create', $data);
    }

    public function save()
    {
        $cId = $this->input->get_post('cid');
        $title = $this->input->get_post('title');
        $content = $this->input->get_post('content');
        $mId = $this->input->get_post('mid');


        $data = array(
            'cid' => $cId,
            'title' => $title,
            'content' => $content,
            'staff_id' => $this->amInfo['staff_id'],
            'author' => $this->amInfo['login_name'],
        );

        $this->load->model('model_message', 'message');

        $categoryData = $this->message->categoryIsAlone($cId);

        if (!$categoryData) {
            show_error('不能添加消息，该分类下有其他子分类！');
        }

        $this->message->save($data, $mId);

        $this->load->helper('url');
        redirect('message/index');
    }

    public function delete()
    {
        $mId = $this->uri->segment(3);

        if (!$mId) {
            show_error('消息ID为空！');
        }

        $this->load->model('model_message', 'message');
        //$status = $this->message->categoryIsAlone($mId);

        $this->message->delete($mId);

        $this->load->helper('url');
        redirect('message/index');
    }

    public function restore()
    {
        $mId = $this->uri->segment(3);
        $this->load->model('model_message', 'message');

        $this->message->save(array('is_del' => '0'), $mId);

        $this->load->helper('url');
        redirect('message/index/1');
    }

    public function recycle_delete()
    {
        $mId = $this->uri->segment(3);
        $this->load->model('model_message', 'message');
        $this->message->delete($mId, 0);

        $this->load->helper('url');
        redirect('message/index/1');
    }

    public function detail()
    {

        $mId = $this->input->get_post('mid');
        if (empty ($mId)) {
            $msg = array('error' => '0', 'msg' => '消息ID为空', 'code' => 'message_empty');
            $this->json_output($msg);
            return ;
        }

        $this->load->model('model_message', 'message');
        $messageData = $this->message->getMessageById($mId);

        $this->json_output($messageData);
    }







    public function category_index()
    {
        $this->load->model('model_message', 'message');
        $categoryData = $this->message->getMessageCategory(1000);

        $this->load->view('message/category_index', array('data' => $categoryData, 'user_type' => config_item('user_type')));
    }

    public function category_create()
    {
        $this->load->model('model_message', 'message');
        $categoryData = $this->message->getMessageCategory(1000);

        $data = array(
            'category_data' => $categoryData,
        );
        $this->load->view('message/category_create', $data);
    }

    public function category_edit()
    {
        $categoryId = $this->uri->segment(3);

        if (!$categoryId) {
            show_error('分类ID为空！');
        }

        $this->load->model('model_message', 'message');
        $categoryData = $this->message->getMessageCategoryById($categoryId);

        $categoryInfo = $this->message->getMessageCategory(1000);

        $data = array(
            'category_data' => $categoryInfo,
            'data' => $categoryData,
        );

        $this->load->view('message/category_create', $data);
    }

    public function category_save()
    {
        $name = $this->input->get_post('name');
        $parentId = $this->input->get_post('parent_id');
        $descr = $this->input->get_post('descr');
        $categoryId = $this->input->get_post('category_id');


        $data = array(
            'name' => $name,
            'parent_id' => $parentId,
            'descr' => $descr,
        );

        $this->load->model('model_message', 'message');

        $messageData = $this->message->getMessageBycId($parentId);
        if (!empty ($messageData)) {
            show_error('此分类下拥有消息，不能添加子分类！');
        }

        $this->message->categorySave($data, $categoryId);

        $this->load->helper('url');
        redirect('message/category_index');
    }

    public function category_delete()
    {
        $categoryId = $this->uri->segment(3);

        if (!$categoryId) {
            show_error('分类ID为空！');
        }

        $this->load->model('model_message', 'message');
        $status = $this->message->categoryIsAlone($categoryId);

        $this->message->categoryDelete($categoryId, 0);

        $this->load->helper('url');
        redirect('message/category_index');
    }



    public function push()
    {
        $this->load->model('model_message', 'message');
        $categoryData = $this->message->getMessageCategory(1000);
        //p($categoryData);
        $data = array(
            'category' => $categoryData,
            'user_type' =>config_item('user_type'),
            'message_type' => config_item('message_type'),
        );
        $this->load->view('message/push', $data);
    }

    public function getMessageBycId()
    {
        $cId = $this->input->get_post('cid');
        if (!$cId) {
            $msg = array('error' => '0', 'msg' => '分类ID为空', 'code' => 'message_empty');
            $this->json_output($msg);
            return ;
        }
        $this->load->model('model_message', 'message');
        $messageData = $this->message->getMessageBycId($cId);

        $this->json_output($messageData);
    }

    public function send()
    {
        $userType = $this->input->get_post('user_type');
        $mId = $this->input->get_post('mid');
        $recipient = $this->input->get_post('recipient');
        $type = $this->input->get_post('type');

        $this->load->model('model_message', 'message');
        $messageData = $this->message->getMessageById($mId);

        if (!$messageData) {
            show_error('消息不存在!');
        }

        $this->load->model('model_user', 'user');
        $this->load->model('model_chauffeur', 'cf');

        if ($userType == '2') {
            if (empty ($recipient)) {
                $userList = $this->cf->getChauffeur(10000000, 0, '*', array('status' => '1', 'is_del' => '0'));
            } else {
                $arr = explode(',', $recipient);
                $userList = $this->cf->getChauffeurWhereIn(10000000, 0, '*', $arr);
            }
        } else {
            if (empty ($recipient)) {
                $userList = $this->user->getUser(10000000, 0, '*', array('status' => '1', 'is_del' => '0'));
            } else {
                $arr = explode(',', $recipient);
                $userList = $this->user->getUserWhereIn(10000000, 0, '*', $arr);
            }
        }

        if (empty ($userList)) {
            show_error('没有所要发送的用户！');
        }
        foreach ($userList as $v) {
            $data = array(
                'user_type' => $userType,
                'mid' => $mId,
                'title' => $messageData['title'],
                'content' => $messageData['content'],
                'staff_id' => $this->amInfo['staff_id'],
                'types' => $type,
                'recipient_id' => isset($v['uid']) ? $v['uid'] : $v['chauffeur_id'],
                'recipient' => isset($v['cname']) ? $v['cname'] : $v['cname'],
            );

            $this->message->send($data);
        }

        $this->load->helper('url');
        redirect('message/sendRecord');
    }

    public function sendRecord()
    {
        $this->load->helper('url');
        $Limit = 20;
        $currentPage = $this->uri->segment(3, 1);
        $offset = ($currentPage - 1) * $Limit;

        $this->load->model('model_message', 'message');
        $totalNum = $this->message->getMessageSendRecordCount(array());
        $recordInfo = $this->message->getMessageSendRecord($Limit, $offset, '*', null, 'create_time desc');

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('message/sendRecord');
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


        $this->load->model('model_staff', 'staff');
        $staffData = $this->staff->getStaff(100000);
        $data = array(
            'record_info' => $recordInfo,
            'pageHtml' => $pageHtml,
            'user_type' => config_item('user_type'),
            'message_type' => config_item('message_type'),
            'staffData' => $staffData,
            'totalNum' => $totalNum,
        );

        $this->load->view('message/send_record', $data);
    }
}
