<?php
defined('BASEPATH') or exit ('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Share extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->load->model('User_model');
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Post(path="/share/create",
     *   consumes={"multipart/form-data"},
     *   tags={"Share"},
     *   summary="分享",
     *   description="分享",
     *   operationId="shareCreate",
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="用户登陆token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="user_id",
     *     description="上级用户id",
     *     required=true,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function create_post()
    {
        if(!$this->user_id){
            return $this->json(null, 401, '请登录');//未登录
        }

        $shareId = $parentId = intval($this->input->post('user_id'));

        if($parentId == $this->user_id){
            return $this->json(null, 500, '自己不能邀请自己为会员！');//未登录
        }
        $result = $this->User_model->find($this->user_id);

        if(empty($result)){
            return $this->json(null, 500, '未找到该用户');//未登录
        }

        if($result->is_vip == 1){
            return $this->json(null, 500, '该用户已经成为会员！');//未登录
        }

        if($result->parent_id){
            return $this->json(null, 500, '该用户已经拥有上级用户！');//未登录
        }

        $parentInfo = $this->User_model->find($parentId);

        if(!$parentInfo){
            return $this->json(null, 500, '未找到上级用户！');//未登录
        }

        $parentId = $this->getParentInfo($parentId);
        if($parentId  == 0)
        {
            $parentId = $shareId;
        }

        $update = [
            'parent_id' => $parentId,
            'share_id' => $shareId,
        ];

        $updateStatus = $this->User_model->update_info($this->user_id, $update);

        if($updateStatus){
            return $this->json([], 200, '绑定成功！');
        }else{
            return $this->json([], 500, '绑定失败！');
        }
    }

    private function getParentInfo($id)
    {
        $parentInfo = $this->User_model->find($id);
        if($parentInfo->is_operator == 1){
            return $parentInfo->id;
        }else if($parentInfo->parent_id == 0){
            return 0;
        }else{
            return $this->getParentInfo($parentInfo->parent_id);
        }
    }



}

?>