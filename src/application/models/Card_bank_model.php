<?php
/**
 * Created by PhpStorm.
 * User: joy
 * Date: 2018/8/19
 * Time: 上午9:01
 */

class Card_bank_model extends CI_Model
{
    private $table = 'card_banks as cb';

    private $insert_table = 'card_banks';

    private function _select()
    {
        return  $select = array(
            'cb.id',
            'cb.card_number',
            'cb.card_name',
            'cb.bank_name',
            'cb.pic',
            'b.bank_icon',
            'b.bank_background',
            'cb.card_type'
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

        $this->db->insert($this->insert_table, $data);
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
        
        $this->db->join('banks as b', 'b.bank_code = cb.bank_code', 'left');
        $this->db->where('cb.is_delete', 0);
        $this->db->from($this->table);
        $this->db->order_by("cb.create_at", "DESC");
        $query = $this->db->get();

        return $query->result_array();
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

        $this->db->from($this->table)->where(['cb.id' => $id, 'cb.is_delete' => 0]);
        $this->db->join('banks as b', 'b.bank_code = cb.bank_code', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    function update($id, $data)
    {
        $this->db->where('id', $id);

        $data['update_at'] = date('Y-m-d H:i:s');

        return $this->db->update($this->insert_table, $data);
    }

}