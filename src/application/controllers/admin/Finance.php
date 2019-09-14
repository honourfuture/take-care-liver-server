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

            $base_url = base_url('/admin/finance/cash_out');
            if (!empty($keyword)) {
                $base_url .= "?keyword=" . $keyword;
            }
            $total_rows = $this->Cash_out_model->getCount($keyword);
            $this->initPage($base_url, $total_rows, $this->admin_pre_page);
            $this->data['report_list'] = $this->Cash_out_model->getAll($this->per_page, $this->offset, $keyword);
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
}
