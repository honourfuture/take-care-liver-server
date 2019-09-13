<?php
/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/8/19
 * Time: 下午7:32
 */

class Role_permission_model extends Base_Model
{
    private $table = 'admin_role_permission';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tableName = $this->table;
    }

    /**
     * 根据roleid查询角色权限信息
     */
    public function find_by_roleid($role_id) {
        $this->db->select('permission_id');
        $this->db->from($this->table);
        $this->db->where('role_id', $role_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_by_role_id($role_id) {
        return $this->db->delete($this->table, array('role_id' => $role_id));
    }

    public function create($values) {
        $data = array(
            'id'         => NULL,
            'role_id'  => $values['role_id'],
            'permission_id'   => $values['permission_id']
        );
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}