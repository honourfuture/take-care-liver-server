<?php
/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/8/19
 * Time: 下午7:11
 */

class Permissions extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('permission_model');
        $this->load->model('menu_model');
    }

    //角色列表界面
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

            $base_url = base_url('/admin/permissions');
            if (!empty($keyword)) {
                $base_url .= "?keyword=" . $keyword;
            }
            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->permission_model->getCount($keyword);
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
            $this->data['permissions_show_begin'] = $show_begin;
            $this->data['permissions_show_end'] = $show_end;
            $this->data['permissions_total_rows'] = $config['total_rows'];
            $this->data['permissions_list'] = $this->permission_model->getAll($config['per_page'], $offset, $keyword);

            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            //加载模板
            $this->template->admin_load('admin/permissions/index', $this->data);
        } else {
            redirect("/admin/admin");
        }
    }

    //编辑
    public function edit()
    {
        $id = $this->uri->segment(4);
        if ($this->input->method() == "post") {
            // 表单校验
            $this->form_validation->set_rules('name', '权限名称', 'required|min_length[2]');
            $this->form_validation->set_rules('url', '权限url', 'required');
            $this->form_validation->set_rules('menu', '所属菜单', 'required');
            if ($this->form_validation->run() == true) {
                $name = $this->input->post('name');
                $url = $this->input->post('url');
                $menu = $this->input->post('menu');
                $data = array(
                    'name' => $name,
                    'url' => $url,
                    'menu_id' => $menu
                );
                if ($id) {
                    $this->permission_model->update($id, $data);
                } else {
                    $this->permission_model->create($data);
                }
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "修改成功！");

                //返回列表页面
                $form_url = $this->session->userdata('list_page_url');
                if (empty($form_url)) {
                    $form_url = "/admin/permissions";
                } else {
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');
            } else {
                // 传递错误信息
                $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
                $this->data['name'] = $this->form_validation->set_value('name');
                $this->data['url'] = $this->form_validation->set_value('url');

                //当前列表页面的url
                $form_url = $this->session->userdata('list_page_url');
                if (empty($form_url)) {
                    $form_url = "/admin/permissions";
                }
                $this->data['form_url'] = $form_url;
            }
        } else {
            //当前列表页面的url
            $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
            if (!(strripos($form_url, "admin/permissions/index") === false)) {
                $this->session->set_userdata('list_page_url', $form_url);
            } else {
                $form_url = "/admin/permissions/" . $id;
            }
            //获取菜单列表
            $menus = $this->menu_model->getAll();
            $this->data['form_url'] = $form_url;
            $this->data['menu_list'] = $menus;
            if ($id) {
                //获取数据
                $this->data['data'] = $this->permission_model->find($id);
                if (empty($this->data['data'])) {
                    redirect('admin/permissions', 'refresh');
                }
            }
        }
        //加载模板
        $this->template->admin_load('admin/permissions/edit', $this->data);
    }

    //删除角色页面
    public function del()
    {
        $id = $this->uri->segment(4);
        $data = array();
        //获取数据
        $permission = $this->permission_model->find($id);
        if (empty($permission)) {
            redirect('admin/permissions', 'refresh');
        } else {
            if ($this->input->post("id") == $id) {
                if ($this->permission_model->delete($id)) {
                    $this->session->set_flashdata('message_type', 'success');
                    $this->session->set_flashdata('message', "删除成功！");
                } else {
                    $data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除权限时发生错误，请稍后再试！</div>";
                }
            }
        }

        $data['permission'] = $permission;
        $this->load->view('admin/permissions/modals/del', $data);
    }
}