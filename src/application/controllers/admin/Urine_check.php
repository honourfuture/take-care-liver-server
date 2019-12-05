<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * urine_check 控制器
 */
class Urine_check extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Urine_check_model');
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

        $this->data['summarys'] = $this->Urine_check_model->getSummary();
        $this->data['waring_types'] = $this->Urine_check_model->getWaring_type();

        //搜索筛选
        $this->data['search'] = $this->input->get('search', TRUE);
        if($this->data['search']) {

            $this->data['id'] = $this->input->get('id', TRUE);
            if($this->data['id'] !== '') {
                $param['id'] = $this->data['id'];
            }

            $this->data['color'] = $this->input->get('color', TRUE);
            if($this->data['color']) {
                $likeParam['color'] = $this->data['color'];
            }

            $this->data['summary'] = $this->input->get('summary', TRUE);
            if($this->data['summary']) {
                $likeParam['summary'] = $this->data['summary'];
            }

            $this->data['details'] = $this->input->get('details', TRUE);
            if($this->data['details']) {
                $likeParam['details'] = $this->data['details'];
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

            $this->data['waring_type'] = $this->input->get('waring_type', TRUE);
            if($this->data['waring_type'] !== '') {
                $param['waring_type'] = $this->data['waring_type'];
            }

        }

        $this->data['keyword'] = $this->input->get('keyword', TRUE);

        if($this->data['keyword']){
            $likeParam1['summary'] = $this->data['keyword'];
            $likeParam1['color'] = $this->data['keyword'];
            $likeParam1['details'] = $this->data['keyword'];
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
        $pageUrl = B_URL.'urine_check/index';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $result = $this->Urine_check_model->getResult($param, $this->per_page, $this->offset, 'waring_type asc', $inParams, $likeParam1);

        //生成分页链接
        $total = $this->Urine_check_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/urine_check/index',$this->data); //$this->data
    }

    public function save() {
        $data = array();
        $data['summarys'] = $this->Urine_check_model->getSummary();
        $data['waring_types'] = $this->Urine_check_model->getWaring_type();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('color', 'color', 'trim');
            $this->form_validation->set_rules('summary', 'summary', 'trim');
            $this->form_validation->set_rules('details', 'details', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');
            $this->form_validation->set_rules('update_time', 'update_time', 'trim');
            $this->form_validation->set_rules('waring_type', 'waring_type', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
            'color' => $this->input->post('color', TRUE),
            'summary' => $this->input->post('summary', TRUE),
            'details' => $this->input->post('details', TRUE),
            'update_time' => date('Y-m-d H:i:s'),
            'waring_type' => $this->input->post('waring_type', TRUE),

        );
            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/urine_check/save', $data);
            } else {
                //保存记录
                $save = $this->Urine_check_model->save($param);

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
                    $form_url = "/admin/urine_check/index";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'urine_check', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->Urine_check_model->getRow(array('id' => $id));
            }
            $this->template->admin_load('admin/urine_check/save', $data);
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
                        $this->Urine_check_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'set_summary') {
                    $setValue = $this->input->post('set_summary', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'summary' => $setValue,
                            );
                            $this->Urine_check_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                } elseif ($manageName == 'set_waring_type') {
                    $setValue = $this->input->post('set_waring_type', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'waring_type' => $setValue,
                            );
                            $this->Urine_check_model->save($param);
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
            $form_url = "/admin/urine_check";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');

        //$this->backend_lib->showMessage(B_URL. 'urine_check', $message);
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->Urine_check_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/urine_check/modals/del', $this->data);
    }

        //详情
        public function view()
        {
            $id = $this->uri->segment(4);

            //获取数据
            $obj = $this->Urine_check_model->getRow(array("id" => $id));
            if(empty($obj)){
                redirect('admin/urine_check/index', 'refresh');
            }        $this->data['summarys'] = $this->Urine_check_model->getSummary();
        $this->data['waring_types'] = $this->Urine_check_model->getWaring_type();

            // 传递数据
            $this->data['data']  = $obj;

            //当前列表页面的url
            $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
            if(strripos($form_url,"admin/urine_check") === FALSE){
                $form_url = "/admin/urine_check/index";
            }
            $this->data['form_url'] = $form_url;
            //加载模板
            $this->template->admin_load('admin/urine_check/view', $this->data);
        }
   }
