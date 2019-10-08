<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Liver extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ftp_model');
    }

    /**
     * @SWG\Post(path="/liver/ftp",
     *   consumes={"multipart/form-data"},
     *   tags={"Liver"},
     *   summary="肝检测接口",
     *   description="肝检测接口",
     *   operationId="liverFtp",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     in="header",
     *     name="vison",
     *     description="vison",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="compression_chioce",
     *     description="compression_chioce",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="encryption",
     *     description="encryption",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="encryption_choice",
     *     description="encryption_choice",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="proto_type",
     *     description="proto_type",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="proto_token",
     *     description="proto_token",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="package_type",
     *     description="package_type",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="package_id",
     *     description="package_id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="header",
     *     name="package_seq",
     *     description="package_seq",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function ftp_post()
    {
        $header['vison'] = $this->input->get_request_header('vison');
        $header['compression_chioce'] = $this->input->get_request_header('compression_chioce');
        $header['encryption'] = $this->input->get_request_header('encryption');
        $header['encryption_choice'] = $this->input->get_request_header('encryption_choice');
        $header['proto_type'] = $this->input->get_request_header('proto_type');
        $header['proto_token'] = $this->input->get_request_header('proto_token');
        $header['package_type'] = $this->input->get_request_header('package_type');
        $header['package_id'] = $this->input->get_request_header('package_id');
        $header['package_seq'] = $this->input->get_request_header('package_seq');

        $ftp = [
            'ftp_address' => '39.106.18.66',
            'ftp_port' => '21',
            'ftp_username' => 'xxg',
            'ftp_password' => 'Ckce6sWFL8LFDGZG',
            'ftp_path' => '/',
        ];

        $this->Ftp_model->create($header);
        return $this->response($ftp, 200, $header);
    }
}

?>