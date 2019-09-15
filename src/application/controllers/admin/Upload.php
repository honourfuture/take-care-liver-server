<?php
/**
 * 上传文件
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends Admin_Controller {

	public function __construct(){
		parent::__construct();

	}

    public function upload_image(){
        //$config['is_save_local']  = config_item('upload')['is_save_local'];
        $config['filepath']    = config_item('upload')['filepath'];
        $config['allowtype']   = config_item('upload')['allowtype'];
        //$config['qiniu']   = config_item('upload')['qiniu'];
        $this->load->library('Myupload', $config);
        if ( ! $this->myupload->uploadFile('upload_file'))
        {
            $message = $this->myupload->getErrorMsg();
            $this->ajaxReturn('', 0, $message, true);
        }
        else
        {
            if( config_item('upload')['is_save_local']){
                //如果是保存在本地
                $data = array(
                    'file_name' => $this->myupload->getNewFileName(),
                    //'url' => substr(config_item('upload')['filepath'],1).$this->myupload->getNewFileName()//返回文件地址
                    'url' => qiniu_image($this->myupload->getNewFileName(), false)
                );
                $this->ajaxReturn($data, 1, '', true, "Content-type: text/html");//修改上传成功以后不能正常响应的问题
            }else{
                //保存文件到七牛
                $filePath = $this->myupload->getNewFilePath();
                $qiniu_config = config_item('upload')['qiniu'];
                //加载七牛插件
                $this->load->library('Qiniu');
                $res = Qiniu::upload($filePath, '', $qiniu_config['bucket'], $qiniu_config['accessKey'], $qiniu_config['secretKey']);
                if ($res['status']) {
                    unlink($filePath);//删除文件
                    $this->load->helper('common');
                    $data = array(
                        'file_name' =>$res['data']['key'],
                        'url' => qiniu_image($res['data']['key'])
                    );
                    $this->ajaxReturn($data, 1, '', true, "Content-type: text/html");//修改上传成功以后不能正常响应的问题
                }else{
                    $data = array(
                        'file_name' =>'',
                        'url' => ''
                    );
                    $this->ajaxReturn($data, 0, '上传成功', true, "Content-type: text/html");
                }
            }

        }
    }
}
