<?php
/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/8/19
 * Time: 下午7:12
 */

class Permission_Model extends Base_Model
{
    private $table = 'admin_permission';
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tableName = $this->table;
    }

    //权限列表
    public function permission_list($num = 15, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();
        $list = $query->result_array();
        return $list;
    }

    public function find_by_ids($ids) {
        $this->db->select('id, name, menu_id');
        $this->db->from('admin_permission');
        $this->db->where_in('id', $ids);
        $query = $this->db->get();
        return $query->result();
    }

    public function find_by_menu_ids($menu_ids) {
        $this->db->select('id, name, menu_id');
        $this->db->from('admin_permission');
        $this->db->where_in('menu_id', $menu_ids);
        $query = $this->db->get();
        return $query->result();
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
    public function permission_save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    //查找
    function find($id)
    {
        $query = $this->db->where('id', $id);
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
            'url'   => $values['url'],
            'menu_id'   => $values['menu_id'],
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
            'url' => $values['url'],
            'menu_id'   => $values['menu_id'],
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