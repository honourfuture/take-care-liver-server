<?php
/**
 * Created by PhpStorm.
 * User: joy
 * Date: 2018/8/19
 * Time: 上午9:01
 */

class Config_model extends CI_Model
{
    private $table = 'config';

    private function _select()
    {
        return  $select = array(
            'id',
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
     * @param $data
     * @return mixed
     */
    public function create_emoji($data)
    {
        $this->db->insert('test_emoji', $data);
        return $this->db->insert_id();
    }

    public function find_emoji($id)
    {
        $query = $this->db->from('test_emoji')->where(array('id' => $id))->get();
        return $query->row_array();
    }
    /**
     * @param $wheres
     * @param $select
     * @return mixed
     */
    public function get($wheres, $select)
    {
        $this->db->select($select);
        foreach ($wheres as $filed => $where) {
            $this->db->where($filed, $where);
        }
        $this->db->from($this->table);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function findByAttributes($wheres = array(), $select)  {

        $this->db->select($select);
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

        return $this->db->update($this->table, $data);
    }

}