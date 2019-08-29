<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Baogao extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    private function json($data, $code = 0, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }


    /**
     * @SWG\Get(path="/baogao/data",
     *   tags={"Baogao"},
     *   summary="列表",
     *   description="报告的内容列表",
     *   operationId="baogaodata",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function data_get()
    {
        $offset = $this->input->get('offset');
        $limit = $this->input->get('limit');
        $user_id = $this->session->userdata('user_id');
        $this->load->model('Baogao_model');
        $data = $this->Baogao_model->getAllData($user_id, $offset, $limit);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 0, $message = '没有数据');
        }
    }
    /**
     * @SWG\Get(path="/baogao/info",
     *   consumes={"multipart/form-data"},
     *   tags={"Baogao"},
     *   summary="报告的详情",
     *   description="报告的内容详情",
     *   operationId="baogaoinfo",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function info_get()
    {
        $id = $this->input->get('id');
        $user_id = $this->session->userdata('user_id');
        $this->load->model('Baogao_model');
        $data = $this->Baogao_model->findByUserId($id,$user_id);
        if ($data) {
            $this->json($data);
        } else {
            $this->json([], 0, $message = '没有数据');
        }
    }
}

?>