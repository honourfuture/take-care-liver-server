<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrators extends Employee_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('employee_model');
	}

	//编辑页面
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
					$this->employee_model->update($id, $data);
				} else {

					$this->employee_model->create($data);
				}

				$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', "修改成功！");

				//返回列表页面
				$form_url = $this->session->userdata('list_page_url');
				if (empty($form_url)) {
					$form_url = "/employee/dashboard";
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
					$form_url = "/employee/dashboard";
				}
				$this->data['form_url'] = $form_url;
			}
		} else {
			//当前列表页面的url
            $form_url = "/employee/dashboard";
			$this->data['form_url'] = $form_url;

			if($id) {
				//获取数据
				$this->data['data'] = $this->employee_model->find($id);

				if (empty($this->data['data'])) {
					redirect('employee/dashboard', 'refresh');
				}
			}

		}

		//加载模板
		$this->template->employee_load('employee/administrators/edit', $this->data);
	}

	
}
