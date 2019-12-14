<?php
/**
 * Created by PhpStorm.
 * User: joy
 * Date: 2018/8/19
 * Time: 上午9:01
 */

class Cash_out_model extends CI_Model
{
    private $table = 'cash_out';

    private $joinTable = 'cash_out as co';

    private function _select()
    {
        return  $select = array(
            'id',
            'card_number',
            'card_name',
            'bank_name',
            'pic'
        );
    }
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $data['create_at'] = date('Y-m-d H:i:s');
        $data['update_at'] =date('Y-m-d H:i:s');

        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * @param $wheres
     * @return mixed
     */
    public function get($wheres)
    {
        $select = $this->_select();

        $this->db->select($select);
        foreach ($wheres as $filed => $where) {
            $this->db->where($filed, $where);
        }

        $this->db->from($this->table);
        $this->db->order_by("create_at", "DESC");
        $query = $this->db->get();

        return $query->result_array();
    }

    /**
     * @param int $num
     * @param int $offset
     * @param string $keyword
     * @return mixed
     */
    public function getAll($num = 30, $offset = 0, $keyword = '',$status,$start_date ,$end_date)
    {
        $select = array(
            'co.cash_out_money',
            'u.username',
            'u.real_name',
            'cb.bank_name',
            'cb.card_number',
            'cb.card_name',
            'cb.phone',
            'co.apply_time',
            'co.id',
            'co.type',
            'co.status'
        );
        $this->db->select($select);

        if (!empty($keyword)) {
            $this->db->like('u.username', $keyword, 'both');
        }
        if($start_date){
            $this->db->where('co.apply_time>=', $start_date);
        }
        if($end_date){
            $this->db->where('co.apply_time<=', $end_date);
        }


        $this->db->from($this->joinTable);
        $this->db->join('card_banks as cb', 'cb.id = co.card_bank_id', 'left');
        $this->db->join('users as u', 'u.id = co.user_id', 'left');
        if($num && $offset) {
            $this->db->limit($num, $offset);
        }
        $this->db->order_by("apply_time", "DESC");
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * @param $keyword
     * @return mixed
     */
    public function getCount($keyword,$status,$start_date ,$end_date)
    {
        $this->db->select("*");

        if (!empty($keyword)) {
            $this->db->like('u.username', $keyword, 'both');
        }
        if(!is_null($status) && ($status == 0 || $status == 1 || $status == 2)){
            $this->db->where('co.status', $status);
        }
        if($start_date){
            $this->db->where('co.apply_time>=', $start_date);
        }
        if($end_date){
            $this->db->where('co.apply_time<=', $end_date);
        }
        $this->db->from($this->joinTable);
        $this->db->join('card_banks as cb', 'cb.id = co.card_bank_id', 'left');
        $this->db->join('users as u', 'u.id = co.user_id', 'left');

        $total = $this->db->count_all_results();
        return $total;
    }
    public function findByAttributes($wheres = array())  {

        foreach ($wheres as $filed => $where) {
            $this->db->where($filed, $where);
        }
        $this->db->from($this->table);
        $query = $this->db->limit(1)->get();
        return $query->row_array();
    }

    public function find($id)
    {
        $select = $this->_select();

        $this->db->select($select);
        $query = $this->db->from($this->table)->where(array('id' => $id))->get();
        return $query->row_array();
    }

    function update($id, $data)
    {
        $this->db->where('id', $id);

        $data['update_at'] = date('Y-m-d H:i:s');

        return $this->db->update($this->table, $data);
    }

}