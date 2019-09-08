<?php
defined('BASEPATH') or exit ('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Balance extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->load->model('BalanceDetails_model');
        $this->load->model('User_model');
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/balance/find",
     *   tags={"Balance"},
     *   summary="余额总数",
     *   description="提现记录",
     *   operationId="balanceFind",
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
    public function find_get()
    {
        if(!$this->user_id){
            return  $this->json([], 500, '请登录');
        }

        $wheres = [
            'user_id' => $this->user_id,
            'type' => 2
        ];

        $data = $this->BalanceDetails_model->getSum($wheres);
        $result = $this->User_model->find($this->user_id);

        if ($data || $result) {
            $data->profitMoney = (int) $data->profitMoney;
            $data->balance = (int) $result->balance;

            return $this->json($data);
        } else {
            return $this->json([], 0, $message = '没有数据');
        }
    }

    /**
     * @SWG\Get(path="/balance/list",
     *   tags={"Balance"},
     *   summary="余额明细",
     *   description="余额明细",
     *   operationId="balanceList",
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
    public function list_get()
    {
        if(!$this->user_id){
            return  $this->json([], 500, '请登录');
        }

        $wheres = [
            'user_id' => $this->user_id,
        ];

        $data = $this->BalanceDetails_model->getList($wheres, $this->per_page, $this->offset, 1);
        if ($data) {
            return $this->json($data);
        } else {
            return $this->json([], 0, $message = '没有数据');
        }
    }

    /**
     * @SWG\Get(path="/balance/profit",
     *   tags={"Balance"},
     *   summary="收益明细",
     *   description="收益明细",
     *   operationId="balancProfit",
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
    public function profit_get()
    {
        if(!$this->user_id){
            return  $this->json([], 500, '请登录');
        }

        $wheres = [
            'user_id' => $this->user_id,
            'type' => 2
        ];

        $data = $this->BalanceDetails_model->getList($wheres, $this->per_page, $this->offset, 1);
        if ($data) {
            return $this->json($data);
        } else {
            return $this->json([], 0, $message = '没有数据');
        }
    }
}

?>