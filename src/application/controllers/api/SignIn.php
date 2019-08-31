<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class SignIn extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('SignIn_model');
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
     * @SWG\Get(path="/signIn/list",
     *   tags={"SignIn"},
     *   summary="签到记录",
     *   description="获取用户签到记录",
     *   operationId="signInList",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     in="query",
     *     name="start_date",
     *     description="开始时间(2019-01-02)",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="end_date",
     *     description="结束时间(2019-01-02)",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get()
    {

        if(!$this->user_id){
            return  $this->json([], 500, '请登录');
        }

        $startDate = $this->input->get('start_date');
        $endDate = $this->input->get('end_date');

        if(!$startDate){
            $startDate = date('Y-m-01', time());
        }

        if(!$endDate){
            $endDate = date('Y-m-d', time());
        }

        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);
        $results = [];
        for($time = $startTime; $time <= $endTime; $time+=86400){
            $key = date('Y-m-d', $time);
            $results[$key] = [
              'date' => $key,
              'continue' => 0
            ];
        }

        $where = [
            'user_id' => $this->user_id,
            'date >= ' => $startDate,
            'date <= ' => $endDate,
        ];

        $data = $this->SignIn_model->get($where);

        foreach ($data as $datum){
            if(isset($results[$datum['date']])){
                $results[$datum['date']]['continue'] = $datum['continue'];
            }
        }
        array_values($results);
        return $this->json($results);

    }

    /**
     * @SWG\Post(path="/signIn/add",
	 *   consumes={"multipart/form-data"},
     *   tags={"SignIn"},
     *   summary="发起签到",
     *   description="发起签到",
     *   operationId="signInAdd",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function add_post()
    {

        if (!$this->user_id) {
            return $this->json([], 500, '请登录');
        }
        $where = [
            'date' => date('Y-m-d', time()),
            'user_id' => $this->user_id
        ];
        $data = $this->SignIn_model->findByAttributes($where);
        if($data){
            return $this->json(true, 200, '您已经签到过了！');
        }

        $where = [
            'date' => date('Y-m-d', strtotime("-1 day")),
            'user_id' => $this->user_id
        ];

        $lastData = $this->SignIn_model->findByAttributes($where);
        $continue = 1;
        if($lastData){
            $continue = $lastData['continue'] + 1;
        }

        $data = [
            'date' => date('Y-m-d', time()),
            'user_id' => $this->user_id,
            'continue' => $continue
        ];

        if($this->SignIn_model->create($data)) {
            return $this->json(true, 200, '签到成功！');
        } else {
            return $this->json([], 500, '服务器出错');
        }
    }
}

?>