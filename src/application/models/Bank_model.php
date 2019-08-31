<?php
/**
 * Created by PhpStorm.
 * User: joy
 * Date: 2018/8/19
 * Time: 上午9:01
 */

class Bank_model extends CI_Model
{
    private $table = 'banks';

    private function _select()
    {
        return  $select = array(
            'id',
            'bank_name',
            'bank_code',
            'bank_icon',
            'bank_background'
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
    public function get($wheres = [])
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

    public function find($code)
    {
        $select = $this->_select();

        $this->db->select($select);
        $query = $this->db->from($this->table)->where(array('bank_code' => $code))->get();
        return $query->row_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);

        $data['update_at'] = date('Y-m-d H:i:s');

        return $this->db->update($this->table, $data);
    }

    public function validate($cardNum)
    {
        $url = "https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?cardNo={$cardNum}&cardBinCheck=true";
        return json_decode($this->doGet($url), true);
    }


    /**
     * @param string $url
     * @return mixed
     */
    public function doGet($url)
    {
        //初始化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // 执行后不直接打印出来
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // 不从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }
}