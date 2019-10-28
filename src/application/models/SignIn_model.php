<?php
/**
 * Created by PhpStorm.
 * User: joy
 * Date: 2018/8/19
 * Time: 上午9:01
 */

class SignIn_model extends CI_Model
{
    private $table = 'sign_in';

    private function _select()
    {
        return  $select = array(
            'id',
            'date',
            'continue',
            'is_apply'
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

    public function find($wheres)
    {
        $select = $this->_select();

        $this->db->select($select);
        $query = $this->db->from($this->table);
        foreach ($wheres as $filed => $where) {
            $this->db->where($filed, $where);
        }
        $query = $query->get();
        return $query->row_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
}