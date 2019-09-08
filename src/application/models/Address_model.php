<?php

/**
 *  Admin Model
 *
 **/
class Address_model extends CI_Model
{
    private $table = 'address';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function myAddress($id)
    {
        $this->db->select('id, address, name, phone, is_default');
        $this->db->from($this->table);
        $this->db->where("user_id",$id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function create($data)
    {
        if($data['is_default']){
            $this->userUpdate($data['user_id'], ['is_default' => 0]);
        }
        $data['created_at'] = date('Y-m-d H:i:s');

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
        if($data['is_default']){
            $this->userUpdate($data['user_id'], ['is_default' => 0]);
        }

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    /*
       * 创建
       */

    function userUpdate($id, $data)
    {

        $this->db->where('user_id', $id);
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
    * 删除
    */

    function find($id)
    {
        $query = $this->db->where('id', $id);
        $query = $this->db->get($this->table);

        return $query->row();

    }
}