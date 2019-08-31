<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class RetailStore extends REST_Controller
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

}

?>