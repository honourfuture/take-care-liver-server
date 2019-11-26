<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Crontab extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @SWG\Get(path="/crontab/push",
     *   tags={"Crontab"},
     *   summary="计划任务-推送签到通知",
     *   description="推送签到通知",
     *   operationId="crontabPush",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function push_get()
    {
        $this->load->model('User_model');
        $where = [
            'formId != ' => null,
        ];
        $users = $this->User_model->getAllPage($where, 0, 0, 0);

        $appid = config_item('wechatpay_config')['app_id'];
        $appSecret = config_item('wechatpay_config')['app_secret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appSecret}";

        $accessToken = file_get_contents($url);

        $info = json_decode($accessToken, true);
        if(isset($info['access_token'])){
            $accessToken = $info['access_token'];
        }else{
            echo 'token获取失败';
        }

        $sendUrl = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={$accessToken}";

        foreach ($users as $user) {
            $sendData = [
                'touser' => $user['openId'],
                'template_id' => config_item('wechatpay_config')['sign_template_id'],
                'form_id' => $user['formId'],
                'data' => [
                    "keyword1" => [
                        'value' => '打卡时间为： 21:00 ~ 23:00'
                    ],
                    "keyword2" => [
                        'value' => '小心肝签到领礼品！'
                    ]
                ]
            ];
            $result = $this->_curl($sendUrl, $sendData);
            print_r($result);
        }
    }

    private function _curl($url, $data)
    {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if(!$data){
            return 'data is null';
        }
        if(is_array($data))
        {
            $data = json_encode($data);
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length:' . strlen($data),
            'Cache-Control: no-cache',
            'Pragma: no-cache'
        ));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        $errorno = curl_errno($curl);
        if ($errorno) {
            return ['errrno' => $errorno, 'status' => 500];
        }
        curl_close($curl);

        return ['status' => 200, 'result' => $res];

    }
}

?>