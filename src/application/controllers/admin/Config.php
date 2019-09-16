<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 系统参数
 * Class Settings
 */
class Config extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Config_model');
    }


    //关于我们
    public function index()
    {
        if ($this->input->method() == "post") {
            $type = $this->input->post('type');
            //$data['pic'] = $this->input->post('pic');
            //$data['title'] = $this->input->post('title');
            $data['pic'] = "";
            $data['title'] = "";
            $data['details'] = $this->input->post('details');

            $res = $this->Config_model->update(1, array($type => json_encode($data)));
            if($res){
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', "操作成功！");
            }else{
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', "操作失败！");
                return ;
            }
            $form_url = $this->session->userdata('list_page_url');
            if (empty($form_url)) {
                $form_url = "/admin/config/index?type=".$type;
            } else {
                $this->session->unset_userdata('list_page_url');
            }
            redirect($form_url, 'refresh');
        } else {
            $type = $this->input->get('type');
            $data = $this->Config_model->findByAttributes(array('id' => 1), $type);
            $this->data['type'] = $type;
            $bootPage = json_decode($data[$type],true);
            $this->data['data'] = $bootPage;
            //加载模板
            $this->template->admin_load('admin/config/index', $this->data);
        }
    }



}
