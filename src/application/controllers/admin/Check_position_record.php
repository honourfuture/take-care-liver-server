<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * check_position_record 控制器
 */
class Check_position_record extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Check_position_record_model');
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


        //搜索筛选
        //自动获取get参数
        $urlGet = '';
        $this->data['keyword'] = $this->input->get('keyword', TRUE);
        if($this->data['keyword']) {

            $likeParam['check_postion'] = $this->data['keyword'];
            $likeParam['remark'] = $this->data['keyword'];
            $urlGet = "?keyword=".$this->data['keyword'];
        }

        $this->data['id'] = $this->input->get('id', TRUE);
        if($this->data['id']) {
            $param['check_position_id'] =$this->data['id'] ;
            if($this->data['keyword']) {
                $urlGet .= "&id=".$this->data['id'];
            }else{
                $urlGet .= "?id=".$this->data['id'];
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
        $pageUrl = B_URL.'check_position_record/index';  //分页链接


        //获取数据
        $result = $this->Check_position_record_model->getResult($param, $this->per_page, $this->offset, $orderBySQL, $inParams, $likeParam);

        //生成分页链接
        $total = $this->Check_position_record_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$urlGet, $total, $this->per_page);

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/check_position_record/index',$this->data); //$this->data
    }

    public function save() {
        $data = array();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('user_id', 'user_id', 'trim');
            $this->form_validation->set_rules('check_position_id', 'check_position_id', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');
            $this->form_validation->set_rules('update_time', 'update_time', 'trim');
            $this->form_validation->set_rules('date', 'date', 'trim');
            $this->form_validation->set_rules('money', 'money', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
            'user_id' => $this->input->post('user_id', TRUE),
            'check_position_id' => $this->input->post('check_position_id', TRUE),
            'update_time' => date('Y-m-d H:i:s'),
            'money' => $this->input->post('money', TRUE),

        );
            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/check_position_record/save', $data);
            } else {
                //保存记录
                $save = $this->Check_position_record_model->save($param);

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
                    $form_url = "/admin/check_position_record/index";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'check_position_record', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->Check_position_record_model->getRow(array('id' => $id));
            }
            $this->template->admin_load('admin/check_position_record/save', $data);
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
                        $this->Check_position_record_model->delete($param);
                    }
                    $message = '删除成功';
                }
            }
        }

        $this->session->set_flashdata('message_type', 'success');
        $this->session->set_flashdata('message', $message);

        //返回列表页面
        $form_url = $this->session->userdata('list_page_url');
        if (empty($form_url)) {
            $form_url = "/admin/check_position_record";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');

        //$this->backend_lib->showMessage(B_URL. 'check_position_record', $message);
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->Check_position_record_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/check_position_record/modals/del', $this->data);
    }

        //详情
        public function view()
        {
            $id = $this->uri->segment(4);

            //获取数据
            $obj = $this->Check_position_record_model->getRow(array("id" => $id));
            if(empty($obj)){
                redirect('admin/check_position_record/index', 'refresh');
            }
            // 传递数据
            $this->data['data']  = $obj;

            //当前列表页面的url
            $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
            if(strripos($form_url,"admin/check_position_record") === FALSE){
                $form_url = "/admin/check_position_record/index";
            }
            $this->data['form_url'] = $form_url;
            //加载模板
            $this->template->admin_load('admin/check_position_record/view', $this->data);
        }
   }
