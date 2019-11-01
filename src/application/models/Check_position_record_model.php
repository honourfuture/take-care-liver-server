<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * check_position_record 模型
 */
class Check_position_record_model extends Base_model {
    private $_name='check_position_record';

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }

}
