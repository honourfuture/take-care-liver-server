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

    //管理员登录(密码md5加密)
    public function admin_check($user_name, $password)
    {
        $this->db->select('id,user_name,level');
        $this->db->where('user_name', $user_name);
        $this->db->where('password', md5($password));
        $query = $this->db->get($this->tableName);

        $admin_detail = $query->row_array();

        return $admin_detail;
    }


    /*
	* 查找
	*/
    function find($id)
    {
        $query = $this->db->where('id', $id);
        $query = $this->db->get($this->tableName);

        return $query->row();

    }

    /*
    * 创建
    */
    function create($values)
    {
        $data = array(
            'id'         => NULL,
            'user_name'  => $values['user_name'],
            'password'   => $values['password'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert($this->tableName, $data);

        return $this->db->insert_id();
    }

    /*
    * 编辑
    */
    function update($id, $values)
    {
        $data = array(
            'user_name'   => $values['user_name'],
            'password' => $values['password'],
            'updated_at'  => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $id);
        $this->db->update($this->tableName, $data);
    }
}
