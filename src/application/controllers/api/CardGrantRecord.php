<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class CardGrantRecord extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    private function json($data, $code = 0, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }
    /**
     * @SWG\Get(path="/cardgrantrecord/list",
     *   tags={"cardgrantrecord"},
     *   summary="列表",
     *   description="体检卡的发放记录列表",
     *   operationId="cardgrantrecordlist",
     *  @SWG\Parameter(
     *     in="query",
     *     name="user_id",
     *     description="当前用户的标识user_id",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="limit",
     *     description="每页显示条数",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="offset",
     *     description="从第几条开始获取",
     *     required=false,
     *     type="integer"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
        $offset = intval($this->input->get('offset'));
        $limit = intval($this->input->get('limit'));
        $user_id = $this->input->get('user_id');
        $where = [];
        if($limit<=0) {
            $limit = 10;
        }
        if($limit>=100) {
            $limit = 100;
        }
        if($user_id) {
            $where['user_id'] = $user_id;
        }
        $this->load->model('CardGrantRecord_model');
        $orwhere = [];
        $data = $this->CardGrantRecord_model->getAll($where, $orwhere, $limit, $offset);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 500, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/cardgrantrecord/info",
     *   tags={"cardgrantrecord"},
     *   summary="详情记录",
     *   description="体检卡发放记录详情",
     *   operationId="cardgrantrecordinfo",
     *  @SWG\Parameter(
     *     in="query",
     *     name="user_id",
     *     description="当前用户的标识user_id",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="当前列表数据的唯一标识id",
     *     required=true,
     *     type="integer"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function info_get() {
        $id = intval($this->input->get('id'));
        $user_id = $this->input->get('user_id');
        $where = [];
        if($id) {
            $where['id'] = $id;
        }
        if($user_id) {
            $where['user_id'] = $user_id;
        }
        if ($where) {
            $this->load->model('CardGrantRecord_model');
            $data = $this->CardGrantRecord_model->findByParams($where);
            if ($data) {
                $this->json($data);
            } else {
                $this->json([], 500, $message = '没有数据');
            }
        } else {
            $this->json([], 500, $message = '没有数据');
        }
    }
    /**
     * @SWG\Post(path="/cardgrantrecord/add",
     *   consumes={"multipart/form-data"},
     *   tags={"cardgrantrecord"},
     *   summary="发放",
     *   description="体检卡发放",
     *   operationId="cardgrantrecordadd",
     *  @SWG\Parameter(
     *     in="formData",
     *     name="user_id",
     *     description="当前用户的标识user_id",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="type",
     *     description="1 : 肝检, 2 : 尿检",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="times",
     *     description="体检卡发放次数",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="valid_start_time",
     *     description="体检卡有效开始时间:2019-08-31 14:28:16",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="valid_end_time",
     *     description="体检卡有效结束时间:2019-09-31 14:28:16",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function add_post() {
        $type = intval($this->input->post('type'));//体检卡类型【1、肝检。2尿检】
        $times = intval($this->input->post('times'));//体检卡发放次数
        $valid_start_time = trim($this->input->post('valid_start_time'));//体检卡有效开始时间
        $valid_end_time = trim($this->input->post('valid_end_time'));//体检卡有效结束时间
        $user_id = trim($this->input->post('user_id'));
        $data = [];
        if(!$type || !in_array($type,[1,2])) {
            $this->json([], 500, $message = '请求数据不合法');
        }
        if($times<=0) {
            $this->json([], 500, $message = '请求数据不合法');
        }
        if(empty($valid_start_time)) {
            $valid_start_time = date('Y-m-d H:i:s');
        }
        if(empty($valid_end_time)) {
            $valid_end_time = date("Y-m-d H:i:s",strtotime("+1years"));
        }
        if(strtotime($valid_start_time)<=0 || strtotime($valid_end_time) <= strtotime($valid_start_time)) {
            $this->json([], 500, $message = '请求数据不合法');
        }
        if(empty($user_id)) {
            $this->json([], 500, $message = '请求数据不合法');
        }
        $data['type'] = $type;
        $data['times'] = $times;
        $data['user_id'] = $user_id;
        $data['valid_start_time'] = $valid_start_time;
        $data['valid_end_time'] = $valid_end_time;
        if (!empty($data)) {
            $this->load->model('CardGrantRecord_model');
            $data = $this->CardGrantRecord_model->add($data);
            if ($data) {
                $this->json($data);
            } else {
                $this->json([], 500, $message = '发放失败');
            }
        } else {
            $this->json([], 500, $message = '发放失败');
        }
    }
}

?>