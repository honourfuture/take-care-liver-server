<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/8/19
 * Time: 上午11:36
 */
class Roles extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model');
        $this->load->model('role_permission_model');
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

            $base_url = base_url('/admin/roles');
            if (!empty($keyword)) {
                $base_url .= "?keyword=" . $keyword;
            }
            $config['base_url'] = $base_url;
            $config['total_rows'] = $this->role_model->getCount($keyword);
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
            $this->data['roles_show_begin'] = $show_begin;
            $this->data['roles_show_end'] = $show_end;
            $this->data['roles_total_rows'] = $config['total_rows'];
            $this->data['roles_list'] = $this->role_model->getAll($config['per_page'], $offset, $keyword);

            //初始化分页
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            //加载模板
            $this->template->admin_load('admin/roles/index', $this->data);
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
            if ($id) {
                $this->form_validation->set_rules('name', '角色名称', 'required|min_length[2]');
            } else {
                $this->form_validation->set_rules('name', '角色名称', 'required|min_length[2]|is_unique[admin_roles.name]');
            }
            $this->form_validation->set_rules('description', '描述', 'required');
            if ($this->form_validation->run() == true) {
                $name = $this->input->post('name');
                $description = $this->input->post('description');
                $data = array(
                    'name' => $name,
                    'description' => $description
                );
                if ($id) {
                    $this->role_model->update($id, $data);
                } else {
                    $this->role_model->create($data);
                }
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "修改成功！");

                //返回列表页面
                $form_url = $this->session->userdata('list_page_url');
                if (empty($form_url)) {
                    $form_url = "/admin/roles";
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
                    $form_url = "/admin/roles";
                }
                $this->data['form_url'] = $form_url;
            }
        } else {
            //当前列表页面的url
            $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
            if (!(strripos($form_url, "admin/roles/index") === false)) {
                $this->session->set_userdata('list_page_url', $form_url);
            } else {
                $form_url = "/admin/roles/" . $id;
            }
            $this->data['form_url'] = $form_url;
            if ($id) {
                //获取数据
                $this->data['data'] = $this->role_model->find($id);
                if (empty($this->data['data'])) {
                    redirect('admin/roles', 'refresh');
                }
            }
        }
        //加载模板
        $this->template->admin_load('admin/roles/edit', $this->data);
    }

    //删除角色页面
    public function del()
    {
        $id = $this->uri->segment(4);
        $data = array();
        //获取数据
        $role = $this->role_model->find($id);
        if (empty($role)) {
            redirect('admin/roles', 'refresh');
        } else {
            if ($this->input->post("id") == $id) {
                if ($this->role_model->delete($id)) {
                    $this->session->set_flashdata('message_type', 'success');
                    $this->session->set_flashdata('message', "删除成功！");
                } else {
                    $data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除角色时发生错误，请稍后再试！</div>";
                }
            }
        }

        $data['role'] = $role;
        $this->load->view('admin/roles/modals/del', $data);
    }

    /**
     * 根据角色id查询所有权限
     * @return mixed
     */
    public function role_permissions()
    {
        $role_id = $this->uri->segment(4);

        //查询所有模块
        $menus = $this->menu_model->getAll();
        $menu_ids = array();
        foreach ($menus as $menu) {
            $menu_ids[] = $menu->id;
        }
        //根据菜单获取所有权限
        $permissions = $this->permission_model->find_by_menu_ids($menu_ids);
        $has_permissions = $this->role_permission_model->find_by_roleid($role_id);
        $has_permission_ids = array();
        foreach ($has_permissions as $has_permission) {
            $has_permission_ids[] = $has_permission->permission_id;
        }

        $num = count($menus);
        for ($i = 0; $i < $num; $i++) {
            $menus[$i]->permissions = array();
            foreach ($permissions as $permission) {
                if ($menus[$i]->id == $permission->menu_id) {
                    if (in_array($permission->id, $has_permission_ids)) {
                        $permission->check = true;
                    } else {
                        $permission->check = false;
                    }
                    $menus[$i]->permissions[] = $permission;
                }
            }
        }
        $role = $this->role_model->find($role_id);
        $this->data['menu_list'] = $menus;
        $this->data['role_id'] = $role_id;
        $this->data['role_name'] = $role->name;
        //加载模板
        $this->template->admin_load('admin/role_permission/index', $this->data);
    }

    /**
     * 保存角色权限信息
     */
    public function save_role_permission()
    {
        $id = $this->input->method();
        $this->form_validation->set_rules('role_id', '角色ID', 'required');

        if ($this->form_validation->run() == true) {
            $role_id = $this->input->post('role_id');
            $permission_ids = $this->input->post('permission_ids');
            //优先删除角色id下的所有权限
            $this->role_permission_model->delete_by_role_id($role_id);
            //添加选中的权限id
            foreach ($permission_ids as $permission_id) {
                $this->role_permission_model->create(array(
                    'role_id' => $role_id,
                    'permission_id' => $permission_id
                ));
            }
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', "修改成功！");
            //返回列表页面
            $form_url = $this->session->userdata('list_page_url');
            if (empty($form_url)) {
                $form_url = "/admin/roles";
            } else {
                $this->session->unset_userdata('list_page_url');
            }
            redirect($form_url, 'refresh');
        } else {
            // 传递错误信息
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            //当前列表页面的url
            redirect("/admin/roles", 'refresh');
        }
    }
}