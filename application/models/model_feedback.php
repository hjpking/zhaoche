<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-9
 * Time: 下午7:49
 * To change this template use File | Settings | File Templates.
 */
class model_feedback extends MY_Model
{
    /**
     * 获取投诉建议
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getFeedback($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('feedback');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array();
        //return $this->sortdata($data);
    }

    /**
     * 获取投诉建议数量
     *
     * @param array $where
     * @return mixed
     */
    public function getFeedbackCount($where = array())
    {
        $this->db->select('*')->from('feedback')->where($where);

        return $this->db->count_all_results();
    }
}
