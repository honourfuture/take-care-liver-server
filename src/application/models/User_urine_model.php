<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * user_urine 模型
 */
class User_urine_model extends Base_model {
    private $_name='user_urine';
    private $join_name='user_urine si';

    public function __construct() {
        parent::__construct();
        $this->tableName = $this->_name;
    }
   
    public function getType($key='') {
        $data = array(1 => '尿检', 2 => '肝检', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }


    /**
     * 获取全部结果
     * @param type $param
     * @param type $limit
     * @param type $start
     * @param type $orderBy
     * @param type $inParams
     * @param type $likeParam
     * @param type $orParams
     * @return type
     */
    public function getList($param = array(), $limit = 0, $start = 0, $orderBy = 'id DESC', $likeParam =array()) {

        $this->db->select("si.*,u.username,u.mobile");
        $this->db->from($this->join_name);
        $this->db->where($param);
        $this->db->order_by($orderBy);
        $this->db->join("users u","si.user_id = u.id","left");

        //limit组合
        if($limit && $start) {
            $this->db->limit($limit, $start);
        } elseif($limit) {
            $this->db->limit($limit);
        }

        //like数组
        if($likeParam != null) {
            $count = 0;
            foreach ($likeParam as $key => $value) {
                if($count == 0){
                    $this->db->like($key, $value);
                }else{
                    $this->db->or_like($key, $value);
                }
                $count++;
            }
        }

        //获取结果
        $query = $this->db->get();
        $result = $query->result_array();

        //echo $this->db->last_query();
        return $result;
    }
}
