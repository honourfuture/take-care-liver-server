<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * hospitals 控制器
 */
class Hospitals extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('hospital_model');
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

        $this->data['business_types'] = $this->hospital_model->getBusiness_type();

        //搜索筛选
        $this->data['search'] = $this->input->get('search', TRUE);
        $keyword = $this->input->get('keyword', TRUE);
        if($keyword) {
            $likeParam['telphone'] = $keyword;
            $likeParam['name'] = $keyword;
            $likeParam['position'] = $keyword;
            $likeParam['detail'] = $keyword;
        }
        $this->data['keyword'] = $keyword;
        if($this->data['search']) {

            $this->data['id'] = $this->input->get('id', TRUE);
            if($this->data['id'] !== '') {
                $param['id'] = $this->data['id'];
            }

            $this->data['name'] = $this->input->get('name', TRUE);
            if($this->data['name']) {
                $likeParam['name'] = $this->data['name'];
            }

            $this->data['telphone'] = $this->input->get('telphone', TRUE);
            if($this->data['telphone']) {
                $likeParam['telphone'] = $this->data['telphone'];
            }

            $this->data['position'] = $this->input->get('position', TRUE);
            if($this->data['position']) {
                $likeParam['position'] = $this->data['position'];
            }

            $this->data['detail'] = $this->input->get('detail', TRUE);
            if($this->data['detail'] !== '') {
                $param['detail'] = $this->data['detail'];
            }

            $this->data['pic'] = $this->input->get('pic', TRUE);
            if($this->data['pic']) {
                $likeParam['pic'] = $this->data['pic'];
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

            $this->data['business_type'] = $this->input->get('business_type', TRUE);
            if($this->data['business_type'] !== '') {
                $param['business_type'] = $this->data['business_type'];
            }

            $this->data['distance'] = $this->input->get('distance', TRUE);
            if($this->data['distance']) {
                $likeParam['distance'] = $this->data['distance'];
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
        $pageUrl = B_URL.'hospitals/index';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $result = $this->hospital_model->getResult($param, $this->per_page, $this->offset, $orderBySQL, $inParams, $likeParam);

        //生成分页链接
        $total = $this->hospital_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/hospitals/index',$this->data); //$this->data
    }

    public function save() {
        $data = array();
        $data['business_types'] = $this->hospital_model->getBusiness_type();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('name', 'name', 'trim');
            $this->form_validation->set_rules('telphone', 'telphone', 'trim');
            $this->form_validation->set_rules('position', 'position', 'trim');
            $this->form_validation->set_rules('longitude', 'longitude', 'trim');
            $this->form_validation->set_rules('latitude', 'latitude', 'trim');
            $this->form_validation->set_rules('detail', 'detail', 'trim');
            $this->form_validation->set_rules('pic', 'pic', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');
            $this->form_validation->set_rules('update_time', 'update_time', 'trim');
            $this->form_validation->set_rules('business_type', 'business_type', 'trim');
            $this->form_validation->set_rules('distance', 'distance', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
            'name' => $this->input->post('name', TRUE),
            'telphone' => $this->input->post('telphone', TRUE),
            'position' => $this->input->post('position', TRUE),
            'longitude' => $this->input->post('longitude', TRUE),
            'latitude' => $this->input->post('latitude', TRUE),
            'detail' => $this->input->post('detail', TRUE),
            'pic' => $this->input->post('pic', TRUE),
            'update_time' => date('Y-m-d H:i:s'),
            'business_type' => $this->input->post('business_type', TRUE),
            'distance' => $this->input->post('distance', TRUE),

        );
            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/hospitals/save', $data);
            } else {
                //保存记录
                $save = $this->hospital_model->save($param);

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
                    $form_url = "/admin/hospitals/index";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'hospitals', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->hospital_model->getRow(array('id' => $id));
            }
            $this->template->admin_load('admin/hospitals/save', $data);
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
                        $this->hospital_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'set_business_type') {
                    $setValue = $this->input->post('set_business_type', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'business_type' => $setValue,
                            );
                            $this->hospital_model->save($param);
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
            $form_url = "/admin/hospitals";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');

        //$this->backend_lib->showMessage(B_URL. 'hospitals', $message);
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->hospital_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/hospitals/modals/del', $this->data);
    }

    //详情
    public function view()
    {
        $id = $this->uri->segment(4);

        //获取数据
        $obj = $this->hospital_model->getRow(array("id" => $id));
        if(empty($obj)){
            redirect('admin/hospitals/index', 'refresh');
        }        $this->data['business_types'] = $this->hospital_model->getBusiness_type();

        // 传递数据
        $this->data['data']  = $obj;

        //当前列表页面的url
        $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
        if(strripos($form_url,"admin/hospitals") === FALSE){
            $form_url = "/admin/hospitals/index";
        }
        $this->data['form_url'] = $form_url;
        //加载模板
        $this->template->admin_load('admin/hospitals/view', $this->data);
    }

    //地图
    public function map()
    {
        //加载模板
        //$this->template->admin_load('admin/hospitals/map', $this->data);
        $CI = & get_instance();
        return $CI->load->view('admin/hospitals/map', $this->data);
    }
}
