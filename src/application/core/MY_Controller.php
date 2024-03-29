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
    public $cur_page = 0;//当前页
    public $per_page = 20;//每页显示条数
    public $offset = 0;//偏移量

    function __construct()
    {
        parent::__construct();
        ob_start();
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->load->helper('text');

        //处理分页-satart
        $cur_page = intval($this->input->get_post('per_page'));
        $offset = intval($this->input->get_post('offset'));
        $per_page = 20;
        if($cur_page < 1){
            $cur_page = 1;
        }
        $this->cur_page = $cur_page;
        $this->per_page = $per_page ? $per_page : $this->per_page;
        $this->offset = $offset ? $offset : ($this->cur_page - 1) * $this->per_page;
        //处理分页-end

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
        }else if ($type == 'E') {
            return $this->session->userdata('system_employee_id');
        }else if ($type == 'EmployeeName') {
            return $this->session->userdata('system_employee_name');
        }else if ($type == 'C') {
            return $this->session->userdata('system_customer_id');
        }else if ($type == 'CustomerName') {
            return $this->session->userdata('system_customer_name');
        }else if ($type == 'LevelName') {
            return $this->session->userdata('system_level_name');
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

    public function ajaxReturn($data,$status = 0,$message='', $contentType='')
    {
        $ret['data'] = $data;
        $ret['status'] = $status;
        $ret['message'] = $message;
        if($contentType){
            header($contentType);
        }else{
            header('Content-type: application/json');
        }

        exit(json_encode($ret));
    }

    /*
     * 翻页初始化
     * @param string $base_url 跳链URL
     * @param int $total_rows 总记录数
     * @param int $per_page 当前页
     */
    public function initPage($base_url, $total_rows, $per_page = NULL)
    {
        $page_config = config_item("pagination");

        //$keyword = $this->input->get("keyword");
        //$this->data['keyword'] = $keyword;
        //$per_page = $this->input->get("per_page");

        $page_config['use_page_numbers'] = true;
        $page_config['page_query_string'] = true;
        $page_config['first_link'] = '&laquo;';
        $page_config['last_link'] = '&raquo;';
        $page_config['next_link'] = '下一页';
        $page_config['prev_link'] = '上一页';

        $page_config['num_tag_open'] = '<li>';
        $page_config['num_tag_close'] = '</li>';
        $page_config['cur_tag_open'] = '<li class="active"><a href="#">';
        $page_config['cur_tag_close'] = '</a></li>';
        $page_config['prev_tag_open'] = '<li>';
        $page_config['prev_tag_close'] = '</li>';
        $page_config['next_tag_open'] = '<li>';
        $page_config['next_tag_close'] = '</li>';
        $page_config['first_tag_open'] = '<li>';
        $page_config['first_tag_close'] = '</li>';
        $page_config['last_tag_open'] = '<li>';
        $page_config['last_tag_close'] = '</li>';

        $page_config['base_url'] = $base_url;
        $page_config['total_rows'] = $total_rows;
        $page_config['per_page'] = is_null($per_page) ? $this->per_page : $per_page;
        $this->data['total_page'] = ceil($page_config['total_rows']/$page_config['per_page']);

        $page = $this->cur_page - 1;
        if($page > $this->data['total_page']){
            $page = $this->data['total_page'];
        }
        $page_config['offset'] = $page_config['per_page'] * $page;
        $this->data['total_rows'] = $page_config['total_rows'];

        $this->per_page = $page_config['per_page'];
        $this->offset = $page_config['offset'];
        //初始化分页
        $this->load->library('pagination');
        $this->pagination->initialize($page_config);
    }

}

//管理员Controller
class Admin_Controller extends MY_Controller
{
    public $privStatus;
    public $admin_pre_page = 20;
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('admin_model');

