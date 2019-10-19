<?php
/**
 * Created by PhpStorm.
 * User: qinyong
 * Date: 2018/8/25
 * Time: 上午9:11
 */

class Information_model extends CI_Model
{

    private $table = 'information';
    private $table_paylog = 'order_paylog';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    //总数
    public function get_information_count()
    {
        $this->db->select('id');
        $this->db->from($this->table);
        $total = $this->db->count_all_results();
        return $total;
    }

    /**
     * 查询一定范围内的信息
     * $longitude 经度
     * $latitude 纬度
     * $radius 半径
     *
     */
    public function search_infos($longitude='', $latitude='', $radius=0, $keywords ='')
    {
        $sql = "select inf.*,usr.username,usr.mobile,usr.head_pic,'' as first_pic, ".
            " case when inf.reden_radius = 0 then '1' when inf.distance < inf.reden_radius then '1' else '0' end as can_get_reden ".
            " from (select info.*, convert((st_distance(point(info.longitude,info.latitude),".
            " point(?,?))*111195)/1000,decimal(10,2) ) as distance ".
            " from information info where status = 2 and audit_status = 2 ";
        $sql1 = " and (info.title like '%".$this->db->escape_like_str($keywords)."%' or  info.content like '%".$this->db->escape_like_str($keywords)."%' )";
        $sql2 = " and (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(info.publish_at))< (info.indate*3600)) inf ".
            " left join users usr on inf.user_id = usr.id where inf.distance <= ? ".
            " order by inf.distance desc";
        if(!empty($keywords)){
            $sql = $sql . $sql1. $sql2;
        }else{
            $sql = $sql . $sql2;
        }
        $param = array($longitude, $latitude, $radius);
        $query = $this->db->query($sql, $param);
        return $query->result_array();
    }

    //用户的信息列表
    public function information_user_list($user_id=0, $num = 15, $offset = 0, $keywords ='')
    {
        $this->db->select($this->table.'.*');
        $this->db->from($this->table);
        //$this->db->join('users', $this->table.'.user_id = users.id');
        if($user_id){
            $this->db->where('user_id', $user_id);
        }
        if($keywords){
            $this->db->like('title', $keywords, 'both');
            $this->db->or_like('content', $keywords, 'both');
        }
        $this->db->where('status', 2);//已发布
        $this->db->where('audit_status', 2);//审批通过
        $this->db->order_by($this->table.'.id', 'desc');
        if($offset){
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get();
        $list = $query->result_array();

        return $list;
    }

    //总数
    public function get_user_information_count($user_id=0, $status = 0, $keywords ='', $is_delete = 0)
    {
        $this->db->select('id');
        $this->db->from($this->table);
        if($user_id){
            $this->db->where('user_id', $user_id);
        }
        if($status){
            $this->db->where('status', $status);
        }
        if($is_delete){
            $this->db->where('is_delete', $is_delete);
        }
        if($keywords){
            $this->db->like('title', $keywords, both);
            $this->db->orlike('content', $keywords, both);
        }
        $total = $this->db->count_all_results();
        return $total;
    }

    //所有数据列表
    public function information_list($num = 15, $offset = 0)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();
        $list = $query->result_array();
        return $list;
    }

    //总数
    public function getCount($keyword = '', $status=0, $audit_status=0, $is_delete=null, $phone='', $is_reden=null, $is_coupon=null)
    {
        $this->db->select('id');

        if (!empty($keyword)) {
            $this->db->like('title', $keyword, 'both');
            //$this->db->or_like('content', $keyword, 'both');
        }

        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        if (!empty($audit_status)) {
            $this->db->where('audit_status', $audit_status);
        }

        if ($is_delete === 1 || $is_delete === 0) {
            $this->db->where('is_delete', $is_delete);
        }

        if (!empty($phone)) {
            $this->db->like('phone', $phone, 'both');
        }

        if ($is_reden === 1 || $is_reden === 0) {
            $this->db->where('is_reden', $is_reden);
        }

        if ($is_coupon === 1 || $is_coupon === 0) {
            $this->db->where('is_coupon', $is_coupon);
        }

        $this->db->from($this->table);
        $total = $this->db->count_all_results();
        return $total;
    }

