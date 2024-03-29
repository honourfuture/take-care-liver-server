<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Employee_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Employee_model');
	}

	public function index()
	{
		$admin_id = $this->checkLogin('E');
		if(!empty($admin_id)){

			$keyword = $this->input->get("keyword");
			$this->data['keyword'] = $keyword;
			$page = $this->input->get("per_page");

			//此配置文件可自行独立
			$this->load->library('pagination');
			$config['use_page_numbers'] = TRUE;
			$config['page_query_string'] = TRUE;
			$config['first_link'] = '&laquo;';
			$config['last_link'] = '&raquo;';
			$config['next_link'] = '下一页';
			$config['prev_link'] = '上一页';

			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';

			$base_url = base_url('/employee/users/index');
			if(!empty($keyword)){
				$base_url .="?keyword=".$keyword;
			}
			$config['base_url'] = $base_url;

			$level = $this->checkLogin('LevelName');
			$employee_ids = $this->Employee_model->get_k_ids($level, $admin_id);
			$param = array("is_operator"=>"0");

			$config['total_rows'] = $this->User_model->getCount($keyword, $param, $employee_ids);
			$config['per_page'] = 20;

			if($page > 1){
				$page = $page - 1;
			}
			else{
				$page = 0;
			}

			$show_begin = $config['per_page'] * $page;
			if($config['total_rows'] > 0)$show_begin = $show_begin+1;

			$show_end = $config['per_page'] * ($page + 1);
			if($config['total_rows'] < $show_end)$show_end = ($config['per_page'] * $page) + ($config['total_rows'] % $config['per_page']);

			$offset = $config['per_page'] * $page;
			$this->data['users_show_begin'] = $show_begin;
			$this->data['users_show_end'] = $show_end;
			$this->data['users_total_rows'] = $config['total_rows'];


			$this->data['users_list'] = $this->User_model->getAll($config['per_page'], $offset, $keyword, $param, $employee_ids);

			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->employee_load('employee/users/index', $this->data);
		}else{
			redirect("/employee/dashboard");
		}

	}

	//已付费
	public function pay()
	{
		$admin_id = $this->checkLogin('E');
		if(!empty($admin_id)){

			$keyword = $this->input->get("keyword");
			$this->data['keyword'] = $keyword;
			$page = $this->input->get("per_page");

			//此配置文件可自行独立
			$this->load->library('pagination');
			$config['use_page_numbers'] = TRUE;
			$config['page_query_string'] = TRUE;
			$config['first_link'] = '&laquo;';
			$config['last_link'] = '&raquo;';
			$config['next_link'] = '下一页';
			$config['prev_link'] = '上一页';

			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';

			$base_url = base_url('/employee/users/index');
			if(!empty($keyword)){
				$base_url .="?keyword=".$keyword;
			}
			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->User_model->getCount($keyword);
			$config['per_page'] = 20;

			if($page > 1){
				$page = $page - 1;
			}
			else{
				$page = 0;
			}

			$show_begin = $config['per_page'] * $page;
			if($config['total_rows'] > 0)$show_begin = $show_begin+1;

			$show_end = $config['per_page'] * ($page + 1);
			if($config['total_rows'] < $show_end)$show_end = ($config['per_page'] * $page) + ($config['total_rows'] % $config['per_page']);

			$offset = $config['per_page'] * $page;
			$this->data['users_show_begin'] = $show_begin;
			$this->data['users_show_end'] = $show_end;
			$this->data['users_total_rows'] = $config['total_rows'];
			$param = array("is_operator"=>"0");

			$level = $this->checkLogin('LevelName');
			$employee_ids = $this->Employee_model->get_k_ids($level, $admin_id);
			$param['is_vip'] =1;
			$this->data['users_list'] = $this->User_model->getAll($config['per_page'], $offset, $keyword,$param, $employee_ids);

			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->employee_load('employee/users/index', $this->data);
		}else{
			redirect("/employee/dashboard");
		}

	}

	//未付费
	public function unpay()
	{
		$admin_id = $this->checkLogin('E');
		if(!empty($admin_id)){

			$keyword = $this->input->get("keyword");
			$this->data['keyword'] = $keyword;
			$page = $this->input->get("per_page");

			//此配置文件可自行独立
			$this->load->library('pagination');
			$config['use_page_numbers'] = TRUE;
			$config['page_query_string'] = TRUE;
			$config['first_link'] = '&laquo;';
			$config['last_link'] = '&raquo;';
			$config['next_link'] = '下一页';
			$config['prev_link'] = '上一页';

			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';

			$base_url = base_url('/employee/users/index');
			if(!empty($keyword)){
				$base_url .="?keyword=".$keyword;
			}
			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->User_model->getCount($keyword);
			$config['per_page'] = 20;

			if($page > 1){
				$page = $page - 1;
			}
			else{
				$page = 0;
			}

			$show_begin = $config['per_page'] * $page;
			if($config['total_rows'] > 0)$show_begin = $show_begin+1;

			$show_end = $config['per_page'] * ($page + 1);
			if($config['total_rows'] < $show_end)$show_end = ($config['per_page'] * $page) + ($config['total_rows'] % $config['per_page']);

			$offset = $config['per_page'] * $page;
			$this->data['users_show_begin'] = $show_begin;
			$this->data['users_show_end'] = $show_end;
			$this->data['users_total_rows'] = $config['total_rows'];

			$level = $this->checkLogin('LevelName');
			$employee_ids = $this->Employee_model->get_k_ids($level, $admin_id);
			$param = array("is_operator"=>"0");
			$param['is_vip'] = 0;
			$this->data['users_list'] = $this->User_model->getAll($config['per_page'], $offset, $keyword,$param,$employee_ids);

			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->employee_load('employee/users/index', $this->data);
		}else{
			redirect("/employee/dashboard");
		}

	}

	//查看用户
	public function view($id)
	{
		$id = $this->uri->segment(4);

		//获取数据
		$user = $this->User_model->find($id);
		if(empty($user)){
			redirect('employee/users', 'refresh');
		}

		// 传递数据
		$this->data['user']  = $user;

		//当前列表页面的url
		$form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
		if(strripos($form_url,"employee/users/index") === FALSE){
			$form_url = "/employee/users";
		}
		$this->data['form_url'] = $form_url;


		//加载模板
		$this->template->employee_load('employee/users/view', $this->data);
	}



}
