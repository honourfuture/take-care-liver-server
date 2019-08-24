<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * 通用的Controller函数
 * @author Teamtweaks
 *
 */
date_default_timezone_set('Asia/Shanghai');

//基础Controller
class MY_Controller extends CI_Controller
{
    public $data = array();

    function __construct()
    {
        parent::__construct();
        ob_start();
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->load->helper('text');

        /*
         * Connecting Database
         */
        $this->load->database();
    }

    /**
     *
     * 返回登陆参数
     * @param $type
     */
    public function checkLogin($type = '')
    {
        if ($type == 'A') {
            return $this->session->userdata('system_admin_id');
        } else if ($type == 'AdminName') {
            return $this->session->userdata('system_admin_name');
        } else if ($type == 'AdminPrivilege') {
            return $this->session->userdata('system_admin_privileges');
        } else if ($type == 'U') {
			return $this->session->userdata('system_user_id');
        } else if ($type == 'UserName') {
			return $this->session->userdata('system_user_id');
        }
    }

    /**
     *
     * 错误提示
     * @param string $type
     * @param string $msg
     */
    public function setErrorMessage($type = '', $msg = '')
    {
        ($type == 'success') ? $msgVal = 'alert-success' : $msgVal = 'alert-danger';
        $this->session->set_flashdata('system_alert_type', $msgVal);
        $this->session->set_flashdata('system_alert_msg', $msg);
    }

    public function ajaxReturn($data,$status = 0,$message='')
    {
        $ret['data'] = $data;
        $ret['status'] = $status;
        $ret['message'] = $message;
        header('Content-type: application/json');
        exit(json_encode($ret));
    }

}

//管理员Controller
class Admin_Controller extends MY_Controller
{
    public $privStatus;
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('admin_model');
		
		$admin_id = $this->checkLogin('A');
		$method = $this->router->fetch_method();
		if ($this->router->fetch_class() == 'dashboard' && in_array($method,array('login','check_admin'))){
			if(!empty($admin_id)){
				redirect("/admin/");
			}
		}
		else{
			if(empty($admin_id)){
				redirect("/admin/login");
			}
			else{
				$this->load->library(array('form_validation'));
				
				/* Data */
				$this->data['title']       = $this->config->item('title');
				$this->data['title_lg']    = $this->config->item('title_lg');
				$this->data['title_mini']  = $this->config->item('title_mini');
				$this->data['admin_id'] = $admin_id;
				
				$this->data['message_type']  = strtolower($this->session->flashdata('message_type'));
				$this->data['message']  = $this->session->flashdata('message');	
			}
	
		}

		
    }


    /**
     *
     * 管理员权限
     * @param String $name ->    Management Name
     * @param Integer $right ->    0 for view, 1 for add, 2 for edit, 3 delete
     */
    public function checkPrivileges($name = '', $right = '')
    {
        $prev = '0';
        $privileges = $this->session->userdata('system_admin_privileges');
        extract($privileges);
        $userName = $this->session->userdata('system_admin_name');

        if (isset(${$name}) && is_array(${$name}) && in_array($right, ${$name})) {
            $prev = '1';
        }
        if ($prev == '1') {
            return TRUE;
        } else {
            return FALSE;
        }
    }	
	
}

//PC网站Controller
class Web_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

	}


}

//手机版网站Controller
class Mobile_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

        
	}
}


//微信APP端Controller
class Weixin_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

        
	}
}


//API端Controller
class API_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

        
	}
}
