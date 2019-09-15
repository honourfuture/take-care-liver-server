<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class CardUseRecord extends REST_Controller
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
     * @SWG\Get(path="/carduserecord/list",
     *   tags={"carduserecord"},
     *   summary="列表",
     *   description="体检卡的使用记录列表",
     *   operationId="carduserecordlist",
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
        }else {
            $this->json([], 401, $message = '未登录');
        }
        $this->load->model('CardUseRecord_model');
        $orwhere = [];
        $data = $this->CardUseRecord_model->getAll($where, $orwhere, $this->per_page, $this->offset);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/carduserecord/info",
     *   tags={"carduserecord"},
     *   summary="详情记录",
     *   description="体检卡使用记录详情",
     *   operationId="carduserecordinfo",
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
        if($this->user_id) {
            $where['user_id'] = $this->user_id;
        }
        if ($where) {
            $this->load->model('CardUseRecord_model');
            $data = $this->CardUseRecord_model->findByParams($where);
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
     * @SWG\Post(path="/carduserecord/add",
     *   consumes={"multipart/form-data"},
     *   tags={"carduserecord"},
     *   summary="使用",
     *   description="体检卡使用",
     *   operationId="carduserecorddd",
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
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
        $user_id = $this->user_id;
        $card_grand_record_id = intval($this->input->post('card_grand_record_id'));//使用体检卡的id
        $status = intval($this->input->post('status'));//使用记录状态【1已使用，0未使用、2退回】
        if($card_grand_record_id<=0) {
            $this->json([], 500, $message = '请求数据不合法');
        }

        $this->load->model('CardUseRecord_model');
        $data = $this->CardUseRecord_model->useCard($user_id, $card_grand_record_id, $status);
        echo json_encode($data);die;
    }
}

?>