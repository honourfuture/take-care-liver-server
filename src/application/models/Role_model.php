<?php
/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/8/19
 * Time: 上午11:38
 */

class Role_Model extends CI_Model
{
    private $table = 'admin_roles';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //角色列表
    public function role_list($num = 15, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();
        $list = $query->result_array();
        return $list;
    }

    /*
	* 查找
	*/
    function getAll($num = 30, $offset = 0, $keyword = '')
    {
        $this->db->select('*');

        if (!empty($keyword)) {
            $this->db->like('name', $keyword, 'both');
        }

        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();

        return $query->result();
    }

    /*
	* 查找
	*/
    function findAll()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
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

    //保存角色
    public function role_save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    //查找
    function find($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }

    /*
	* 创建
	*/
    function create($values)
    {
        $data = array(
            'id'         => NULL,
            'name'  => $values['name'],
            'description'   => $values['description'],
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /*
	* 编辑
	*/
    function update($id, $values)
    {
        $data = array(
            'name'   => $values['name'],
            'description' => $values['description'],
            'updated_at'  => date('Y-m-d H:i:s')
        );

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
}