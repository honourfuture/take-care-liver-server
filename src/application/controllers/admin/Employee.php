<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * employee 控制器
 */
class Employee extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Employee_model');
        $this->load->library(array('form_validation'));
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

        $this->data['levels'] = $this->Employee_model->getLevel();
        $this->data['is_defaults'] = $this->Employee_model->getIs_default();

        //搜索筛选
        $this->data['keyword'] = $this->input->get('keyword', TRUE);
        if($this->data['keyword']) {
            $likeParam['user_name'] = $this->data['keyword'];
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
        $pageUrl = B_URL.'employee/index';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $result = $this->Employee_model->getResult($param, $this->per_page, $this->offset, $orderBySQL, $inParams, $likeParam);

        //生成分页链接
        $total = $this->Employee_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/employee/index',$this->data); //$this->data
    }

    public function save() {
        $data = array();
        $data['levels'] = $this->Employee_model->getLevel();
        $data['is_defaults'] = $this->Employee_model->getIs_default();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('user_name', 'user_name', 'trim');
            $this->form_validation->set_rules('password', 'password', 'trim');
            /*$this->form_validation->set_rules('created_at', 'created_at', 'trim');
            $this->form_validation->set_rules('updated_at', 'updated_at', 'trim');*/
            $this->form_validation->set_rules('level', 'level', 'trim');
            //$this->form_validation->set_rules('is_default', 'is_default', 'trim');
            $this->form_validation->set_rules('parent_id', 'parent_id', 'trim');


            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/employee/save', $data);
            } else {

                $id = $this->input->post('id', TRUE);
                $password = $this->input->post('password', TRUE);
                $level = $this->input->post('level', TRUE);
                $param = array(
                    'id' => $id,
                    'user_name' => $this->input->post('user_name', TRUE),
                    //'password' => $this->input->post('password', TRUE),
                    //'updated_at' => $this->input->post('updated_at', TRUE),
                    'level' => $level,
                    'is_default' => $this->input->post('is_default', TRUE),
                    'parent_id' => $this->input->post('parent_id', TRUE),
                );
                if($id){
                    if($password){
                        $param['password'] = md5($password);
                        $param['updated_at'] = date('Y-m-d H:i:s');
                    }
                }else{
                    if($password){
                        $param['password'] = md5($password);
                    }else{
                        $param['password'] = md5("123456");
                    }
                    $param['created_at'] = date('Y-m-d H:i:s');

                    $param1 = array('level'=>$level);
                    $result1 = $this->Employee_model->getResult($param1, null, null, null, null, null);
                    if(empty($result1)){
                        $param['is_default'] = 1;
                    }
                }

                //保存记录
                $save = $this->Employee_model->save($param);

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
                    $form_url = "/admin/employee/index";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'employee', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->Employee_model->getRow(array('id' => $id));
            }
            $this->template->admin_load('admin/employee/save', $data);
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
                        $this->Employee_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'set_level') {
                    $setValue = $this->input->post('set_level', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'level' => $setValue,
                            );
                            $this->Employee_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                } elseif ($manageName == 'set_is_default') {
                    $setValue = $this->input->post('set_is_default', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'is_default' => $setValue,
                            );
                            $this->Employee_model->save($param);
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
            $form_url = "/admin/employee";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');

        //$this->backend_lib->showMessage(B_URL. 'employee', $message);
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->Employee_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/employee/modals/del', $this->data);
    }

    //取消客服
    public function cancle() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            $param = array(
                'id' => $id,
                'is_customer' => 2,
                'updated_at' => date('Y-m-d H:i:s')
            );
            //保存记录
            $save = $this->Employee_model->save($param);
            if ($save) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/employee/modals/cancle', $this->data);
    }

    //详情
    public function view()
    {
        $id = $this->uri->segment(4);

        //获取数据
        $obj = $this->Employee_model->getRow(array("id" => $id));
        if(empty($obj)){
            redirect('admin/employee/index', 'refresh');
        }        $this->data['levels'] = $this->Employee_model->getLevel();
        $this->data['is_defaults'] = $this->Employee_model->getIs_default();

        // 传递数据
        $this->data['data']  = $obj;

        //当前列表页面的url
        $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
        if(strripos($form_url,"admin/employee") === FALSE){
            $form_url = "/admin/employee/index";
        }
        $this->data['form_url'] = $form_url;
        //加载模板
        $this->template->admin_load('admin/employee/view', $this->data);
    }

    /***
     * 查询父级
     */
    public function parent()
    {
        $level = $_POST['level'];
        $param = array('level'=>$level);
        $result = $this->Employee_model->getResult($param, null, null, null, null, null);
        if(empty($result)){
            $this->ajaxReturn('', 0, '暂无数据！');
        }
        $this->ajaxReturn($result, 1, '查询成功！');
    }



    public function lists() {
        //$data = array();
        $param = array();
        $inParams = array();
        $likeParam = array();
        $orParam = array();

        $this->data['levels'] = $this->Employee_model->getLevel();
        $this->data['is_defaults'] = $this->Employee_model->getIs_default();

        //搜索筛选
        $this->data['keyword'] = $this->input->get('keyword', TRUE);
        if($this->data['keyword']) {
            $likeParam['user_name'] = $this->data['keyword'];
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
        $pageUrl = B_URL.'employee/lists';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $inParams['level'] = ["F","G"];
        $param['is_customer'] = "1";
        $result = $this->Employee_model->getResult($param, $this->per_page, $this->offset, $orderBySQL, $inParams, $likeParam,$orParam);

        //生成分页链接
        $total = $this->Employee_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/employee/list',$this->data); //$this->data
    }

    public function edit() {
        $data = array();
        $data['levels'] = $this->Employee_model->getCustomerLevel();
        //$data['is_defaults'] = $this->Employee_model->getIs_default();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'required|integer');

            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                $this->session->set_flashdata('message_type', $message_type);
                $this->session->set_flashdata('message', $message);
                //加载模板
                $this->template->admin_load('admin/employee/edit', $data);
            } else {

                $id = $this->input->post('id', TRUE);
                $param = array(
                    'id' => $id,
                    'is_customer' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                );

                //保存记录
                $save = $this->Employee_model->save($param);

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
                    $form_url = "/admin/employee/lists";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'employee', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            //$id = $this->uri->segment(4);
            //if ($id) {
            //    $data['data'] = $this->Employee_model->getRow(array('id' => $id));
           // }
            $this->template->admin_load('admin/employee/edit', $data);
        }
    }

}
