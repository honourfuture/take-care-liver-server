<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Hospital extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hospital_model');
    }

    private function json($data, $code = 200, $message = '获取数据成功!')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/hospital/list",
     *   tags={"Hospital"},
     *   summary="医院列表",
     *   description="医院列表",
     *   operationId="hospitalList",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     in="query",
     *     name="cur_page",
     *     description="当前页",
     *     required=true,
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
        $data = $this->Hospital_model->getAllByCid($this->per_page, $this->offset);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 0, $message = '没有数据');
        }
    }

    /**
     * @SWG\Get(path="/hospital/find",
     *   tags={"Hospital"},
     *   summary="医院详情",
     *   description="医院详情",
     *   operationId="hospitalFind",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="医院id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function find_get()
    {
        $businessDate = [
            '1' => '周一至周五 09:00-18:00',
            '2' => '周一至周日',
        ];

        $id = $this->input->get('id');
        $data = $this->Hospital_model->find($id);

        if ($data) {
            $data->business = $businessDate[$data->business_type];
            $this->json($data);
        } else {
            $this->json([], 0, $message = '没有数据');
        }
    }


}

?>