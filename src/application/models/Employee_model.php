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
    public function admin_check($user_name, $password, $is_customer)
    {
        $this->db->select('id,user_name,level');
        $this->db->where('user_name', $user_name);
        if(!empty($is_customer)){
            $this->db->where('is_customer', $is_customer);
        }
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

    //查询K级管理员id数组
    public function get_k_ids($level='', $employee_id=0)
    {
        $result = array();
        if(empty($level) || empty($employee_id)){
            return $result;
        }
        //$int= chr($int);
        $indexStrat = ord($level);
        $indexEnd = ord("K");

        $sql1= "";
        $prexx_last = "t";
        for($i =0;$i<($indexEnd-$indexStrat);$i++){
            $prexx = "t".($i+1);
            $prexx_pre = "t".$i;
            if($i == 0){
                $prexx_pre = "t";
            }
            $sql1 .= " left join employee {$prexx} on {$prexx}.parent_id = {$prexx_pre}.id ";
            if($i== ($indexEnd-$indexStrat-1)){
                $prexx_last = "t".($i+1);
            }
        }
        $sql = " select {$prexx_last}.id from employee t ";
        $sql2 =  " where t.level = ? and t.id=? ";
        if(!empty($sql1)) {
            $sql = $sql.$sql1.$sql2;
        }else{
            $sql = $sql.$sql2;
        }
        $param = array($level, $employee_id);
        $query = $this->db->query($sql, $param);
        $query_result = $query->result_array();
        if(!empty($query_result) && count($query_result)>0){
            foreach($query_result as $key=>$value){
                $result[] = $value['id'];
            }
        }
        return $result;
    }
}
