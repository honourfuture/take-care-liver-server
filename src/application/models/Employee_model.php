<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * employee 模型
 */
class Employee_model extends Base_model {
    private $_name='employee';

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }
   
    public function getLevel($key='') {
        $data = array('A' => 'A级管理员', 'B' => 'B级管理员', 'C' => 'C级管理员', 'D' => 'D级管理员', 'E' => 'E级管理员',
            'F' => 'F级管理员', 'G' => 'G级管理员', 'H' => 'H级管理员', 'I' => 'I级管理员', 'J' => 'J级管理员', 'K' => 'K级管理员', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }

    public function getCustomerLevel($key='') {
        $data = array('F' => 'F级管理员', 'G' => 'G级管理员' );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }

    public function getIs_default($key='') {
        $data = array(1 => '是', 2 => '否', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }

    public function getIs_customer($key='') {
        $data = array(1 => '是', 2 => '否', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }
}
