<?php
header("Content-Type: text/html; charset=utf-8");

require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
require_once(dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');

class AppPush{
	
	public $appkey = '';//自行替换成你的值
	public $appid = '';//自行替换成你的值
	public $mastersecret = '';//自行替换成你的值
	public $host = 'http://sdk.open.api.igexin.com/apiex.htm';
	//单推接口
	function pushMessageToSingle($alias,$title,$content,$pTitle,$pContent,$url="http://dizhensubao.igexin.com/dl/com.ceic.apk"){
	    $igt = new IGeTui($this->host,$this->appkey,$this->mastersecret);
	 
	    //消息模版：
	    // 1.TransmissionTemplate:透传功能模板
	    // 2.LinkTemplate:通知打开链接功能模板
	    // 3.NotificationTemplate：通知透传功能模板
	    // 4.NotyPopLoadTemplate：通知弹框下载功能模板
	 	
	    //$template = IGtNotyPopLoadTemplate();
	    //$template = IGtLinkTemplateDemo();
	    //$template = IGtNotificationTemplateDemo();
	    // $template = IGtTransmissionTemplateDemo();
	 
	 	$template =  new IGtNotyPopLoadTemplate();
        $template ->set_appId($this->appid);                      //应用appid
        $template ->set_appkey($this->appkey);                    //应用appkey
 
 		//通知栏
		$template ->set_notyTitle($title);//通知栏标题
		$template ->set_notyContent($content);//通知栏内容
		$template ->set_notyIcon("");//通知栏logo
		$template ->set_isBelled(true);//是否响铃
		$template ->set_isVibrationed(true);//是否震动
		$template ->set_isCleared(true);//通知栏是否可清除
		//弹框
		$template ->set_popTitle($pTitle);//弹框标题
		$template ->set_popContent($pContent);//弹框内容
		$template ->set_popImage("");//弹框图片
		$template ->set_popButton1("下载");//左键
		$template ->set_popButton2("取消");//右键
		//下载
		$template ->set_loadIcon("");//弹框图片
		$template ->set_loadTitle("地震速报下载");
		$template ->set_loadUrl($url);
		$template ->set_isAutoInstall(false);
		$template ->set_isActived(true);
		
	    //个推信息体
	    $message = new IGtSingleMessage();
	 
	    $message->set_isOffline(true);//是否离线
	    $message->set_offlineExpireTime(3600*12*1000);//离线时间
	    $message->set_data($template);//设置推送消息类型
	    //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
	    
	    //接收方
	    $target = new IGtTarget();
	    $target->set_appId($this->appid);
	    $target->set_clientId($alias);
	    $target->set_alias($alias);
	 
	 
	    try {
	        $rep = $igt->pushMessageToSingle($message, $target);
	        //var_dump($rep);
	        //echo ("<br><br>");
	 
	    }catch(RequestException $e){
	        $requstId =e.getRequestId();
	        $rep = $igt->pushMessageToSingle($message, $target,$requstId);
	        //var_dump($rep);
	        //echo ("<br><br>");
	    }
	    //return $rep;
	}
	
	//ios推送
	function pushios($devicetoken,$title,$countent)
	{
		$igt = new IGeTui($this->host,$this->appkey,$this->mastersecret);
	    $template = new IGtAPNTemplate();
	  //$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
	    $template->set_pushInfo("", 1, $title, "com.gexin.ios.silence", $countent, "", "", "");
	    $message = new IGtSingleMessage();
	
	    $message->set_data($template);
	    $ret = $igt->pushAPNMessageToSingle($this->appid, $devicetoken, $message);
	}
	
	/*
	function IGtNotyPopLoadTemplate($tile,$content,$pTitle,$pContent,$url="http://dizhensubao.igexin.com/dl/com.ceic.apk"){
        $template =  new IGtNotyPopLoadTemplate();
        $template ->set_appId($this->appid);                      //应用appid
        $template ->set_appkey($this->appkey);                    //应用appkey
 
 		//通知栏
		$template ->set_notyTitle($tile);//通知栏标题
		$template ->set_notyContent($content);//通知栏内容
		$template ->set_notyIcon("");//通知栏logo
		$template ->set_isBelled(true);//是否响铃
		$template ->set_isVibrationed(true);//是否震动
		$template ->set_isCleared(true);//通知栏是否可清除
		//弹框
		$template ->set_popTitle($pTitle);//弹框标题
		$template ->set_popContent($pContent);//弹框内容
		$template ->set_popImage("");//弹框图片
		$template ->set_popButton1("下载");//左键
		$template ->set_popButton2("取消");//右键
		//下载
		$template ->set_loadIcon("");//弹框图片
		$template ->set_loadTitle("地震速报下载");
		$template ->set_loadUrl($url);//"http://dizhensubao.igexin.com/dl/com.ceic.apk"
		$template ->set_isAutoInstall(false);
		$template ->set_isActived(true);
 
 
        //设置通知定时展示时间，结束时间与开始时间相差需大于6分钟，消息推送后，客户端将在指定时间差内展示消息（误差6分钟）
        
        $begin = "2015-02-28 15:26:22";
        $end = "2015-02-28 15:31:24";
        $template->set_duration($begin,$end);
        
        return $template;
	}
	*/
}

?>
