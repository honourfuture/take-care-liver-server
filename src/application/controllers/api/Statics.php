<?php
defined('BASEPATH') or exit ('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

/**
 * Class Config
 */
class Statics extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Config_model');
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/statics/find",
     *   tags={"Statics"},
     *   summary="静态页配置",
     *   description="静态页配置",
     *   operationId="staticsFind",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     in="query",
     *     name="type",
     *     description="
     * 公益免费 : publicGoodFree
     * 肝检测 : liverCheck
     * 肝养护 : liverCuring
     * 小心肝公益 : babyLiver
     * 知识库 : knowledgeWarehouse
     * 转发详情 : aboutShare
     * 关于我们 : aboutUs
     * 首页介绍以及价格 : homePage
     * 首页促销图 : promotion",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */

    public function find_get()
    {
        $type = $this->input->get('type');
        $info = ['publicGoodFree', 'liverCheck', 'liverCuring', 'babyLiver', 'babyLiver', 'knowledgeWarehouse', 'aboutUs', 'aboutShare', 'homePage', 'promotion'];
        if(!in_array($type, $info)){
            return $this->json(null,200, '未找到该数据！');
        }
        try{
            $data = $this->Config_model->findByAttributes(array(), $type);
        }catch (\Exception $e){
            return $this->json(null, 200, '未找到该数据！');
        }
        if ($data) {
            return $this->json(json_decode($data[$type]));
        } else {
            return $this->json(null,200, '未找到该数据！');
        }
    }




}

?>