<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->user_id = $this->session->userdata('user_id');
    }

    private function json($data, $code = 200, $message = '获取数据成功!')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/product/list",
     *   tags={"Product"},
     *   summary="商品",
     *   description="商品列表",
     *   operationId="productList",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
        $data = $this->Product_model->getAllByCid();
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 0, $message = '没有数据');
        }
    }

}

?>