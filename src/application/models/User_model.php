<?php
/**
 * Created by PhpStorm.
 * User: OneDouble
 * Date: 2016/11/8
 * Time: 下午3:35
 */

class User_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private $table = 'users';

    private function _select()
    {
        return  $select = array(
            '*'
        );
    }
    private function getIP() {

        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknow";

        return $ip;
    }

    /*
    * 编辑
    */
    function update($id, $values)
    {
        $values['updated'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $rslt = $this->db->update('users', $values);
        return $rslt;
    }

    public function login_check($phone,$passwd)
    {
        $this->db->select('*');
        $this->db->where('mobile',$phone);
        $this->db->where('password',md5($passwd));
        $this->db->from('users');

        $res = $this->db->get();
        $first_row = $res->row_array(0);

        return $first_row["id"];//无匹配返回0，和false等效
    }

    //先检查用户名密码,然后更新最后登陆时间和IP
    public function login($phone,$passwd)
    {
        $id = $this->login_check($phone,$passwd);
        if($id)
        {
            $data = array(
                'last_login'     	=> time(),
                'last_ip_address'	=> $this->getIP()

            );

            $this->db->where('mobile', $phone);
            $this->db->update('users', $data);

        }
        return $id;
    }

    //先检查用户名密码,然后更新最后登陆时间和IP
    public function change_password($phone,$new_passwd)
    {

        $data = array(
            'updated'     		=> time(),
            'password'	=> md5($new_passwd)
        );

        $this->db->where('mobile', $phone);
        $this->db->update('users', $data);
        return true;

    }
    //注册时检查重复用户
    //@return:true 已注册过,false 未注册
    public function phone_check($phone)
    {
        $this->db->select('*');
        $this->db->where('mobile',$phone);
        $this->db->from('users');

        $total = $this->db->count_all_results();

        return $total>0;//已有此用户
    }
    //新注册用户
    public function create_user($phone,$passwd)
    {
        $data = array(
            'mobile'			=> $phone,
            'password'     		=> md5($passwd),
            'last_ip_address'	=> $this->getIP(),
            'updated'	=> time(),
            'created'	=> time()
        );

        $this->db->insert('users', $data);

        return $this->db->insert_id();
    }

    //编辑用户信息
    function update_info($id, $values)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $values);
    }

    //#########   for admin panel begin   ########

	//总数
	public function getCount($keyword='')
	{
		$this->db->select('id');

		if(!empty($keyword)){
			$this->db->like('name',$keyword,'both');
			$this->db->or_like('mobile',$keyword,'both');
			$this->db->or_like('username',$keyword,'both');
		}

        $this->db->from('users');
        
        $total = $this->db->count_all_results();
		return $total;
	}
	
	/*
	* 查找
	*/
	function getAll($num=30,$offset=0,$keyword='')
	{
		$this->db->select('*');

		if(!empty($keyword)){
			$this->db->like('name',$keyword,'both');
            $this->db->or_like('mobile',$keyword,'both');
            $this->db->or_like('username',$keyword,'both');
		}

		$this->db->from('users');
		$this->db->order_by('id','desc');
		$this->db->limit($num,$offset);
		$query = $this->db->get();

		return $query->result();
	}
	/*
	* 查找
	*/
	function find($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('users');
		return $query->row();		
	}
	/*
	* 创建
	*/
	function create($values)
	{
		/*$data = array(
			'id'			=> null,
			'name'     		=> $values['name'],
			'description'	=> $values['description'],
			'updated_at'	=> date('Y-m-d H:i:s'),
			'created_at'	=> date('Y-m-d H:i:s')
		);*/

		$this->db->insert('users', $values);

		return $this->db->insert_id();
	}
	
	/*
	* 删除
	*/
	function delete($id)
	{
		return $this->db->delete('users', array('id' => $id));
	}

    //#########   for admin panel end   ########

    /*
	* 通过手机号码查找
	*/
    function find_by_mobile($mobile)
    {
        $this->db->select('*');
        $this->db->where('mobile', $mobile);
        $query = $this->db->get('users');
        return $query->row_array();

    }

    /*
	* 查找用户乘车记录数
	*/
    function find_rides_count_by_mobile($keyword='')
    {
        if (!empty($keyword)) {
            //查询拼车和快车订单
            $sql = 'select a.id as order_id from kuaiche_orders_info a, users b where a.user_id = b.id and b.mobile = '
                .$keyword;
            $sql2 = 'select a.id as order_id from pinche_order a, users b where a.user_id = b.id and b.mobile = '
                .$keyword;
            $sql = 'select count(1) as total from('.$sql.' union '.$sql2.' ) c';
            $query = $this->db->query($sql);
            $count = $query->row()->total;
            if($count){
                return $count;
            }
        }
        return 0;
    }
    /**
     * @param $wheres
     * @return mixed
     */
    public function getAllPageTotal($wheres)
    {
        $this->db->select('*');

        if (!empty($wheres) && is_array($wheres)) {
            foreach($wheres as $k=>$val) {
                if(!is_array($val) && !is_object($val)) {
                    $this->db->where($k, $val);
                }
            }
        }

        $this->db->order_by('id', 'desc');

        $this->db->from($this->table);
        $total = $this->db->count_all_results();

        return $total;
    }
    /**
     * @param $wheres
     * @return mixed
     */
    public function getAllPage($wheres, $page, $offset)
    {
        $select = $this->_select();

        $this->db->select($select);

        if (!empty($wheres) && is_array($wheres)) {
            foreach($wheres as $k=>$val) {
                if(!is_array($val) && !is_object($val)) {
                    $this->db->where($k, $val);
                }
            }
        }
        $this->db->order_by('id', 'desc');
        $this->db->limit($page, $offset);

        $this->db->from($this->table);
        $query = $this->db->get();

        return $query->result_array();
    }
}

?>