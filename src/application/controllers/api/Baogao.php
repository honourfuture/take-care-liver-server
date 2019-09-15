<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Baogao extends REST_Controller
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
     * @SWG\Get(path="/baogao/data",
     *   tags={"Baogao"},
     *   summary="列表",
     *   description="报告的内容列表",
     *   operationId="baogaodata",
     *   produces={"application/json"},
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
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function data_get()
    {
        $where = [];
        if($this->user_id) {
            $where['user_id'] = $this->user_id;
        }else {
            $this->json([], 401, $message = '请登录');
        }
        $this->load->model('Baogao_model');
        $data = $this->Baogao_model->getAllData($this->user_id, $this->offset, $this->per_page);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/baogao/info",
     *   consumes={"multipart/form-data"},
     *   tags={"Baogao"},
     *   summary="报告的详情",
     *   description="报告的内容详情",
     *   operationId="baogaoinfo",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function info_get()
    {
        $id = $this->input->get('id');
        $this->load->model('Baogao_model');
        $data = $this->Baogao_model->findByUserId($id,$this->user_id);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }
}

?>