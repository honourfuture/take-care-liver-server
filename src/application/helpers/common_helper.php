<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('active_link_controller'))
{
    function active_link_controller($controller)
    {
        $CI    =& get_instance();
        $class = $CI->router->fetch_class();

        return ($class == $controller) ? 'active' : NULL;
    }
}


if ( ! function_exists('active_link_method'))
{
    function active_link_method($controller, $method)
    {
        $CI    =& get_instance();
        $class = $CI->router->fetch_class();
        $method_name = $CI->router->fetch_method();

        return (($class == $controller) && ($method_name == $method)) ? 'active' : NULL;
    }
}

if ( ! function_exists('get_rand_str'))
{
    /**
     *
     * 随机字符串
     * @param Integer $length
     */
    function get_rand_str($length = '6')
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}
if ( ! function_exists('getMillisecond'))
{
    /**
     * 获取毫秒时间
     * @return float
     */
    function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
}
if ( ! function_exists('createLongNumberNo'))
{
    /**
     * 生成长数字字符串
     * 生成订单号规则
     * @return string
     */
    function createLongNumberNo($len = 19) {
        $str = "";
        $time = getMillisecond();
        if($len-13>0) {
            $str = substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $len-13);
        }
        $longNo = $time.$str;
        return $longNo;
    }
}