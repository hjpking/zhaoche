<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-11
 * Time: 下午4:02
 * To change this template use File | Settings | File Templates.
 */
class model_service_type extends MY_Model
{
    /**
     * 获取服务类别
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getServiceType($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('service_type');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array('sid');
    }

    /**
     * 获取服务类别 -- 通过ID
     *
     * @param $sId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getServiceTypeById($sId, $field = '*', $where = array())
    {
        $where['sid'] = $sId;
        $data = $this->db->select($field)->get_where('service_type', $where)->row_array();

        return $data;
    }

    /**
     * 保存获取服务类别信息
     *
     * @param array $data
     * @param int $sId
     */
    public function save(array $data, $sId = 0)
    {
        if ($sId) {
            $this->db->where('sid', $sId);
            $this->db->update('service_type', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('service_type', $data);
        }
    }

    /**
     * 删除获取服务类别信息
     *
     * @param $sId
     * @param int $operaType
     * @return bool
     */
    public function delete($sId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('sid', $sId);
            $this->db->update('service_type', array('is_del' => 1));
        } else {
            $this->db->delete('service_type', array('sid' => $sId));
        }

        return true;
    }
}
