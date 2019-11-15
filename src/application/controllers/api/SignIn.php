<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class SignIn extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('SignIn_model');
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
    public function list_get()
    {

        if(!$this->user_id){
            return  $this->json([], 401, '请登录');
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
                'continue' => 0,
                'is_apply' => 1,
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
                $results[$datum['date']]['is_apply'] = $datum['is_apply'];
            }
        }
        $results = array_values($results);
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
    public function add_post()
    {

        if (!$this->user_id) {
            return $this->json([], 401, '请登录');
        }
        $hour = date('H', time());
        if($hour < 21 || $hour > 23){
            return $this->json([], 500, '对不起，现在不是约定的打卡签到时段。请在每天21:00--23:00时段参与打卡！');
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

    /**
     * @SWG\Post(path="/signIn/apply",
     *   consumes={"multipart/form-data"},
     *   tags={"SignIn"},
     *   summary="领取奖品",
     *   description="领取奖品",
     *   operationId="signInApply",
     *   produces={"application/json"},
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
    public function apply_post()
    {
        if (!$this->user_id) {
            return $this->json([], 401, '请登录');
        }

        $where = [
            'user_id' => $this->user_id,
            'continue >=' => 21,
            'is_apply' => 1
        ];
        $lastData = $this->SignIn_model->findByAttributes($where);
        if(!$lastData){
            return $this->json([], 500, '未到达领取条件，请继续努力');
        }
        $data = [
          'is_apply' => 2
        ];
        if($this->SignIn_model->update($lastData['id'], $data)) {
            return $this->json([], 200, '领取成功，请等待发货！');
        } else {
            return $this->json([], 500, '服务器出错');
        }
    }
}

?>