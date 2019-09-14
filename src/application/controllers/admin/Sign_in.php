<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sign_in 控制器
 */
class Sign_in extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Sign_in_model');
        $this->load->model('User_model');
        $this->load->model('Address_model');
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
        $likeParam1 = array();

        //$this->data['userss'] = $this->User_model->getResult(array(), '', '', 'id DESC');
        $this->data['is_sends'] = $this->Sign_in_model->getIs_send();

        //搜索筛选
        //$this->data['search'] = $this->input->get('search', TRUE);
        $this->data['keyword'] = $this->input->get('keyword', TRUE);

        if($this->data['keyword']){
            $likeParam1['user_id'] = $this->data['keyword'];
            $likeParam1['username'] = $this->data['keyword'];
            $likeParam1['date'] = $this->data['keyword'];
            $likeParam1['mobile'] = $this->data['keyword'];
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
        $pageUrl = B_URL.'sign_in/index';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $result = $this->Sign_in_model->getList($param, $this->per_page, $this->offset, $orderBySQL, $likeParam1);

        //生成分页链接
        $total = $this->Sign_in_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/sign_in/index',$this->data); //$this->data
    }


    public function index_express() {
        //$data = array();
        $param = array();
        $inParams = array();
        $likeParam = array();
        $likeParam1 = array();

        //$this->data['userss'] = $this->User_model->getResult(array(), '', '', 'id DESC');
        $this->data['is_sends'] = $this->Sign_in_model->getIs_send();

        //搜索筛选
        $this->data['keyword'] = $this->input->get('keyword', TRUE);

        if($this->data['keyword']){
            $likeParam1['user_id'] = $this->data['keyword'];
            $likeParam1['username'] = $this->data['keyword'];
            $likeParam1['date'] = $this->data['keyword'];
            $likeParam1['mobile'] = $this->data['keyword'];
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
        $pageUrl = B_URL.'sign_in/index';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $param['continue >='] = 21;//21天达标
        $result = $this->Sign_in_model->getList($param, $this->per_page, $this->offset, $orderBySQL, $likeParam1);

        //生成分页链接
        $total = $this->Sign_in_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/sign_in/index_express',$this->data); //$this->data
    }

    public function save() {
        $data = array();
        $data['userss'] = $this->User_model->getResult(array(), '', '', 'id DESC');
        $data['is_sends'] = $this->Sign_in_model->getIs_send();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
//            $this->form_validation->set_rules('date', 'date', 'trim');
//            $this->form_validation->set_rules('user_id', 'user_id', 'trim');
//            $this->form_validation->set_rules('continue', 'continue', 'trim');
//            $this->form_validation->set_rules('create_time', 'create_time', 'trim');
//            $this->form_validation->set_rules('update_time', 'update_time', 'trim');
            $this->form_validation->set_rules('is_send', 'is_send', 'trim');
            $this->form_validation->set_rules('address_id', 'address_id', 'trim');
            $this->form_validation->set_rules('express_no', 'express_no', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
//            'date' => $this->input->post('date', TRUE),
//            'user_id' => $this->input->post('user_id', TRUE),
//            'continue' => $this->input->post('continue', TRUE),
//            'update_time' => date('Y-m-d H:i:s'),
            'is_send' => $this->input->post('is_send', TRUE),
            'address_id' => $this->input->post('address_id', TRUE),
            'express_no' => $this->input->post('express_no', TRUE),
        );
            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/sign_in/save', $data);
            } else {
                //保存记录
                $save = $this->Sign_in_model->save($param);

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
                    $form_url = "/admin/sign_in/index_express";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'sign_in', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->Sign_in_model->getRow(array('id' => $id));
                $data['addresss'] = $this->Address_model->myAddress($data['data']['user_id']);
            }
            $this->template->admin_load('admin/sign_in/save', $data);
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
                        $this->Sign_in_model->delete($param);
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
                            $this->Sign_in_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }

                } elseif ($manageName == 'set_is_send') {
                    $setValue = $this->input->post('set_is_send', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'is_send' => $setValue,
                            );
                            $this->Sign_in_model->save($param);
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
            $form_url = "/admin/sign_in";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');

        //$this->backend_lib->showMessage(B_URL. 'sign_in', $message);
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->Sign_in_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/sign_in/modals/del', $this->data);
    }

        //详情
        public function view()
        {
            $id = $this->uri->segment(4);

            //获取数据
            $obj = $this->Sign_in_model->getRow(array("id" => $id));
            if(empty($obj)){
                redirect('admin/sign_in/index', 'refresh');
            }        $this->data['userss'] = $this->User_model->getResult(array(), '', '', 'id DESC');        $this->data['is_sends'] = $this->Sign_in_model->getIs_send();

            // 传递数据
            $this->data['data']  = $obj;

            //当前列表页面的url
            $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
            if(strripos($form_url,"admin/sign_in") === FALSE){
                $form_url = "/admin/sign_in/index";
            }
            $this->data['form_url'] = $form_url;
            //加载模板
            $this->template->admin_load('admin/sign_in/view', $this->data);
        }
   }
