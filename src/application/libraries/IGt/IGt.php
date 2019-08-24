<?php
header("Content-Type: text/html; charset=utf-8");

require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
require_once(dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');

class IGt{
	public $appkey = 'C7Ts9L6jAB9Gs4A7isRvA3';
	public $appid = 'wy0r4njUIP9ujABZ3FUqE9';
	public $mastersecret = 'uFfVDETc1z7LqOe6UjwLg5';
	public $cid = '';
	
	public $devicetoken = '';
	public $host = 'http://sdk.open.api.igexin.com/apiex.htm';
	
	public $taskId = 'OSS-0213_UA9Aq1pG1W97zj1zJ3q0e5';
	
	
	function getUserTags() {
		$igt = new IGeTui($this->host,$this->appkey,$this->mastersecret);
		$rep = $igt->getUserTags($this->appid,$this->cid);
		//$rep.connect();
		var_dump($rep);
		echo ("<br><br>");
	}
	
	function getPushResult() {
		$igt = new IGeTui($this->host,$this->appkey,$this->mastersecret);
		$rep = $igt->getPushResult(taskId);
		var_dump($rep);
		echo ("<br><br>");
	}
	
	function setspeedtest()
	{  
		$igt=new IGtAppMessage();
		$igt->set_speed(10);
		var_dump($igt->get_speed());
	
	}
	
	function setdurationTest()
	{
	
		$begin = "2015-01-31 00:11:22";
		$end = "2015-02-28 15:31:24";
	     date_default_timezone_set('asia/shanghai');
	    //1422634282000
	    $hour =substr($begin,11,2);
	    $minute =substr($begin,14,2);
	    $second=substr($begin,17,2);
	    $month=substr($begin,5,2);
	    $day=substr($begin,8,2);
	    $year=substr($begin,0,4);
	//var_dump($hour,$minute,$second,$month,$day,$year);
	    $ss=mktime($hour,$minute,$second,$month,$day,$year)*1000;
	    $hour =substr($end,11,2);
	    $minute =substr($end,14,2);
	    $second=substr($end,17,2);
	    $month=substr($end,5,2);
	    $day=substr($end,8,2);
	    $year=substr($end,0,4);
	    $e = mktime($hour,$minute,$second,$month,$day,$year)*1000;;
	    if ($ss<=0 || $e<=0)
	        throw new Exception("DateFormat: yyyy-MM-dd HH:mm:ss");
	    if ($ss>$e)
	        throw new Exception("startTime should be smaller than endTime");
	
	    var_dump($ss."-".$e);
	    var_dump(mktime(15, 26, 22, 2, 28, 2015));
	
		echo ("<br><br>");
	
	}
	
	function getUserStatus() {
	    $igt = new IGeTui($this->host,$this->appkey,$this->mastersecret);
	    $rep = $igt->getClientIdStatus($this->appid,$this->cid);
	    var_dump($rep);
	    echo ("<br><br>");
	}
	
	//ios推送
	function pushAPN($title,$countent){
	    $igt = new IGeTui($this->host,$this->appkey,$this->mastersecret);
	    $template = new IGtAPNTemplate();
	  //$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
	    $template->set_pushInfo("a", 4, $title, "com.gexin.ios.silence", $countent, "", "", "");
	    $message = new IGtSingleMessage();
	
	    $message->set_data($template);
	    $ret = $igt->pushAPNMessageToSingle($this->appid, $this->devicetoken, $message);
	    var_dump($ret);
	}
	
	/**
	 * 点开通知打开网页  不支持 ios
	 * @param $tile
	 * @param $content
	 * @param $link
	 */
	function pushMessageToSingleLinkTemp($cid,$tile,$content,$link){
		$igt = new IGeTui("",$this->appkey,$this->mastersecret);
	
	    $template =  new IGtLinkTemplate();
	    $template ->set_appId($this->appid);//应用appid
	    $template ->set_appkey($this->appkey);//应用appkey
	    $template ->set_title($tile);//通知栏标题
	    $template ->set_text($content);//通知栏内容
	    $template ->set_logo("");//通知栏logo http://wwww.igetui.com/logo.png
	    $template ->set_isRing(true);//是否响铃
	    $template ->set_isVibrate(true);//是否震动
	    $template ->set_isClearable(true);//通知栏是否可清除
	    $template ->set_url($link);//打开连接地址
	
	
	   // $template->set_pushInfo("actionLocKey","badge","message",
	     //   "sound","payload","locKey","locArgs","launchImage");
	/**
	     $begin = "2015-03-06 13:18:00";
	     $end = "2015-03-06 13:24:00";
	     $template ->set_duration($begin,$end);
	   **/
		//个推信息体
		$message = new IGtSingleMessage();
		$message->set_isOffline(true);//是否离线
		$message->set_offlineExpireTime(3600*12*1000);//离线时间
		$message->set_data($template);//设置推送消息类型
		//接收方
		$target = new IGtTarget();
		$target->set_appId($this->appid);
		$target->set_clientId($cid);
	
		$rep = $igt->pushMessageToSingle($message,$target);
		//var_dump($rep);
	    //print_r($rep["taskId"]);
		//$rep = $igt->getPushResult($rep["taskId"]);
		//$rep = $igt->getPushResult("OSL-0306_ToPCZDWTV68j9Bux2rgVF");
		//var_dump($rep);
	
	    //echo ("<br><br>");
	}
	/**
	 * 点击通知栏弹框下载
	 * @param $tile  通知栏标题 
	 * @param $content   通知栏内容
	 * @param $pTitle  弹框标题
	 * @param $pContent   弹框内容
	 * @param $url  下载链接
	 */
	function pushMessageToSingleNotyPopLoadTemp($cid,$tile,$content,$pTitle,$pContent,$url){
		$igt = new IGeTui("",$this->appkey,$this->mastersecret);
	
	    
		$template =  new  IGtNotyPopLoadTemplate();
		$template ->set_appId($this->appid);//应用appid
		$template ->set_appkey($this->appkey);//应用appkey
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
	
		//个推信息体
		$message = new IGtSingleMessage();
		$message->set_isOffline(true);//是否离线
		$message->set_offlineExpireTime(3600*12*1000);//离线时间
		$message->set_data($template);//设置推送消息类型
		//接收方
		$target = new IGtTarget();
		$target->set_appId($this->appid);
		$target->set_clientId($cid);
	
		$rep = $igt->pushMessageToSingle($message,$target);
		//var_dump($rep);
	  //  print_r($rep["taskId"]);
		//$rep = $igt->getPushResult($rep["taskId"]);
		//$rep = $igt->getPushResult("OSL-0306_ToPCZDWTV68j9Bux2rgVF");
	//	var_dump($rep);
	
	 //   echo ("<br><br>");
	}
	/**
	 * 透传推送  android 
	 * @param $content
	 */
	function pushMessageToSingle($cid,$content){
		$igt = new IGeTui("",$this->appkey,$this->mastersecret);
	
	    $template =  new IGtTransmissionTemplate();
	    //应用appid
	    $template->set_appId($this->appid);
	    //应用appkey
	    $template->set_appkey($this->appkey);
	    //透传消息类型
	    $template->set_transmissionType(2);
	    //透传内容
	    $template->set_transmissionContent($content);
	   // $template->set_pushInfo("actionLocKey","badge","message",
	     //   "sound","payload","locKey","locArgs","launchImage");
	
	     $begin = "2015-03-06 13:18:00";
	     $end = "2015-03-06 13:24:00";
	   //  $template ->set_duration($begin,$end);
	     
		//个推信息体
		$message = new IGtSingleMessage();
		$message->set_isOffline(true);//是否离线
		$message->set_offlineExpireTime(3600*12*1000);//离线时间
		$message->set_data($template);//设置推送消息类型
		//接收方
		$target = new IGtTarget();
		$target->set_appId($this->appid);
		$target->set_clientId($cid);
	
		$rep = $igt->pushMessageToSingle($message,$target);
		//var_dump($rep);
	   // print_r($rep["taskId"]);
		//$rep = $igt->getPushResult($rep["taskId"]);
		//$rep = $igt->getPushResult("OSL-0306_ToPCZDWTV68j9Bux2rgVF");
		//var_dump($rep);
	
	    //echo ("<br><br>");
	}

	function pushMessageToList(){
		try {
			$taskGroupName = "test";
			// $taskGroupName = null;
			$igt = new IGeTui($this->host,$this->appkey,$this->mastersecret);
			//putenv("needDetails=true");
			//消息类型 :状态栏链接 点击通知打开网页 
			$template =  new IGtLinkTemplate();
			$template ->set_appId($this->appid);//应用appid
			$template ->set_appkey($this->appkey);//应用appkey
			$template ->set_title("个推");//通知栏标题
			$template ->set_text("个推最新版点击下载");//通知栏内容
			$template ->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo
			$template ->set_isRing(true);//是否响铃
			$template ->set_isVibrate(true);//是否震动
			$template ->set_isClearable(true);//通知栏是否可清除
			$template ->set_url("http://www.igetui.com/");//打开连接地址
			   // $begin = "2015-02-28 18:31:40";
	           // $end = "2015-02-28 18:37:42";
	        //$template ->set_duration($begin,$end);
			//个推信息体
			$message = new IGtListMessage();
			$message->set_isOffline(true);//是否离线
			$message->set_offlineExpireTime(3600*12*1000);//离线时间
			$message->set_data($template);//设置推送消息类型
			if (is_null($taskGroupName)){
		 		$contentId = $igt->getContentId($message); 		
		 	} 
	
		 	else {
		 		$contentId = $igt->getContentId($message, $taskGroupName);
		 	}
	
			//接收方1	
			$target1 = new IGtTarget();
			$target1->set_appId($this->appid);
			$target1->set_clientId($this->cid);
			$targetList[] = $target1;
		    $igt->needDetails = true;
			$rep = $igt->pushMessageToList($contentId, $targetList);
			var_dump($rep);
		} catch (Exception $e) {
			echo ($e);
		}
	    echo ("<br><br>");
	
	}
	
	function pushMessageToApp(){
	
		try {
			// $taskGroupName = "test";
			$taskGroupName = null;
	
			$igt = new IGeTui($this->host,$this->appkey,$this->mastersecret);
			
			//消息类型 : 状态栏通知 点击通知启动应用
			$template =  new IGtNotificationTemplate(); 
	
			$template->set_appId($this->appid);//应用appid
			$template->set_appkey($this->appkey);//应用appkey
			$template->set_transmissionType(2);//透传消息类型
			$template->set_transmissionContent("测试离线");//透传内容
			$template->set_title("个推");//通知栏标题
			$template->set_text("个推最新版点击下载");//通知栏内容
			$template->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo
			$template->set_isRing(true);//是否响铃
			$template->set_isVibrate(true);//是否震动
			$template->set_isClearable(true);//通知栏是否可清除
			// iOS推送需要设置的pushInfo字段
			//$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
			//$template ->set_pushInfo("test",1,"message","","","","","");
			//个推信息体
			//基于应用消息体
			$message = new IGtAppMessage();
			$message->set_isOffline(true);
			$message->set_offlineExpireTime(3600*12*1000);
			$message->set_data($template);
	        $message->set_speed(10);
	
			$message->set_appIdList(array($this->appid));
			$message->set_phoneTypeList(array('ANDROID'));
			// $message->set_provinceList(array('浙江','北京','河南'));
		 //    $message->set_tagList(array('tag1','tag2'));        
		   
	
		 	if (is_null($taskGroupName)){
		 		$rep = $igt->pushMessageToApp($message); 		
		 	} 
	
		 	else {
		 		$rep = $igt->pushMessageToApp($message, $taskGroupName);
		 	}
			
			var_dump($rep);
			//var_dump($rep);
			print_r($rep["contentId"]);
			$rep = $igt->getPushResult($rep["contentId"]);
			var_dump($rep);
		} catch (Exception $e) {
			echo ($e);
		}
		
	    echo ("<br><br>");
	
	}
	
   public $hosts=array("http://code.google.com/p/pb4php/downloads/list",
              "http://sdk.open.api.getui.net/serviceex",
               "http://code.google.com/p/pb4php/downloads/list");

   function getfasthost_nt($hosts)
   {
		if (count($hosts)==1)
          $host= $hosts[0];
		else{
				 $mint=60.0;
					$s_url="";
				for ($i=0;$i<count($hosts);$i++)
        {
            $start = array_sum(explode(" ",microtime()));

			$opts = array(  
               'http'=>array(  
                  'method'=>"GET",  
               'timeout'=>10,  
              )  );  
			$context = stream_context_create($opts); 		 
			try {
            $homepage = file_get_contents($hosts[$i], false, $context);
				} catch (Exception $e) {
            throw new Exception("host:[" . $host);
				 }
            $ends = array_sum(explode(" ",microtime()));

            if ($homepage==NULL ||$homepage="")
              $diff=60.0;
            else
              $diff=$ends-$start;
			var_dump($diff);
            if ($mint > $diff)
            {$mint=$diff;
                $s_url=$hosts[$i];}
         }
        
            $host =$s_url;
			var_dump( $host);
        }
            
    }
	
	
}

//getfasthost_nt($hosts);

?>
