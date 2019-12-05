<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * user_urine 控制器
 */
class User_urine extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('User_urine_model');
        $this->load->model('Urine_model');
        $this->load->model('Liver_model');

        //检查登录
        //$this->backend_lib->checkLoginOrJump();
                
        //检查权限管理的权限
        //$this->backend_lib->checkPermissionOrJump(1);
    }
                
    public function index() {
        //$data = array();
        $param = array();
        $inParams = array();
        $likeParam1 = array();
        $likeParam = array();

        $this->data['types'] = $this->User_urine_model->getType();

        //搜索筛选
        $this->data['keyword'] = $this->input->get('keyword', TRUE);
        $this->data['user_id'] = $this->input->get('user_id', TRUE);
        if($this->data['keyword']) {
            $likeParam1['user_id'] = $this->data['keyword'];
            $likeParam1['username'] = $this->data['keyword'];
            $likeParam1['date'] = $this->data['keyword'];
            $likeParam1['mobile'] = $this->data['keyword'];
        }
        if($this->data['user_id']) {
            $param['user_id'] = $this->data['user_id'];
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
        $pageUrl = B_URL.'user_urine/index';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;
        $param['type'] = 1;
        //获取数据
        $result = $this->User_urine_model->getList($param, $this->per_page, $this->offset, $orderBySQL, $likeParam1);

        //生成分页链接
        $total = $this->User_urine_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/user_urine/index',$this->data); //$this->data
    }

    public function liver() {
        //$data = array();
        $param = array();
        $inParams = array();
        $likeParam1 = array();
        $likeParam = array();

        $this->data['types'] = $this->User_urine_model->getType();

        //搜索筛选
        $this->data['keyword'] = $this->input->get('keyword', TRUE);
        $this->data['user_id'] = $this->input->get('user_id', TRUE);
        if($this->data['keyword']) {
            $likeParam1['user_id'] = $this->data['keyword'];
            $likeParam1['username'] = $this->data['keyword'];
            $likeParam1['date'] = $this->data['keyword'];
            $likeParam1['mobile'] = $this->data['keyword'];
        }
        if($this->data['user_id']) {
            $param['user_id'] = $this->data['user_id'];
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
        $pageUrl = B_URL.'user_urine/liver';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;
        $param['type'] = 2;
        //获取数据
        $result = $this->User_urine_model->getList($param, $this->per_page, $this->offset, $orderBySQL, $likeParam1);

        //生成分页链接
        $total = $this->User_urine_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/user_urine/liver',$this->data); //$this->data
    }

    public function save() {
        $data = array();
        $data['types'] = $this->User_urine_model->getType();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('user_id', 'user_id', 'trim');
            $this->form_validation->set_rules('urine_check_id', 'urine_check_id', 'trim');
            $this->form_validation->set_rules('type', 'type', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');
            $this->form_validation->set_rules('update_time', 'update_time', 'trim');
            $this->form_validation->set_rules('date', 'date', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
            'user_id' => $this->input->post('user_id', TRUE),
            'urine_check_id' => $this->input->post('urine_check_id', TRUE),
            'type' => $this->input->post('type', TRUE),
            'update_time' => date('Y-m-d H:i:s'),

        );
            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/user_urine/save', $data);
            } else {
                //保存记录
                $save = $this->User_urine_model->save($param);

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
                    $form_url = "/admin/user_urine/index";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'user_urine', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->User_urine_model->getRow(array('id' => $id));
            }
            $this->template->admin_load('admin/user_urine/save', $data);
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
                        $this->User_urine_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'set_type') {
                    $setValue = $this->input->post('set_type', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'type' => $setValue,
                            );
                            $this->User_urine_model->save($param);
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
            $form_url = "/admin/user_urine";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');

        //$this->backend_lib->showMessage(B_URL. 'user_urine', $message);
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->User_urine_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/user_urine/modals/del', $this->data);
    }

    //详情
    public function view()
    {
        $id = $this->uri->segment(4);

        //获取数据
        $obj = $this->User_urine_model->getRow(array("id" => $id));
        if(empty($obj)){
            redirect('admin/user_urine/index', 'refresh');
        }        $this->data['types'] = $this->User_urine_model->getType();

        // 传递数据
        $this->data['data']  = $obj;

        //当前列表页面的url
        $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
        if(strripos($form_url,"admin/user_urine") === FALSE){
            $form_url = "/admin/user_urine/index";
        }
        $this->data['form_url'] = $form_url;
        //加载模板
        $this->template->admin_load('admin/user_urine/view', $this->data);
    }

    public function liverView(){
        $id = $this->uri->segment(4);
        $data = $this->Liver_model->find($id);
        if ($data) {
            $this->data['data'] = json_decode($data->info, true);
            $this->template->admin_load('admin/user_urine/liverView', $this->data);
        }
    }
   }
