<?php

/**
 *  Admin Model
 *
 **/
class Hospital_model extends Base_model
{
    private $table = 'hospitals';

    public function getAllByCid( $page, $offset)
    {
        $this->db->select('id,name,telphone,position,pic,detail,distance');
        $this->db->from($this->table);
        $this->db->order_by("create_time", "DESC");
        $this->db->limit($page, $offset);
        $query = $this->db->get();

        return $query->result();
    }

    public function getAllByPosi( $page, $offset,$longitude='', $latitude='',$id=0)
    {
        $sql = "select hs.id,hs.name,hs.telphone,hs.position,hs.pic,hs.detail,hs.business_type,hs.create_time,hs.distance1 as distance,hs.longitude,hs.latitude ".
            " from (select info.*, convert((st_distance(point(info.longitude,info.latitude),".
            " point(?,?))*111195)/1000,decimal(10,2) ) as distance1 ".
            " from hospitals info ) hs ";
        if($page && $offset){
            $sql = $sql." limit ".$offset.",".$page;
        }
        if($id){
            $sql = $sql." where hs.id =?";
        }
        $sql = $sql. "order by hs.distance asc";
        $param = array();
        if(!empty($id)){
            $param = array($longitude, $latitude,$id );
        }else{
            $param = array($longitude, $latitude);
        }

        $query = $this->db->query($sql, $param);
        return $query->result_array();
    }

    public function __construct()
    {
        parent::__construct();
        $this->tableName = $this->table;
        $this->load->database();
    }

    function create($data)
    {
        $this->db->insert($this->table, $data);

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
    * 查找
    */
    function getAll($num = 30, $offset = 0)
    {
        $this->db->select('*');

        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        if($num && $offset){
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get();

        return $query->result();
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

    public function getBusiness_type($key='') {
        $data = array(1 => '周一至周五 09:00-18:00', 2 => '周一至周日', );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }


    public function getFinance( $page, $offset, $from_date ='', $end_date ='', $is_count=0, $keyword = '', $hospitalId = 0)
    {
        if($is_count){
            $this->db->select('cpr.id');
        }else{
            $this->db->select('h.id as hospital_id, h.name , cp.id as cp_id, cp.check_postion, cpr.user_id, cpr.date, cpr.money');
        }
        $this->db->from('check_position_record as cpr');
        $this->db->join('check_postion as cp', 'cpr.check_position_id = cp.id', 'left');
        $this->db->join($this->table." as h", 'h.id = cp.hospital_id', 'left');

        if (!empty($keyword)) {
            $this->db->like('h.name', $keyword, 'both');
//            $this->db->like('cp.check_position', $keyword, 'both');
        }
        if($hospitalId){
            $this->db->where('cp.hospital_id', $hospitalId);
        }
        if($from_date){
            $this->db->where('cpr.date >=', $from_date);
        }
        if($end_date){
            $this->db->where('cpr.date <=', $end_date);
        }
        if($is_count){
            $total = $this->db->count_all_results();
            return $total;
        }else{
            $this->db->order_by("cpr.create_time", "DESC");
            if($page && $offset){
                $this->db->limit($page, $offset);
            }
            $query = $this->db->get();
            return $query->result();
        }

    }
}