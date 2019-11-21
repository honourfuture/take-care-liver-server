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
     *   @SWG\Parameter(
     *     in="query",
     *     name="longitude",
     *     description="经度，格式如：121.41606",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="latitude",
     *     description="纬度，格式如：31.21563",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
//        $openMember = [
//            'price' => 499,
//            'liver' => [
//                'icon' => '',
//                'title' => '',
//                'detail' => ''
//            ],
//            'urine' => [
//                'icon' => '',
//                'title' => '',
//                'detail' => ''
//            ],
//            'memberCard' => ''
//        ];
//        echo json_encode($openMember);die;
        $longitude = trim($this->input->get('longitude'));
        $latitude = trim($this->input->get('latitude'));
        $data = $this->Hospital_model->getAllByPosi($this->per_page, $this->offset,$longitude,$latitude);
        if ($data) {
            $this->json([]);
        } else {
            $this->json([], 200, $message = '没有数据');
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
     *   @SWG\Parameter(
     *     in="query",
     *     name="longitude",
     *     description="经度，格式如：121.41606",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="latitude",
     *     description="纬度，格式如：31.21563",
     *     required=true,
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
        $longitude = trim($this->input->get('longitude'));
        $latitude = trim($this->input->get('latitude'));
        $data = $this->Hospital_model->getAllByPosi(null,null,$longitude,$latitude,$id);

        if ($data) {
            $data = $data[0];
            $data['business'] = $data['business_type'];
            return $this->json([], 200, $message = '没有数据');
            $this->json($data);
        } else {
            $this->json([], 200, $message = '没有数据');
        }
    }


}

?>