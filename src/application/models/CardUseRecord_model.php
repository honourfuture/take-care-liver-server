<?php
/**
 *  体检卡使用记录
 *  CardUseRecord_model Model
 *
 **/
class CardUseRecord_model extends CI_Model
{
    private $table = 'card_use_record';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /*
    * 根据where和orwhere查询总数
    */
    public function getCount($where=[], $orwhere=[]) {
        $this->db->select('id');

        if (!empty($where) && is_array($where)) {
			foreach($where as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->where($k, $val);
				}
			}
        }
        if (!empty($orwhere) && is_array($orwhere)) {
			foreach($orwhere as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->or_where($k, $val);
				}
			}
        }

        $this->db->from($this->table);
        $total = $this->db->count_all_results();
        return $total;
    }
    /*
    * 根据where和orwhere查询结果
    */
    function getAll($where=[], $orwhere=[], $num = 30, $offset = 0) {
        $this->db->select('*');

        if (!empty($where) && is_array($where)) {
			foreach($where as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->where($k, $val);
				}
			}
        }
        if (!empty($orwhere) && is_array($orwhere)) {
			foreach($orwhere as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->or_where($k, $val);
				}
			}
        }
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    /*
    * 添加
    */
    function add($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    /*
    * 更新
    */
    function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    /*
    * 删除
    */
    function delete($id) {
        return $this->db->delete($this->table, array('id' => $id));
    }
    /*
    * 根据id查询指定数据
    */
    function find($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }
	
    /*
    * 根据id查询指定数据
    */
    function findByParams($where) {
		if (!empty($where) && is_array($where)) {
			foreach($where as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->where($k, $val);
				}
			}
			$query = $this->db->get($this->table);
			return $query->row();
        }
		return [];
    }
}