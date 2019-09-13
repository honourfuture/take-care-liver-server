<?php

/**
 *  Admin Model
 *
 **/
class Admin_Model extends CI_Model
{
	private $table = 'admin_users';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	//管理员登录(密码md5加密)
	public function admin_check($user_name, $password)
	{
		$this->db->select('id,user_name');
		$this->db->where('user_name', $user_name);
		$this->db->where('password', md5($password));
		$query = $this->db->get($this->table);

		$admin_detail = $query->row_array();

		return $admin_detail;
	}

	//管理员总数
	public function get_admin_count()
	{
		$this->db->select('id');
		$this->db->from($this->table);

		$total = $this->db->count_all_results();

		return $total;
	}

	//管理员列表
	public function admin_list($num = 15, $offset = 0)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->order_by('id', 'desc');
		$this->db->limit($num, $offset);
		$query = $this->db->get();

		$list = $query->result_array();

		return $list;
	}

	//保存管理员账号
	public function admin_save($user_name, $password)
	{
		$data = array(
			'user_name' => $user_name,
			'password'  => md5($password),
		);

		$this->db->insert($this->table, $data);

		return $this->db->insert_id();
	}

	//总数
	public function getCount($keyword = '')
	{
		$this->db->select('id');

		if (!empty($keyword)) {
			$this->db->like('user_name', $keyword, 'both');
		}

		$this->db->from($this->table);

		$total = $this->db->count_all_results();

		return $total;
	}

	/*
	* 查找
	*/
	function getAll($num = 30, $offset = 0, $keyword = '')
	{
		$this->db->select('*');

		if (!empty($keyword)) {
			$this->db->like('user_name', $keyword, 'both');
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
			'user_name'  => $values['user_name'],
			'password'   => $values['password'],
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
			'user_name'   => $values['user_name'],
			'password' => $values['password'],
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


    /**
     * check user privilege
     * @access public
     * @param $admin_id
     * @param String $action privilege action
     * @return false or true
     */
    function checkUserPrivilege($admin_id, $action)
    {
        $sql = "select * from admin_user_role, admin_roles, admin_role_permission, admin_permission
				where admin_user_role.user_id = ? and
				admin_roles.id = admin_user_role.role_id AND 
				admin_roles.id = admin_role_permission.role_id and
				admin_role_permission.permission_id = admin_permission.id and
				admin_permission.url = ?";

        $query = $this->db->query($sql, array($admin_id, $action));
        if($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

?>