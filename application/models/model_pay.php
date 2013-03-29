<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-9
 * Time: 下午7:42
 * To change this template use File | Settings | File Templates.
 */
class model_pay extends MY_Model
{
    /**
     * 获取充值记录
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getPay($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('pay_record');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array();
    }

    /**
     * 获取充值记录数量
     *
     * @param array $where
     * @return mixed
     */
    public function getPayCount($where = array())
    {
        $this->db->select('*')->from('pay_record')->where($where);

        return $this->db->count_all_results();
    }
}
