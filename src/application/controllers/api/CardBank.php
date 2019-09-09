<?php
defined('BASEPATH') or exit ('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class CardBank extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->load->model('Record_model');
        $this->load->model('Card_bank_model');
        $this->load->model('Bank_model');
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }
    /**
     * @SWG\Get(path="/cardBank/validate_bank",
     *   tags={"CardBank"},
     *   summary="验证银行卡",
     *   description="验证银行卡",
     *   operationId="get",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="query",
     *     name="card_num",
     *     description="银行卡id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function validate_bank_get()
    {
        $cardNum = $this->input->get('card_num');

        try{
            $results = $this->Bank_model->validate($cardNum);

            $bank_code = isset($results['bank']) ? $results['bank'] : false;
            if(!$bank_code){
                return $this->json([], 500, '银行卡格式不正确');
            }
            $card_bank = $this->_cardType($results['cardType']);

            $results = $this->Bank_model->find($bank_code);
            $results['card_bank'] = $card_bank;
            return $this->json($results);

        }catch (\Exception $e){
            return $this->json([], 500, '未知错误请联系管理员');
        }
    }

    /**
     * @param $type
     * @return mixed
     */
    private function _cardType($type)
    {
        $bankType =  array(
            'DC' => '储蓄卡',
            'CC' => '信用卡',
            'SCC' => '准贷记卡',
            'PC' => '预付费卡'
        );
        if(!isset($bankType)){
            return false;
        }
        return $bankType[$type];
    }

    /**
     * @SWG\Get(path="/cardBank/list",
     *   tags={"CardBank"},
     *   summary="获取用户全部银行卡",
     *   description="获取用户全部银行卡",
     *   operationId="get",
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
    public function list_get()
    {
        if(!$this->user_id){
            return $this->json([], 401, '请登录');//未登录
        }

        $wheres = array('user_id' => $this->user_id);
        $results = $this->Card_bank_model->get($wheres);

        return $this->json($results);
    }
    /**
     * @SWG\Get(path="/cardBank/bankList",
     *   tags={"CardBank"},
     *   summary="获取全部银行卡",
     *   description="获取全部银行卡",
     *   operationId="get",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function bankList_get()
    {
        $results = $this->Bank_model->get();

        return $this->json($results);
    }
    /**
     * @SWG\Post(path="/cardBank/create",
     *   consumes={"multipart/form-data"},
     *   tags={"CardBank"},
     *   summary="添加银行卡",
     *   description="添加银行卡",
     *   operationId="cardBank",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="phone",
     *     description="预留手机号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="card_number",
     *     description="银行卡卡号",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="bank_name",
     *     description="发卡行(招商银行 中国农业银行)",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="card_name",
     *     description="真实姓名",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="bank_code",
     *     description="银行卡code(验证接口 CCB ABC ...)",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="card_type",
     *     description="银行卡类型(储蓄卡 信用卡)",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="verify",
     *     description="验证码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function create_post()
    {
        if(!$this->user_id){
            return $this->json([],401, '请登录');//未登录
        }

        $cardNumber = trim($this->input->post('card_number'));
        $bankName = trim($this->input->post('bank_name'));
        $bankCode = trim($this->input->post('bank_code'));
        $cardType = trim($this->input->post('card_type'));
        $cardName = trim($this->input->post('card_name'));
        $phone = $this->input->post('phone');
        $verify = $this->input->post('verify');

        $redisVerify = $this->Record_model->getVerify($phone, 'addCardBank');

        if($verify == null){
            return $this->json([], 500, '验证码发送失败，请联系管理员');
        }

        if ($redisVerify != $verify) {
            return $this->json([], 500, '验证码错误！');
        }

        $insert = array(
            'card_number' => $cardNumber,
            'bank_name' => $bankName,
            'bank_code' => $bankCode,
            'card_name' => $cardName,
            'phone' => $phone,
            'card_type' => $cardType,
            'user_id' => $this->user_id
        );
        $isHas = $this->Card_bank_model->findByAttributes($insert);

        if($isHas){
            $this->json([], 500, '该银行卡已被绑定!');//未登录
        }

        $id = $this->Card_bank_model->create($insert);

        if(!$id){
            return $this->json([], 500, '未知错误请联系管理员');
        }

        return $this->json([], 200, '添加成功！');
    }

    /**
     * @SWG\Post(path="/cardBank/delete",
     *   consumes={"multipart/form-data"},
     *   tags={"CardBank"},
     *   summary="解绑银行卡",
     *   description="解绑银行卡",
     *   operationId="delete",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="card_bank_id",
     *     description="银行卡id",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function delete_post()
    {
        if(!$this->user_id){
            return $this->json([], 401,'请登录');//未登录
        }

        $cardBankId = intval($this->input->post('card_bank_id'));

        $checkData = array(
            'cb.id' => $cardBankId,
            'cb.user_id' => $this->user_id,
            'cb.is_delete' => 0
        );

        $check = $this->Card_bank_model->get($checkData);
        if(!$check){
            return $this->json([], 500, '删除失败，未找到银行卡信息');
        }

        $data = array('is_delete' => 1);
        $status = $this->Card_bank_model->update($cardBankId, $data);

        if(!$status){
            return $this->json([], 500, '删除失败，请联系管理员');
        }

        return $this->json([], 200,'删除成功');
    }

    /**
     * @SWG\Get(path="/cardBank/find",
     *   tags={"CardBank"},
     *   summary="根据ID查询银行卡",
     *   description="根据ID查询银行卡",
     *   operationId="find",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="银行卡id",
     *     required=true,
     *     type="integer"
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
        if(!$this->user_id){
            return $this->json([], 401, '未登录');//未登录
        }

        $id = intval($this->input->get('id'));

        $data = $this->Card_bank_model->find($id);
        if ($data) {
            return $this->json($data);
        } else {
            return $this->json([],500, '未找到银行卡');//暂无数据
        }
    }
}

?>