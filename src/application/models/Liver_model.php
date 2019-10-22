<?php

/**
 *  Admin Model
 *
 **/
class Liver_model extends CI_Model
{
    private $table = 'liver';

    public function getAllByCid()
    {
        $this->db->select('*');

        $this->db->from($this->table);
        $this->db->where('is_zip', 0);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
    }
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getFind($id)
    {
        $this->db->select([
            'uu.id',
            'uu.date',
            'uc.color',
            'uc.waring_type',
            'uc.summary',
            'uc.details',
            'u.username',
            'u.mobile'
        ]);

        $this->db->join('urine_check as uc', 'uc.id = uu.urine_check_id', 'left');
        $this->db->join('users as u', 'uu.user_id = u.id', 'left');
        $this->db->where('uu.id', $id);
        $this->db->from($this->table);

        $query = $this->db->get();

        return $query->row();
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
    * 更新
    */

    function update($id, $data)
    {

        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    /*
    * 删除
    */

    function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }

    /*
    * 查询
    */
    function find($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);

        return $query->row();
    }
}