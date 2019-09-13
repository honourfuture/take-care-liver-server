<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrators extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('admin_model');
	}

	//编辑角色页面
	public function edit()
	{
		$id = $this->uri->segment(4);


		if ($this->input->method() == "post") {
			// 表单校验
			if($id) {
				$this->form_validation->set_rules('user_name', '管理员名称', 'required|min_length[2]');
			} else {
				$this->form_validation->set_rules('user_name', '管理员名称', 'required|min_length[2]|is_unique[admin_users.user_name]');
			}
			$this->form_validation->set_rules('password', '密码', 'required|min_length[6]');

			if ($this->form_validation->run() == true) {

				$name = $this->input->post('user_name');
				$password = $this->input->post('password');
				$data =array(
					'user_name'        => $name,
					'password'=>md5($password)
				);
				if($id) {

					$this->admin_model->update($id, $data);
				} else {

					$this->admin_model->create($data);
				}

				$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', "修改成功！");

				//返回列表页面
				$form_url = $this->session->userdata('list_page_url');
				if (empty($form_url)) {
					$form_url = "/admin/administrators";
				} else {
					$this->session->unset_userdata('list_page_url');
				}
				redirect($form_url, 'refresh');
			} else {
				// 传递错误信息
				$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

				$this->data['name'] = $this->form_validation->set_value('name');
				$this->data['description'] = $this->form_validation->set_value('description');

				//当前列表页面的url
				$form_url = $this->session->userdata('list_page_url');
				if (empty($form_url)) {
					$form_url = "/admin/administrators";
				}
				$this->data['form_url'] = $form_url;
			}
		} else {
			//当前列表页面的url
			$form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
			if (!(strripos($form_url, "admin/administrators/index") === false)) {
				$this->session->set_userdata('list_page_url', $form_url);
			} else {
				$form_url = "/admin/administrators/".$id;
			}
			$this->data['form_url'] = $form_url;

			if($id) {
				//获取数据
				$this->data['data'] = $this->admin_model->find($id);

				if (empty($this->data['data'])) {
					redirect('admin/administrators', 'refresh');
				}
			}

		}

		//加载模板
		$this->template->admin_load('admin/administrators/edit', $this->data);
	}

	//删除角色页面
	public function del()
	{
		$id = $this->uri->segment(4);

		$data = array();

		//获取数据
		$role = $this->admin_model->find($id);
		if (empty($role)) {
			redirect('admin/administrators', 'refresh');
		} else {

			if ($this->input->post("id") == $id) {
				if ($this->admin_model->delete($id)) {
					$this->session->set_flashdata('message_type', 'success');
					$this->session->set_flashdata('message', "删除成功！");
				} else {
					$data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除角色时发生错误，请稍后再试！</div>";
				}
			}
		}

		$data['role'] = $role;
		$this->load->view('admin/administrators/modals/del', $data);

	}

	//查看角色页面
	public function view($id)
	{
		$id = $this->uri->segment(4);

		//获取数据
		$role = $this->admin_model->find($id);
		if (empty($role)) {
			redirect('admin/administrators', 'refresh');
		}

		// 传递数据
		$this->data['role'] = $role;

		//当前列表页面的url
		$form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
		if (strripos($form_url, "admin/administrators/index") === false) {
			$form_url = "/admin/administrators";
		}
		$this->data['form_url'] = $form_url;

		//加载模板
		$this->template->admin_load('admin/administrators/view', $this->data);
	}

	//管理员列表界面
	public function index()
	{

		$admin_id = $this->checkLogin('A');
		if (!empty($admin_id)) {

			$keyword = $this->input->get("keyword");
			$this->data['keyword'] = $keyword;
			$page = $this->input->get("per_page");

			//此配置文件可自行独立
			$this->load->library('pagination');
			$config['use_page_numbers'] = true;
			$config['page_query_string'] = true;
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

			$base_url = base_url('/admin/administrators/index');
			if (!empty($keyword)) {
				$base_url .= "?keyword=" . $keyword;
			}
			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->admin_model->getCount($keyword);
			$config['per_page'] = 20;

			if ($page > 1) {
				$page = $page - 1;
			} else {
				$page = 0;
			}

			$show_begin = $config['per_page'] * $page;
			if ($config['total_rows'] > 0) $show_begin = $show_begin + 1;

			$show_end = $config['per_page'] * ($page + 1);
			if ($config['total_rows'] < $show_end) $show_end = ($config['per_page'] * $page) + ($config['total_rows'] % $config['per_page']);

			$offset = $config['per_page'] * $page;
			$this->data['administrators_show_begin'] = $show_begin;
			$this->data['administrators_show_end'] = $show_end;
			$this->data['administrators_total_rows'] = $config['total_rows'];
			$this->data['administrators_list'] = $this->admin_model->getAll($config['per_page'], $offset, $keyword);

			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->admin_load('admin/administrators/index', $this->data);
		} else {
			redirect("/admin/admin");
		}
	}

    /**
     * 无权访问连接
     */
    public function no_permission()
    {
        $this->data['heading'] = '';
        $this->data['message'] = '您无权访问该操作';
        $this->template->admin_load('errors/cli/error_general', $this->data);
    }

    /**
     * 管理员角色
     */
    public function admin_roles()
    {
        $user_id = $this->uri->segment(4);
        //查询用户信息
        $user = $this->admin_model->find($user_id);
        //查询所有角色
        $roles = $this->role_model->findAll();
        //查询用户拥有的角色
        $user_roles = $this->admin_user_role_model->find_by_admin_id($user_id);

        for ($i = 0; $i < count($roles); $i++) {
            foreach ($user_roles as $user_role) {
                if ($user_role->role_id == $roles[$i]->id) {
                    $roles[$i]->check = true;
                    break;
                }
            }
        }

        $this->data['role_list'] = $roles;
        $this->data['user_id'] = $user_id;
        $this->data['user_name'] = $user->user_name;
        //加载模板
        $this->template->admin_load('admin/user_role/index', $this->data);
    }

    public function save_admin_role()
    {
        $this->form_validation->set_rules('user_id', '管理员ID', 'required');
        if ($this->form_validation->run() == true) {
            $user_id = $this->input->post('user_id');
            $role_ids = $this->input->post('role_ids');
            //优先删除用户id下的所有角色
            $this->admin_user_role_model->delete_by_user_id($role_id);
            //添加选中的权限id
            foreach ($role_ids as $role_id) {
                $this->admin_user_role_model->create(array(
                    'role_id' => $role_id,
                    'user_id' => $user_id
                ));
            }
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', "修改成功！");
            //返回列表页面
            $form_url = $this->session->userdata('list_page_url');
            if (empty($form_url)) {
                $form_url = "/admin/administrators";
            } else {
                $this->session->unset_userdata('list_page_url');
            }
            redirect($form_url, 'refresh');
        } else {
            // 传递错误信息
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            //当前列表页面的url
            redirect("/admin/administrators", 'refresh');
        }
    }
}
