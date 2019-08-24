<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Notice extends REST_Controller
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
     * @SWG\Get(path="/notice/list",
     *   tags={"Notice"},
     *   summary="通知列表",
     *   description="通知列表",
     *   operationId="noticeList",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
        $this->load->model('Notice_model');
        $data = $this->Notice_model->getAllByCid();
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 0, $message = '没有数据');
        }
    }

}

?>