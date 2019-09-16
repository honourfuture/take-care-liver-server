<?php

/**
 *  Admin Model
 *
 **/
class Product_model extends Base_Model
{
    private $table = 'products';

    public function getAllByCid($wheres, $page, $offset)
    {
        $this->db->select('id,name,price,details,describe,old_price,pic,banner_pic');

        foreach ($wheres as $filed => $where) {
            $this->db->where($filed, $where);
        }

        $this->db->from($this->table);

        $this->db->order_by("create_time", "DESC");

        $this->db->limit($page, $offset);

        $query = $this->db->get();

        return $query->result();
    }

    public function getCount($wheres)
    {
        $this->db->select('id');

        foreach ($wheres as $filed => $where) {
            $this->db->where($filed, $where);
        }

        $this->db->from($this->table);
        $total = $this->db->count_all_results();

        return $total;
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tableName = $this->table;
    }

    function create($data)
    {

        $data['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
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