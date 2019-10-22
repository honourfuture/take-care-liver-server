<?php
defined('BASEPATH') or exit ('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

class Liver extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ftp_model');
        $this->load->model('File_model');
        $this->load->model('Liver_model');
        $this->load->model('User_model');
        $this->load->model('Urine_model');
        $this->load->model('Urine_check_model');
        $this->load->model('CardGrantRecord_model');
        $this->load->model('CardUseRecord_model');
    }
    private function json($data, $code = 200, $message = '获取数据成功!')
    {
        $res['status'] = $code;
        $res['data'] = $data;
        $res['msg'] = $message;
        $this->response($res);
    }

    /**
     * @SWG\Get(path="/liver/zip",
     *   tags={"Liver"},
     *   summary="解压接口",
     *   description="解压接口",
     *   operationId="liverZip",
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function zip_get()
    {
        $files = $this->File_model->getAllByCid();
        foreach ($files as $file){
            $filePath = 'xxg/'.$file->file_path.$file->file_name;
            $outPath = 'xxg/'.$file->file_path.time().rand(0,1000);
            $zip = new ZipArchive();
            $openRes = $zip->open($filePath, true);
            if ($openRes === TRUE) {
                $zip->extractTo($outPath);
                $zip->close();

                $update = [
                    'is_zip' => 1,
                    'path_file' => $outPath.'/Clinet.xml'
                ];

                $this->File_model->update($file->id, $update);

                $xml = file_get_contents($outPath.'/Clinet.xml');
                $xml =simplexml_load_string($xml); //xml转object
                $json = json_encode($xml);
                $xmlData = json_decode($json,true); //json转array
                $phone = isset($xmlData['telePhone']) && $xmlData['telePhone'] ? $xmlData['telePhone'] : '';
                $name = $xmlData['patientName'];

                $liver = [
                    'phone' => $phone,
                    'name' => $name,
                    'info' => $json
                ];
                $id = $this->Liver_model->create($liver);
                $user = $this->User_model->find_by_mobile($phone);
                if($user){
                    $data = [
                        'date' => $xmlData['examinationDate'] ? $xmlData['examinationDate'] : date('Y-m-d H:i:s'),
                        'user_id'=> $user['id'],
                        'urine_check_id' => $id,
                        'type' => 2
                    ];

                    $wheres = [
                        'valid_start_time <= ' =>  date('Y-m-d H:i:s'),
                        'valid_end_time  >= ' =>date('Y-m-d H:i:s'),
                        'type' => 1,
                        'times > ' => 0,
                        'user_id' => $user['id']
                    ];

                    $urineNums = $this->CardGrantRecord_model->findOne($wheres);
                    if(!$urineNums){
                        return $this->json(true, 500, '没有尿检次数');
                    }

                    if($this->Urine_model->create($data)) {
                        $data = $this->CardUseRecord_model->useCard($this->user_id, $urineNums->id);
                        if($data['status'] == 200){
                            return $this->json(true, 200, '添加成功');
                        }else{
                            return $this->response($data);
                        }

                    } else {
                        return $this->json([], 500, '服务器出错');
                    }
                }
            }
        }
    }
    /**
     * @SWG\Post(path="/liver/xxg",
     *   consumes={"multipart/form-data"},
     *   tags={"Liver"},
     *   summary="肝检测接口",
     *   description="肝检测接口",
     *   operationId="liverXxg",
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
     *   @SWG\Parameter(
     *     in="formData",
     *     name="file_name",
     *     description="file_name",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="file_path",
     *     description="file_path",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="machine_id",
     *     description="machine_id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="detection_time",
     *     description="detection_time",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function xxg_post()
    {
        $data=file_get_contents("php://input"); //取得json数据
        $data = json_decode($data, TRUE);   //格式化

        $result = [];
        if(isset($data['content'])){
            $header['file_name'] = $data['content']['file_name'];
            $header['file_path'] = $data['content']['file_path'];
            $header['machine_id'] = $data['content']['machine_id'];
            $header['detection_time'] = $data['content']['detection_time'];

            $result = [
                'content' => [
                    'result' => 0,
                    'msg' => '成功',
                    'file_name' => $data['content']['file_name'],
                    'time' => date('Y-m-d H:i:s')
                ],
                'head' => $header
            ];

            $header = $data['head'];
            $header['file_name'] = $data['content']['file_name'];
            $header['file_path'] = $data['content']['file_path'];
            $header['machine_id'] = $data['content']['machine_id'];
            $header['detection_time'] = $data['content']['detection_time'];

            $this->File_model->create($header);
        }else{
            $result = [
                'content' => [
                    'ftp_address' => '39.106.18.66',
                    'ftp_port' => '21',
                    'ftp_username' => 'xxg',
                    'ftp_password' => 'Ckce6sWFL8LFDGZG',
                    'ftp_path' => '/',#
                ],

                'head' => $data['head']
            ];

            $this->Ftp_model->create($data['head']);
        }

        return $this->response($result);
    }
}

?>