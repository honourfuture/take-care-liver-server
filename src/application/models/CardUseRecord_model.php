<?php
/**
 *  体检卡使用记录
 *  CardUseRecord_model Model
 *
 **/
class CardUseRecord_model extends CI_Model
{
    private $table = 'card_use_record';
    private $grant_table = 'card_grant_record';

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
    function find($id)
    {
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
     * 使用体检卡
     * @param $user_id
     * @param $card_grand_record_id
     * @param int $status
     */
    function useCard($user_id, $card_grand_record_id, $status=1) {
        $this->db->where('id', $card_grand_record_id);;
        $data = $this->db->get($this->grant_table)->row();
        if(empty($data)) {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "请求数据不合法";
            return $res;
        }
        if(isset($data->times) && $data->times<=0) {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "当前体检卡次数已用完";
            return $res;
        }
        if(isset($data->valid_end_time) && strtotime($data->valid_end_time)<=time()) {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "该体检卡已过期";
            return $res;
        }
        $type = isset($data->type) ? $data->type : 0;
        $insertData = [];
        $insertData['type'] = $type;
        $insertData['user_id'] = $user_id;
        $insertData['card_grand_record_id'] = $card_grand_record_id;
        $insertData['status'] = $status;
        if (!empty($insertData)) {
            $data = $this->add($insertData);
            if ($data) {
                $update = $this->db->query("update card_grant_record set times=times-1 where id=$card_grand_record_id");
                if($update) {
                    $res['status'] = 200;
                    $res['data'] = $update;
                    $res['msg'] = "使用成功";
                    return $res;
                }else{
                    $res['status'] = 500;
                    $res['data'] = [];
                    $res['msg'] = "使用失败";
                    return $res;
                }
            } else {
                $res['status'] = 500;
                $res['data'] = [];
                $res['msg'] = "使用失败";
                return $res;
            }
        } else {
            $res['status'] = 500;
            $res['data'] = [];
            $res['msg'] = "使用失败";
            return $res;
        }
    }
    /*
   * 根据where和orwhere查询结果
   */
    function getGroupCountAll($where=[], $orwhere=[], $field="*") {
        $this->db->select($field);
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
        $this->db->group_by('type');//($num, $offset);
        $query = $this->db->get();
        return $query->result();
    }
}