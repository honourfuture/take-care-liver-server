<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends Admin_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('User_model');
        $this->load->model('Card_bank_model');
        $this->load->model('BalanceDetails_model');


	}

	public function list()
	{
		$admin_id = $this->checkLogin('A');
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

			$base_url = base_url('/admin/operator/list');
			$wheres = [
			  'is_operator' => 1
            ];

			if(!empty($keyword)){
				$base_url .="?keyword=".$keyword;
			}
			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->User_model->getCount($keyword, $wheres);
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
			$this->data['users_list'] = $this->User_model->getAll($config['per_page'], $offset, $keyword, $wheres);

			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->admin_load('admin/operator/list', $this->data);
		}else{
			redirect("/admin/admin");
		}
	}

    public function apply()
    {
        $admin_id = $this->checkLogin('A');
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

            $base_url = base_url('/admin/operator/apply');
            $wheres = [
                'is_operator' => 2
            ];

            if(!empty($keyword)){
                $base_url .="?keyword=".$keyword;
            }
            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->User_model->getCount($keyword, $wheres);
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
            $this->data['users_list'] = $this->User_model->getAll($config['per_page'], $offset, $keyword, $wheres);

            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            //加载模板
            $this->template->admin_load('admin/operator/apply', $this->data);
        }else{
            redirect("/admin/admin");
        }
    }

    public function card()
    {
        $admin_id = $this->checkLogin('A');
        if(!empty($admin_id)){
            $user_id = $this->input->get("user_id");
            $wheres = array('user_id' => $user_id);
            $this->data['list'] = $this->Card_bank_model->get($wheres);

            //加载模板
            $this->template->admin_load('admin/operator/card', $this->data);
        }else{
            redirect("/admin/admin");
        }
    }

    public function balance()
    {
        $admin_id = $this->checkLogin('A');
        if(!empty($admin_id)){
            $user_id = $this->input->get("user_id");
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

            $base_url = base_url('/admin/operator/apply');
            $wheres = [
                'user_id' => $user_id,
            ];
            if(!empty($keyword)){
                $base_url .="?keyword=".$keyword;
            }
            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->BalanceDetails_model->getCount($wheres);
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


            $this->data['users_list'] =  $this->BalanceDetails_model->getList($wheres, $config['per_page'], $offset);
            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $this->template->admin_load('admin/operator/balance', $this->data);
        }else{
            redirect("/admin/admin");
        }
    }

    public function member()
    {
        $admin_id = $this->checkLogin('A');
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

            $base_url = base_url('/admin/operator/member');
            $user_id = $this->input->get("user_id");

            $wheres = [
                'is_operator' => 1,
                'is_vip' => 1,
                'parent_id' => $user_id
            ];

            if(!empty($keyword)){
                $base_url .="?keyword=".$keyword;
            }
            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->User_model->getCount($keyword, $wheres);
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
            $this->data['users_list'] = $this->User_model->getAll($config['per_page'], $offset, $keyword, $wheres);

            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            //加载模板
            $this->template->admin_load('admin/operator/member', $this->data);
        }else{
            redirect("/admin/admin");
        }

    }

    public function editReal($id = 0)
    {
        if($this->input->method() == "post") {
            //获取数据
            $user = $this->User_model->find($id);
            if (empty($user)) {
                redirect('admin/operator/list', 'refresh');
            }

            $data['is_operator'] = $name = $this->input->post('is_operator');

            $isSuccess = $this->User_model->update($id, $data);
            if ($isSuccess) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "审核成功！");
            } else {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', "审核失败！");
            }
            redirect('admin/operator/list', 'refresh');
        }else{
            $id = $this->uri->segment(4);

            //获取数据
            $user = $this->User_model->find($id);

            if(empty($user)){
                redirect('admin/operator/list', 'refresh');
            }

            // 传递数据
            $this->data['user']  = $user;

            //当前列表页面的url
            $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
            if(strripos($form_url,"admin/operator/list") === FALSE){
                $form_url = "/admin/operator/list";
            }
            $this->data['form_url'] = $form_url;

            //加载模板
            $this->template->admin_load('admin/operator/editReal', $this->data);
        }
    }
	//查看用户
	public function view($id)
	{
		$id = $this->uri->segment(4);

		//获取数据
		$user = $this->User_model->find($id);
		if(empty($user)){
			redirect('admin/users', 'refresh');
		}

		// 传递数据
		$this->data['user']  = $user;

		//当前列表页面的url
		$form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
		if(strripos($form_url,"admin/users/index") === FALSE){
			$form_url = "/admin/users";
		}
		$this->data['form_url'] = $form_url;


		//加载模板
		$this->template->admin_load('admin/users/view', $this->data);
	}


	//删除用户
	public function del($id=0)
	{
		$id = $this->uri->segment(4);
		if(empty($id)){
			redirect('admin/users', 'refresh');
		}
		$data = array();

		//获取数据
		$user = $this->User_model->find($id);
		if(empty($user)){
			redirect('admin/users', 'refresh');
		}
		else{

			if($this->input->post("id") == $id)
			{
				if($this->User_model->delete($id)){
					$this->session->set_flashdata('message_type', 'success');
					$this->session->set_flashdata('message', "删除成功！");
				}
				else{
					$data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除数据时发生错误，请稍后再试！</div>";
				}
			}
		}

		$data['user'] = $user;
		$this->load->view('admin/users/modals/del', $data);
	}

	//更新用户信息
	public function edit($id = 0)
	{
		$id = $this->uri->segment(4);

		//获取数据
		$user = $this->User_model->find($id);
		if(empty($user)){
			redirect('admin/users', 'refresh');
		}

		if($this->input->method() == "post")
		{
			// 表单校验
			$this->form_validation->set_rules('name', '姓名', 'required|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('username', '昵称', 'required|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('mobile', '手机', 'required|min_length[11]|max_length[11]');
			$this->form_validation->set_rules('active', '状态', 'required');


			if ($this->form_validation->run() == TRUE)
			{
				$name = $this->input->post('name');
				$username = $this->input->post('username');
				$mobile = $this->input->post('mobile');
				$password = $this->input->post('password');
				$active = $this->input->post('active');
				$gender = $this->input->post('gender');
				$birthday = $this->input->post('birthday');
				$info = $this->input->post('info');


				$data = array(
					'name' => $name,
					'username'  => $username,
					'mobile'  => $mobile,
					'password'  => $password,
					'active'  => $active,
					'updated'	=> time(),
					'birthday'	=> $birthday,
					'gender'	=> $gender,
					'info'	=> $info,
				);

				$this->User_model->update($id, $data);

				$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', "修改成功！");

				//返回列表页面
				$form_url = $this->session->userdata('list_page_url');
				if(empty($form_url)){
					$form_url = "/admin/users";
				}
				else{
					$this->session->unset_userdata('list_page_url');
				}

				redirect($form_url, 'refresh');
			}
			else{
				// 传递错误信息
				$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

				$this->data['name'] = $this->form_validation->set_value('name');
				$this->data['username'] = $this->form_validation->set_value('username');
				$this->data['mobile'] = $this->form_validation->set_value('mobile');
				$this->data['password'] = $this->form_validation->set_value('password');
				$this->data['active'] = $this->form_validation->set_value('active');


				//当前列表页面的url
				$form_url = $this->session->userdata('list_page_url');
				if(empty($form_url)){
					$form_url = "/admin/users";
				}
				$this->data['form_url'] = $form_url;

			}
		}
		else{
			//当前列表页面的url
			$form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
			if(!(strripos($form_url,"admin/users/index") === FALSE)){
				$this->session->set_userdata('list_page_url', $form_url);
			}
			else{
				$form_url = "/admin/users";
			}
			$this->data['form_url'] = $form_url;

		}

		// 传递数据
		$this->data['user'] = $user;

		$this->data['name'] = isset($this->data['name']) ? $this->data['name'] : $user->name ;
		$this->data['username'] = isset($this->data['username']) ? $this->data['username'] : $user->username ;
		$this->data['mobile'] = isset($this->data['mobile']) ? $this->data['mobile'] : $user->mobile ;
		$this->data['password'] = isset($this->data['password']) ? $this->data['password'] : $user->password ;
		$this->data['active'] = isset($this->data['active']) ? $this->data['active'] : $user->active ;
		$this->data['birthday'] = isset($this->data['birthday']) ? $this->data['birthday'] : $user->birthday ;
		$this->data['gender'] = isset($this->data['gender']) ? $this->data['gender'] : $user->gender ;
		$this->data['info'] = isset($this->data['info']) ? $this->data['info'] : $user->info ;

		//加载模板
		$this->template->admin_load('admin/users/edit', $this->data);
	}

	//创建用户
	public function create()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		if($this->input->method() == "post"){

			// 表单校验
			$this->form_validation->set_rules('name', '姓名', 'required|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('username', '昵称', 'required|min_length[2]|max_length[20]|is_unique[users.username]');
			$this->form_validation->set_rules('mobile', '手机', 'required|min_length[11]|max_length[11]|is_unique[users.mobile]');
			$this->form_validation->set_rules('password', '密码', 'required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('active', '状态', 'required');

			if ($this->form_validation->run() == TRUE)
			{
				$name = $this->input->post('name');
				$username = $this->input->post('username');
				$mobile = $this->input->post('mobile');
				$password = $this->input->post('password');
				$active = $this->input->post('active');
				$gender = $this->input->post('gender');
				$birthday = $this->input->post('birthday');
				$info = $this->input->post('info');

				$data = array(
					'name' => $name,
					'username'  => $username,
					'mobile'  => $mobile,
					'password'  => $password,
					'active'  => $active,
					'created'	=> time(),
					'birthday'	=> $birthday,
					'gender'	=> $gender,
					'info'	=> $info,
				);
				$this->User_model->create($data);

				$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', "添加成功！");

				redirect('admin/users', 'refresh');
			}
			else
			{
				$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

				$this->data['name'] = $this->form_validation->set_value('name');
				$this->data['username'] = $this->form_validation->set_value('username');
				$this->data['password'] = $this->form_validation->set_value('password');
				$this->data['mobile'] = $this->form_validation->set_value('mobile');
				$this->data['active'] = $this->form_validation->set_value('active');
			}
		}

		//加载模板
		$this->template->admin_load('admin/users/create', $this->data);
	}

	//重置密码
	public function reset_password()
	{
		$id = $this->uri->segment(4);
		if(empty($id)){
			redirect('admin/users', 'refresh');
		}
		//获取数据
		$user = $this->User_model->find($id);
		if(empty($user)){
			redirect('admin/users', 'refresh');
		}else{
			if($this->input->post("id") == $id)
			{
				//TODO优化加密，以及重置方式
				$password = md5("11111");
				$data = array(
					'password'  => $password,
					'updated'	=> time(),
//                    'updated'	=> date('Y-m-d H:i:s'),
				);
				if($this->User_model->update($id, $data)){
					$this->session->set_flashdata('message_type', 'success');
					$this->session->set_flashdata('message', "重置成功！");
				}else{
					$data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>重置密码时发生错误，请稍后再试！</div>";
				}
			}
		}
		$data['user'] = $user;
		$this->load->view('admin/users/modals/reset_password', $data);
	}

	/**
	 * 用户积分兑换记录
	 */
	public function integral()
	{
		$admin_id = $this->checkLogin('A');
		if(!empty($admin_id)){
			$keyword = $this->uri->segment(4);
			if(empty($keyword)){
				redirect('admin/users', 'refresh');
			}
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

			$base_url = base_url('/admin/users/integral');
			if(!empty($keyword)){
				$base_url .="?keyword=".$keyword;
			}
			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->userbonus_model->getCount('',$keyword);
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
			$this->data['integrals_show_begin'] = $show_begin;
			$this->data['integrals_show_end'] = $show_end;
			$this->data['integrals_total_rows'] = $config['total_rows'];
			$this->data['integrals_list'] = $this->userbonus_model->getAll($config['per_page'], $offset, '',$keyword);

			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->admin_load('admin/users/integral', $this->data);
		}else{
			redirect("/admin");
		}

	}

	/**
	 * 用户乘车记录
	 */
	public function rides()
	{
		$admin_id = $this->checkLogin('A');
		if(!empty($admin_id)){
			$keyword = $this->uri->segment(4);
			if(empty($keyword)){
				redirect('admin/users', 'refresh');
			}
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

			$base_url = base_url('/admin/users/rides');
			if(!empty($keyword)){
				$base_url .="?keyword=".$keyword;
			}
			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->User_model->find_rides_count_by_mobile($keyword);
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
			$this->data['rides_show_begin'] = $show_begin;
			$this->data['rides_show_end'] = $show_end;
			$this->data['rides_total_rows'] = $config['total_rows'];
			$this->data['rides_list'] = $this->User_model->find_rides_by_mobile($config['per_page'], $offset, $keyword);

			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->admin_load('admin/users/rides', $this->data);
		}else{
			redirect("/admin");
		}

	}

	/**
	 * 订单评价记录
	 */
	public function evaluation()
	{
		$admin_id = $this->checkLogin('A');
		if(!empty($admin_id)){
			$keyword = $this->input->get("keyword");
			$user_id = $this->uri->segment(4);
			if(empty($user_id)){
				redirect('admin/users', 'refresh');
			}
			$this->data['keyword'] = $keyword;
			$this->data['user_id'] = $user_id;
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

			$base_url = base_url('/admin/users/evaluation');
			if(!empty($keyword)){
				$base_url .="?keyword=".$keyword;
			}
			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->evaluation_model->getCount($keyword,$user_id);
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
			$this->data['evaluations_show_begin'] = $show_begin;
			$this->data['evaluations_show_end'] = $show_end;
			$this->data['evaluations_total_rows'] = $config['total_rows'];
			$this->data['evaluations_list'] = $this->evaluation_model->getAll($config['per_page'], $offset, $keyword, $user_id);

			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->admin_load('admin/users/evaluation', $this->data);
		}else{
			redirect("/admin");
		}

	}
}
