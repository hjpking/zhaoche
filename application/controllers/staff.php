<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-5
 * Time: 下午3:27
 * To change this template use File | Settings | File Templates.
 */
class staff extends MY_Controller
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

        $where = array('is_del' => $isDelStatus);
        $realname = $this->input->get_post('realname');
        $departId = $this->input->get_post('depart_id');

        $realname && $where['realname'] = $realname;
        $departId && $where['depart_id'] = $departId;

        $this->load->model('model_staff', 'staff');
        $totalNum = $this->staff->getStaffCount($where);
        $staffInfo = $this->staff->getStaff($Limit, $offset, '*', $where);

        $pageHtml = '';
        if ($totalNum > $Limit) { //页数不足一页
            $this->load->library('pagination');
            $config['base_url'] = site_url('/staff/index/'.$isDelStatus);
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

        $staffData = $this->staff->getStaff(100000);
        $departmentInfo = $this->staff->getDepartment(1000, 0, '*', array('is_del' => '0'));
//p($departmentInfo);
        $data = array(
            'staff' => $staffInfo,
            'pageHtml' => $pageHtml,
            'realname' => $realname,
            'departId' => $departId,
            'staffData' => $staffData,
            'departmentInfo' => $departmentInfo,
            'user_sex' => config_item('user_sex'),
            'isDelStatus' => $isDelStatus,
            'totalNum' => $totalNum,
        );
        $this->load->view('staff/index', $data);
    }

    public function create()
    {
        $this->load->model('model_staff', 'staff');
        $departmentInfo = $this->staff->getDepartment(1000, 0, '*', array('is_del' => '0'));

        $data = array(
            'departmentInfo' => $departmentInfo,
            'user_sex' => config_item('user_sex'),
            'competence' => config_item('view_nav'),
        );
        $this->load->view('staff/create', $data);
    }

    public function detail()
    {
        $staffId = $this->uri->segment(3);
        $this->load->model('model_staff', 'staff');
        $staffInfo = $this->staff->getStaffById($staffId);

        $departmentInfo = $this->staff->getDepartment(1000, 0, '*', array('is_del' => '0'));

        $this->load->model('model_competence_correspond', 'cc');
        $userCompetence = $this->cc->getUserCompetence($staffId, '*', null, 'competence_id');

        $data = array(
            'data' => $staffInfo,
            'departmentInfo' => $departmentInfo,
            'user_sex' => config_item('user_sex'),
            'competence' => config_item('view_nav'),
            'userCompetence' => $userCompetence,
        );

        $this->load->view('staff/detail', $data);
    }

    public function edit()
    {
        $staffId = $this->uri->segment(3);
        $this->load->model('model_staff', 'staff');
        $departmentInfo = $this->staff->getDepartment(1000, 0, '*', array('is_del' => '0'));

        $staffInfo = $this->staff->getStaffById($staffId);

        $this->load->model('model_competence_correspond', 'cc');
        $userCompetence = $this->cc->getUserCompetence($staffId, '*', null, 'competence_id');
//p($userCompetence);
        $data = array(
            'departmentInfo' => $departmentInfo,
            'user_sex' => config_item('user_sex'),
            'competence' => config_item('view_nav'),
            'data' => $staffInfo,
            'userCompetence' => $userCompetence,
            'isEdit' => 1
        );
//p($userCompetence);
        $this->load->view('staff/create', $data);
    }

    public function save()
    {
        $loginName = $this->input->get_post('login_name');
        $password = $this->input->get_post('password');
        $realname = $this->input->get_post('realname');
        $sex = $this->input->get_post('sex');
        $phone = $this->input->get_post('phone');
        $idCard = $this->input->get_post('id_card');
        $email = $this->input->get_post('email');
        $departId = $this->input->get_post('depart_id');
        $descr = $this->input->get_post('descr');
        $competence = $this->input->get_post('competence');
        $staffId = $this->input->get_post('staff_id');

        $data = array(
            'login_name' => $loginName,
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

        if (!empty ($competence)) {
            $this->load->model('model_competence_correspond', 'cc');
            $this->cc->save($competence, $lastId);
        }


        $this->load->helper('url');
        redirect('staff/index', 'refresh');
    }

    public function delete()
    {
        $staffId = $this->uri->segment(3);
        $this->load->model('model_staff', 'staff');
        $this->staff->delete($staffId);

        $this->load->helper('url');
        redirect('staff/index/0', 'refresh');
    }

    public function recycle_delete()
    {
        $staffId = $this->uri->segment(3);
        $this->load->model('model_staff', 'staff');
        $this->staff->delete($staffId, 0);

        $this->load->helper('url');
        redirect('staff/index/1', 'refresh');
    }

    public function restore()
    {
        $staffId = $this->uri->segment(3);
        $this->load->model('model_staff', 'staff');

        $this->staff->restore($staffId);

        $this->load->helper('url');
        redirect('/staff/index/1');
    }

    public function recycle_list()
    {
        $this->load->view('staff/recycle_list');
    }



    public function department_index()
    {
        $this->load->model('model_staff', 'staff');
        $departmentInfo = $this->staff->getDepartment(1000, 0, '*', array('is_del' => '0'));

        $data = array('data' => $departmentInfo);
        $this->load->view('staff/department_index', $data);
    }

    public function department_create()
    {
        $this->load->model('model_staff', 'staff');
        $departmentInfo = $this->staff->getDepartment(1000, 0, '*', array('is_del' => '0'));

        $data = array(
            'depart_data' => $departmentInfo,
        );
        $this->load->view('staff/department_create', $data);
    }

    public function department_save()
    {
        $name = $this->input->get_post('name');
        $parentId = $this->input->get_post('parent_id');
        $descr = $this->input->get_post('descr');
        $departId = $this->input->get_post('depart_id');

        $data = array(
            'name' => $name,
            'parent_id' => $parentId,
            'descr' => $descr,
            'is_del' => '0',
        );

        $this->load->model('model_staff', 'staff');
        if (!$departId) {
            $dInfo = $this->staff->getDepartment(100, 0, '*', array('name' => $name));
            if (!empty ($dInfo)) {
                echo '<script>alert("部门已存在!");history.go(-1);</script>';
                exit;
            }
        }

        $this->staff->departmentSave($data, $departId);

        $this->load->helper('url');
        redirect('staff/department_index');
    }

    public function department_edit()
    {
        $departId = $this->uri->segment(3);

        $this->load->model('model_staff', 'staff');
        $data = $this->staff->getDepartmentById($departId);

        $departInfo = $this->staff->getDepartment(1000, 0, '*', array('is_del' => '0'));

        $this->load->view('staff/department_create', array('data' => $data, 'depart_data' => $departInfo,'isEdit' => 1));
    }

    public function department_delete()
    {
        $departId =  $this->input->get_post('did');;
        $response = array('code' => '0', 'msg' => '删除成功');

        do {
            if (empty ($departId)) {
                $response = error(10001);//参数不全
                break;
            }

            $this->load->model('model_staff', 'staff');
            $status = $this->staff->isAlone($departId);

            if ($status) {
                $response = error(10037);//还有下级部门未删除
                break;
            }

            $this->staff->departmentDelete($departId);
        } while (false);

        $this->json_output($response);
    }
}
