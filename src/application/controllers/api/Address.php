<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Address extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Address_model');
        $this->user_id = $this->session->userdata('user_id');
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/address/list",
     *   tags={"Address"},
     *   summary="收货地址列表",
     *   description="收货地址列表",
     *   operationId="addressList",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {
        $this->load->model('Address_model');
        if ($this->user_id) {
            $data = $this->Address_model->myAddress($this->user_id);
            if ($data) {
                $this->json($data);
            } else {
                $this->json([], 200, $message = '没有数据');
            }
        } else {
            $this->json([], 500, '请登录');
        }
    }

    /**
     * @SWG\Post(path="/address/add",
	 *   consumes={"multipart/form-data"},
     *   tags={"Address"},
     *   summary="添加收货地址",
     *   description="添加收货地址",
     *   operationId="addressAdd",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="formData",
     *     name="address",
     *     description="收货地址",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     description="电话号码",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="name",
     *     description="姓名",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="is_default",
     *     description="是否默认（1默认）",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function add_post()
    {
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $name = $this->input->post('name');
        $is_default = $this->input->post('is_default', 0);

        if (!$this->user_id) {
            return $this->json([], 500, '请登录');
        }
        $data = [
            'address' => $address,
            'phone' => $phone,
            'name' => $name,
            'user_id'=>$this->user_id,
            'is_default' => $is_default
        ];

        if($this->Address_model->create($data)) {
            return $this->json(true, 200, '添加成功');
        } else {
            return $this->json([], 500, '服务器出错');
        }
    }
}

?>