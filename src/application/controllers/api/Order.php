<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;
require(APPPATH."/libraries/wechatpay/MY_WxPayConfig.php");
require(APPPATH."/libraries/wechatpay/MY_WxPay.php");
require(APPPATH."/libraries/wechatpay/MY_WxPayNotify.php");
class Order extends REST_Controller
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
     * @SWG\Get(path="/order/list",
     *   tags={"Order"},
     *   summary="列表",
     *   description="订单的发放记录列表",
     *   operationId="orderlist",
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
        $data = $this->OrderAndPay_model->getOrderAll($where, $orwhere, $this->per_page, $this->offset);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/order/info",
     *   tags={"Order"},
     *   summary="详情记录",
     *   description="订单记录详情",
     *   operationId="orderinfo",
     *  @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="当前列表数据的唯一标识id",
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
    public function info_get() {
        $id = intval($this->input->get('id'));
        $where = [];
        if($id) {
            $where['id'] = $id;
        } else {
            $this->json([], 401, $message = '未登录');
        }
        if($this->user_id) {
            $where['user_id'] = $this->user_id;
        } else {
            $this->json([], 500, $message = '登录状态异常');
        }
        if ($where) {
            $this->load->model('OrderAndPay_model');
            $data = $this->OrderAndPay_model->findOrderInfoByParams($where);
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
     * @SWG\Post(path="/order/buy",
     *   consumes={"multipart/form-data"},
     *   tags={"Order"},
     *   summary="产品下单购买",
     *   description="产品下单",
     *   operationId="orderbuy",
     *  @SWG\Parameter(
     *     in="formData",
     *     name="token",
     *     description="当前用户的标识token",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="products_id",
     *     description="产品唯一标识id",
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
    public function buy_post() {
        $pay_from = intval($this->input->post('pay_from'));//支付来源如【1APP，2Web，3微信公众号，4小程序】
        $pay_type = 2;//支付来源如【1APP，2Web，3微信公众号，4小程序】
        $products_id = trim($this->input->post('products_id'));//产品id
        $address_id = trim($this->input->post('address_id'));//收货地址id
        $buy_number = 1;//购买数量
        $this->load->model('Product_model');
        $projectData = $this->Product_model->find($products_id);
        if(empty($projectData)) {
            $this->json([], 500, $message = '购买产品不存在');
        }
        $user_id = 0;
        if($this->user_id) {
            $user_id = $this->user_id;
        } else {

            return $this->json([], 401, $message = '登录状态异常');
        }

        $addOrder['old_price'] =isset($projectData->old_price) ? $projectData->old_price : 0;//原价
        $addOrder['now_price'] =isset($projectData->price) ? $projectData->price : 0;//现价
        $addOrder['price'] =isset($projectData->price) ? $projectData->price*$buy_number : 0;//下单金额
        $addOrder['products_title'] =isset($projectData->name) ? $projectData->name : "体检项目";
        $addOrder['products_describe'] =isset($projectData->describe) ? $projectData->describe : "体检项目";
        $addOrder['products_pic'] =isset($projectData->pic) ? $projectData->pic : "";
        $addOrder['products_id'] = $products_id;
        $addOrder['address_id'] = $address_id;
        $addOrder['pay_from'] = $pay_from;
        $addOrder['pay_type'] = $pay_type;
        $addOrder['user_id'] = $user_id;
        $addOrder['status'] = 10;
        $addOrder['order_no'] = createLongNumberNo(19);

        $this->load->model('OrderAndPay_model');
        $this->load->model('BalanceDetails_model');
        $this->load->model('User_model');
        $price = $addOrder['price'];
        $addOrderStatus = $this->OrderAndPay_model->addOrder($addOrder);
        if($addOrderStatus) {
            $userInfo = $this->User_model->find($user_id);

            $biz_content = array(
                'body' => $addOrder['products_title'], // 订单
                'out_trade_no' => $addOrder['order_no'],
                'total_fee' => $price*100,
                'openId' => $userInfo->openId
            );
            $MY_WxPay = new MY_WxPay();

            $data = $MY_WxPay->unifiedOrder($biz_content);

            if($data){
                return $this->json($data, 200, $message = '拉起支付');
            }else{
                return $this->json($data, 500, $message = '拉起支付失败');
            }

        }
        return $this->json([], 500, $message = '下单异常');
    }
}

?>