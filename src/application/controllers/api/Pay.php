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
        $this->load->model('OrderAndPay_model');
        $orwhere = [];
        $data = $this->OrderAndPay_model->getPayAll($where, $orwhere, $limit, $offset);
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
     *  @SWG\Parameter(
     *     in="formData",
     *     name="user_id",
     *     description="当前用户的标识user_id",
     *     required=true,
     *     type="integer"
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
        $user_id = intval($this->input->post('user_id'));//用户id
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
        $addPay['user_id'] = $user_id;
        $addPay['app_id'] = "1495338032";
        $addPay['my_trade_no'] = createLongNumberNo(18);
        $this->load->model('OrderAndPay_model');
        $addPayResult = $this->OrderAndPay_model->addPay($addPay);
        if($addPayResult) {
            return $this->json($addPayResult);
        }
        return $this->json([], 500, $message = '下单异常');
    }
}

?>