		$admin_id = $this->checkLogin('A');
		$method = $this->router->fetch_method();
		/*if ($this->router->fetch_class() == 'dashboard' && in_array($method,array('login','check_admin'))){
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
				
				//Data
				$this->data['title']       = $this->config->item('title');
				$this->data['title_lg']    = $this->config->item('title_lg');
				$this->data['title_mini']  = $this->config->item('title_mini');
				$this->data['admin_id'] = $admin_id;
				
				$this->data['message_type']  = strtolower($this->session->flashdata('message_type'));
				$this->data['message']  = $this->session->flashdata('message');	
			}
	
		}*/
        //新增 权限控制（精确到按钮级别）
        if ($this->router->fetch_class() == 'dashboard' && in_array($method,array('login','check_admin'))){
            if(!empty($admin_id)){
                redirect("/admin/");
            }
        } else {
            $uri_str = '';
            $uri_array = $this->uri->segment_array();
            for ($i = 1; $i <= count($uri_array); $i++) {
                if ($i == 4) {
                    break;
                }
                $uri_str = $uri_str . $uri_array[$i] . '/';
            }
            $uri_str = substr($uri_str, 0, strlen($uri_str) - 1);
            if(empty($admin_id)){
                redirect("/admin/login");
            }
            else if (!in_array($uri_str, $this->config->item('privilege_url')) && !$this->admin_model->checkUserPrivilege($admin_id, $uri_str)) {
                redirect("/admin/administrators/no_permission");
            } else {
                $this->load->library(array('form_validation'));

                /* Data */
                $this->data['title']       = $this->config->item('title');
                $this->data['title_lg']    = $this->config->item('title_lg');
                $this->data['title_mini']  = $this->config->item('title_mini');
                $this->data['admin_id'] = $admin_id;

                $this->data['message_type']  = strtolower($this->session->flashdata('message_type'));
                $this->data['message']  = $this->session->flashdata('message');

                //保存操作日志
                $this->load->model('Permission_model');
                $permission = $this->Permission_model->getRow(array("url"=>$uri_str));
                if($permission){
                    $this->load->model('Admin_logs_model');
                    $admin_name = $this->checkLogin('AdminName');
                    $this->Admin_logs_model->save(array(
                        "menu_id"=>$permission['menu_id'],
                        "user_id"=>$admin_id,
                        "user_name"=>$admin_name,
                        "permission_id"=>$permission['id'],
                        "remark"=>$permission['name'],
                        "create_time"=>date('Y-m-d H:i:s'),
                    ));
                }

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


//员工Controller
class Employee_Controller extends MY_Controller
{
    public $privStatus;
    public $admin_pre_page = 20;
    public function __construct()
    {
        parent::__construct();

        $this->load->model('employee_model');

        $admin_id = $this->checkLogin('E');
        $method = $this->router->fetch_method();
        if ($this->router->fetch_class() == 'dashboard' && in_array($method,array('login','check_admin'))){
            if(!empty($admin_id)){
                redirect("/employee/");
            }
        }
        else{
            if(empty($admin_id)){
                redirect("/employee/login");
            }
            else{
                $this->load->library(array('form_validation'));

                //Data
                $this->data['title']       = $this->config->item('title');
                $this->data['title_lg']    = $this->config->item('title_lg');
                $this->data['title_mini']  = $this->config->item('title_mini');
                $this->data['admin_id'] = $admin_id;

                $this->data['message_type']  = strtolower($this->session->flashdata('message_type'));
                $this->data['message']  = $this->session->flashdata('message');

                $admin_id = $this->checkLogin('E');
                $admin_name = $this->checkLogin('EmployeeName');
                $level_name = $this->checkLogin('LevelName');
                $this->data['admin_id'] = $admin_id;
                $this->data['admin_name'] = $admin_name;
                $this->data['level_name'] = $level_name;
            }

        }

    }
}

//客服Controller
class Customer_Controller extends MY_Controller
{
    public $privStatus;
    public $admin_pre_page = 20;
    public function __construct()
    {
        parent::__construct();

        $this->load->model('employee_model');

        $admin_id = $this->checkLogin('C');
        $method = $this->router->fetch_method();
        if ($this->router->fetch_class() == 'dashboard' && in_array($method,array('login','check_admin'))){
            if(!empty($admin_id)){
                redirect("/customer/");
            }
        }
        else{
            if(empty($admin_id)){
                redirect("/customer/login");
            }
            else{
                $this->load->library(array('form_validation'));
                //Data
                $this->data['title']       = $this->config->item('title');
                $this->data['title_lg']    = $this->config->item('title_lg');
                $this->data['title_mini']  = $this->config->item('title_mini');
                $this->data['admin_id'] = $admin_id;

                $this->data['message_type']  = strtolower($this->session->flashdata('message_type'));
                $this->data['message']  = $this->session->flashdata('message');

                $admin_id = $this->checkLogin('C');
                $admin_name = $this->checkLogin('CustomerName');
                $level_name = $this->checkLogin('LevelName');
                $this->data['admin_id'] = $admin_id;
                $this->data['admin_name'] = $admin_name;
                $this->data['level_name'] = $level_name;
            }
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
