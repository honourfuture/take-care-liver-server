<?php
/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/8/21
 * Time: 上午11:27
 */

class Menu_model extends CI_Model
{
    private $table = 'admin_menu';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 根据id列表查询
     */
    public function find_by_ids($ids) {
        $this->db->select('id', 'name');
        $this->db->where_in('id', $ids);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    /*
	* 查找
	*/
    function getAll($keyword = '')
    {
        $this->db->select('*');

        if (!empty($keyword)) {
            $this->db->like('name', $keyword, 'both');
        }

        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
    }
}