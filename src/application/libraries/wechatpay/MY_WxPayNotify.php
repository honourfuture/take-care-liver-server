<?php defined('BASEPATH') OR exit('No direct script access allowed');

// 加载微信支付接口类
require_once APPPATH.'libraries/wechatpay/lib/WxPay.Api.php';
require_once APPPATH.'libraries/wechatpay/lib/WxPay.Notify.php';
require_once 'log.php';
$logHandler= new CLogFileHandler(APPPATH."libraries/wechatpay/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
/**
 * Class PayNotifyCallBack
 * create by wjf @2019-01-17
 */
class PayNotifyCallBack extends WxPayNotify
{
    private $_CI;

    public function  __construct(){
        // 获得 CI 超级对象 使得自定义类可以使用Controller类的方法
        $this->_CI = & get_instance();
        $this->_CI->load->model('Information_model');
        $this->_CI->load->model('User_model');
        $this->_CI->load->model('BalanceDetails_model');
        $this->_CI->load->model('CardGrantRecord_model');
        $this->_CI->load->model('OrderAndPay_model');
        $this->_CI->load->model('Config_model');
        $this->_CI->load->model('Product_model');

    }

    /**
     *
     * 回包前的回调方法
     * 业务可以继承该方法，打印日志方便定位
     * @param string $xmlData 返回的xml参数
     *
     **/
    public function LogAfterProcess($xmlData)
    {
        Log::DEBUG("call back， return xml:" . $xmlData);
        return;
    }

    //重写回调处理函数
    /**
     * @param WxPayNotifyResults $data 回调解释出的参数
     * @param WxPayConfigInterface $config
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        Log::DEBUG("call back:" . json_encode($data));
        //将参数计入日志
        //app_id
        $app_id = $data['appid'];
        //商户订单号
        $out_trade_no = $data['out_trade_no'];
        //微信交易号
        $trade_no = $data['transaction_id'];
        //交易状态
        $trade_status = $data['return_code'];
        //支付金额
        $total_fee = $data['total_fee'];

        //支付方式
        $trade_type = $data['trade_type'];

        //写日志
        $logdata = array(
            "url_type" => 'wechatpay_notify_url',
            "app_id" => $app_id,
            "out_trade_no" => $out_trade_no,
            "trade_no" => $trade_no,
            "trade_status" => $trade_status,
            "total_fee" => $total_fee,
            "post_msg" => json_encode($data),
            "create_at" => date('Y-m-d H:i:s')
        );
        $log_id = $this->_CI->Information_model->save_pay_log($logdata);

        //TODO 1、进行参数校验
        if(!array_key_exists("return_code", $data)
            ||(array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
            //TODO失败,不是支付成功的通知
            //如果有需要可以做失败时候的一些清理处理，并且做一些监控
            $pay_data['pay_type'] =  "";//支付类型  alipay、wechatpay
            $pay_data['trans_no'] =  "";//支付流水号
            $pay_data['status'] = 1;//未支付
            $pay_data['pay_at'] = NULL;//支付时间
            $this->_CI->Information_model->update_by_out_trade_no($pay_data, $out_trade_no);
            $msg = "异常异常";
            $upd_data['return_msg'] = $msg;
            $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
            return false;
        }
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            $upd_data['return_msg'] = $msg;
            $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
            return false;
        }

        //TODO 2、进行签名验证
        try {
            $checkResult = $objData->CheckSign($config);
            if($checkResult == false){
                //签名错误
                Log::ERROR("签名错误...");
                $msg = "签名错误...";
                $upd_data['return_msg'] = $msg;
                $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
                return false;
            }
        } catch(Exception $e) {
            Log::ERROR(json_encode($e));
        }

        //TODO 3、处理业务逻辑
        if(config_item('wechatpay_config')['app_id'] != $app_id){
            $msg = "appid不符！";
            $upd_data['return_msg'] = $msg;
            $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
            return false;
        }
        //查询订单，判断订单真实性
//        Log::DEBUG("查询订单判断真实性" .$data["transaction_id"]);
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            $upd_data['return_msg'] = $msg;
            $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
            return false;
        }
        //判断订单状态
//        LOG::DEBUG("判断订单状态".$out_trade_no);
        $order = $this->_CI->OrderAndPay_model->findOrderSnInfo($out_trade_no);

        if(empty($order)){
            $msg = "非本系统订单！";
            $upd_data['return_msg'] = $msg;
            $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
            return false;
        }
        if($order->status == 0){
            $msg = "订单已取消，请勿支付！";
            $upd_data['return_msg'] = $msg;
            $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
            return true;
        } elseif($order->status == 20){
            $msg = "订单已完成，请勿支付！";
            $upd_data['return_msg'] = $msg;
            $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
            return true;
        }
        $pay_data['status'] = 20;//已支付
        $this->_CI->OrderAndPay_model->updateOrderInfo($order->id, $pay_data);
        $startDate = date('Y-m-d H:i:s',time());
        $endDate = date("Y-m-d H:i:s",strtotime("+1 years",strtotime($startDate)));
        $data = $this->_CI->Config_model->findByAttributes(array('id' => 1), 'addMoney');
        $add = $data['addMoney'];

        $product = $this->_CI->Product_model->find($order->products_id);
        if($product->type == 4){
            $userInfo = $this->_CI->User_model->find($order->user_id);
            if($userInfo->parent_id){
                $parentUser = $this->_CI->User_model->find($userInfo->parent_id);
                //增加parent_id 的余额
                $balance = $parentUser->balance + $add;
                if($parentUser->is_operator == 1){
                    $this->_CI->User_model->update($parentUser->id, ['balance' => $balance]);
                    //增加parent_id 的余额记录
                    $create = [
                        'user_id' => $parentUser->id,
                        'money' => $add,
                        'type' => 2,
                        'status' => 1,
                        'about_id' => $order->user_id
                    ];
                    $this->_CI->BalanceDetails_model->create($create);
                }
                //增加分享者尿检次数
                $this->_CI->CardGrantRecord_model->grantCard($parentUser->id, 2, $startDate, $endDate, 1, 1);
            }

            //修改当前用户为vip
            $this->_CI->User_model->update($order->user_id, ['is_vip' => 1]);

            //增加当前用户的肝次数和尿次数
            $this->_CI->CardGrantRecord_model->grantCard($order->user_id, 1, $startDate, $endDate, 12, 1);
            $this->_CI->CardGrantRecord_model->grantCard($order->user_id, 2, $startDate, $endDate, 1, 1);
        }

        //增加当前用户的余额记录
        $create = [
            'user_id' => $order->user_id,
            'money' => $order->price,
            'type' => 3,
            'status' => 2,
            'about_id' => $order->products_id
        ];
        $this->_CI->BalanceDetails_model->create($create);

        $upd_data['return_msg'] = "success";
        $this->_CI->Information_model->update_pay_log($upd_data, $log_id);
        return true;
    }

    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);

        $config = new WxPayConfig();
        $result = WxPayApi::orderQuery($config, $input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

}