<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Pay extends REST_Controller
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
     * @SWG\Get(path="/pay/list",
     *   tags={"Pay"},
     *   summary="列表",
     *   description="支付记录列表",
     *   operationId="paylist",
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
            $this->json([], 500, $message = '没有数据');
        }
        $this->load->model('OrderAndPay_model');
        $orwhere = [];
        $data = $this->OrderAndPay_model->getPayAll($where, $orwhere, $this->per_page, $this->offset);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 500, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/pay/info",
     *   tags={"Pay"},
     *   summary="支付详情详情记录",
     *   description="支付记录详情",
     *   operationId="payinfo",
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
        }
        if ($where) {
            $this->load->model('OrderAndPay_model');
            $data = $this->OrderAndPay_model->findPayInfoByParams($where);
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
     * @SWG\Post(path="/pay/pay",
     *   consumes={"multipart/form-data"},
     *   tags={"Pay"},
     *   summary="订单支付",
     *   description="订单支付",
     *   operationId="paypay",
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="order_id",
     *     description="订单的唯一标识id",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="pay_from",
     *     description="支付来源如【1APP，2Web，3微信公众号，4小程序】",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="address_id",
     *     description="收货地址id标识",
     *     required=false,
     *     type="integer"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function pay_post() {
        $pay_from = intval($this->input->post('pay_from'));//支付来源如【1APP，2Web，3微信公众号，4小程序】
        $pay_type = 2;//支付来源如【1APP，2Web，3微信公众号，4小程序】
        $order_id = trim($this->input->post('order_id'));//订单id
        $address_id = trim($this->input->post('address_id'));//收货地址id
        $this->load->model('OrderAndPay_model');
        $orderData = $this->OrderAndPay_model->findOrderInfo($order_id);
        if(empty($orderData)) {
            $this->json([], 500, $message = '购买产品不存在');
        }
        $addPay['total_amount'] =isset($orderData->price) ? $orderData->price : 0;//下单金额
        $addPay['products_title'] =isset($orderData->products_title) ? $orderData->products_title : "体检项目";
        $addPay['order_id'] = $order_id;
        $addPay['address_id'] = $address_id;
        $addPay['pay_from'] = $pay_from;
        $addPay['pay_type'] = $pay_type;
        $addPay['user_id'] = $this->user_id;
        $addPay['app_id'] = "1495338032";
        $addPay['my_trade_no'] = createLongNumberNo(18);
        $this->load->model('OrderAndPay_model');
        $addPayResult = $this->OrderAndPay_model->addPay($addPay);
        if($addPayResult) {
            return $this->json($addPayResult);
        }
        return $this->json([], 500, $message = '下单异常');
    }
    /**
     * @SWG\Get(path="/pay/callback",
     *   tags={"Pay"},
     *   summary="支付回调",
     *   description="支付回调",
     *   operationId="paycallback",
     *  @SWG\Parameter(
     *     in="query",
     *     name="pay_id",
     *     description="当前支付的唯一标识标识id",
     *     required=true,
     *     type="integer"
     *   ),
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
    public function callback_get() {
        $pay_id = intval($this->input->get('pay_id'));

        $where = [];
        if($pay_id<=0) {
            return $this->json([], 500, $message = '请求参数异常');
        }
        $where['id'] = $pay_id;
        if($this->user_id<=0) {
            return $this->json([], 500, $message = '请求参数异常');
        }
        $where['user_id'] = $this->user_id;
        if ($where) {
            $this->load->model('OrderAndPay_model');
            $data = $this->OrderAndPay_model->findPayInfoByParams($where);
            if (!empty($data)) {
                $this->load->model('User_model');
                $updateUserInfoData['is_vip'] = 1;
                $updateUserRet = $this->User_model->update_info($user_id, $updateUserInfoData);
                $userData = $this->User_model->find($user_id);
                $parent_id = isset($userData->parent_id) ? $userData->parent_id : 0;
                if($parent_id) {
                    $this->load->model('CardGrantRecord_model');
                    //看到文章列表及详情，点击“转发”，转发到自己的微信好友，若改好友成为了平台的会员，则转发的人获得一次免费的尿检
                    $grantCardRecord = $this->CardGrantRecord_model->grantCard($parent_id, 2, date('Y-m-d H:i:s'),
                        date("Y-m-d H:i:s",strtotime("+1years")), 1, 3);
                }
                $this->json($updateUserRet);
            } else {
                return $this->json([], 500, $message = '没有数据');
            }
        } else {
            return $this->json([], 500, $message = '没有数据');
        }
    }
}

?>