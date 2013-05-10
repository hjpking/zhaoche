<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-12
 * Time: 下午7:50
 * To change this template use File | Settings | File Templates.
 */
class login extends CI_Controller
{
    public function index()
    {
        $this->load->view('login');
    }

    public function doLogin()
    {
        $this->load->helper('url');

        $url = '/login/index';
        $username = $this->input->get_post('username');
        $password = $this->input->get_post('password');

        if (empty ($username) || empty ($password)) {
            //show_error('用户名或密码为空!', 500);
            echo '<script>alert("用户名或密码为空!");window.location.href="/login/index";</script>';
            exit;
        }

        $this->load->model('model_staff', 'staff');
        $status = $this->staff->staffLogin($username, $password);

        if (!$status) {
            //show_error('用户名或密码错误!', 500);
            echo '<script>alert("用户名或密码错误!");window.location.href="/login/index";</script>';
            exit;
        }

        if ($status) {
            $this->load->helper('cookie');
            set_cookie('admin_auth', authcode("{$status['staff_id']}\t{$status['login_name']}\t{$status['password']}", 'ENCODE'), 36000);

            $this->amInfo = $status;
            /*
            $ip = $this->input->ip_address();
            $this->adminuser->adminUserLoginLog($status['am_uid'], $ip);
            //*/
            $url = '/index/index';
        }
        //echo $url;exit;
        redirect($url);
    }

    public function logout()
    {
        $this->load->helper('cookie');
        $this->load->helper('url');

        delete_cookie('admin_auth');
        redirect('/login/index');
    }
}
