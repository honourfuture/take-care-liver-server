<?php

/**
 *  Admin Model
 *
 **/
class Urine_check_model extends Base_Model
{
    private $table = 'urine_check';

    public function getAllByCid()
    {
        $this->db->select('id,color');

        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();

        return $query->result();
    }
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->tableName = $this->table;
    }

    public function getList()
    {
        $this->db->select([
            'id',
            'color'
        ]);
        $this->db->from($this->table);
        $this->db->order_by("create_time", "DESC");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function getFind($id)
    {
        $this->db->select([
            'uu.id',
            'uu.date',
            'uc.color',
            'uc.summary',
            'uc.details',
        ]);

        $this->db->join('urine_check as uc', 'uc.id = uu.urine_check_id', 'left');
        $this->db->where('uu.user_id', $id);
        $this->db->from($this->table);

        $query = $this->db->get();

        return $query->row();
    }

    function create($data)
    {

        $data['created_at'] = date('Y-m-d H:i:s');

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
        $this->db->limit($num, $offset);
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

    public function getSummary($key='') {
        #f6e3dd:尿糖偏高、尿液偏碱|#fceeeb:尿糖偏高1|#f7eedf:尿糖偏高2|#f3e64a:尿糖偏高3|#e5e9ec:尿糖偏高4|#fb9c30:尿糖偏高5|#58e1ee:尿糖偏高6|#eee181:尿糖偏高7
        $data = array(
            "#f6e3dd" => '尿糖偏高、尿液偏碱',
            "#fceeeb" => '尿糖偏高1',
            "#f7eedf" => '尿糖偏高2',
            "#f3e64a" => '尿糖偏高3',
            "#e5e9ec" => '尿糖偏高4',
            "#fb9c30" => '尿糖偏高5',
            "#58e1ee" => '尿糖偏高6',
            "#eee181" => '尿糖偏高7',
        );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }

    public function getWaring_type($key='') {
        $data = array(
            1 => '#17C419',
            2 => '#FD7925',
            3 => '#FC2F24',
        );

        if ($key !== '') {
            return $data[$key];
        } else {
            return $data;
        }
    }


}