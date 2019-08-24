<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template {

    protected $CI;

    public function __construct()
    {	
		$this->CI =& get_instance();
    }


    /*
	* 加载管理后台页面
	*/public function admin_load($content, $data = NULL)
    {
        if ( ! $content)
        {
            return NULL;
        }
        else
        {
            $this->template['header']          = $this->CI->load->view('admin/_templates/header', $data, TRUE);
            $this->template['content']         = $this->CI->load->view($content, $data, TRUE);
            $this->template['footer']          = $this->CI->load->view('admin/_templates/footer', $data, TRUE);

            return $this->CI->load->view('admin/_templates/template', $this->template);
        }
	}

    /*
	* 加载PC网站页面
	*/public function web_load($content, $data = NULL)
    {
        if ( ! $content)
        {
            return NULL;
        }
        else
        {
            $this->template['header']          = $this->CI->load->view('web/_templates/header', $data, TRUE);
            $this->template['content']         = $this->CI->load->view($content, $data, TRUE);
            $this->template['footer']          = $this->CI->load->view('web/_templates/footer', $data, TRUE);

            return $this->CI->load->view('web/_templates/template', $this->template);
        }
	}



    /*
	* 加载手机版网站页面
	*/public function mobile_load($content, $data = NULL)
    {
        if ( ! $content)
        {
            return NULL;
        }
        else
        {
            $this->template['header']          = $this->CI->load->view('mobile/_templates/header', $data, TRUE);
            $this->template['content']         = $this->CI->load->view($content, $data, TRUE);
            $this->template['footer']          = $this->CI->load->view('mobile/_templates/footer', $data, TRUE);

            return $this->CI->load->view('mobile/_templates/template', $this->template);
        }
	}



    /*
	* 加载微信页面
	*/public function wechat_load($content, $data = NULL)
    {
        if ( ! $content)
        {
            return NULL;
        }
        else
        {
            $this->template['header']          = $this->CI->load->view('wechat/_templates/header', $data, TRUE);
            $this->template['content']         = $this->CI->load->view($content, $data, TRUE);
            $this->template['footer']          = $this->CI->load->view('wechat/_templates/footer', $data, TRUE);

            return $this->CI->load->view('wechat/_templates/template', $this->template);
        }
	}


    /*
	* 加载API页面
	*/public function api_load($content, $data = NULL)
    {
        if ( ! $content)
        {
            return NULL;
        }
        else
        {
            $this->template['header']          = $this->CI->load->view('api/_templates/header', $data, TRUE);
            $this->template['content']         = $this->CI->load->view($content, $data, TRUE);
            $this->template['footer']          = $this->CI->load->view('api/_templates/footer', $data, TRUE);

            return $this->CI->load->view('api/_templates/template', $this->template);
        }
	}



    /*
	* 加载开发版网站页面
	*/public function development_load($content, $data = NULL)
    {
        if ( ! $content)
        {
            return NULL;
        }
        else
        {
            $this->template['header']          = $this->CI->load->view('development/_templates/header', $data, TRUE);
            $this->template['content']         = $this->CI->load->view($content, $data, TRUE);
            $this->template['footer']          = $this->CI->load->view('development/_templates/footer', $data, TRUE);

            return $this->CI->load->view('development/_templates/template', $this->template);
        }
	}


}