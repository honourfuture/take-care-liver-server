<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Cash_out_model');
        $this->load->model('User_model');
    }

    //列表界面
    public function cash_out()
    {
        $admin_id = $this->checkLogin('A');
        if (!empty($admin_id)) {

            $keyword = $this->input->get("keyword");
            $this->data['keyword'] = $keyword;
            $status = $this->input->get("status");
            if(strlen($status)>0 && ($status == 0 || $status == 1 || $status == 2)){
                $this->data['status'] = $status;
            }else{
                $status = null;
            }
            $base_url = base_url('/admin/finance/cash_out');
            if (!empty($keyword)) {
                $base_url .= "?keyword=" . $keyword;
            }
            $total_rows = $this->Cash_out_model->getCount($keyword,$status);
            $this->initPage($base_url, $total_rows, $this->admin_pre_page);
            $this->data['report_list'] = $this->Cash_out_model->getAll($this->per_page, $this->offset, $keyword,$this->data['status']);
            //加载模板
            $this->template->admin_load('admin/finance/cash_out', $this->data);

        } else {
            redirect("/admin/report");
        }
        //加载模板
    }

    public function edit()
    {
        $id = $this->uri->segment(4);
        $status = $this->uri->segment(5);

        $data = array();
        if ($this->input->method() == "get") {
            if ($this->Cash_out_model->update($id, array('status' => $status))) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "审核成功！");
            } else {
                $data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>审核时发生错误，请稍后再试！</div>";
            }
        }

        redirect('/admin/finance/cash_out', 'refresh');
    }


    //举报处理列表界面
    public function record()
    {
        $admin_id = $this->checkLogin('A');
        if (!empty($admin_id)) {

            $keyword = $this->input->get("keyword");
            $this->data['keyword'] = $keyword;

            $base_url = base_url('/admin/report/record');
            if (!empty($keyword)) {
                $base_url .= "?keyword=" . $keyword;
            }
            $total_rows = $this->Report_dispose_record_model->getCount($keyword, 0);
            $this->initPage($base_url, $total_rows, $this->admin_pre_page);
            $this->data['report_list'] = $this->Report_dispose_record_model->getAll($this->per_page, $this->offset, $keyword, 0);
            //加载模板
            $this->template->admin_load('admin/report/record', $this->data);

        } else {
            redirect("/admin/report/record");
        }
        //加载模板
    }

    //删除举报处理记录
    public function record_del()
    {
        $id = $this->uri->segment(4);

        $data = array();

        if ($this->Report_dispose_record_model->delete($id)) {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', "删除成功！");
        } else {
            $data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
        }
        $data['id'] = $id;
        $this->load->view('admin/report/modals/record_del', $data);

    }


    //处理
    public function dispose()
    {

        $id = $this->uri->segment(4);
        $type = $this->input->get("type");
        if ($this->input->method() == "post") {
            $result = null;
            if ($type == 11) {
                $upd = array(
                    'is_delete' => 1,
                );
                //举报清零
                $this->Info_report_model->update_by_user_id($id, $upd);
                $this->load->driver('cache');
                //删除限制发布
                $result = $this->cache->redis->delete('REPORT_LIMIT:NOT_PUBLISH:' . $id);
            } else if ($type == 12) {
                //禁用账号
                $upd = array(
                    'active' => 0,//禁用账号
                );
                $result = $this->User_model->update($id, $upd);
            }

            if ($result) {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "操作成功！");
            } else {
                $data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        $data['id'] = $id;
        $data['type'] = $type;
        $this->load->view('admin/report/modals/dispose', $data);
    }



    //导出
    public function export() {
        set_time_limit(0);//不设置过期时间

        $message_type = 'error';
        $message = '导出失败';
        //if ($this->input->method() == "post") {
            //搜索筛选
            $this->data['keyword'] = $this->input->get('keyword', TRUE);
            $status = $this->input->get("status");
            if(strlen($status)>0 && ($status == 0 || $status == 1 || $status == 2)){
                $this->data['status'] = $status;
            }else{
                $status = null;
            }
            $result = $this->Cash_out_model->getAll(null, null,  $this->data['keyword'], $this->data['status']);
            if (!empty($result)) {
                foreach($result as $item){
                    if($item->status === '0'){
                        $item->status ='待审批';
                    }else  if($item->status === '1'){
                        $item->status ='已通过';
                    }else {
                        $item->status ='已拒绝';
                    }
                }
                $fields_array = array('cash_out_money', 'username', 'bank_name', 'card_number', 'phone', 'apply_time','status');
                return $this->exportExcel($result, "导出数据.xlsx", $fields_array);
            }else{
                $message = "暂无数据";
            }

      //  }
        $this->session->set_flashdata('message_type', $message_type);
        $this->session->set_flashdata('message', $message);
        //返回列表页面
        $form_url = $this->session->userdata('list_page_url');
        if (empty($form_url)) {
            $form_url = "/admin/finance/cash_out";
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

        $header_arr = array('A','B','C','D','E','F','G','H',"I");

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
