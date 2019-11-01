<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * check_postion 控制器
 */
class Check_postion extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Check_postion_model');
        $this->load->model('Hospital_model');
    }
                
    public function index() {
        //$data = array();
        $param = array();
        $inParams = array();
        $likeParam = array();

        $this->data['statuss'] = $this->Check_postion_model->getStatus();

        //自动获取get参数
        $urlGet = '';
        //搜索筛选
        $this->data['keyword'] = $this->input->get('search', TRUE);
        if($this->data['keyword']) {
            $likeParam['check_postion'] = $this->data['keyword'];
            $likeParam['remark'] = $this->data['keyword'];
            $urlGet = "?keyword=".$this->data['keyword'];
        }

        //排序
        $orderBy = $this->input->get('orderBy', TRUE);
        $orderBySQL = 'id DESC';
        if ($orderBy == 'idASC') {
            $orderBySQL = 'id ASC';
        }
        $this->data['orderBy'] = $orderBy;

        //分页参数
        $pageUrl = B_URL.'check_postion/index';  //分页链接


        //获取数据
        $result = $this->Check_postion_model->getResult($param, $this->per_page, $this->offset, $orderBySQL, $inParams, $likeParam);

        //生成分页链接
        $total = $this->Check_postion_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$urlGet, $total, $this->per_page);


        //获取数据
        $hospitals = $this->Hospital_model->getAll();
        $this->data['hospitals'] = $hospitals;
        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/check_postion/index',$this->data); //$this->data
    }

    public function save() {
        $data = array();
        $data['statuss'] = $this->Check_postion_model->getStatus();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('check_postion', 'check_postion', 'trim');
            $this->form_validation->set_rules('hospital_id', 'hospital_id', 'trim');
            $this->form_validation->set_rules('money', 'money', 'trim');
            $this->form_validation->set_rules('remark', 'remark', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');
            $this->form_validation->set_rules('update_time', 'update_time', 'trim');
            $this->form_validation->set_rules('status', 'status', 'trim');

            $param = array(
                'id' => $this->input->post('id', TRUE),
                'check_postion' => $this->input->post('check_postion', TRUE),
                'hospital_id' => $this->input->post('hospital_id', TRUE),
                'money' => $this->input->post('money', TRUE),
                'remark' => $this->input->post('remark', TRUE),
                'update_time' => date('Y-m-d H:i:s'),
                'status' => $this->input->post('status', TRUE),

            );
            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/check_postion/save', $data);
            } else {
                //保存记录
                $save = $this->Check_postion_model->save($param);

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
                    $form_url = "/admin/check_postion/index";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->Check_postion_model->getRow(array('id' => $id));
            }
            //获取数据
            $hospitals = $this->Hospital_model->getAll();
            $data['hospitals'] = $hospitals;
            $this->template->admin_load('admin/check_postion/save', $data);
        }
    }

    public function manage() {

        if ($this->input->method() == "post") {
            $hospital_id = $this->input->post('hospital_id', TRUE);
            $ids = $this->input->post('ids', TRUE);
            $ids = json_decode($ids);

            $datas = array("hospital_id"=> $hospital_id);
            $result = $this->Check_postion_model->updates($ids, $datas);
            if ($result) {
                $message = "操作成功！";
            } else {
                $message = "操作失败！";
            }
            $this->ajaxReturn($ids, 0, $message, true);
            return;
        }else{
            return $this->ajaxReturn(null, -1, "操作失败", true);
        }
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->Check_postion_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/check_postion/modals/del', $this->data);
    }

        //详情
        public function view()
        {
            $id = $this->uri->segment(4);

            //获取数据
            $obj = $this->Check_postion_model->getRow(array("id" => $id));
            if(empty($obj)){
                redirect('admin/check_postion/index', 'refresh');
            }        $this->data['statuss'] = $this->Check_postion_model->getStatus();

            // 传递数据
            $this->data['data']  = $obj;

            //当前列表页面的url
            $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
            if(strripos($form_url,"admin/check_postion") === FALSE){
                $form_url = "/admin/check_postion/index";
            }
            $this->data['form_url'] = $form_url;
            //加载模板
            $this->template->admin_load('admin/check_postion/view', $this->data);
        }
   }
