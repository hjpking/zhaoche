<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-8
 * Time: 下午8:51
 * To change this template use File | Settings | File Templates.
 */
class model_chauffeur extends MY_Model
{
    /**
     * 获取司机列表
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getChauffeur($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('chauffeur');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array();
        //return $this->sortdata($data);
    }

    /**
     * 获取司机信息　where in
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param $in
     * @return mixed
     */
    public function getChauffeurWhereIn($limit = 20, $offset = 0, $field= '*', $in)
    {
        $this->db->select($field);
        $this->db->from('chauffeur');
        $this->db->where_in('cname', $in);
        $this->db->limit($limit, $offset);

        return $data = $this->db->get()->result_array();
    }

    /**
     * 获取司机信息 -- 通过司机ID
     *
     * @param $chauffeurId
     * @param string $field
     * @param $where
     * @return mixed
     */
    public function getChauffeurById($chauffeurId, $field = '*', $where = array())
    {
        $where['chauffeur_id'] = $chauffeurId;
        $data = $this->db->select($field)->get_where('chauffeur', $where)->row_array();

        return $data;
    }

    /**
     * 获取司机信息 -- 通过司机手机号
     *
     * @param $phone
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getChauffeurByPhone($phone, $field = '*', $where = array())
    {
        $where['phone'] = $phone;
        $data = $this->db->select($field)->get_where('chauffeur', $where)->row_array();

        return $data;
    }

    /**
     * 获取司机数量
     *
     * @param array $where
     * @return mixed
     */
    public function getChauffeurCount(array $where)
    {
        $this->db->select('*')->from('chauffeur');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 删除司机
     *
     * @param $chauffeurId
     * @param int $operaType
     * @return bool
     */
    public function delete($chauffeurId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('chauffeur_id', $chauffeurId);
            $this->db->update('chauffeur', array('is_del' => 1));
        } else {
            $this->db->delete('chauffeur', array('chauffeur_id' => $chauffeurId));
        }

        return true;
    }

    /**
     * 保存司机数据
     *
     * @param array $data
     * @param $chauffeur_id
     */
    public function save(array $data, $chauffeur_id)
    {
        if ($chauffeur_id) {
            $this->db->where('chauffeur_id', $chauffeur_id);
            $this->db->update('chauffeur', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('chauffeur', $data);
        }
    }

    /**
     * 恢复司机
     *
     * @param $chauffeurId
     * @return mixed
     */
    public function restore($chauffeurId)
    {
        $this->db->where('chauffeur_id', $chauffeurId);
        return $this->db->update('chauffeur', array('is_del' => 0));
    }

    /**
     * 保存司机当前位置
     *
     * @param array $data
     * @return bool
     */
    public function saveCurrentLocation(array $data)
    {
        $chauffeurId = $data['chauffeur_id'];
        if (!$chauffeurId) {
            return false;
        }

        $l = $this->isCurrentLocation($chauffeurId);

	$data['update_time'] = date('Y-m-d H:i:s', TIMESTAMP);
        if (!$l) {
            //$data['update_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('chauffeur_location', $data);
            return $this->db->insert_id();
        }

        unset ($data['chauffeur_id']);
        $this->db->where('chauffeur_id', $chauffeurId);
        return $this->db->update('chauffeur_location', $data);
    }

    /**
     * 是否有司机当前位置数据
     *
     * @param $chauffeurId
     * @return mixed
     */
    public function isCurrentLocation($chauffeurId)
    {
        $l = $this->db->select('*')->get_where('chauffeur_location', array('chauffeur_id' => $chauffeurId))->row_array();
        return $l;
    }

    /**
     * 获取当前位置 －－ 通过城市ID
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getCurrentLocationByCityId($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('chauffeur_location');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array();
    }

    /**
     * 保存验证码
     * @param array $data
     * @return bool
     */
    public function verifySave(array $data)
    {
        $phone = $data['phone'];
        if (!$phone) {
            return false;
        }
        $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);

        $d = $this->getVerify($phone);

        if (!$d) {
            $this->db->insert('chauffeur_verify', $data);
            return $this->db->insert_id();
        }

        $this->db->where('phone', $phone);
        return $this->db->update('chauffeur_verify', $data);
    }

    /**
     * 获取验证码
     *
     * @param $phone
     * @return mixed
     */
    public function getVerify($phone)
    {
        $l = $this->db->select('*')->get_where('chauffeur_verify', array('phone' => $phone))->row_array();
        return $l;
    }
}
