<?php
/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/10/8
 * Time: 下午5:24
 */

class Admin_user_role_model extends CI_Model
{
    private $table = 'admin_user_role';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 根据管理员ID查询角色信息
     * @param $admin_id
     */
    public function find_by_admin_id($admin_id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('user_id', $admin_id);
        $query = $this->db->get();

        return $query->result();
    }

    public function delete_by_user_id($user_id)
    {
        return $this->db->delete($this->table, array('user_id' => $user_id));
    }

    public function create($values) {
        $data = array(
            'id'         => NULL,
            'user_id'  => $values['user_id'],
            'role_id'   => $values['role_id']
        );
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
}