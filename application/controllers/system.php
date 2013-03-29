<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-5
 * Time: 下午7:12
 * To change this template use File | Settings | File Templates.
 */
class system extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function profile()
    {
        $this->load->model('model_staff', 'staff');
        $staffInfo = $this->staff->getStaffById($this->amInfo['staff_id']);

        $departmentInfo = $this->staff->getDepartment(1000, 0, '*', array('is_del' => '0'));

        $data = array(
            'data' => $staffInfo,
            'department' => $departmentInfo,
        );
        $this->load->view('system/profile', $data);
    }

    public function saveProfile()
    {
        $password = $this->input->get_post('password');
        $realname = $this->input->get_post('realname');
        $sex = $this->input->get_post('sex');
        $phone = $this->input->get_post('phone');
        $idCard = $this->input->get_post('id_card');
        $email = $this->input->get_post('email');
        $departId = $this->input->get_post('depart_id');
        $descr = $this->input->get_post('descr');
        //$competence = $this->input->get_post('competence');
        $staffId = $this->input->get_post('staff_id');

        $data = array(
            'realname' => $realname,
            'phone' => $phone,
            'email' => $email,
            'sex' => $sex,
            'depart_id' => $departId,
            'descr' => $descr,
            'id_card' => $idCard,
            'is_del' => '0',
        );
        $password && $data['password'] = md5($password);

        $this->load->model('model_staff', 'staff');
        $lastId = $this->staff->save($data, $staffId);

        if (!empty ($staffId)) {
            $lastId = $staffId;
        }

        $this->load->helper('url');
        redirect('system/profile', 'refresh');
    }

    public function reset_password()
    {
        $this->load->view('system/reset_password');
    }

    public function changePassword()
    {
        $currentPassword = $this->input->get_post('current_password');
        $newPassword = $this->input->get_post('new_password');
        $repatPassword = $this->input->get_post('repat_password');

        if ($newPassword != $repatPassword) {
            show_error('两次输入的密码错误！');
        }

        $this->load->model('model_staff', 'staff');
        $status = $this->staff->getStaffById($this->amInfo['staff_id'], '*', array('password' => md5($currentPassword)));

        if (!$status) {
            show_error('当前用户密码错误!');
        }

        $this->staff->save(array('password' => md5(trim($newPassword))), $this->amInfo['staff_id']);

        echo '<script>alert("修改密码成功!");window.location.href="system/reset_password"</script>';
    }

    public function card_index()
    {
        $this->load->helper('url');

        $Limit = 20;
        $currentPage = $this->uri->segment(3, 1);
        $offset = ($currentPage - 1) * $Limit;

        $cardNo = $this->input->get_post('card_no');
        $modelId = $this->input->get_post('model_id');

        $where = array();
        $cardNo && $where['card_no'] = $cardNo;
        $modelId && $where['model_id'] = $modelId;

        $this->load->model('model_card', 'card');

        $totalNum = $this->card->getCardCount($where);
        $cardData = $this->card->getCard($Limit, $offset, '*', $where);

        //echo '<pre>';print_r($cityData);exit;
        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('system/card_index');
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

        $cardModel = $this->card->getcardModel(10000);

        $data = array(
            'card_data' => $cardData,
            'pageHtml' => $pageHtml,
            'model_id' => $modelId,
            'card_no' => $cardNo,
            'card_model' => $cardModel,
        );
        $this->load->view('system/card_index', $data);
    }

    public function card_model_index()
    {
        $this->load->model('model_card', 'card');

        $cardData = $this->card->getCardModel(1000);

        $data = array(
            'data' => $cardData,
        );
        $this->load->view('system/card_model_index', $data);
    }

    public function card_model_create()
    {
        $data = array();

        $this->load->view('system/card_model_create', $data);
    }

    public function card_model_edit()
    {
        $modelId = $this->uri->segment(3);

        $this->load->model('model_card', 'card');
        $modelData = $this->card->getCardModelById($modelId);

        $data = array('data' => $modelData);
        $this->load->view('system/card_model_create', $data);
    }

    public function card_model_save()
    {
        $name = $this->input->get_post('name');
        $amount = $this->input->get_post('amount');
        $num = $this->input->get_post('num');
        $endTime = $this->input->get_post('end_time');
        $descr = $this->input->get_post('descr');
        $modelId = $this->input->get_post('model_id');

        $data = array(
            'name' => $name,
            'amount' => $amount,
            'num' => $num,
            'end_time' => $endTime,
            'amount' => $amount,
            'descr' => $descr,
            'is_genera' => '0',
        );

        $this->load->model('model_card', 'card');
        $this->card->cardModelSave($data, $modelId);

        $this->load->helper('url');
        redirect('system/card_model_index');
    }

    public function card_model_delete()
    {
        $modelId = $this->uri->segment(3);

        $this->load->model('model_card', 'card');

        $this->card->cardModelDelete($modelId, 0);

        $this->load->helper('url');
        redirect('system/card_model_index');
    }

    public function card_model_genera()
    {
        $modelId = $this->uri->segment(3);
        if (empty ($modelId)) {
            show_error('模型ID为空!');
        }

        $this->load->model('model_card', 'card');

        $modelData = $this->card->getCardModelById($modelId);
        if (empty ($modelData)) {
            show_error('卡模型为存在!');
        }

        $cardNum = $this->card->getCardCount(array('model_id' => $modelId));
        if ($cardNum >= $modelData['num']) {
            $this->load->helper('url');
            redirect('system/card_model_index');
            return ;
        }

        //最终要生成多少张卡
        $randNum = mt_rand(10000, 99999);
        $cardNum = $modelData['num'] - $cardNum;
        $generationNum = $randNum + $cardNum;

        $model_id = str_pad($modelId, 6, '0', STR_PAD_LEFT);
        for ($i = $randNum; $i < $generationNum; $i++) {
            $unIqId = str_pad($i, 10, '0', STR_PAD_LEFT);
            $cardNo = $model_id.$unIqId;
            $password = mt_rand(100000, 999999);

            $data = array(
                'card_no' => $cardNo,
                'model_id' => $modelId,
                'amount' => $modelData['amount'],
                'password' => $password,
                'end_time' => $modelData['end_time'],
            );

            $this->card->cardSave($data);
        }

        $this->card->cardModelSave(array('is_genera' => '1'), $modelId);

        $this->load->helper('url');
        //redirect('system/card_model_index');
    }
}
