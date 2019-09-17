<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('User_model');
        $this->load->model('Product_model');
	}

	public function index()
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
                'type' => 3
            ];

			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->Product_model->getCount($wheres);
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
            $this->data['type'] = 3;
			$this->data['users_total_rows'] = $config['total_rows'];
			$this->data['users_list'] = $this->Product_model->getAllByCid($wheres, $config['per_page'], $offset);
			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->admin_load('admin/product/index', $this->data);
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

            $base_url = base_url('/admin/operator/list');

            $wheres = [
                'type' => 4
            ];

            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->Product_model->getCount($wheres);
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
            $this->data['users_list'] = $this->Product_model->getAllByCid($wheres, $config['per_page'], $offset);
            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            //加载模板
            $this->template->admin_load('admin/product/index', $this->data);
        }else{
            redirect("/admin/admin");
        }
    }

	//删除用户
	public function del($id=0)
	{
		$id = $this->uri->segment(4);
		if(empty($id)){
			redirect('admin/products/index', 'refresh');
		}
		$data = array();

		//获取数据
		$product = $this->Product_model->find($id);
		if(empty($user)){
			redirect('admin/products/index', 'refresh');
		}
		else{

			if($this->input->post("id") == $id)
			{
				if($this->Product_model->delete($id)){
					$this->session->set_flashdata('message_type', 'success');
					$this->session->set_flashdata('message', "删除成功！");
				}
				else{
					$data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除数据时发生错误，请稍后再试！</div>";
				}
			}
		}

		$data['product'] = $product;
		$this->load->view('admin/products/modals/del', $data);
	}

	//更新用户信息
	public function details($id = 0)
	{
		$id = $this->uri->segment(4);

		//获取数据
		$product = $this->Product_model->find($id);

		if($this->input->method() == "post")
		{
			// 表单校验
			$this->form_validation->set_rules('name', '商品名称', 'required|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('price', '价格', 'required|min_length[1]|max_length[11]');
			$this->form_validation->set_rules('describe', '商品描述', 'required');

            $type = $this->input->post('type');

            $url = $type == 3 ? 'index' : 'member';
			if ($this->form_validation->run() == TRUE)
			{
				$name = $this->input->post('name');
				$price = $this->input->post('price');
				$old_price = $this->input->post('old_price');
				$describe = $this->input->post('describe');
				$pic = $this->input->post('pic');
                $banner = $this->input->post('banner');
				$details = $this->input->post('details');

				$data = array(
					'name' => $name,
					'price'  => $price,
					'old_price'  => $old_price,
					'describe'  => $describe,
					'pic'  => $pic,
					'details'	=> $details,
                    'banner_pic' => json_encode($banner)
				);


				$this->Product_model->update($id, $data);

				$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', "修改成功！");

                $form_url = "/admin/products/{$url}";
				redirect($form_url, 'refresh');
			}
			else{
				// 传递错误信息
				$this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

				$this->data['name'] = $this->form_validation->set_value('name');
				$this->data['price'] = $this->form_validation->set_value('price');
                $this->data['old_price'] = $this->form_validation->set_value('old_price');

                $form_url = "/admin/products/{$url}";
				$this->data['form_url'] = $form_url;
			}
		}
		else{
			//当前列表页面的url
			$form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];

			$this->data['form_url'] = $form_url;
		}

		$product->banner_pic = json_decode($product->banner_pic, true);
		// 传递数据
		$this->data['product'] = $product;

		//加载模板
		$this->template->admin_load('admin/product/edit', $this->data);
	}

	//创建用户
	public function create()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		if($this->input->method() == "post"){

            // 表单校验
			$this->form_validation->set_rules('name', '商品名称', 'required|min_length[2]|max_length[20]');
			$this->form_validation->set_rules('price', '价格', 'required|min_length[1]|max_length[11]');
			$this->form_validation->set_rules('describe', '商品描述', 'required');

			if ($this->form_validation->run() == TRUE)
			{
			    $name = $this->input->post('name');
                $price = $this->input->post('price');
                $old_price = $this->input->post('old_price');
                $describe = $this->input->post('describe');
                $pic = $this->input->post('pic');
                $banner = $this->input->post('banner');
                $details = $this->input->post('details');

                $data = array(
                    'name' => $name,
                    'price'  => $price,
                    'old_price'  => $old_price,
                    'describe'  => $describe,
                    'pic'  => $pic,
                    'details'	=> $details,
                    'banner_pic' => json_encode($banner),
                    'type' => 3
                );

                $this->Product_model->create($data);

				$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', "添加成功！");

				redirect('admin/products/index', 'refresh');
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
		$this->template->admin_load('admin/product/create', $this->data);
	}

}
