<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Banner extends REST_Controller
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
     * @SWG\Get(path="/banner/data",
     *   tags={"Banner"},
     *   summary="3-5张Bananer图轮播",
     *   description="banner中心的Bananer内容列表",
     *   operationId="bannerdata",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function data_get()
    {
        $this->load->model('Banner_model');
        $data = $this->Banner_model->getShowAllData();
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 500, $message = '没有数据');
        }
    }
}

?>