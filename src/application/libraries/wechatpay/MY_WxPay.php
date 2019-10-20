<?php defined('BASEPATH') OR exit('No direct script access allowed');

// ����΢��֧���ӿ���
require_once APPPATH.'libraries/wechatpay/lib/WxPay.JsApiPay.php';
require_once APPPATH.'libraries/wechatpay/lib/WxPay.Data.php';
require_once "MY_WxPayConfig.php";
require_once 'log.php';
$logHandler= new CLogFileHandler(APPPATH."libraries/wechatpay/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

/**
 * �Զ���΢��֧����
 * Class MY_WxPay
 * create by wjf @2019-01-17
 */
class MY_WxPay {

    /***
     * ͳһ�µ��ӿ�
     */
    public function unifiedOrder($biz_content)
    {
        try{
            //②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetBody($biz_content['body']);
            $input->SetOut_trade_no($biz_content['out_trade_no']);
//            $input->SetTotal_fee($biz_content['total_fee']);
            $input->SetTotal_fee(100);
            $input->SetNotify_url(config_item('wechatpay_config')['notify_url']);
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($biz_content['openId']);
            $config = new WxPayConfig();
            $order = WxPayApi::unifiedOrder($config, $input);
//            $order['timeStamp'] = (string) time();
//            $order['prepay_id'] = 'prepay_id='.$order['prepay_id'];

            $key = config_item('wechatpay_config')['key'];
            $jsApiParameters = $this->GetJsApiParameters($config, $order);
            $jsApiParameters['paySign'] = MD5("appId={$jsApiParameters['appId']}&nonceStr={$jsApiParameters['nonceStr']}&package={$jsApiParameters['prepayId']}&signType=MD5&timeStamp={$jsApiParameters['timeStamp']}&key={$key}");
            return $jsApiParameters;
        } catch(Exception $e) {
            Log::ERROR(json_encode($e));
            return false;
        }
    }

    /**
     *
     * ��ȡ����֧���Ĳ���
     * @param array $UnifiedOrderResult ͳһ֧���ӿڷ��ص�����
     * @throws WxPayException
     *
     * @return json���ݣ���ֱ�Ӵ���app��Ϊ����
     */
    public function GetJsApiParameters($config, $UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("��������");
        }
        $jsapi = new WxPayAppApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $jsapi->SetPartnerid(config_item('wechatpay_config')['mch_id']);
        $jsapi->SetPrepayid($UnifiedOrderResult['prepay_id']);
        $jsapi->SetPackage('Sign=WXPay');
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");

        $jsapi->SetSign($jsapi->MakeSign($config, false));
        $data = array(
            'appId' => $jsapi->GetAppid(),
            'partnerId' => $jsapi->GetPartnerid(),
            'prepayId' => 'prepay_id='.$jsapi->GetPrepayid(),
            'packageValue' => $jsapi->GetPackage(),
            'nonceStr' => $jsapi->GetNoncestr(),
            'timeStamp' => $jsapi->GetTimeStamp(),
            'sign' => $jsapi->GetSign(),
        );
        $parameters = json_encode($data);
        Log::DEBUG("to_app:".$parameters);
        return $data;
    }

    /**
     * createPayQRcode2
     * ����ֱ��֧��url ֧��url��Ч��Ϊ2Сʱ ģʽ��
     * @param array $params ͳһ�µ�������
     * @return void
     */
    public function createPayQRcode2($biz_content){
        $input = new WxPayUnifiedOrder();
        $input->SetBody($biz_content['body']);
        $input->SetOut_trade_no($biz_content['out_trade_no']);
        $input->SetTotal_fee($biz_content['total_fee']);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url(config_item('wechatpay_config')['notify_url']);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($biz_content['product_id']);
        $config = new WxPayConfig();
        Log::DEBUG("otherpay:".json_encode($input->GetValues()));
        $result = WxPayApi::unifiedOrder($config, $input);
        Log::DEBUG("otherpay return:".json_encode($result));
        return $result['code_url'];
    }

    /**
     * wxPayOrderQuery ΢��֧��������ѯ
     * @access public
     * @param string $order_no
     * @param bool $mode
     * @return array
     */
    public function getWxPayOrderInfo($config, $order_no, $mode = false){
        Log::DEBUG("query:".$order_no);
        if (! $order_no) return false;
        $input = new WxPayOrderQuery();
        if ($mode) {
            // ΢�Ŷ�����
            $input->SetTransaction_id($order_no);
        } else {
            // �̻�������
            $input->SetOut_trade_no($order_no);
        }
        $result = WxPayApi::orderQuery($config, $input);
        Log::DEBUG("query return:".json_encode($result));
        if($result["return_code"] == "SUCCESS"){
            return true;
        } else {
            return false;
        }
    }

    /**
     * wxPayOrderQuery ΢��֧�������ر�
     * @access public
     * @param string $order_no
     * @return array
     */
    public function closeWxPayOrder($config, $order_no){
        Log::DEBUG("close:".$order_no);
        $input = new WxPayCloseOrder();
        $input->SetOut_trade_no($order_no);
        $result = WxPayApi::closeOrder($config, $input);
        Log::DEBUG("close return:".json_encode($result));
    }

}


