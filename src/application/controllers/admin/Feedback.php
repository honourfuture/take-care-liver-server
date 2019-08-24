<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class feedback extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('feedback_model');
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

            $base_url = base_url('/admin/feedback/index');
            if (!empty($keyword)) {
                $base_url .= "?keyword=" . $keyword;
            }
            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->feedback_model->getCount($keyword);
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
            $this->data['administrators_list'] = $this->feedback_model->getAll($config['per_page'], $offset, $keyword);

            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            //加载模板
            $this->template->admin_load('admin/feedback/index', $this->data);
        } else {
            redirect("/admin/feedback");
        }
        //加载模板
    }

    //编辑角色页面
    public function edit()
    {

        if ($this->input->method() == "post") {
            $data['q'] = $this->input->post('q');
            $data['a'] = $this->input->post('a');

            $id = $this->input->post('id');

            if ($id) {
                $data['replay_at'] = date('Y-m-d H:i:s');
                $this->feedback_model->update($id, $data);

            } else {
                $this->feedback_model->create($data);

            }

            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', "修改成功！");

            //返回列表页面
            $form_url = $this->session->userdata('list_page_url');
            if (empty($form_url)) {
                $form_url = "/admin/feedback";
            } else {
                $this->session->unset_userdata('list_page_url');
            }
            redirect($form_url, 'refresh');

        } else {
            $id = $this->uri->segment(4);

            if ($id) {
                $this->data['data'] = $this->feedback_model->find($id);
            }

            $this->template->admin_load('admin/feedback/edit', $this->data);

            //加载模板
        }
    }

    public function del()
    {
        $id = $this->uri->segment(4);

        $data = array();

        if ($this->feedback_model->delete($id)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', "删除成功！");
        } else {
            $data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
        }
        $data['id'] = $id;
        $this->load->view('admin/feedback/modals/del', $data);

    }
}
