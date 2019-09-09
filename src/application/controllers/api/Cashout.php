<?php
defined('BASEPATH') or exit ('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Cashout extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->load->model('User_model');
        $this->load->model('Cash_out_model');
        $this->load->model('Card_bank_model');
        $this->load->model('BalanceDetails_model');
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/cashout/list",
     *   tags={"Cashout"},
     *   summary="提现记录",
     *   description="提现记录",
     *   operationId="cashoutList",
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
            return  $this->json([], 401, '请登录');
        }

        $wheres = [
            'user_id' => $this->user_id,
            'type' => 1
        ];
        $data = $this->BalanceDetails_model->getList($wheres, $this->per_page, $this->offset, 1);
        if ($data) {
            return $this->json($data);
        } else {
            return $this->json([], 0, $message = '没有数据');
        }
    }

    /**
     * @SWG\Post(path="/cashout/create",
     *   consumes={"multipart/form-data"},
     *   tags={"Cashout"},
     *   summary="提现申请",
     *   description="提现申请",
     *   operationId="cashoutCreate",
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="用户登陆token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="cash_out_money",
     *     description="提现金额",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="card_bank_id",
     *     description="银行卡id",
     *     required=true,
     *     type="integer"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function create_post()
    {
        if(!$this->user_id){
            return $this->json(null, 401, '请登录');//未登录
        }

        $cashOutMoney = floatval($this->input->post('cash_out_money'));
        $cardBankId = intval($this->input->post('card_bank_id'));

        $where = array(
            'id' => $cardBankId,
            'user_id' => $this->user_id,
        );
        $isHas = $this->Card_bank_model->findByAttributes($where);
        if(!$isHas){
            return $this->json(null, 500, '未找到银行卡');
        }
        $result = $this->User_model->find($this->user_id);

        if($result->balance < $cashOutMoney){
            return $this->json(null, 500, '余额不够');
        }
        $insert = array(
            'card_bank_id' => $cardBankId,
            'user_id' => $this->user_id,
            'cash_out_money' => $cashOutMoney,
            'type' => 0,
            'apply_time' => date('Y-m-d H:i:s')
        );

        $blance = $result->balance - $cashOutMoney;
        $this->db->trans_start();
        $id = $this->Cash_out_model->create($insert);
        $userId = $this->User_model->update($this->user_id, array('balance' => $blance));

        $withdrawApplyInsert = array(
            'user_id' => $this->user_id,
            'money' => $cashOutMoney,
            'type' => 1,
            'status' => 2,
            'cash_out_id' => $id
        );

        $withdrawApplyId = $this->BalanceDetails_model->create($withdrawApplyInsert);
        $this->db->trans_complete();

        if(!$id || !$userId || !$withdrawApplyId){
            return $this->json(null, 500, 'db_error');
        }

        return $this->json([], 200, '提现申请成功！');
    }



}

?>