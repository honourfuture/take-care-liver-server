<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * admin_logs 控制器
 */
class Admin_logs extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Admin_logs_model');
        $this->load->model('Admin_model');
        $this->load->model('Menu_model');
        $this->load->model('Permission_model');
        //检查登录
        //$this->backend_lib->checkLoginOrJump();
                
        //检查权限管理的权限
        //$this->backend_lib->checkPermissionOrJump(1);
    }
                
    public function index() {
        //$data = array();
        $param = array();
        $inParams = array();
        $likeParam = array();

//        $this->data['admin_userss'] = $this->Admin_model->getResult(array(), '', '', 'id DESC');
//        $this->data['admin_menus'] = $this->Menu_model->getResult(array(), '', '', 'id DESC');
//        $this->data['admin_permissions'] = $this->Permission_model->getResult(array(), '', '', 'id DESC');

        //搜索筛选
        $this->data['search'] = $this->input->get('search', TRUE);
        if($this->data['search']) {

            $this->data['id'] = $this->input->get('id', TRUE);
            if($this->data['id'] !== '') {
                $param['id'] = $this->data['id'];
            }

            $this->data['user_id'] = $this->input->get('user_id', TRUE);
            if($this->data['user_id'] !== '') {
                $param['user_id'] = $this->data['user_id'];
            }

            $this->data['user_name'] = $this->input->get('user_name', TRUE);
            if($this->data['user_name']) {
                $likeParam['user_name'] = $this->data['user_name'];
            }

            $this->data['menu_id'] = $this->input->get('menu_id', TRUE);
            if($this->data['menu_id'] !== '') {
                $param['menu_id'] = $this->data['menu_id'];
            }

            $this->data['permission_id'] = $this->input->get('permission_id', TRUE);
            if($this->data['permission_id'] !== '') {
                $param['permission_id'] = $this->data['permission_id'];
            }

            $this->data['data_id'] = $this->input->get('data_id', TRUE);
            if($this->data['data_id'] !== '') {
                $param['data_id'] = $this->data['data_id'];
            }

            $this->data['remark'] = $this->input->get('remark', TRUE);
            if($this->data['remark']) {
                $likeParam['remark'] = $this->data['remark'];
            }

            $this->data['create_time_start'] = $this->input->get('create_time_start', TRUE);
            $this->data['create_time_end'] = $this->input->get('create_time_end', TRUE);
            if ($this->data['create_time_start'] && $this->data['create_time_end']) {
                $param['create_time >='] = date('Y-m-d', strtotime($this->data['create_time_start']));
                $param['create_time <'] = date('Y-m-d', strtotime($this->data['create_time_end']));
            }

            $this->data['update_time_start'] = $this->input->get('update_time_start', TRUE);
            $this->data['update_time_end'] = $this->input->get('update_time_end', TRUE);
            if ($this->data['update_time_start'] && $this->data['update_time_end']) {
                $param['update_time >='] = date('Y-m-d', strtotime($this->data['update_time_start']));
                $param['update_time <'] = date('Y-m-d', strtotime($this->data['update_time_end']));
            }

        }

        //自动获取get参数
        $urlGet = '';
        $gets = $this->input->get();
        if ($gets) {
            $i = 0;
            foreach ($gets as $getKey => $get) {
                if ($i) {
                    $urlGet .= "&$getKey=$get";
                } else {
                    $urlGet .= "/?$getKey=$get";
                }
                $i++;
            }
        }
                
        //排序
        $orderBy = $this->input->get('orderBy', TRUE);
        $orderBySQL = 'id DESC';
        if ($orderBy == 'idASC') {
            $orderBySQL = 'id ASC';
        }
        $this->data['orderBy'] = $orderBy;
                
        //分页参数
        $pageUrl = B_URL.'admin_logs/index';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $result = $this->Admin_logs_model->getResult($param, $this->per_page, $this->offset, $orderBySQL, $inParams, $likeParam);

        //生成分页链接
        $total = $this->Admin_logs_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/admin_logs/index',$this->data); //$this->data
    }

    public function save() {
        $data = array();
        $data['admin_userss'] = $this->Admin_model->getResult(array(), '', '', 'id DESC');
        $data['admin_menus'] = $this->Menu_model->getResult(array(), '', '', 'id DESC');
        $data['admin_permissions'] = $this->Permission_model->getResult(array(), '', '', 'id DESC');

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('user_id', 'user_id', 'trim');
            $this->form_validation->set_rules('user_name', 'user_name', 'trim');
            $this->form_validation->set_rules('menu_id', 'menu_id', 'trim');
            $this->form_validation->set_rules('permission_id', 'permission_id', 'trim');
            $this->form_validation->set_rules('data_id', 'data_id', 'trim');
            $this->form_validation->set_rules('remark', 'remark', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');
            $this->form_validation->set_rules('update_time', 'update_time', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
            'user_id' => $this->input->post('user_id', TRUE),
            'user_name' => $this->input->post('user_name', TRUE),
            'menu_id' => $this->input->post('menu_id', TRUE),
            'permission_id' => $this->input->post('permission_id', TRUE),
            'data_id' => $this->input->post('data_id', TRUE),
            'remark' => $this->input->post('remark', TRUE),
            'update_time' => date('Y-m-d H:i:s'),

        );
            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/admin_logs/save', $data);
            } else {
                //保存记录
                $save = $this->Admin_logs_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                    $message_type = 'success';
                } else {
                    $message = '保存失败';
                }

                $this->session->set_flashdata('message_type', $message_type);
                $this->session->set_flashdata('message', $message);
                 //返回列表页面
                $form_url = $this->session->userdata('list_page_url');
                if(empty($form_url)){
                    $form_url = "/admin/admin_logs/index";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'admin_logs', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->Admin_logs_model->getRow(array('id' => $id));
            }
            $this->template->admin_load('admin/admin_logs/save', $data);
        }
    }

    public function manage() {
        $data = array();
        $this->form_validation->set_rules('ids[]', 'Ids', 'required');
        $this->form_validation->set_rules('manageName', '操作名称', 'required');

        $manageName = $this->input->post('manageName', TRUE);
        $ids = $this->input->post('ids', TRUE);

        $success = FALSE;
        $message = '';

        if ($this->form_validation->run() == FALSE) {
            $message = '表单填写有误';
        } else {
            if ($ids != null) {
                if ($manageName == 'delete') {
                    //删除记录
                    foreach ($ids as $key => $id) {
                        $param = array(
                            'id' => $id,
                        );
                        $this->Admin_logs_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'set_user_id') {
                    $setValue = $this->input->post('set_user_id', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'user_id' => $setValue,
                            );
                            $this->Admin_logs_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }

                } elseif ($manageName == 'set_menu_id') {
                    $setValue = $this->input->post('set_menu_id', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'menu_id' => $setValue,
                            );
                            $this->Admin_logs_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }

                } elseif ($manageName == 'set_permission_id') {
                    $setValue = $this->input->post('set_permission_id', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'permission_id' => $setValue,
                            );
                            $this->Admin_logs_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }

                }
            }
        }

        $this->session->set_flashdata('message_type', 'success');
        $this->session->set_flashdata('message', $message);

        //返回列表页面
        $form_url = $this->session->userdata('list_page_url');
        if (empty($form_url)) {
            $form_url = "/admin/admin_logs";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');

        //$this->backend_lib->showMessage(B_URL. 'admin_logs', $message);
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->Admin_logs_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/admin_logs/modals/del', $this->data);
    }

        //详情
        public function view()
        {
            $id = $this->uri->segment(4);

            //获取数据
            $obj = $this->Admin_logs_model->getRow(array("id" => $id));
            if(empty($obj)){
                redirect('admin/admin_logs/index', 'refresh');
            }        
            $this->data['admin_userss'] = $this->Admin_model->getResult(array(), '', '', 'id DESC');        
            $this->data['admin_menus'] = $this->Menu_model->getResult(array(), '', '', 'id DESC');        
            $this->data['admin_permissions'] = $this->Permission_model->getResult(array(), '', '', 'id DESC');
            // 传递数据
            $this->data['data']  = $obj;

            //当前列表页面的url
            $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
            if(strripos($form_url,"admin/admin_logs") === FALSE){
                $form_url = "/admin/admin_logs/index";
            }
            $this->data['form_url'] = $form_url;
            //加载模板
            $this->template->admin_load('admin/admin_logs/view', $this->data);
        }
   }
