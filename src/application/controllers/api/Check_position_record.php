<?php
defined('BASEPATH') or exit ('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

/**
 * check_position_record API控制器
 */
class Check_position_record extends REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Check_position_record_model');
        $this->load->library(array('form_validation'));
            }

    /**
     * @SWG\Get(path="/check_position_record/list",
     *   consumes={"multipart/form-data"},
     *   tags={"CheckPositionRecord"},
     *   summary="查询检测点检测记录列表",
     *   description="查询检测点检测记录列表",
     *   operationId="check_position_recordList",
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
        //if (!$this->user_id) {
        //    return $this->json(null, REST_Controller::NOT_LOGIN, $this->lang->line('text_resp_unlogin'));
        //}
        $total = $this->Check_position_record_model->count(array());
        $result = $this->Check_position_record_model->getResult(array(), $this->per_page, $this->offset);
        if ($result) {
            //foreach ($result as $check_position_record) {
            //}
            return $this->json(array("list" => $result, "total" => $total), $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
           return $this->json(null, $this::NO_DATA, $message = $this->lang->line('text_resp_no_data'));
        }
    }

    /**
     * @SWG\Post(path="/check_position_record/add",
     *   consumes={"multipart/form-data"},
     *   tags={"CheckPositionRecord"},
     *   summary="添加检测点检测记录",
     *   description="添加检测点检测记录",
     *   operationId="addCheckPositionRecord",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="user_id",
     *     description="用户ID",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="check_position_id",
     *     description="检测点ID",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="date",
     *     description="检测时间",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="money",
     *     description="金额",
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
        
                    $this->form_validation->set_rules('user_id', 'user_id', 'trim');
                        $this->form_validation->set_rules('check_position_id', 'check_position_id', 'trim');
                        $this->form_validation->set_rules('date', 'date', 'trim');
                        $this->form_validation->set_rules('money', 'money', 'trim');
                    $this->form_validation->set_error_delimiters('', '');//去除p标签和换行
        if ($this->form_validation->run() == false) {
            // 传递错误信息
            $message = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            return $this->json(null, $this::SYS_ERROR, format_message($message));
        }
        $param = array(            'id' => $this->input->post('id', TRUE),
            'user_id' => $this->input->post('user_id', TRUE),
            'check_position_id' => $this->input->post('check_position_id', TRUE),
            'update_time' => date('Y-m-d H:i:s'),
            'money' => $this->input->post('money', TRUE),

        );
        $id = $this->Check_position_record_model->save($param);
        if ($id) {
            return $this->json(array("id" => $id), $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
            return $this->json(null, $this::SYS_ERROR, $this->lang->line('text_resp_fail'));
        }
    }

    /**
     * @SWG\Post(path="/check_position_record/edit",
     *   consumes={"multipart/form-data"},
     *   tags={"CheckPositionRecord"},
     *   summary="编辑检测点检测记录",
     *   description="编辑检测点检测记录",
     *   operationId="editCheckPositionRecord",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="id",
     *     description="id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="user_id",
     *     description="用户ID",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="check_position_id",
     *     description="检测点ID",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="date",
     *     description="检测时间",
     *     required=false,
     *     type="string"
     *   ),

     *   @SWG\Parameter(
     *     in="formData",
     *     name="money",
     *     description="金额",
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
                    $this->form_validation->set_rules('user_id', 'user_id', 'trim');
                        $this->form_validation->set_rules('check_position_id', 'check_position_id', 'trim');
                        $this->form_validation->set_rules('date', 'date', 'trim');
                        $this->form_validation->set_rules('money', 'money', 'trim');
                    $this->form_validation->set_error_delimiters('', '');//去除p标签和换行
        if ($this->form_validation->run() == false) {
            // 传递错误信息
            $message = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            return $this->json(null, $this::SYS_ERROR, format_message($message));
        }

        //$id = intval($this->input->post('id'));
        $param = array(
                    'id' => $this->input->post('id', TRUE),
            'user_id' => $this->input->post('user_id', TRUE),
            'check_position_id' => $this->input->post('check_position_id', TRUE),
            'update_time' => date('Y-m-d H:i:s'),
            'money' => $this->input->post('money', TRUE),

        );
        $id = intval($this->input->post('id'));
        $result = $this->Check_position_record_model->save($param);
        if ($result) {
            return $this->json(array("id" => $id), $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
            return $this->json(null, $this::SYS_ERROR, $this->lang->line('text_resp_fail'));
        }
    }

    /**
     * @SWG\Post(path="/check_position_record/remove",
     *   consumes={"multipart/form-data"},
     *   tags={"CheckPositionRecord"},
     *   summary="删除检测点检测记录",
     *   description="删除检测点检测记录",
     *   operationId="removeCheckPositionRecord",
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

        if ($this->Check_position_record_model->delete($id)) {
            return $this->json(array("id" => $id), $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
            return $this->json(null, $this::SYS_ERROR, $this->lang->line('text_resp_fail'));
        }
    }

    /**
     * @SWG\Get(path="/check_position_record/find",
     *   consumes={"multipart/form-data"},
     *   tags={"CheckPositionRecord"},
     *   summary="查询检测点检测记录",
     *   description="查询检测点检测记录",
     *   operationId="findCheckPositionRecord",
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
        $data = $this->Check_position_record_model->find($id);
        if ($data) {
            return $this->json($data, $this::SUCCESS, $message = $this->lang->line('text_resp_success'));
        } else {
            return $this->json(null, $this::NO_DATA, $message = $this->lang->line('text_resp_no_data'));
        }
    }
}
