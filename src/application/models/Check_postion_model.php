<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * check_postion 模型
 */
class Check_postion_model extends Base_model {
    private $_name='check_postion';

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }
   
    public function getStatus($key='') {
        $data = array(0 => '禁用', 1 => '启用', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }

    function updates($ids, $data)
    {
        $this->db->where_in('id', $ids);

        return $this->db->update($this->tableName, $data);
    }

    public function find($check_postion)
    {
        $query = $this->db->where('check_postion', $check_postion);
        $query = $this->db->get($this->tableName);

        return $query->row();
    }

}
