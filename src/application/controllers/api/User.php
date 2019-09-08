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
     *     name="code",
     *     description="code",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phoneSercert",
     *     description="phoneSercert",
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
     *     name="user_id",
     *     description="shareId 邀请人id",
     *     required=false,
     *     type="string"
     *   ),
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function login_post()
    {
        $codeWx = $this->input->post('code');
        $phoneWx = $this->input->post('phoneSercert');
        $iv = $this->input->post('iv');

        $ret = array();

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
        $parentId = $this->input->post('user_id');
        $phone = $wx->phoneNumber;

        $user = $this->User_model->firstOrCreate($phone, $openId, $parentId);

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
            $result['data']['token'] = $token;
        }else{
            $result['msg'] = '登陆成功!';
            $result['status'] = '200';
            $result['data'] = [];
        }
        return $this->response($result);
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
            $result['msg'] = '请登录后操作!';
            $result['status'] = '500';
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

        if($this->input->post('username'))
            $in["username"] = $this->input->post('username');
        if($this->input->post('id_card'))
            $in["id_card"] = $this->input->post('id_card');
        if($this->input->post('mobile'))
            $in["id_card"] = $this->input->post('mobile');
        if($this->input->post('id_front_pic'))
            $in["id_card"] = $this->input->post('id_front_pic');
        if($this->input->post('id_back_pic'))
            $in["id_card"] = $this->input->post('id_back_pic');

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
            $result['msg'] = '请登录后操作!';
            $result['status'] = '500';
            $result['data'] = [];
            return $this->response($result);
        }

        if($this->input->post('gender'))
            $in["gender"] = $this->input->post('gender');
        if($this->input->post('head_pic'))
            $in["head_pic"] = $this->input->post('head_pic');
        if($this->input->post('weight'))
            $in["weight"] = $this->input->post('weight');
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
            $result['msg'] = '请登录后操作!';
            $result['status'] = '500';
            $result['data'] = [];
            return $this->response($result);
        }
        if($user_id){
            $result = $this->User_model->find($user_id);
            if(!empty($result)){
                //定义返回的信息
                $user = array(
                    'username'	=> $result->username,
                    'mobile'	=> $result->mobile,
                    'gender'	=> $result->gender,//性别   0:女  1:男'
                    'active'	=> $result->active,//态状  1:正常    -1:冻结'
                    'head_pic'	    => $result->head_pic,
                    'weight'	    => $result->weight,
                    'id_card'	    => $result->id_card,
                    'is_vip'	    => $result->is_vip,
                    'is_operator'	=> $result->is_operator, //是否是经营者 0 否 1 是 2 待审核
                );
            }
        }
        $this->json($user);
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
}

?>