/**
 *
 * �ύJSAPI�������
 * @author widyhu
 *
 */
class WxPayAppApiPay extends WxPayDataBase
{
    /**
     * ����΢�ŷ���Ĺ����˺�ID
     * @param string $value
     **/
    public function SetAppid($value)
    {
        $this->values['appid'] = $value;
    }
    /**
     * ��ȡ΢�ŷ���Ĺ����˺�ID��ֵ
     * @return ֵ
     **/
    public function GetAppid()
    {
        return $this->values['appid'];
    }
    /**
     * �ж�΢�ŷ���Ĺ����˺�ID�Ƿ����
     * @return true �� false
     **/
    public function IsAppidSet()
    {
        return array_key_exists('appid', $this->values);
    }

    /**
     * ����΢�ŷ�����̻���
     * @param string $value
     **/
    public function SetPartnerid($value)
    {
        $this->values['partnerid'] = $value;
    }
    /**
     * ��ȡ΢�ŷ�����̻���
     * @return ֵ
     **/
    public function GetPartnerid()
    {
        return $this->values['partnerid'];
    }
    /**
     * �ж�΢�ŷ�����̻����Ƿ����
     * @return true �� false
     **/
    public function IsPartneridSet()
    {
        return array_key_exists('partnerid', $this->values);
    }

    /**
     * ����΢�ŷ�����̻���
     * @param string $value
     **/
    public function SetPrepayid($value)
    {
        $this->values['prepayid'] = $value;
    }
    /**
     * ��ȡ΢�ŷ�����̻���
     * @return ֵ
     **/
    public function GetPrepayid()
    {
        return $this->values['prepayid'];
    }
    /**
     * �ж�΢�ŷ�����̻����Ƿ����
     * @return true �� false
     **/
    public function IsPrepayidSet()
    {
        return array_key_exists('prepayid', $this->values);
    }


    /**
     * ����֧��ʱ���
     * @param string $value
     **/
    public function SetTimeStamp($value)
    {
        $this->values['timestamp'] = $value;
    }
    /**
     * ��ȡ֧��ʱ�����ֵ
     * @return ֵ
     **/
    public function GetTimeStamp()
    {
        return $this->values['timestamp'];
    }
    /**
     * �ж�֧��ʱ����Ƿ����
     * @return true �� false
     **/
    public function IsTimeStampSet()
    {
        return array_key_exists('timestamp', $this->values);
    }

    /**
     * ����ַ���
     * @param string $value
     **/
    public function SetNonceStr($value)
    {
        $this->values['noncestr'] = $value;
    }
    /**
     * ��ȡnotify����ַ���ֵ
     * @return ֵ
     **/
    public function GetNoncestr()
    {
        return $this->values['noncestr'];
    }
    /**
     * �ж�����ַ����Ƿ����
     * @return true �� false
     **/
    public function IsNoncestrSet()
    {
        return array_key_exists('noncestr', $this->values);
    }


    /**
     * ���ö���������չ�ַ���
     * @param string $value
     **/
    public function SetPackage($value)
    {
        $this->values['package'] = $value;
    }
    /**
     * ��ȡ����������չ�ַ�����ֵ
     * @return ֵ
     **/
    public function GetPackage()
    {
        return $this->values['package'];
    }
    /**
     * �ж϶���������չ�ַ����Ƿ����
     * @return true �� false
     **/
    public function IsPackageSet()
    {
        return array_key_exists('package', $this->values);
    }

    /**
     * ����ǩ����ʽ
     * @param string $value
     **/
    public function SetSign($value)
    {
        $this->values['sign'] = $value;
    }
    /**
     * ��ȡǩ����ʽ
     * @return ֵ
     **/
    public function GetSign()
    {
        return $this->values['sign'];
    }
    /**
     * �ж�ǩ����ʽ�Ƿ����
     * @return true �� false
     **/
    public function IsSignSet()
    {
        return array_key_exists('sign', $this->values);
    }
}

/* End of file MY_Wxpay.php */
/* Location: ./application/libraries/MY_WxPay.php */