<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Urine extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Urine_model');
        $this->load->model('Urine_check_model');
    }

    private function json($data, $code = 200, $message = '获取数据成功!')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/urine/list",
     *   tags={"Urine"},
     *   summary="尿检",
     *   description="尿检列表",
     *   operationId="urineList",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
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
        if(!$this->user_id){
            return  $this->json([], 500, '请登录');
        }

        $waring = [
          '1' => '#17C419',
          '2' => '#FD7925',
          '3' => '#FC2F24',

        ];
        $data = $this->Urine_model->getList($this->user_id, $this->per_page, $this->offset, 1);
        if ($data) {
            foreach ($data as &$datum){
                $datum['waringColor'] = $waring[$datum['waring_type']];
            }
            return $this->json($data);
        } else {
            return $this->json([], 0, $message = '没有数据');
        }
    }

    /**
     * @SWG\Get(path="/urine/color",
     *   tags={"Urine"},
     *   summary="试纸颜色",
     *   description="试纸颜色",
     *   operationId="urineColor",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function color_get()
    {
        $data = $this->Urine_check_model->getList();
        if ($data) {
            return $this->json($data);
        } else {
            return $this->json([], 0, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/urine/find",
     *   tags={"Urine"},
     *   summary="尿检",
     *   description="尿检详情",
     *   operationId="urineFind",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="尿检id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function find_get()
    {
        $id = $this->input->get('id');
        $data = $this->Urine_model->getFind($id);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 0, $message = '没有数据');
        }
    }


    /**
     * @SWG\Post(path="/urine/add",
     *   consumes={"multipart/form-data"},
     *   tags={"Urine"},
     *   summary="添加尿检",
     *   description="添加尿检",
     *   operationId="urineAdd",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="color_id",
     *     description="颜色id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="date",
     *     description="日期(2019-07-27 00:00:00)",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function add_post()
    {
        $date = $this->input->post('date');
        $colorId = $this->input->post('color_id');

        if (!$this->user_id) {
            return $this->json([], 500, '请登录');
        }

        $data = [
            'date' => $date,
            'user_id'=>$this->user_id,
            'urine_check_id' => $colorId,
            'type' => 1
        ];

        if($this->Urine_model->create($data)) {
            return $this->json(true, 200, '添加成功');
        } else {
            return $this->json([], 500, '服务器出错');
        }
    }
}

?>