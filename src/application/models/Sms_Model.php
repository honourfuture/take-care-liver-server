<?php
/**
 * Created by PhpStorm.
 * User: OneDouble
 * Date: 2016/11/9
 * Time: 下午3:28
 */
require APPPATH . '/libraries/MessageSend.class.php';
class Sms_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //随机生成验证码
    private function generateNum($length)
    {
        $chars = "0123456789";
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
            $password .= $chars[ rand(0, strlen($chars) - 1) ];

        return $password;
    }
    public function send_msg($mobiles="")
    {
        $username="AC00377";							//实际账户名
        $password="24E4DF8CF751B75F16610F679E6BAB61";	//实际短信发送密码
        $num = $this->generateNum(6);
        $content="您的验证码是".$num."，该验证码30分钟内有效。若非本人操作请忽略！【程程通】";
        $extnumber="";
        $plansendtime='';						//定时短信发送时间,格式 2016-06-06T06:06:06，null或空串表示为非定时短信(即时发送)
        //$plansendtime='2016-06-06T06:06:06'
        $result=WsMessageSend::send($username, $password, $mobiles, $content,$extnumber,$plansendtime);

        if($result !=null)
        {
            $this->addRecord($num,$mobiles);
            return true;
            //echo "接口调用失败";
        }
        else
        {
            return false;
            //print_r($result);
            //echo "返回信息提示：",$result->Description,"\n";
            //echo "返回状态为：",$result->StatusCode,"\n";
            //echo "返回余额：",$result->Amount,"\n";
            //echo "返回本次任务ID：",$result->MsgId,"\n";
            //echo "返回成功短信数：",$result->SuccessCounts,"\n";
        }
    }
    //发送完短信添加记录
    private function addRecord($verify,$phone)
    {
        $data = array(
            'verify'			=> $verify,
            'mobile'            => $phone,
            'ip'	=> $this->getIP(),
            'time'	=> time()
        );

        $this->db->insert('sms_record', $data);

        return $this->db->insert_id();
    }

    private function getIP() {

        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknow";

        return $ip;
    }

    public  function getLastVerify($phone)
    {
        $this->db->select('verify');
        $this->db->where('mobile',$phone);
        $this->db->where('time >',time()-1800);//30分钟有效
        $this->db->from('sms_record');
        $this->db->order_by("time","DESC");
        $this->db->limit(1);
        $query = $this->db->get();

        $list = $query->row_array(0);
        return $list["verify"];
    }
}
