<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Banner extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Banner_model');

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
        $data = $this->Banner_model->getShowAllData();
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }


    /**
     * @SWG\Get(path="/banner/find",
     *   tags={"Banner"},
     *   summary="地址",
     *   description="获取地址",
     *   operationId="bannerFind",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="banner_id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function find_get()
    {
        $id = $this->input->get('id');
        $data = $this->Banner_model->find($id);
        if ($data) {
            return $this->json($data);
        } else {
            return $this->json([], 200, $message = '没有数据');
        }
    }
}

?>