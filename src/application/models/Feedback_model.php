<?php

/**
 *  Admin Model
 *
 **/
class Feedback_model extends CI_Model
{
    private $table = 'feedback';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function myFeedback($id)
    {
        $this->db->select('id,q,a,created_at,replay_at');

        $this->db->from($this->table);
        $this->db->where("uid",$id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
    }

    function create($data)
    {

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
    * 删除
    */

    function find($id)
    {
        $query = $this->db->where('id', $id);
        $query = $this->db->get($this->table);

        return $query->row();
    }
}