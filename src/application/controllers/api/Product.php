<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
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
     *  @SWG\Parameter(
     *     in="query",
     *     name="type",
     *     description="商品类型 3 普通商品 4 年度会员",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="cur_page",
     *     description="当前页",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="per_page",
     *     description="每页数量 [默认10条]",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
        $type = $this->input->get('type');
        if(empty($type)){
            $type = 3;
        }
        $wheres = [
            'type' => $type,
            'is_delete' => 1
        ];
        $data = $this->Product_model->getAllByCid($wheres, $this->per_page, $this->offset);
        if ($data) {

            foreach ($data as &$datum){
                $datum->banner_pic = json_decode($datum->banner_pic);
            }
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }

    /**
     * @SWG\Get(path="/product/find",
     *   tags={"Product"},
     *   summary="商品",
     *   description="商品列表",
     *   operationId="productFind",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="商品id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function find_get()
    {
        $id = $this->input->get('id');
        $data = $this->Product_model->find($id);
        if ($data) {
            if($data->is_delete == 0){
                $this->json([], 500, $message = '该商品已下架!');
            }
            $data->banner_pic = json_decode($data->banner_pic);
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }
}

?>