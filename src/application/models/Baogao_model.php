<?php

/**
 *  Admin Model
 *
 **/
class Baogao_model extends CI_Model
{
    private $table = 'baogao';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllData($user_id=0, $offset=0, $limit=10)
    {
        $this->db->select('id,user_id,check_result,check_range_min,check_range_max');
        if($user_id) {
            $this->db->where('user_id',$user_id);
        }
        $this->db->limit($offset,$limit);
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    //总数

    public function getCount($keyword = '')
    {
        $this->db->select('id');

        if (!empty($keyword)) {
            $this->db->like('name', $keyword, 'both');
        }

        $this->db->from($this->table);
        $total = $this->db->count_all_results();

        return $total;
    }

    /*
    * 查找
    */
    function getAll($num = 30, $offset = 0)
    {
        $this->db->select('*');

        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();

        return $query->result();
    }

    /*
    * 创建
    */

    function update($id, $data)
    {

        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    /*
    * 编辑
    */

    function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }

    /*
    * 获取详情
    */

    function find($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);

        return $query->row();

    }
    /*
    * 获取详情
    */

    function findByUserId($id, $user_id)
    {
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->table);
        return $query->row();
    }
}