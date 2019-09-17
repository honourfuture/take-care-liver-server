<?php

/**
 *  Admin Model
 *
 **/
class Banner_model extends Base_Model
{
    private $table = 'banner';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tableName = $this->table;
    }

    public function getShowAllData()
    {
        $this->db->select('id,picture_url,url as content');
        $this->db->where('status',1);
        $this->db->limit(5,0);
        $this->db->from($this->table);
        $this->db->order_by('sort', 'desc');
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
    * 删除
    */

    function find($id)
    {
        $this->db->select('id,name,picture_url,url as content');
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);

        return $query->row();

    }

    public function getStatus($key='') {
        $data = array("1" => '上线', "0" => '下线', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }
}