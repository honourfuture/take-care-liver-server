<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/Myupload.php';
class Dashboard extends Admin_Controller {

	function __construct(){
        parent::__construct();
    }
	
	//加载登录页面
	public function index()
	{
		$admin_id = $this->checkLogin('A');

        $this->load->model('User_Model');
        $this->load->model('OrderAndPay_model');
        $this->load->model('Product_model');

        $this->data['userCount'] = $this->User_Model->getAllPageTotal();
        $this->data['vipCount'] = $this->User_Model->getAllPageTotal(['is_vip'=> 1]);
        $this->data['operatorCount'] = $this->User_Model->getAllPageTotal(['is_operator'=> 1]);
        $this->data['orderCount'] = $this->OrderAndPay_model->getOrderCount();
        $this->data['orders'] = $this->OrderAndPay_model->getOrderAll(['status' => 20]);
//        $this->data['products'] = $this->Product_model->getAll(['type' => 3]);

//        print_r($this->data['products']);die;
		$this->template->admin_load('admin/dashboard', $this->data);
	}

	//加载登录页面
	public function login()
	{
		$this->load->view('admin/login');
	}
	
	//检查管理员账号
	public function check_admin()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$admin_detail = $this->admin_model->admin_check($username,$password);
		if(!empty($admin_detail)){
			//设置session
			$array = array(
				'system_admin_id'=>$admin_detail['id'],
				'system_admin_name'=>$admin_detail['user_name'],
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
		redirect("/admin/login");
	}

}
