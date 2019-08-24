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
