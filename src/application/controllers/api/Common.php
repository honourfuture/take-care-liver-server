<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Common extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    private function json($data, $code = 200, $message = '获取数据成功!')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }
    /**
     * @SWG\Post(path="/common/upload",
     *   consumes={"multipart/form-data"},
     *   tags={"Common"},
     *   summary="文件上传",
     *   description="文件上传",
     *   operationId="commonupload",
     *  @SWG\Parameter(
     *     in="formData",
     *     name="files",
     *     description="文件资源",
     *     required=true,
     *     type="file"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function upload_post() {
        // 上传文件到服务器目录
        $config['upload_path'] = './upload';
        // 允许上传哪些类型
        $config['allowed_types'] = 'gif|png|jpg|jpeg';
        // 上传后的文件名，用uniqid()保证文件名唯一
        $config['file_name'] = uniqid();

        // 加载上传库
        $this->load->library('upload', $config);
        // 上传文件，这里的pic是视图中file控件的name属性
        $result = $this->upload->do_upload('files');
        // 如果上传成功，获取上传文件的信息
        if ($result) {
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (
                isset($_SERVER['SERVER_NAME']) ? $_SERVER['HTTP_HOST'] : ""
            );
            if($host) {
                $fileUrl = $host."/"."upload/". $this->upload->file_name;
                return $this->json($fileUrl);
            }
//            var_dump($this->upload->data());
        }
        return $this->json([], 0, $message = '上传失败');
    }
}