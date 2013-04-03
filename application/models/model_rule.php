<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-11
 * Time: 下午6:40
 * To change this template use File | Settings | File Templates.
 */
class model_rule extends MY_Model
{
    /**
     * 获取计费规则
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getRule($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('bill_rule');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

         return $data = $this->db->get()->result_array('rule_id');
    }

    /**
     * 获取计费规则数量
     *
     * @param array $where
     * @return mixed
     */
    public function getRuleCount(array $where)
    {
        $this->db->select('*')->from('bill_rule');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 获取计费规则 －－ 通过ID
     *
     * @param $ruleId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getRuleById($ruleId, $field = '*', $where = array())
    {
        $where['rule_id'] = $ruleId;
        $data = $this->db->select($field)->get_where('bill_rule', $where)->row_array();

        return $data;
    }

    /**
     * 保存计划规则信息
     *
     * @param array $data
     * @param int $ruleId
     */
    public function save(array $data, $ruleId = 0)
    {
        if ($ruleId) {
            $this->db->where('rule_id', $ruleId);
            $this->db->update('bill_rule', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('bill_rule', $data);
        }
    }

    /**
     * 删除计费规则
     *
     * @param $ruleId
     * @param int $operaType
     * @return bool
     */
    public function delete($ruleId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('rule_id', $ruleId);
            $this->db->update('bill_rule', array('is_del' => 1));
        } else {
            $this->db->delete('bill_rule', array('rule_id' => $ruleId));
        }

        return true;
    }
}
