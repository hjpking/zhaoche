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

    /**
     * 保存充值记录
     *
     * @param array $data
     * @param int $payId
     * @return mixed
     */
    public function savePay(array $data, $payId = 0)
    {
        if ($payId) {
            $this->db->where('pay_id', $payId);
            return $this->db->update('pay_record', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('pay_record', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * 获取充值信息 －－ 通过充值ID
     *
     * @param $payId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getPayById($payId, $field = '*', $where = array())
    {
        $where['pay_id'] = $payId;
        $data = $this->db->select($field)->get_where('pay_record', $where)->row_array();

        return $data;
    }

    public function getBeUserPayLog($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('be_user_pay_record');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array();
    }

    public function getBeUserPayLogCount($where = array())
    {
        $this->db->select('*')->from('be_user_pay_record')->where($where);

        return $this->db->count_all_results();
    }

    /**
     * 保存给用户充值的日志
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function saveBeUserPayLog(array $data, $id = 0)
    {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->update('be_user_pay_record', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('be_user_pay_record', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * 获取一条给用户充值的日志
     *
     * @param $id
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getBeUserPayLogById($id, $field = '*', $where = array())
    {
        $where['id'] = $id;
        $data = $this->db->select($field)->get_where('be_user_pay_record', $where)->row_array();

        return $data;
    }

    /**
     * 获取司机充值记录
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getChauffeurPay($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('chauffeur_to_user_pay_log');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array();
    }

    /**
     * 获取司机充值记录数量
     *
     * @param array $where
     * @return mixed
     */
    public function getChauffeurPayCount($where = array())
    {
        $this->db->select('*')->from('chauffeur_to_user_pay_log')->where($where);

        return $this->db->count_all_results();
    }

    /**
     * 获取司机给用户的充值记录
     *
     * @param $chauffeurId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getChauffeurPayByChauffeurId($chauffeurId, $field = '*', $where = array())
    {
        $where['chauffeur_id'] = $chauffeurId;
        $data = $this->db->select($field)->get_where('chauffeur_to_user_pay_log', $where)->result_array();

        return $data;
    }
}
