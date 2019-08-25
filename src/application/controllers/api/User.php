<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('User_model');
    }

	/**
     * @SWG\Post(path="/user/register",
	 *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="注册用户",
     *   description="注册用户",
     *   operationId="userRegister",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="password",
     *     description="密码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="verify",
     *     description="验证码",
     *     required=true,
     *     type="string"
     *   ),
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function register_post()
    {
        $ret = array();
        $phone = $this->input->post('phone');
        $passwd = $this->input->post('password');
        if( !$this->user_model->phone_check($phone))//确定未注册过
        {
            $verify = $this->input->post('verify');
            $lastVerify = $this->sms_model->getLastVerify($phone);
            if($verify == $lastVerify && $verify!=null) //验证码通过才能修改
            {
                $lastid = $this->user_model->create_user($phone, $passwd);
                $ret['ret']=0;
                $ret['msg']="注册成功";
                $ret["id"]=$lastid;//最后的id
            }
            else
            {
                $ret['ret']= -2;
                $ret['msg']="验证码错误";
            }
        }
        else
        {
            $ret['ret']=-1;
            $ret['msg']="注册过的账号";
        }

        $this->response($ret);
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
     *     name="phone",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function login_post()
    {

        $ret = array();

        $phone = $this->input->post('phone');

        $user = $this->User_model->find_by_mobile($phone);
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
     * @SWG\Post(path="/user/password_modify",
	 *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="密码修改",
     *   description="密码修改",
     *   operationId="userPasswordModify",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="old_password",
     *     description="旧密码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="new_password",
     *     description="新密码",
     *     required=true,
     *     type="string"
     *   ),
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function password_modify_post()
    {
        $phone = $this->input->post('phone');
        $old_passwd = $this->input->post('old_password');
        $new_passwd = $this->input->post('new_password');
        if($this->User_Model->login_check($phone,$old_passwd))//旧密码正确
        {
            $result = $this->User_Model->change_password($phone, $new_passwd);
            $ret = array();
            $ret["ret"] = $result ? 0 : -1;
        }
        else
            $ret["ret"] = 0;
        $this->response($ret);
    }

	/**
     * @SWG\Post(path="/user/send_verify_code",
	 *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="用户需要发一条短信，注册或找回密码用",
     *   description="用户需要发一条短信，注册或找回密码用",
     *   operationId="userSendVerifyCode",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function send_verify_code_post()
    {
        $ret = array();
        $phone = $this->input->post('phone');
        $result=$this->sms_model->send_msg($phone);
        $ret['ret']=$result?0:-1;
        $ret['msg']=$result?"发送成功":"发送失败";
        $this->response($ret);
    }

    //用户最近的一条验证码记录是否一致
    //@param phone
    //@return 1，0

	/**
     * @SWG\Post(path="/user/check_verify_code",
	 *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="用户最近的一条验证码记录是否一致",
     *   description="用户最近的一条验证码记录是否一致",
     *   operationId="userCheckVerifyCode",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
	 *   @SWG\Parameter(
     *     in="formData",
     *     name="verify",
     *     description="验证码",
     *     required=true,
     *     type="string"
     *   ),
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function check_verify_code_post()
    {
        $ret = array();

        $phone = $this->input->post('phone');
        $verify = $this->input->post('verify');
        $lastVerify = $this->sms_model->getLastVerify($phone);

        if($lastVerify==null)
        {
            $ret['ret']=-1;
            $ret['msg']="验证码过期";
        }
        else if($verify!=$lastVerify){
            $ret['ret'] = -2;
            $ret['msg'] ="验证码不正确";
        }
        else
        {
            $ret['ret'] = 0;
            $ret['msg'] ="验证通过";
        }
        $this->response($ret);
    }

	/**
     * @SWG\Post(path="/user/password_forgot",
	 *   consumes={"multipart/form-data"},
     *   tags={"User"},
     *   summary="忘记密码，用验证码修改密码",
     *   description="忘记密码，用验证码修改密码",
     *   operationId="userPasswordForgot",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
	 *   @SWG\Parameter(
     *     in="formData",
     *     name="new_password",
     *     description="新密码",
     *     required=true,
     *     type="string"
     *   ),
	 *   @SWG\Parameter(
     *     in="formData",
     *     name="verify",
     *     description="验证码",
     *     required=true,
     *     type="string"
     *   ),
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function password_forgot_post()
    {
        $ret = array();
        //get posted username,password,phone,
        $phone = $this->input->post('phone');
        $new_passwd = $this->input->post('new_password');
        $verify = $this->input->post('verify');
        $lastVerify = $this->sms_model->getLastVerify($phone);
        if ($verify == $lastVerify && $verify != null) //验证码通过才能修改
        {
            $result = $this->user_model->change_password($phone, $new_passwd);
            $ret["ret"] = $result ? 0 : -2;
            $ret["msg"] = $result ? "修改成功" : "修改失败";
        } else
        {

        $ret["ret"] = -1;
        $ret["msg"] = "验证码不正确" ;
        }
        $this->response($ret);
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
	 *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function update_info_post()
    {
        $ret = array();
        $in = array();
        $user_id = $this->session->userdata('user_id');
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
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function get_info_get(){
        $user = array();
        $user_id = $this->session->userdata('user_id');
        if($user_id){
            $result = $this->User_model->find($user_id);
            if(!empty($result)){
                //定义返回的信息
                $user = array(
                    'username'	=> $result->username,
                    'mobile'	=> $result->mobile,
                    'gender'	=> $result->gender,//性别   0:女  1:男'
                    'active'	=> $result->active,//态状  1:正常    -1:冻结'
                    'info'	    => $result->info,
                    'head_pic'	    => $result->head_pic,
                    'weight'	    => $result->weight,
                    'id_card'	    => $result->id_card,
                );
            }
        }
        $this->response($user);
    }

}

?>