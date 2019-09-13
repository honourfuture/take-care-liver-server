<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * admin_logs 模型
 */
class Admin_logs_model extends Base_model {
    private $_name='admin_logs';

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }

}
