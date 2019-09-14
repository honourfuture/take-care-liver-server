<?php
/**
 *  订单与支付相关的模型操作
 *  OrderAndPay_model Model
 *
 **/
class OrderAndPay_model extends CI_Model
{
    private $order_table = 'order';
    private $order_join_table = 'order as o';
    private $pay_table = 'pay';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    /*
    * 根据where和orwhere查询支付数据总数
    */
    public function getPayCount($where=[], $orwhere=[]) {
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
        $this->db->from($this->pay_table);
        $total = $this->db->count_all_results();
        return $total;
    }

    /*
	* 查找
	*/
    function getAll($num=30, $offset=0, $keyword='', $wheres=[])
    {
        $this->db->select(['o.id','o.order_no','u.username','o.status','o.create_time', 'o.products_title','o.price','u.mobile']);

        if(!empty($keyword)){
            $this->db->or_like('u.mobile',$keyword,'both');
            $this->db->or_like('u.username',$keyword,'both');
        }
        $this->db->join('users as u', 'u.id = o.user_id', 'left');
        $this->db->from($this->order_join_table);

        if (!empty($wheres) && is_array($wheres)) {
            foreach($wheres as $k=>$val) {
                if(!is_array($val) && !is_object($val)) {
                    $this->db->where($k, $val);
                }
            }
        }

        $this->db->order_by('o.id','desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();

        return $query->result();
    }
    /*
    * 根据where和orwhere查询订单数据总数
    */
    public function getOrderCount($where=[], $orwhere=[]) {
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
        $this->db->from($this->order_table);
        $total = $this->db->count_all_results();
        return $total;
    }
    /*
    * 根据where和orwhere查询支付结果
    */
    function getPayAll($where=[], $orwhere=[], $num = 30, $offset = 0) {
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
        $this->db->from($this->pay_table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();
        return $query->result();
    }
    /*
    * 根据where和orwhere查询订单结果
    */
    function getOrderAll($where=[], $orwhere=[], $num = 30, $offset = 0) {
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
        $this->db->from($this->order_table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();
        return $query->result();
    }


    //总数
    public function getCount($keyword='', $wheres=[])
    {
        $this->db->select('o.id');

        if(!empty($keyword)){
            $this->db->or_like('u.mobile', $keyword, 'both');
            $this->db->or_like('u.username', $keyword, 'both');
        }

        if (!empty($wheres) && is_array($wheres)) {
            foreach($wheres as $k=>$val) {
                if(!is_array($val) && !is_object($val)) {
                    $this->db->where($k, $val);
                }
            }
        }

        $this->db->from($this->order_join_table);

        $total = $this->db->count_all_results();
        return $total;
    }

    /*
    * 添加订单记录
    */
    function addOrder($data) {
        $this->db->insert($this->order_table, $data);
        return $this->db->insert_id();
    }
    /*
    * 添加支付记录
    */
    function addPay($data) {
        $this->db->insert($this->pay_table, $data);
        return $this->db->insert_id();
    }
    /*
    * 更新订单详情数据
    */
    function updateOrderInfo($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->order_table, $data);
    }
    /*
    * 更新支付详情数据
    */
    function updatePayInfo($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->pay_table, $data);
    }
    /*
    * 删除订单详情数据
    */
    function deleteOrderInfo($id) {
        return $this->db->delete($this->order_table, array('id' => $id));
    }
    /*
    * 删除支付详情数据
    */
    function deletePayInfo($id) {
        return $this->db->delete($this->pay_table, array('id' => $id));
    }
    /*
    * 根据id查询订单详情指定数据
    */
    function findOrderInfo($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->order_table);
        return $query->row();
    }
    /*
    * 根据id查询支付详情指定数据
    */
    function findPayInfo($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->pay_table);
        return $query->row();
    }
    /*
    * 根据id查询指定订单详情数据
    */
    function findOrderInfoByParams($where) {
        if (!empty($where) && is_array($where)) {
            foreach($where as $k=>$val) {
                if(!is_array($val) && !is_object($val)) {
                    $this->db->where($k, $val);
                }
            }
            $query = $this->db->get($this->order_table);
            return $query->row();
        }
        return [];
    }
    /*
    * 根据id查询指定支付详情数据
    */
    function findPayInfoByParams($where) {
		if (!empty($where) && is_array($where)) {
			foreach($where as $k=>$val) {
				if(!is_array($val) && !is_object($val)) {
					$this->db->where($k, $val);
				}
			}
			$query = $this->db->get($this->pay_table);
			return $query->row();
        }
		return [];
    }
}