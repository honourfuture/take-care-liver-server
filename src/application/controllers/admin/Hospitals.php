<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * hospitals 控制器
 */
class Hospitals extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('hospital_model');
        //检查登录
        //$this->backend_lib->checkLoginOrJump();
                
        //检查权限管理的权限
        //$this->backend_lib->checkPermissionOrJump(1);
    }
                
    public function index() {
        //$data = array();
        $param = array();
        $inParams = array();
        $likeParam = array();

        //搜索筛选
        $this->data['search'] = $this->input->get('search', TRUE);
        $keyword = $this->input->get('keyword', TRUE);
        if($keyword) {
            $likeParam['telphone'] = $keyword;
            $likeParam['name'] = $keyword;
            $likeParam['position'] = $keyword;
            $likeParam['detail'] = $keyword;
        }
        $this->data['keyword'] = $keyword;
        if($this->data['search']) {

            $this->data['id'] = $this->input->get('id', TRUE);
            if($this->data['id'] !== '') {
                $param['id'] = $this->data['id'];
            }

            $this->data['name'] = $this->input->get('name', TRUE);
            if($this->data['name']) {
                $likeParam['name'] = $this->data['name'];
            }

            $this->data['telphone'] = $this->input->get('telphone', TRUE);
            if($this->data['telphone']) {
                $likeParam['telphone'] = $this->data['telphone'];
            }

            $this->data['position'] = $this->input->get('position', TRUE);
            if($this->data['position']) {
                $likeParam['position'] = $this->data['position'];
            }

            $this->data['detail'] = $this->input->get('detail', TRUE);
            if($this->data['detail'] !== '') {
                $param['detail'] = $this->data['detail'];
            }

            $this->data['pic'] = $this->input->get('pic', TRUE);
            if($this->data['pic']) {
                $likeParam['pic'] = $this->data['pic'];
            }

            $this->data['create_time_start'] = $this->input->get('create_time_start', TRUE);
            $this->data['create_time_end'] = $this->input->get('create_time_end', TRUE);
            if ($this->data['create_time_start'] && $this->data['create_time_end']) {
                $param['create_time >='] = date('Y-m-d', strtotime($this->data['create_time_start']));
                $param['create_time <'] = date('Y-m-d', strtotime($this->data['create_time_end']));
            }

            $this->data['update_time_start'] = $this->input->get('update_time_start', TRUE);
            $this->data['update_time_end'] = $this->input->get('update_time_end', TRUE);
            if ($this->data['update_time_start'] && $this->data['update_time_end']) {
                $param['update_time >='] = date('Y-m-d', strtotime($this->data['update_time_start']));
                $param['update_time <'] = date('Y-m-d', strtotime($this->data['update_time_end']));
            }

            $this->data['distance'] = $this->input->get('distance', TRUE);
            if($this->data['distance']) {
                $likeParam['distance'] = $this->data['distance'];
            }

        }

        //自动获取get参数
        $urlGet = '';
        $gets = $this->input->get();
        if ($gets) {
            $i = 0;
            foreach ($gets as $getKey => $get) {
                if ($i) {
                    $urlGet .= "&$getKey=$get";
                } else {
                    $urlGet .= "/?$getKey=$get";
                }
                $i++;
            }
        }
                
        //排序
        $orderBy = $this->input->get('orderBy', TRUE);
        $orderBySQL = 'id DESC';
        if ($orderBy == 'idASC') {
            $orderBySQL = 'id ASC';
        }
        $this->data['orderBy'] = $orderBy;
                
        //分页参数
        $pageUrl = B_URL.'hospitals/index';  //分页链接
        $suffix = $urlGet;   //GET参数

        //$pageUri = 4;   //URL参数位置
        //$pagePer = 20;  //每页数量
        //计算分页起始条目
        //$pageNum = intval($this->uri->segment($pageUri)) ? intval($this->uri->segment($pageUri)) : 1;
        //$startRow = ($pageNum - 1) * $pagePer;

        //获取数据
        $result = $this->hospital_model->getResult($param, $this->per_page, $this->offset, $orderBySQL, $inParams, $likeParam);

        //生成分页链接
        $total = $this->hospital_model->count($param, $inParams, $likeParam);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);
        //$this->backend_lib->createPage($pageUrl, $pageUri, $pagePer, $total, $suffix);  //创建分页链接

        //获取联表结果
        //if ($result) {
        //    foreach ($result as $key => $value) {

        //    }
        //}

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/hospitals/index',$this->data); //$this->data
    }

    public function save() {
        $data = array();
        $data['business_types'] = $this->hospital_model->getBusiness_type();

        if ($this->input->method() == "post") {
            $this->form_validation->set_rules('id', 'id', 'trim');
            $this->form_validation->set_rules('name', 'name', 'trim');
            $this->form_validation->set_rules('telphone', 'telphone', 'trim');
            $this->form_validation->set_rules('position', 'position', 'trim');
            $this->form_validation->set_rules('longitude', 'longitude', 'trim');
            $this->form_validation->set_rules('latitude', 'latitude', 'trim');
            $this->form_validation->set_rules('detail', 'detail', 'trim');
            $this->form_validation->set_rules('pic', 'pic', 'trim');
            $this->form_validation->set_rules('create_time', 'create_time', 'trim');
            $this->form_validation->set_rules('update_time', 'update_time', 'trim');
            $this->form_validation->set_rules('business_type', 'business_type', 'trim');
            $this->form_validation->set_rules('distance', 'distance', 'trim');

        $param = array(
            'id' => $this->input->post('id', TRUE),
            'name' => $this->input->post('name', TRUE),
            'telphone' => $this->input->post('telphone', TRUE),
            'position' => $this->input->post('position', TRUE),
            'longitude' => $this->input->post('longitude', TRUE),
            'latitude' => $this->input->post('latitude', TRUE),
            'detail' => $this->input->post('detail', TRUE),
            'pic' => $this->input->post('pic', TRUE),
            'update_time' => date('Y-m-d H:i:s'),
            'business_type' => $this->input->post('business_type', TRUE),
            'distance' => $this->input->post('distance', TRUE),

        );
            $success = FALSE;
            $message = '';
            $message_type = 'fail';

            if ($this->form_validation->run() == FALSE) {
                $message = '表单填写有误';
                 //加载模板
                $this->template->admin_load('admin/hospitals/save', $data);
            } else {
                //保存记录
                $save = $this->hospital_model->save($param);

                if ($save) {
                    $message = '保存成功';
                    $success = TRUE;
                    $message_type = 'success';
                } else {
                    $message = '保存失败';
                }

                $this->session->set_flashdata('message_type', $message_type);
                $this->session->set_flashdata('message', $message);
                 //返回列表页面
                $form_url = $this->session->userdata('list_page_url');
                if(empty($form_url)){
                    $form_url = "/admin/hospitals/index";
                }
                else{
                    $this->session->unset_userdata('list_page_url');
                }
                redirect($form_url, 'refresh');

            }

            //if ($success) {
            //    $this->backend_lib->showMessage(B_URL.'hospitals', $message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //$id = intval($this->input->get('id'));
            $id = $this->uri->segment(4);
            if ($id) {
                $data['data'] = $this->hospital_model->getRow(array('id' => $id));
            }
            $this->template->admin_load('admin/hospitals/save', $data);
        }
    }

    public function manage() {
        $data = array();
        $this->form_validation->set_rules('ids[]', 'Ids', 'required');
        $this->form_validation->set_rules('manageName', '操作名称', 'required');

        $manageName = $this->input->post('manageName', TRUE);
        $ids = $this->input->post('ids', TRUE);

        $success = FALSE;
        $message = '';

        if ($this->form_validation->run() == FALSE) {
            $message = '表单填写有误';
        } else {
            if ($ids != null) {
                if ($manageName == 'delete') {
                    //删除记录
                    foreach ($ids as $key => $id) {
                        $param = array(
                            'id' => $id,
                        );
                        $this->hospital_model->delete($param);
                    }
                    $message = '删除成功';
                } elseif ($manageName == 'set_business_type') {
                    $setValue = $this->input->post('set_business_type', TRUE);
                    if ($setValue !== '') {
                        foreach ($ids as $key => $id) {
                            $param = array(
                                'id' => $id,
                                'business_type' => $setValue,
                            );
                            $this->hospital_model->save($param);
                        }
                        $message = '操作成功';
                    } else {
                        $message = '设置不能为空.';
                    }
                }
            }
        }

        $this->session->set_flashdata('message_type', 'success');
        $this->session->set_flashdata('message', $message);

        //返回列表页面
        $form_url = $this->session->userdata('list_page_url');
        if (empty($form_url)) {
            $form_url = "/admin/hospitals";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');

        //$this->backend_lib->showMessage(B_URL. 'hospitals', $message);
    }

    public function del() {

        $id = $this->uri->segment(4);

        if ($this->input->method() == "post") {
            if ($this->hospital_model->delete($id)) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "删除成功！");
            } else {
                $this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $this->data['id'] = $id;
        $this->load->view('admin/hospitals/modals/del', $this->data);
    }

    //详情
    public function view()
    {
        $id = $this->uri->segment(4);

        //获取数据
        $obj = $this->hospital_model->getRow(array("id" => $id));
        if(empty($obj)){
            redirect('admin/hospitals/index', 'refresh');
        }        $this->data['business_types'] = $this->hospital_model->getBusiness_type();

        // 传递数据
        $this->data['data']  = $obj;

        //当前列表页面的url
        $form_url = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
        if(strripos($form_url,"admin/hospitals") === FALSE){
            $form_url = "/admin/hospitals/index";
        }
        $this->data['form_url'] = $form_url;
        //加载模板
        $this->template->admin_load('admin/hospitals/view', $this->data);
    }

    //地图
    public function map()
    {
        //加载模板
        //$this->template->admin_load('admin/hospitals/map', $this->data);
        $CI = & get_instance();
        return $CI->load->view('admin/hospitals/map', $this->data);
    }



    public function finance() {

        //搜索筛选
        $this->data['start_date'] = $this->input->get('start_date');
        $this->data['end_date'] = $this->input->get('end_date');
        $keyword = $this->input->get('keyword', TRUE);
        $this->data['keyword'] = $keyword;

        //自动获取get参数
        $urlGet = '';
        $gets = $this->input->get();
        if ($gets) {
            $i = 0;
            foreach ($gets as $getKey => $get) {
                if ($i) {
                    $urlGet .= "&$getKey=$get";
                } else {
                    $urlGet .= "/?$getKey=$get";
                }
                $i++;
            }
        }

        //排序
        $orderBy = $this->input->get('orderBy', TRUE);
        $orderBySQL = 'id DESC';
        if ($orderBy == 'idASC') {
            $orderBySQL = 'id ASC';
        }
        $this->data['orderBy'] = $orderBy;

        //分页参数
        $pageUrl = B_URL.'hospitals/finance';  //分页链接
        $suffix = $urlGet;   //GET参数

        //获取数据

        $result = $this->hospital_model->getFinance( $this->per_page, $this->offset, $this->data['start_date'], $this->data['end_date']);

        //生成分页链接
        $total = $this->hospital_model->getFinance($this->per_page, $this->offset, $this->data['start_date'], $this->data['end_date'], true);

        $this->initPage($pageUrl.$suffix, $total, $this->per_page);

        $this->data['result'] = $result;

        //加载模板
        $this->template->admin_load('admin/hospitals/finance',$this->data); //$this->data
    }


    //导出
    public function export() {
        set_time_limit(0);//不设置过期时间

        $message_type = 'error';
        $message = '导出失败';
        if ($this->input->method() == "post") {
            //搜索筛选
            $this->data['keyword'] = $this->input->get('keyword', TRUE);
            $this->data['start_date'] = $this->input->get('start_date', TRUE);
            $this->data['end_date'] = $this->input->get('end_date', TRUE);

            $result = $this->hospital_model->getFinance(0, 0, $this->data['start_date'], $this->data['end_date'], false, $this->data['keyword']);
            if (!empty($result)) {
                $fields_array = array('hospital_id', 'name', 'cp_id', 'check_position', 'user_id', 'date', 'money');
                return $this->exportExcel($result, "导出数据.xlsx", $fields_array);
            }

        }
        $this->session->set_flashdata('message_type', $message_type);
        $this->session->set_flashdata('message', $message);
        //返回列表页面
        $form_url = $this->session->userdata('list_page_url');
        if (empty($form_url)) {
            $form_url = "/admin/hospitals/finance";
        } else {
            $this->session->unset_userdata('list_page_url');
        }
        redirect($form_url, 'refresh');
    }


    /**
     * @param $list
     * @param $filename
     * @param array $indexKey
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     * 比如: $indexKey与$list数组对应关系如下:
     *     $indexKey = array('id','username','sex','age');
     *     $list = array(array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24));
     */
    function exportExcel($list,$filename,$indexKey=array()){
        $this->load->library('PHPExcel');  //注意路径
        //$PHPExcel = new PHPExcel();            //如果excel文件后缀名为.xls，导入这个类

        //$this->load->library('PHPExcel/Writer/Excel2007');
        require(APPPATH . '/libraries/PHPExcel/Writer/Excel2007.php');
        require(APPPATH . '/libraries/PHPExcel/Reader/PHPExcel_Reader_Excel2007.php');
        require(APPPATH . '/libraries/PHPExcel/Reader/PHPExcel_Reader_Excel5.php');
        require(APPPATH . '/libraries/PHPExcel/Writer/Excel5.php');
        require(APPPATH . 'libraries/PHPExcel/IOFactory.php');

        /* require_once dirname(__FILE__) . '/Lib/Classes/PHPExcel/IOFactory.php';
         require_once dirname(__FILE__) . '/Lib/Classes/PHPExcel.php';
         require_once dirname(__FILE__) . '/Lib/Classes/PHPExcel/Writer/Excel2007.php';*/

        $header_arr = array('A','B','C','D','E','F','G','H');

        //$objPHPExcel = new PHPExcel();                        //初始化PHPExcel(),不使用模板
        //$template = dirname(__FILE__).'/template.xls';          //使用模板
        $template = 'downloads/template.xlsx';          //使用模板
        $objPHPExcel = PHPExcel_IOFactory::load($template);     //加载excel文件,设置模板

        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);  //设置保存版本格式

        //接下来就是写数据到表格里面去
        $objActSheet = $objPHPExcel->getActiveSheet();
        //$objActSheet->setCellValue('A2',  "活动名称：江南极客");
        //$objActSheet->setCellValue('C2',  "导出时间：".date('Y-m-d H:i:s'));
        $i = 2;
        foreach ($list as $ikey => $row) {
            foreach ($indexKey as $key => $value){
                $temp = $row->{$value};
//                if( $value == "total_cost" || $value == "profit" ||  $value == "profit_rate"){
//                    $temp = sprintf ($temp, $i);
//                }
                //这里是设置单元格的内容
                $objActSheet->setCellValue($header_arr[$key].$i,$temp);
            }
            $i++;
        }

        //杂费
//        if($list['za_fee']){
//            $objActSheet->setCellValue('B'.($i+6),"杂费：".$list['za_fee']);
//        }

        // 1.保存至本地Excel表格
        //$objWriter->save($filename.'.xls');

        // 2.接下来当然是下载这个表格了，在浏览器输出就好了
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="'.$filename.'"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
}
