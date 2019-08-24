<?PHP
//参考文档 http://hi.testin.cn/activity.action?op=Sms.index&source=banner
//短信平台信息
define('SDK','');//替换成您自己的序列号
define('PWD',''); //此处密码需要加密 加密方式为 md5(sn+password) 32位大写

class MsgSend{
	
	//初始化
    public function index(){
        $wsdl = "http://211.99.191.148/mms/services/info?wsdl";
		$client = new SoapClient($wsdl);
		
		$param = array('in0'=>SDK,'in1'=>PWD,'in2'=>'','in3'=>'','in4'=>'','in5'=>'','in6'=>'','in7'=>'','in8'=>'','in9'=>'','in10'=>'');
		$ret = $client->register($param);
    }
    
    //查看余额
    public function get_money()
    {
    	$wsdl = "http://211.99.191.148/mms/services/info?wsdl";
		$client = new SoapClient($wsdl);
		$param = array('in0'=>SDK,'in1'=>PWD);
		$ret = $client->getbalance($param);
	}
    
    //发送短信
    public function send_msg($mobile,$content)
    {
    	$wsdl = "http://211.99.191.148/mms/services/info?wsdl";
		$client = new SoapClient($wsdl);
		$content = iconv( "UTF-8", "GBK" ,$content);
		$param = array('in0'=>SDK,'in1'=>PWD,'in2'=>$mobile,'in3'=>urlencode($content),'in4'=>'','in5'=>'1','in6'=>'','in7'=>'1','in8'=>'','in9'=>'4');

		$ret = $client->sendSMS($param);
		
		if($ret->out === '0'){
			return true;
		}else{
			return false;
		}
	}
}

?>
