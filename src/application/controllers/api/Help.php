<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Help extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }


    /**
     * @SWG\Get(path="/help/list",
     *   tags={"Help"},
     *   summary="帮助列表",
     *   description="帮助中心的内容列表",
     *   operationId="helpList",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
        $this->load->model('Help_model');
        $data = $this->Help_model->getAllByCid();
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 500, $message = '没有数据');
        }
    }
}

?>