    /*
    * 查找
    */
    function getAll($num = 30, $offset = 0, $keyword = '', $status=0, $audit_status=0, $is_delete=null, $phone='', $is_reden=null, $is_coupon=null)
    {
        $this->db->select('*');

        if (!empty($keyword)) {
            $this->db->like('title', $keyword, 'both');
            //$this->db->or_like('content', $keyword, 'both');
        }

        if (!empty($status)) {
            $this->db->where('status', $status);
        }

        if (!empty($audit_status)) {
            $this->db->where('audit_status', $audit_status);
        }

        if ($is_delete === '1' || $is_delete === '0') {
            $this->db->where('is_delete', $is_delete);
        }

        if (!empty($phone)) {
            $this->db->like('phone', $phone, 'both');
        }

        if ($is_reden === '1' || $is_reden === '0') {
            $this->db->where('is_reden', $is_reden);
        }

        if ($is_coupon === '1' || $is_coupon === '0') {
            $this->db->where('is_coupon', $is_coupon);
        }

        $this->db->from($this->table);
        $this->db->order_by('is_delete', 'asc');
        $this->db->order_by('id', 'desc');
        $this->db->limit($num, $offset);
        $query = $this->db->get();

        return $query->result();
    }

    /*
    * 查找
    */
    function find($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);

