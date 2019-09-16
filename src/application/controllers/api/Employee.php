<?php
defined('BASEPATH') or exit ('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

/**
 * employee API控制器
 */
class Employee extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Employee_model');
        $this->load->library(array('form_validation'));
    }

    /**
     * @SWG\Get(path="/employee/list",
     *   consumes={"multipart/form-data"},
     *   tags={"Employee"},
     *   summary="查询内部员工列表",
     *   description="查询内部员工列表",
     *   operationId="employeeList",
     *     @SWG\Parameter(
     *     in="query",
     *     name="token",
     *     description="用户登录token",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="cur_page",
     *     description="当前页",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="per_page",
     *     description="每页数量 [默认10条]",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get() {
        $data['levels'] = $this->Employee_model->getLevel();
        $data['is_defaults'] = $this->Employee_model->getIs_default();
        //if (!$this->user_id) {
        //    return $this->json(null, REST_Controller::NOT_LOGIN, $this->lang->line('text_resp_unlogin'));
        //}
        $total = $this->Employee_model->count(array());
        $result = $this->Employee_model->getResult(array(), $this->per_page, $this->offset);
        if ($result) {
            //foreach ($result as $employee) {
            //}
            return $this->json(array("list" => $result, "total" => $total), $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
           return $this->json(null, $this::NO_DATA, $message = $this->lang->line('text_resp_no_data'));
        }
    }

    /**
     * @SWG\Post(path="/employee/add",
     *   consumes={"multipart/form-data"},
     *   tags={"Employee"},
     *   summary="添加内部员工",
     *   description="添加内部员工",
     *   operationId="addEmployee",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="user_name",
     *     description="用户名",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="password",
     *     description="密码",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="created_at",
     *     description="创建时间",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="updated_at",
     *     description="更新时间",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="level",
     *     description="等级",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="is_default",
     *     description="是否默认管理员",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="parent_id",
     *     description="父级id",
     *     required=false,
     *     type="string"
     *   ),     *   @SWG\Parameter(
     *     in="formData",
     *     name="token",
     *     description="用户登录token",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function add_post(){
        if (!$this->user_id) {
            return $this->json(null, REST_Controller::NOT_LOGIN, $this->lang->line('text_resp_unlogin'));
        }

        //参数校验
        //$this->form_validation->set_rules('remarks', '备注信息', 'max_length[50]');
        //$this->form_validation->set_rules('amount', '总金额', 'numeric|greater_than_equal_to[0]');
        //$this->form_validation->set_rules('deliver_amount', '运费', 'numeric|greater_than_equal_to[0]');
        //$this->form_validation->set_rules('type', '商品类型', 'required|in_list[1,2]');
        //$this->form_validation->set_rules('address_id', '地址', 'required|integer');
        
                    $this->form_validation->set_rules('user_name', 'user_name', 'trim');
                        $this->form_validation->set_rules('password', 'password', 'trim');
                        $this->form_validation->set_rules('created_at', 'created_at', 'trim');
                        $this->form_validation->set_rules('updated_at', 'updated_at', 'trim');
                        $this->form_validation->set_rules('level', 'level', 'trim');
                        $this->form_validation->set_rules('is_default', 'is_default', 'trim');
                        $this->form_validation->set_rules('parent_id', 'parent_id', 'trim');
                    $this->form_validation->set_error_delimiters('', '');//去除p标签和换行
        if ($this->form_validation->run() == false) {
            // 传递错误信息
            $message = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            return $this->json(null, $this::SYS_ERROR, format_message($message));
        }
        $param = array(            'id' => $this->input->post('id', TRUE),
            'user_name' => $this->input->post('user_name', TRUE),
            'password' => $this->input->post('password', TRUE),
            'updated_at' => $this->input->post('updated_at', TRUE),
            'level' => $this->input->post('level', TRUE),
            'is_default' => $this->input->post('is_default', TRUE),
            'parent_id' => $this->input->post('parent_id', TRUE),

        );
        $id = $this->Employee_model->save($param);
        if ($id) {
            return $this->json(array("id" => $id), $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
            return $this->json(null, $this::SYS_ERROR, $this->lang->line('text_resp_fail'));
        }
    }

    /**
     * @SWG\Post(path="/employee/edit",
     *   consumes={"multipart/form-data"},
     *   tags={"Employee"},
     *   summary="编辑内部员工",
     *   description="编辑内部员工",
     *   operationId="editEmployee",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="id",
     *     description="id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="user_name",
     *     description="用户名",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="password",
     *     description="密码",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="created_at",
     *     description="创建时间",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="updated_at",
     *     description="更新时间",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="level",
     *     description="等级",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="is_default",
     *     description="是否默认管理员",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="parent_id",
     *     description="父级id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="token",
     *     description="用户登录token",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function edit_post(){
        if (!$this->user_id) {
            return $this->json(null, REST_Controller::NOT_LOGIN, $this->lang->line('text_resp_unlogin'));
        }

        //参数校验
        //$this->form_validation->set_rules('remarks', '备注信息', 'max_length[50]');
        //$this->form_validation->set_rules('amount', '总金额', 'numeric|greater_than_equal_to[0]');
        //$this->form_validation->set_rules('deliver_amount', '运费', 'numeric|greater_than_equal_to[0]');
        //$this->form_validation->set_rules('type', '商品类型', 'required|in_list[1,2]');
        //$this->form_validation->set_rules('address_id', '地址', 'required|integer');
        $this->form_validation->set_rules('id', 'ID', 'required|integer');
                    $this->form_validation->set_rules('user_name', 'user_name', 'trim');
                        $this->form_validation->set_rules('password', 'password', 'trim');
                        $this->form_validation->set_rules('created_at', 'created_at', 'trim');
                        $this->form_validation->set_rules('updated_at', 'updated_at', 'trim');
                        $this->form_validation->set_rules('level', 'level', 'trim');
                        $this->form_validation->set_rules('is_default', 'is_default', 'trim');
                        $this->form_validation->set_rules('parent_id', 'parent_id', 'trim');
                    $this->form_validation->set_error_delimiters('', '');//去除p标签和换行
        if ($this->form_validation->run() == false) {
            // 传递错误信息
            $message = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            return $this->json(null, $this::SYS_ERROR, format_message($message));
        }

        //$id = intval($this->input->post('id'));
        $param = array(
                    'id' => $this->input->post('id', TRUE),
            'user_name' => $this->input->post('user_name', TRUE),
            'password' => $this->input->post('password', TRUE),
            'updated_at' => $this->input->post('updated_at', TRUE),
            'level' => $this->input->post('level', TRUE),
            'is_default' => $this->input->post('is_default', TRUE),
            'parent_id' => $this->input->post('parent_id', TRUE),

        );
        $id = intval($this->input->post('id'));
        $result = $this->Employee_model->save($param);
        if ($result) {
            return $this->json(array("id" => $id), $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
            return $this->json(null, $this::SYS_ERROR, $this->lang->line('text_resp_fail'));
        }
    }

    /**
     * @SWG\Post(path="/employee/remove",
     *   consumes={"multipart/form-data"},
     *   tags={"Employee"},
     *   summary="删除内部员工",
     *   description="删除内部员工",
     *   operationId="removeEmployee",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="id",
     *     description="id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="token",
     *     description="用户登录token",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function remove_post(){
        if (!$this->user_id) {
            return $this->json(null, REST_Controller::NOT_LOGIN, $this->lang->line('text_resp_unlogin'));
        }

        //参数校验
        $this->form_validation->set_rules('id', 'ID', 'required|integer');

        $this->form_validation->set_error_delimiters('', '');//去除p标签和换行
        if ($this->form_validation->run() == false) {
            // 传递错误信息
            $message = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            return $this->json(null, $this::SYS_ERROR, format_message($message));
        }
        $id = intval($this->input->post('id'));

        if ($this->Employee_model->delete($id)) {
            return $this->json(array("id" => $id), $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
            return $this->json(null, $this::SYS_ERROR, $this->lang->line('text_resp_fail'));
        }
    }

    /**
     * @SWG\Get(path="/employee/find",
     *   consumes={"multipart/form-data"},
     *   tags={"Employee"},
     *   summary="查询内部员工",
     *   description="查询内部员工",
     *   operationId="findEmployee",
     *     @SWG\Parameter(
     *     in="query",
     *     name="token",
     *     description="用户登录token",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="id",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function find_get(){
        //if (!$this->user_id) {
        //    return $this->json(null, REST_Controller::NOT_LOGIN, $this->lang->line('text_resp_unlogin'));
        //}
        $id = intval($this->input->get('id'));
        //获取数据
        $data = $this->Employee_model->find($id);
        if ($data) {
            return $this->json($data, $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
            return $this->json(null, $this::NO_DATA, $message = $this->lang->line('text_resp_no_data'));
        }
    }
}
