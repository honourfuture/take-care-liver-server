<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class CardUseRecord extends REST_Controller
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
     * @SWG\Get(path="/carduserecord/list",
     *   tags={"carduserecord"},
     *   summary="列表",
     *   description="体检卡的使用记录列表",
     *   operationId="carduserecordlist",
     *  @SWG\Parameter(
     *     in="query",
     *     name="user_id",
     *     description="当前用户的user_id",
     *     required=false,
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
        $this->load->model('CardUseRecord_model');
        $orwhere = [];
        $data = $this->CardUseRecord_model->getAll($where, $orwhere, $limit, $offset);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 500, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/carduserecord/info",
     *   tags={"carduserecord"},
     *   summary="详情记录",
     *   description="体检卡使用记录详情",
     *   operationId="carduserecordinfo",
     *  @SWG\Parameter(
     *     in="query",
     *     name="user_id",
     *     description="当前用户的user_id",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="当前列表数据的唯一标识id",
     *     required=false,
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
            $this->load->model('CardUseRecord_model');
            $data = $this->CardUseRecord_model->findByParams($where);
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
     * @SWG\Post(path="/carduserecord/add",
     *   consumes={"multipart/form-data"},
     *   tags={"carduserecord"},
     *   summary="使用",
     *   description="体检卡使用",
     *   operationId="carduserecorddd",
     *  @SWG\Parameter(
     *     in="formData",
     *     name="user_id",
     *     description="当前用户的user_id",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="card_grand_record_id",
     *     description="使用体检卡的id",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="status",
     *     description="使用状态【1已使用，0未使用、2退回】",
     *     required=true,
     *     type="integer"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function add_post() {
        $user_id = intval($this->input->post('user_id'));
        $card_grand_record_id = intval($this->input->post('card_grand_record_id'));//使用体检卡的id
        $status = intval($this->input->post('status'));//使用记录状态【1已使用，0未使用、2退回】
        if($card_grand_record_id<=0) {
            $this->json([], 500, $message = '请求数据不合法');
        }
        //检查体验卡是否有效
        $this->load->model('CardGrantRecord_model');
        $cardGrantRecordWhere['id'] = $card_grand_record_id;
        $data = $this->CardGrantRecord_model->findByParams($cardGrantRecordWhere);
        if(empty($data)) {
            $this->json([], 500, $message = '请求数据不合法');
        }
        if(isset($data->times) && $data->times<=0) {
            $this->json([], 500, $message = '当前体检卡次数已用完');
        }
        if(isset($data->valid_end_time) && strtotime($data->valid_end_time)<=time()) {
            $this->json([], 500, $message = '该体检卡已过期');
        }
        $type = isset($data->type) ? $data->type : 0;
        $insertData = [];
        $insertData['type'] = $type;
//        $insertData['open_id'] = $open_id;
        $insertData['user_id'] = $user_id;
        $insertData['card_grand_record_id'] = $card_grand_record_id;
        $insertData['status'] = $status;
        if (!empty($insertData)) {
            $this->load->model('CardUseRecord_model');
            $data = $this->CardUseRecord_model->add($insertData);
            if ($data) {
                $update = $this->db->query("update card_grant_record set times=times-1 where id=$card_grand_record_id");
                if($update) {
                    $this->json(1);
                }else{
                    $this->json([], 5001, $message = '使用失败');
                }
            } else {
                $this->json([], 5002, $message = '使用失败');
            }
        } else {
            $this->json([], 5003, $message = '使用失败');
        }
    }
}

?>