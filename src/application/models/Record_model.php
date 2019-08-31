<?php
/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/8/19
 * Time: 上午9:01
 */

class Record_model extends CI_Model
{
    public function __construct()
    {
        $this->load->driver('cache');
        parent::__construct();
    }

    //随机生成验证码
    public function generateNum($length)
    {
        return 123456;
        $chars = "0123456789";
        $password = '';
        for ($i = 0; $i < $length; $i++)
            $password .= $chars[rand(0, strlen($chars) - 1)];

        return $password;
    }

    //发送完短信添加记录
    public function sendMessage($phone,  $type = 'login')
    {
        $verify = $this->generateNum(6);
        $redisKey = md5($phone . $type );
        return $this->cache->redis->save($redisKey, $verify, 18000);

        $options = $this->_buildOptions($verify, $phone);
        $this->load->library('Ucpaas', $options);
        $data = $this->ucpaas->SendSms($options);
        $status = json_decode($data, true);
        if($status['code'] == 000000){
            $redisKey = md5($phone . $type );
            return $this->cache->redis->save($redisKey, $verify, 18000);
        }else{
            return [];
        }
    }

    private function _buildOptions($verify, $phone)
    {
        $options['accountsid']='32e1b3342dcc31f942f2ad2b7a80424d';
        $options['token']='49d8ed42e607586602589747d18db1b9';
        $options['appid'] = "bd56dff45d25446ca5fa22537643cacb";	//应用的ID，可在开发者控制台内的短信产品下查看
        $options['templateid'] = "438810";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
        $options['param']= $verify; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
        $options['mobile'] = $phone;
        return $options;
    }
    /**
     * 获取最后一条记录
     * @param $phone
     * @param $type
     * @param $source
     * @return mixed
     */
    public function getVerify($phone, $type = 'login' )
    {
        $redisKey = md5($phone . $type );
        return $this->cache->redis->get($redisKey);
    }
}