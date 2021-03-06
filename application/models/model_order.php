<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-8
 * Time: 下午10:08
 * To change this template use File | Settings | File Templates.
 */
class model_order extends MY_Model
{
    /**
     * 获取司机订单
     *
     * @param $chauffeurId
     * @param int $limit
     * @param int $offset
     * @param array $where
     * @return mixed
     */
    public function getChauffeurOrder($chauffeurId, $limit = 20, $offset = 0, $where = array())
    {
        $this->db->select('')->from('order')->where('chauffeur_id', $chauffeurId)->where($where)->where('status', '1')->order_by('order_sn', 'desc');
        $data = $this->db->limit($limit, $offset)->get()->result_array();

        return $data;
    }

    /**
     * 获取司机订单数量
     *
     * @param $chauffurId
     * @param int $status
     * @param array $where
     * @return mixed
     */
    public function getChauffeurOrderCount($chauffurId, $status = 0, $where = array())
    {
        $where['chauffeur_id'] = $chauffurId;
        $this->db->select('*')->from('order')->where($where);
        if ($status == 1) {
            $this->db->where('status ', '1');
        } else {
            $this->db->where('status ', '0');
        }

        return $this->db->count_all_results();
    }

    /**
     * 保存订单
     *
     * @param array $data
     * @param int $orderSn
     */
    public function saveOrder(array $data, $orderSn = 0)
    {
        if ($orderSn) {
            $this->db->where('order_sn', $orderSn);
            return $this->db->update('order', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('order', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * 获取订单
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @param string $group
     * @return mixed
     */
    public function getOrder($limit = 20, $offset = 0, $field= '*', $where = null, $order = null, $group = '')
    {
        $this->db->select($field);
        $this->db->from('order');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        $this->db->group_by($group);

        return $data = $this->db->get()->result_array();
    }

	/**
	 *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @param string $group
     * @return mixed
	 */
	public function getOrderWhereIn($limit = 20, $offset = 0, $field= '*', $where = null, $order = null, $whereIn = array())
	{
        $this->db->select($field);
        $this->db->from('order');
        $where && $this->db->where($where);
		$whereIn && $this->db->where_in('status', $whereIn);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by($group);

        return $data = $this->db->get()->result_array();
	}

    /**
     * 获取订单数量
     *
     * @param array $where
     * @return mixed
     */
    public function getOrderCount($where = array())
    {
        $this->db->select('*')->from('order')->where($where);

        return $this->db->count_all_results();
    }

    /**
     * 获取订单信息 -- 通过ID
     *
     * @param $orderSn
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getOrderById($orderSn, $field = '*', $where = array())
    {
        $where['order_sn'] = $orderSn;
        $data = $this->db->select($field)->get_where('order', $where)->row_array();

        return $data;
    }

    /**
     * 用户取消订单
     *
     * @param $uId
     * @param $orderSn
     * @return mixed
     */
    public function cancelOrderByUser($uId, $orderSn)
    {
        $this->db->where('order_sn', $orderSn);
        $this->db->where('uid', $uId);
        return $this->db->update('order', array('status' => '2'));
    }

    /**
     * 司机取消接单
     *
     * @param $chauffeurId
     * @param $orderSn
     * @return mixed
     */
    public function cancelOrderByChauffeur($chauffeurId, $orderSn)
    {
        $data = array(
            'chauffeur_id' => null,
            'chauffeur_login_name' => '',
            'chauffeur_phone' => '',
            'status' => '0',
        );

        $this->db->where('order_sn', $orderSn);
        $this->db->where('chauffeur_id', $chauffeurId);
        return $this->db->update('order', $data);
    }

    /**
     * 用户接单
     */
    public function chauffeurDetermineOrder(array $chauffeurData, $orderSn)
    {
        $data = array(
            'chauffeur_id' => $chauffeurData['chauffeur_id'],
            'chauffeur_login_name' => $chauffeurData['chauffeur_login_name'],
            'chauffeur_phone' => $chauffeurData['chauffeur_phone'],
            'pay_password' => $chauffeurData['pay_password'],
            'status' => 3,
        );

        $this->db->where('order_sn', $orderSn);
        //$this->db->where('uid', $uId);
        return $this->db->update('order', $data);
    }

    /**
     * 确认到达
     *
     * @param $chauffeurId
     * @param $orderSn
     * @param array $upData
     * @return mixed
     */
    public function confirmArrival($chauffeurId, $orderSn, array $upData)
    {
        $where = array(
            'status' => '1',
            'getoff_time' => date('Y-m-d H:i:s', TIMESTAMP),
            'amount' => $upData['total_price'],
            'exceed_time' => $upData['exceed_time'],
            'exceed_time_fee' => $upData['exceed_time_fee'],
            'exceed_km' => $upData['exceed_km'],
            'exceed_km_fee' => $upData['exceed_km_fee'],
            'high_speed_charge' => $upData['high_speed_fee'],
            'park_charge' => $upData['park_fee'],
            'air_service_charge' => $upData['air_service_fee'],
            'air_service_num' => $upData['air_service_num'],
            'mileage' => $upData['mileage'],
            'travel_time' => $upData['travel_time'],
            'kongshi_km' => $upData['kongshi_km'],
            'kongshi_fee' => $upData['kongshi_fee'],
            'night_service_charge' => $upData['night_service_fee'],
        );

        $this->db->where('order_sn', $orderSn);
        $this->db->where('chauffeur_id', $chauffeurId);
        return $this->db->update('order', $where);
    }

    /**
     * 回报车辆行驶路径
     *
     * @param array $data
     * @return mixed
     */
    public function reportRunRd(array $data)
    {
        $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
        $this->db->insert('order_run_path', $data);
        return $this->db->insert_id();
    }
}
