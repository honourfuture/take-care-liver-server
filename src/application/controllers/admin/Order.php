<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Admin_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model('OrderAndPay_model');
	}

	public function index()
	{
        $this->data['start_date'] = $this->input->get('start_date');
        $this->data['end_date'] = $this->input->get('end_date');
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

			$base_url = base_url('/admin/order/index');

			if(!empty($keyword)){
				$base_url .="?keyword=".$keyword;
			}
			$config['base_url'] = $base_url;
			$config['total_rows'] = $this->OrderAndPay_model->getCount($keyword, []);
			$config['per_page'] = 20;

			if($page > 1){
				$page = $page - 1;
			}
			else{
				$page = 0;
			}
            if($this->data['start_date']){
                $this->db->where('o.create_time >=', $this->data['start_date']);
            }

            if($this->data['end_date']){
                $this->db->where('o.create_time <=', $this->data['end_date']);
            }

			$show_begin = $config['per_page'] * $page;
			if($config['total_rows'] > 0)$show_begin = $show_begin+1;

			$show_end = $config['per_page'] * ($page + 1);
			if($config['total_rows'] < $show_end)$show_end = ($config['per_page'] * $page) + ($config['total_rows'] % $config['per_page']);

			$offset = $config['per_page'] * $page;
			$this->data['users_show_begin'] = $show_begin;
			$this->data['users_show_end'] = $show_end;
			$this->data['users_total_rows'] = $config['total_rows'];
			$this->data['order_list'] = $this->OrderAndPay_model->getAll($config['per_page'], $offset, $keyword, []);
            $this->data['page'] = 'index';
			//初始化分页
			$this->load->library('pagination');
			$this->pagination->initialize($config);

			//加载模板
			$this->template->admin_load('admin/order/index', $this->data);
		}else{
			redirect("/admin/admin");
		}

	}

    public function wait()
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

            $base_url = base_url('/admin/order/wait');

            if(!empty($keyword)){
                $base_url .="?keyword=".$keyword;
            }

            $wheres = [
              'status' => 10
            ];
            $this->data['start_date'] = $this->input->get('start_date');
            $this->data['end_date'] = $this->input->get('end_date');
            if($this->data['start_date']){
                $wheres['o.create_time >='] = $this->data['start_date'];
            }

            if($this->data['end_date']){
                $wheres['o.create_time <='] = $this->data['end_date'];
            }
            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->OrderAndPay_model->getCount($keyword, $wheres);
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
            $this->data['order_list'] = $this->OrderAndPay_model->getAll($config['per_page'], $offset, $keyword, $wheres);
            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);
            $this->data['page'] = 'wait';
            //加载模板
            $this->template->admin_load('admin/order/index', $this->data);
        }else{
            redirect("/admin/admin");
        }

    }

    public function over()
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

            $base_url = base_url('/admin/order/over');

            if(!empty($keyword)){
                $base_url .="?keyword=".$keyword;
            }

            $wheres = [
                'status' => 20
            ];

            $this->data['start_date'] = $this->input->get('start_date');
            $this->data['end_date'] = $this->input->get('end_date');
            if($this->data['start_date']){
                $wheres['o.create_time >='] = $this->data['start_date'];
            }

            if($this->data['end_date']){
                $wheres['o.create_time <='] = $this->data['end_date'];
            }
            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->OrderAndPay_model->getCount($keyword, $wheres);
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
            $this->data['order_list'] = $this->OrderAndPay_model->getAll($config['per_page'], $offset, $keyword, $wheres);
            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);
            $this->data['page'] = 'over';
            //加载模板
            $this->template->admin_load('admin/order/index', $this->data);
        }else{
            redirect("/admin/admin");
        }

    }
}
