<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class CardGrantRecord extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    private function json($data, $code = 200, $message = '')
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
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
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
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
        $where = [];
        if($this->user_id) {
            $where['user_id'] = $this->user_id;
        } else {
            $this->json([], 401, $message = '未登录');
        }
        $this->load->model('CardGrantRecord_model');
        $orwhere = [];
        $data = $this->CardGrantRecord_model->getAll($where, $orwhere, $this->per_page, $this->offset);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/cardgrantrecord/info",
     *   tags={"cardgrantrecord"},
     *   summary="详情记录",
     *   description="体检卡发放记录详情",
     *   operationId="cardgrantrecordinfo",
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
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
        $where = [];
        if($id) {
            $where['id'] = $id;
        }
        if($this->user_id) {
            $where['user_id'] = $this->user_id;
        }else {
            $this->json([], 401, $message = '未登录');
        }
        if ($where) {
            $this->load->model('CardGrantRecord_model');
            $data = $this->CardGrantRecord_model->findByParams($where);
            if ($data) {
                $this->json($data);
            } else {
                $this->json([], 200, $message = '没有数据');
            }
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }
    /**
     * @SWG\Post(path="/cardgrantrecord/add",
     *   consumes={"multipart/form-data"},
     *   tags={"cardgrantrecord"},
     *   summary="发放",
     *   description="体检卡发放",
     *   operationId="cardgrantrecordadd",
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
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
     *     description="体检卡有效结束时间:2022-09-30 14:28:16",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="source",
     *     description="1: 购买, 2: 分销，3: 转发",
     *     required=true,
     *     type="integer"
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
        $user_id = $this->user_id;
        $source = intval($this->input->post('source'));
        $this->load->model('CardGrantRecord_model');
        $data = $this->CardGrantRecord_model->grantCard($user_id, $type, $valid_start_time, $valid_end_time, $times, $source);
        echo json_encode($data);die;
    }
    /**
     * @SWG\Get(path="/cardgrantrecord/have",
     *   tags={"cardgrantrecord"},
     *   summary="体检卡次数汇总",
     *   description="体检卡次数汇总",
     *   operationId="cardgrantrecordhave",
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function have_get() {
        $where = [];
        $limit = 100;
        if($this->user_id) {
            $where['user_id'] = $this->user_id;
        } else {
            $this->user_id = intval($this->input->get('user_id'));//用户id
            $where['user_id'] = $this->user_id;
        }
        if(!$this->user_id) {
            $this->json([], 401, $message = '未登录');
        }
        $this->load->model('CardGrantRecord_model');
        $this->load->model('CardUseRecord_model');
        $grantRecordRet = $this->CardGrantRecord_model->getGroupCountAll($where, [],"*,sum(times) as totaltimes");
        $useRecordRet = $this->CardUseRecord_model->getGroupCountAll($where, [],"*,count(*) as totaltimes");
        $data = [
            'grantRecord'=>[
                1=>0,
                2=>0,
            ],
            'useRecord'=>[
                1=>0,
                2=>0,
            ],
        ];
        foreach($grantRecordRet as $k=>$v) {
            if(isset($v->type)) {
                $data['grantRecord'][$v->type]=(isset($v->totaltimes) && $v->totaltimes) ? $v->totaltimes : 0;
            }
        }
        foreach($useRecordRet as $k=>$v) {
            if(isset($v->type)) {
                $data['useRecord'][$v->type]=(isset($v->totaltimes) && $v->totaltimes) ? $v->totaltimes : 0;
            }
        }
        $this->json($data);
    }
}

?>