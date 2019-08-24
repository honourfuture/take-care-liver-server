<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Feedback extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->user_id = $this->session->userdata('user_id');
//        $this->user_id = 1;
    }

    private function json($data, $code = 0, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/feedback/list",
     *   tags={"Feedback"},
     *   summary="反馈列表",
     *   description="用户反馈的内容列表",
     *   operationId="feedbackList",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
        $this->load->model('Feedback_model');
        if ($this->user_id) {
            $data = $this->Feedback_model->myFeedback($this->user_id);
            if ($data) {
                $this->json($data);
            } else {
                $this->json([], 0, $message = '没有数据');
            }
        } else {
            $this->json([], -1, '请登录');
        }
    }

    /**
     * @SWG\Post(path="/feedback/add",
	 *   consumes={"multipart/form-data"},
     *   tags={"Feedback"},
     *   summary="用户提交反馈",
     *   description="用户提交反馈",
     *   operationId="helpAdd",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="formData",
     *     name="q",
     *     description="问题内容",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function addFeedback_post()
    {
        $q = $this->input->post('q');
        if ($this->user_id) {
            $data = [
                'q'=>$q,
                'uid'=>$this->user_id
            ];
            $this->load->model('Feedback_model');
            if($this->Feedback_model->create($data)) {
                $this->json(true, 0, '发布成功');
            } else {
                $this->json([], -1, '服务器出错');
            }
        } else {
            $this->json([], -1, '请登录');
        }
    }
}

?>