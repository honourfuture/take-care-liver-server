<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class RetailStore extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    private function json($data, $code = 200, $message = '')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/retailStore/info",
     *   tags={"RetailStore"},
     *   summary="我的分销",
     *   description="获取我的分销数据",
     *   operationId="retailStoreInfo",
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
     *     in="header",
     *     name="token",
     *     description="token",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function info_get()
    {
        $this->config->config['base_url'];

        $page = $this->input->get('page');

        if(!$page){
            $page = 1;
        }
        if(!$this->user_id){
            return  $this->json([], 401, '请登录');
        }
        $user = $this->User_model->find($this->user_id);
        $results = [];
        $results['shareCode'] = $user->mobile;
        $results['shareUrl'] = $this->config->config['base_url'].'/user_id='.$user->id;

        $where = [
            'parent_id' => $this->user_id,
        ];

        $sonUsers = $this->User_model->getAllPage($where,$this->per_page, $this->offset, $this->user_id);
        $nextMembers = [];

        foreach ($sonUsers as $sonUser){
            $nextMembers[] = [
                'name' => $sonUser['username'],
                'createDate' => $sonUser['create_time'],
                'head_pic' => $sonUser['head_pic'],
                'is_vip' => $sonUser['is_vip']
            ];
        }
        $where = [
            'share_id' => $this->user_id,
        ];
        $where['is_vip'] = 1;
        $totalVip = $this->User_model->getAllPageTotal($where);

        $results['nextMember'] = $nextMembers;
        $results['memberTotal'] = $totalVip;
        $results['urineTotal'] = $totalVip;

        return $this->json($results);

    }
}

?>