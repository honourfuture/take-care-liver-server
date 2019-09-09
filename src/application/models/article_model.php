<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * example 模型
 */
class Article_model extends Base_model {
    private $_name='example';

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }
   
    public function getStatus($key='') {
        $data = array(0 => '停用', 1 => '启用', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }
}
