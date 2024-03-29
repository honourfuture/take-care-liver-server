<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Record_model');
        $this->load->model('User_model');
        $this->load->model('Wx_model');
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }
	/**
     * @SWG\Post(path="/user/login",
	 *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="用户登陆",
     *   description="用户登陆",
     *   operationId="userLogin",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="codeWx",
     *     description="codeWx",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phoneWx",
     *     description="phoneWx",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="iv",
     *     description="iv",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="nickName",
     *     description="nickName",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="avatarUrl",
     *     description="avatarUrl",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="gender",
     *     description="gender",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="shareCode",
     *     description="邀请码",
     *     required=false,
     *     type="string"
     *   ),
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function login_post()
    {
        $codeWx = $this->input->post('codeWx');
        $phoneWx = $this->input->post('phoneWx');

        $iv = $this->input->post('iv');

        $nickName = $this->input->post('nickName');
        $avatarUrl = $this->input->post('avatarUrl');
        $gender = $this->input->post('gender');

        $wx = [];
        $sessionInfo = $this->Wx_model->getSessionKey($codeWx);
        if(isset($sessionInfo->errcode)){
            $result['msg'] = "与微信通信异常 error({$sessionInfo->errcode}) errorMsg ({$sessionInfo->errmsg})!";
            $result['status'] = '500';
            $result['data'] = [];
            return $this->response($result);
        }

        $openId = $sessionInfo->openid;

        $this->Wx_model->decryptData($sessionInfo->session_key, $phoneWx, $iv, $wx);

        if(!$wx){
            $result['msg'] = "与微信通信异常，解密失败! ";
            $result['status'] = '500';
            $result['data'] = [];
            return $this->response($result);
        }

        $wx = json_decode($wx);
        $shareCode = $this->input->post('shareCode');
        $parentUser = $this->User_model->find_by_mobile($shareCode);
        if(!$parentUser){
            $shareId = 0;
            $parentId = 0;
        }else{
            $shareId = $parentId = $parentUser['id'];
        }
        $parentId = $this->getParentInfo($parentId);

        if($parentId == 0 && $parentUser){
            $parentId = $parentUser['id'];
        }
        $phone = $wx->phoneNumber;

        $user = $this->User_model->firstOrCreate($phone, $openId, $parentId, $nickName, $avatarUrl, $gender, $shareId);

        if(!$user){
            $result['msg'] = '未找到该用户!';
            $result['status'] = '500';
            $result['data'] = [];
            return $this->response($result);
        }

        //生成token
        $token = create_uuid();

        $update_data = array(
            'token' => $token,
            'expir_time' => date('Y-m-d H:i:s', strtotime('+' . $this->config->item('token_expire') . ' second'))
        );

        $update = $this->User_model->update($user['id'], $update_data);
        if($update){
            $this->session->set_userdata('user_id', $user['id']);
            $result['msg'] = '登陆成功!';
            $result['status'] = '200';
            $result['data']['id'] = $user['id'];
            $result['data']['token'] = $token;
        }else{
            $result['msg'] = '登陆成功!';
            $result['status'] = '200';
            $result['data'] = [];
        }
        return $this->response($result);
    }

    private function getParentInfo($id)
    {
        if(!$id){
            return 0;
        }
        $parentInfo = $this->User_model->find($id);
        if($parentInfo->is_operator == 1){
            return $parentInfo->id;
        }else if($parentInfo->parent_id == 0){
            return 0;
        }else{
            return $this->getParentInfo($parentInfo->parent_id);
        }
    }

    /**
     * @SWG\Post(path="/user/apply_operator",
     *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="申请成为经营者",
     *   description="申请成为经营者",
     *   operationId="userApplyOperator",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="mobile",
     *     description="电话",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="username",
     *     description="姓名",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="id_card",
     *     description="身份证号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="id_front_pic",
     *     description="身份证正面",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="id_back_picf",
     *     description="身份证反面",
     *     required=true,
     *     type="string"
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
    public function apply_operator_post()
    {
        $in = array();
        if(!$this->user_id){
            $result['msg'] = '请登录';
            $result['status'] = '401';
            $result['data'] = [];
            return $this->response($result);
        }

        $user = $this->User_model->find($this->user_id);
        if($user->is_operator == 1){
            return $this->json([], 500, '您已经是经营者');
        }

        if($user->is_operator == 2){
            return $this->json([], 500, '您的审核已提交，请耐心等待审核');
        }

        if($this->input->post('id_card')){
            $hasUser = $this->User_model->getCard($this->input->post('id_card'), $this->user_id);
            if($hasUser){
                return $this->json([], 500, '该身份证号码已提交审核！');
            }
        }

        if($this->input->post('username'))
            $in["real_name"] = $this->input->post('username');
        if($this->input->post('id_card'))
            $in["id_card"] = $this->input->post('id_card');
        if($this->input->post('mobile'))
            $in["mobile"] = $this->input->post('mobile');
        if($this->input->post('id_front_pic'))
            $in["id_front_pic"] = $this->input->post('id_front_pic');
        if($this->input->post('id_back_pic'))
            $in["id_back_pic"] = $this->input->post('id_back_pic');

        $in['is_operator'] = 2;
        $updateStatus = $this->User_model->update_info($this->user_id, $in);
        if($updateStatus){
            $result['msg'] = '申请成功请等待审核!';
            $result['status'] = '200';
            $result['data'] = [];
        }else{
            $result['msg'] = '申请失败!';
            $result['status'] = '500';
            $result['data'] = [];
        }
        return $this->response($result);
    }

	/**
     * @SWG\Post(path="/user/update_info",
	 *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="更新用户信息",
     *   description="更新用户",
     *   operationId="userUpdateInfo",
	 *   @SWG\Parameter(
     *     in="formData",
     *     name="gender",
     *     description="性别",
     *     required=false,
     *     type="string"
     *   ),
	 *   @SWG\Parameter(
     *     in="formData",
     *     name="head_pic",
     *     description="头像",
     *     required=false,
     *     type="string"
     *   ),
	 *   @SWG\Parameter(
     *     in="formData",
     *     name="weight",
     *     description="体重",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="height",
     *     description="身高",
     *     required=false,
     *     type="string"
     *   ),
	 *   @SWG\Parameter(
     *     in="formData",
     *     name="username",
     *     description="昵称",
     *     required=false,
     *     type="string"
     *   ),
	 *   @SWG\Parameter(
     *     in="formData",
     *     name="id_card",
     *     description="身份证",
     *     required=false,
     *     type="string"
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
    public function update_info_post()
    {
        $ret = array();
        $in = array();
        $user_id = $this->user_id;
        if(!$user_id){
            $result['msg'] = '请登录';
            $result['status'] = '401';
            $result['data'] = [];
            return $this->response($result);
        }

        if($this->input->post('gender'))
            $in["gender"] = $this->input->post('gender');
        if($this->input->post('head_pic'))
            $in["head_pic"] = $this->input->post('head_pic');
        if($this->input->post('weight'))
            $in["weight"] = $this->input->post('weight');
        if($this->input->post('height'))
            $in["height"] = $this->input->post('height');
        if($this->input->post('username'))
            $in["username"] = $this->input->post('username');
        if($this->input->post('id_card'))
            $in["id_card"] = $this->input->post('id_card');

        $updateStatus = $this->User_model->update_info($user_id, $in);

        if($updateStatus){
            $result['msg'] = '修改用户信息成功!';
            $result['status'] = '200';
            $result['data'] = [];
        }else{
            $result['msg'] = '修改用户信息失败!';
            $result['status'] = '500';
            $result['data'] = [];
        }
        return $this->response($result);
    }

    /**
     * @SWG\Post(path="/user/update_form_id",
     *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="更新用户formId",
     *   description="更新用户formId",
     *   operationId="userUpdateFormId",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="formId",
     *     description="formId",
     *     required=false,
     *     type="string"
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
    public function update_form_id_post()
    {
        $in = array();
        $user_id = $this->user_id;
        if(!$user_id){
            $result['msg'] = '请登录';
            $result['status'] = '401';
            $result['data'] = [];
            return $this->response($result);
        }

        if($this->input->post('formId')){
            $in["formId"] = $this->input->post('formId');
        }else{
            $result['msg'] = 'formId不能为空!';
            $result['status'] = '500';
            $result['data'] = [];
            return $this->response($result);
        }

        $updateStatus = $this->User_model->update_info($user_id, $in);

        if($updateStatus){
            $result['msg'] = '修改用户信息成功!';
            $result['status'] = '200';
            $result['data'] = [];
        }else{
            $result['msg'] = '修改用户信息失败!';
            $result['status'] = '500';
            $result['data'] = [];
        }
        return $this->response($result);
    }
	/**
     * @SWG\Get(path="/user/get_info",
     *   tags={"User"},
     *   summary="获得用户详细信息",
     *   description="获得用户详细信息",
     *   operationId="userGetInfo",
	 *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function get_info_get(){
        $user = array();
        $user_id = $this->user_id;
        if(!$user_id){
            $result['msg'] = '请登录';
            $result['status'] = '401';
            $result['data'] = [];
            return $this->response($result);
        }
        if($user_id){
            $result = $this->User_model->find($user_id);
            if(!empty($result)){
                //定义返回的信息
                $user = array(
                    'id'	=> $result->id,
                    'username'	=> $result->username,
                    'real_name'	=> $result->real_name,
                    'mobile'	=> $result->mobile,
                    'gender'	=> $result->gender,//性别   0:女  1:男'
                    'active'	=> $result->active,//态状  1:正常    -1:冻结'
                    'head_pic'	    => $result->head_pic,
                    'weight'	    => $result->weight,
                    'height'	    => $result->height,
                    'id_card'	    => $result->id_card,
                    'is_vip'	    => $result->is_vip,
                    'is_operator'	=> $result->is_operator, //是否是经营者 0 否 1 是 2 待审核
                );
            }
        }
        $this->json($user);
    }
    /**
     * @SWG\Get(path="/user/get_qCode",
     *   tags={"User"},
     *   summary="获得二维码",
     *   description="获得二维码",
     *   operationId="userGetCode",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="query",
     *     description="query",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="width",
     *     description="width",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="is_delete",
     *     description="不传1和空为删除",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function get_qCode_get(){
        $user_id = $this->user_id;
        $user = $this->User_model->find($user_id);
        $isDelete = $this->input->get('is_delete');
        if(!$isDelete){
            $isDelete = 1;
        }
        if($user->qCode && $isDelete == 1){
            return $this->json(config_item('base_url').$user->qCode);
        }

        $query = $this->input->get('query');
        $width = $this->input->get('width');

        if(!$width){
            $width = 400;
        }
        $appid = config_item('wechatpay_config')['app_id'];
        $appSecret = config_item('wechatpay_config')['app_secret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appSecret}";
        $accessToken = file_get_contents($url);
//
        $info = json_decode($accessToken, true);
        if(isset($info['access_token'])){
            $accessToken = $info['access_token'];
        }else{
            return $this->json([], 500, '获取token失败');
        }

        $sendUrl = "https://api.weixin.qq.com/wxa/getwxacode?access_token={$accessToken}";
        $data = [
            'path' => $query,
            'width' => $width,
        ];
        $result = $this->_curl($sendUrl, $data);
        $base64   = base64_encode($result['result']);
        $time = time() . $user_id;
        $f = file_put_contents("./upload/qCode/{$time}.jpg", base64_decode($base64));

        if($f){
            $insertQCode = "/upload/qCode/{$time}.jpg";
            $qCode = config_item('base_url')."/upload/qCode/{$time}.jpg";

            $update = $this->User_model->update($user_id, ['qCode' => $insertQCode]);

            return $this->json($qCode);
        }else{
            return $this->json([], 500, '未知错误');
        }
    }
    /**
     * @SWG\Post(path="/user/send_verify_code",
     *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="发送验证码",
     *   description="发送验证码",
     *   operationId="sendVerifyCode",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     description="电话号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="type",
     *     description="功能标识[login, addCardBank, changePhone]",
     *     required=true,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function send_verify_code_post()
    {
        $phone = $this->input->post('phone');
        $type = $this->input->post('type');

        if(!$this->_verify($phone )){
            return $this->json([], 500, '验证码发送失败！');
        }

        //发送信息
        $result = $this->Record_model->sendMessage($phone, $type);
        if ($result) {
            return $this->json([], 200, '验证码发送成功！');
        } else {
            return $this->json([], 500, '验证码发送失败！');
        }
    }

    private function _verify($accountNumber)
    {
        if(preg_match("/^1[34578]\d{9}$/", $accountNumber)) {
            return true;
        }else{
            return false;
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