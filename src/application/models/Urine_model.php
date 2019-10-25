<?php

/**
 *  Admin Model
 *
 **/
class Urine_model extends CI_Model
{
    private $table = 'user_urine as uu';
    private $insertTable = 'user_urine';

    public function getAllByCid()
    {
        $this->db->select('id,name,price,details,describe,pic,banner_pic');

        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
    }
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getList($uid, $page, $offset, $type = 1)
    {
        $select = $type == 1 ? [
            'uu.id',
            'uu.date',
            'uc.color',
            'uc.waring_type',
            'uc.summary',
            'uc.details',
            'u.username',
            'u.mobile'
        ] : [
            'uu.id',
            'uc.check_date',
            'uc.info',
            'u.username',
            'u.mobile'
        ];
        $this->db->select($select);

        if($type == 1){
            $this->db->join('urine_check as uc', 'uc.id = uu.urine_check_id', 'left');
        }else{
            $this->db->join('liver as uc', 'uc.id = uu.urine_check_id', 'left');
        }

        $this->db->join('users as u', 'uu.user_id = u.id', 'left');
        $this->db->where('uu.user_id', $uid);
        $this->db->where('uu.type', $type);

        $this->db->from($this->table);

        $this->db->order_by("uu.create_time", "DESC");
        $this->db->limit($page, $offset);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getAll($uid, $type = 1)
    {
        $select = $type == 1 ? [
            'uu.id',
            'uu.date',
            'uc.color',
            'uc.waring_type',
            'uc.summary',
            'uc.details',
            'u.username',
            'u.mobile'
        ] : [
            'uu.id',
            'uc.check_date',
            'uc.info',
            'u.username',
            'u.mobile'
        ];
        $this->db->select($select);

        if($type == 1){
            $this->db->join('urine_check as uc', 'uc.id = uu.urine_check_id', 'left');
        }else{
            $this->db->join('liver as uc', 'uc.id = uu.urine_check_id', 'left');
        }

        $this->db->join('users as u', 'uu.user_id = u.id', 'left');
        $this->db->where('uu.user_id', $uid);
        $this->db->where('uu.type', $type);

        $this->db->from($this->table);

        $query = $this->db->get();

        return $query->result_array();
    }

    public function getFind($id, $type = 1)
    {
        $select = $type == 1 ? [
            'uu.id',
            'uu.date',
            'uc.color',
            'uc.waring_type',
            'uc.summary',
            'uc.details',
            'u.username',
            'u.mobile'
        ] : [
            'uu.id',
            'uc.check_date',
            'uc.info',
            'u.username',
            'u.mobile'
        ];
        $this->db->select($select);

        if($type == 1){
            $this->db->join('urine_check as uc', 'uc.id = uu.urine_check_id', 'left');
        }else{
            $this->db->join('liver as uc', 'uc.id = uu.urine_check_id', 'left');
        }

        $this->db->join('users as u', 'uu.user_id = u.id', 'left');
        $this->db->where('uu.id', $id);
        $this->db->from($this->table);

        $query = $this->db->get();

        return $query->row();
    }


    function create($data)
    {

        $this->db->insert($this->insertTable, $data);

        return $this->db->insert_id();
    }

    //总数

    public function getCount($keyword = '')
    {
        $this->db->select('id');

        if (!empty($keyword)) {
            $this->db->like('name', $keyword, 'both');
        }

        $this->db->from($this->table);
        $total = $this->db->count_all_results();

        return $total;
    }

    /*
    * 更新
    */

    function update($id, $data)
    {

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

    /*
    * 查询
    */
    function find($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);

        return $query->row();
    }
}