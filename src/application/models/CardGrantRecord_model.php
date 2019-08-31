<?php
/**
 *  体检卡发放记录
 *  CardGrantRecord_model Model
 *
 **/
class CardGrantRecord_model extends CI_Model
{
    private $table = 'card_grant_record';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /*
    * 根据where和orwhere查询总数
    */
    public function getCount($where=[], $orwhere=[]) {
        $this->db->select('id');

        if (!empty($where) && is_array($where)) {
			foreach($where as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->where($k, $val);
				}
			}
        }
        if (!empty($orwhere) && is_array($orwhere)) {
			foreach($orwhere as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->or_where($k, $val);
				}
			}
        }

        $this->db->from($this->table);
        $total = $this->db->count_all_results();
        return $total;
    }
    /*
    * 根据where和orwhere查询结果
    */
    function getAll($where=[], $orwhere=[], $num = 30, $offset = 0) {
        $this->db->select('*');

        if (!empty($where) && is_array($where)) {
			foreach($where as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->where($k, $val);
				}
			}
        }
        if (!empty($orwhere) && is_array($orwhere)) {
			foreach($orwhere as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->or_where($k, $val);
				}
			}
        }
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();
        return $query->result();
    }
    /*
    * 添加
    */
    function add($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    /*
    * 更新
    */
    function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    /*
    * 删除
    */
    function delete($id) {
        return $this->db->delete($this->table, array('id' => $id));
    }
    /*
    * 根据id查询指定数据
    */
    function find($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        return $query->row();
    }
	
    /*
    * 根据id查询指定数据
    */
    function findByParams($where) {
		if (!empty($where) && is_array($where)) {
			foreach($where as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->where($k, $val);
				}
			}
			$query = $this->db->get($this->table);
			return $query->row();
        }
		return [];
    }
    /**
     * 体检卡发放
     * @param $user_id
     * @param $type
     * @param int $times
     * @param int $source
     * @return mixed
     */
    function grantCard($user_id, $type, $valid_start_time, $valid_end_time, $times=1, $source=1) {
        if(!$type || !in_array($type,[1,2])) {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "请求数据不合法";
            return $res;
        }
        if($times<=0) {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "请求数据不合法";
            return $res;
        }
        if(!$source || !in_array($source,[1,2,3])) {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "请求数据不合法";
            return $res;
        }
        if(empty($valid_start_time)) {
            $valid_start_time = date('Y-m-d H:i:s');
        }
        if(empty($valid_end_time)) {
            $valid_end_time = date("Y-m-d H:i:s",strtotime("+1years"));
        }
        if(strtotime($valid_start_time)<=0 || strtotime($valid_end_time) <= strtotime($valid_start_time)) {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "请求数据不合法";
            return $res;
        }
        if(empty($user_id)) {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "请求数据不合法";
            return $res;
        }
        $data['type'] = $type;
        $data['times'] = $times;
        $data['user_id'] = $user_id;
        $data['source'] = $source;
        $data['valid_start_time'] = date("Y-m-d H:i:s",strtotime($valid_start_time));
        $data['valid_end_time'] = date("Y-m-d H:i:s",strtotime($valid_end_time));
        if (!empty($data)) {
            $data = $this->add($data);
            if ($data) {
                $res['status'] = 0;
                $res['data'] = $data;
                $res['msg'] = "请求数据不合法";
                return $res;
            } else {
                $res['status'] = 500;
                $res['data'] = $data;
                $res['msg'] = "发放失败";
                return $res;
            }
        } else {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "发放失败";
            return $res;
        }
    }
}