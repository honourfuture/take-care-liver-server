<?php
defined('BASEPATH') or exit ('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require(APPPATH."/libraries/wechatpay/MY_WxPayConfig.php");
require(APPPATH."/libraries/wechatpay/MY_WxPay.php");
require(APPPATH."/libraries/wechatpay/MY_WxPayNotify.php");

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
            $this->json([], 401, $message = '未登录');
        }
        $this->load->model('OrderAndPay_model');
        $orwhere = [];
        $data = $this->OrderAndPay_model->getPayAll($where, $orwhere, $this->per_page, $this->offset);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
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
        } else {
            $this->json([], 500, $message = '请求参数异常');
        }
        if($this->user_id) {
            $where['user_id'] = $this->user_id;
        } else {
            $this->json([], 500, $message = '登录状态异常');
        }
        if ($where) {
            $this->load->model('OrderAndPay_model');
            $data = $this->OrderAndPay_model->findPayInfoByParams($where);
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
        $order_id = intval($this->input->post('order_id'));//订单id
        $address_id = intval($this->input->post('address_id'));//收货地址id
        $user_id = 0;
        if($this->user_id) {
            $user_id = $this->user_id;
        } else {
            $this->json([], 500, $message = '登录状态异常');
        }
        if(!$user_id || !$pay_from || !$order_id) {
            $this->json([], 500, $message = '请求参数异常');
        }
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
    /**
     * @SWG\Post(path="/pay/callback",
     *   consumes={"multipart/form-data"},
     *   tags={"Pay"},
     *   summary="支付回调",
     *   description="支付回调",
     *   operationId="paycallback",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function callback_post() {
        $config = new WxPayConfig();
        $notify = new PayNotifyCallBack();
        $notify->Handle($config, true);
    }
    /**
     * @SWG\Get(path="/pay/test",
     *   tags={"Pay"},
     *   summary="测试",
     *   description="测试",
     *   operationId="paytest",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function test_get()
    {
        $biz_content = array(
            'body' => '测试：', // 订单
            'out_trade_no' => '111123',
            'total_fee' => 1*100,
            'openId' => 'o-sUM0XNMsq4BnRdX1BvS-5cLb18'
        );
        $MY_WxPay = new MY_WxPay();
        $data = $MY_WxPay->unifiedOrder($biz_content);
        var_dump($data);
    }
}

?>