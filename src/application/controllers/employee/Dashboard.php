<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/Myupload.php';
class Dashboard extends Employee_Controller {

	function __construct(){
        parent::__construct();
    }
	
	//加载登录页面
	public function index()
	{
		/*$admin_id = $this->checkLogin('E');
		$admin_name = $this->checkLogin('EmployeeName');
		$level_name = $this->checkLogin('LevelName');
        $this->data['admin_id'] = $admin_id;
        $this->data['admin_name'] = $admin_name;
        $this->data['level_name'] = $level_name;*/
		/*if(empty($admin_id)){
			redirect("admin/login");
		}*/

        redirect("/employee/users");
		//$this->template->employee_load('employee/dashboard', $this->data);
	}

	//加载登录页面
	public function login()
	{
		$this->load->view('employee/login');
	}
	
	//检查管理员账号
	public function check_admin()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$admin_detail = $this->employee_model->admin_check($username,$password);
		if(!empty($admin_detail)){
			//设置session
			$array = array(
				'system_employee_id'=>$admin_detail['id'],
				'system_employee_name'=>$admin_detail['user_name'],
				'system_level_name'=>$admin_detail['level'],
			);
			$this->session->set_userdata($array);
			echo 'succ';
		}else{
			echo "failed";
		}
	}
	
	//退出登录
	public function logout()
	{
		$this->session->sess_destroy();
		redirect("/employee/login");
	}

}