        return $query->row();

    }

    /*
    * 根据out_trade_no查找
    */
    function find_by_out_trade_no($out_trade_no ='')
    {
        $this->db->where('out_trade_no', $out_trade_no);
        $query = $this->db->get($this->table);
        return $query->row();

    }

    /**
     * 批量插入
     * @param $data
     */
    function insert_batch($data) {
        return $this->db->insert_batch($this->table, $data);
    }

    /*
    * 创建
    */
    function create($values=array())
    {
      /*  $data = array(
            'id'         => NULL,
            'name'  => $values['name'],
            'mac'   => $values['mac'],
            'probes_count'   => $values['probes_count'],
            'is_wifi'   => $values['is_wifi'],
            'is_channel'   => $values['is_channel'],
            'is_disabled'   => $values['is_disabled'],
            'updated_at' => date('Y-m-d H:i:s'),
            'create_at' => date('Y-m-d H:i:s')
        );*/
        $values['create_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $values);

        return $this->db->insert_id();
    }

    /*
    * 编辑
    */
    function update($id=0, $data=array())
    {
        $data['update_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /*
  * 编辑
  */
    function update_by_out_trade_no($out_trade_no='', $data=array())
    {
        $data['update_at'] = date('Y-m-d H:i:s');
        $this->db->where('out_trade_no', $out_trade_no);
        return $this->db->update($this->table, $data);
    }

    /*
    * 删除
    */
    function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }

    /**
     * 根据属性获取一行记录
     * @param array $where
     * @return array 返回一维数组，未找到记录则返回空数组
     */
    public function findByAttributes($where = array())  {
        $query = $this->db->from($this->table)->where($where)->limit(1)->get();
        return $query->row_array();
    }

    /***
     * 保存支付回调日志
     * @param $data
     * @param $order_id
     */
    public function save_pay_log($logdata){
        $this->db->insert($this->table_paylog, $logdata);//保存订单商品
        return $this->db->insert_id();
    }

    /***
     * 更新支付回调日志最终状态
     * @param $data
     * @param $order_id
     */
    public function update_pay_log($upd_data, $log_id){
        $this->db->where('id', $log_id);
        $this->db->update($this->table_paylog, $upd_data);
    }

    /**
     * 查询用户所有的信息数组
     * @param int $user_id
     * @return mixed
     */
    public function find_all_by_user($user_id=0){
        //先查询当前用户的所有信息
        $this->db->select('id');
        $this->db->from('information');
        $this->db->where('user_id', $user_id);
        $query1 = $this->db->get();
        $info_ids = $query1->result_array();
        $array = array();
        foreach($info_ids as $value){
            $array[] = $value['id'];
        }
        return $array;
    }

    //查询某个信息的优惠券信息
    public function findCoupon($info_id)
    {
        $select = [
            'info.id',
            'info.title',
            'info.coupon_title',
            'info.coupon_content',
            'info.coupon_count',
            'info.coupon_rule',
            'info.coupon_remain_count',
            'info.coupon_use_count',
            'info.coupon_start',
            'info.coupon_end',
            'info.create_at',
            'info.terminal_at',
            'u.name',
            'u.head_pic'
        ];
        $this->db->select($select);
        $this->db->from($this->table.' as info');
        $this->db->join('users as u', 'u.id = info.user_id', 'left');
        $this->db->where('info.id', $info_id);
        //$this->db->limit(1);
        //$this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->row();
    }

    //查询某个信息的红包信息
    public function findReden($info_id)
    {
        $select = [
            'info.id',
            'info.title',
            'info.reden_amount',
            'info.reden_count',
            'info.reden_remain_amount',
            'info.reden_remain_count',
            'info.create_at',
            'info.terminal_at',
            'info.reden_coupon_id',
            'u.username as nick_name',
            'u.head_pic',
            'status'
        ];
        $this->db->select($select);
        $this->db->from($this->table.' as info');
        $this->db->join('users as u', 'u.id = info.user_id', 'left');
        $this->db->where('info.id', $info_id);
        //$this->db->limit(1);
        //$this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->row();
    }


    //查询已发布并审核通过的信息
    public function get_invalid_info($user_id=0)
    {
        $this->db->select($this->table.'.*');
        $this->db->from($this->table);
        $this->db->where('status', 2);//已发布
        $this->db->where('user_id', $user_id);
        $this->db->where('audit_status', 2);//审批通过
        $this->db->order_by($this->table.'.id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }


    /**
     * 查询所有超过有效期的信息
     */
    public function search_no_valid()
    {
        $sql = "select inf.* from information inf where inf.status = 2 and inf.audit_status = 2 ".
            "and (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(inf.publish_at))> (inf.indate*3600)";
        $query = $this->db->query($sql,  array());
        return $query->result_array();
    }

    /**
     * 终止信息
     * @param null $info
     * @return bool
     */
    public function terminal_info($info= null){
        if(empty($info)){
            return false;
        }
        //连接redis数据库
        $redis = new Redis();
        $redis->connect(config_item('redis')['host'],config_item('redis')['port']);

        //更新信息状态
        $info_data = array(
            'status'  => 3,//已终止
            'terminal_at'  =>date('Y-m-d H:i:s'),
        );
        if(!$this->Information_model->update( $info->id ,$info_data)){
            $this->db->trans_rollback();
            return false;
        }

        //是否红包推广，如果是删除红包相关
        if($info->is_reden == 1){
            $queue_key = 'REDEN_QUEUE_'.$info->out_trade_no;
            //删除队列
            $count = $redis->del($queue_key);
            $this->db->trans_begin();
            //已派送红包金额
            $renden_use_amount = bcsub($info->reden_amount ,$info->reden_remain_amount, 2);
            //返回余额的钱
            $balance_new = $info->reden_remain_amount;
            if(!empty($info->reden_coupon_id) && !empty($info->reden_coupon_amount)){
                //如果使用了红包券
                if(bccomp($info->reden_coupon_amount,$renden_use_amount,2)>0){
                    //扣除红包券金额
                    $remain =  bcsub($info->reden_coupon_amount ,$renden_use_amount, 2);
                    if(bccomp($remain,0.00,2)<=0){
                        $remain =  0.00;
                    }
                    $balance_new = bcsub($info->reden_remain_amount ,$remain, 2);
                }
            }
            $CI=&get_instance();
            $CI->load->model('User_model');
            //更新用户余额并记录余额变更明细
            $flag = $CI->User_model->update_balance($info->user_id, $balance_new, 'reden_return', $info->id, 'minus');
            if(!$flag ){
                $this->db->trans_rollback();
                return false;
            }
        }
        //是否优惠券推广
        if($info->is_coupon == 1){
            $coupon_key = 'COUPON_COUNT_'.$info->out_trade_no;
            $redis->del($coupon_key);
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }
}