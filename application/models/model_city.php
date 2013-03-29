<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-8
 * Time: 下午5:49
 * To change this template use File | Settings | File Templates.
 */
class model_city extends MY_Model
{
    /**
     * 获取城市
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @param bool $isSort
     * @return mixed
     */
    public function getCity($limit = 20, $offset = 0, $field= '*', $where = null, $order = null, $isSort = true)
    {
        $this->db->select($field);
        $this->db->from('city');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        $data = $this->db->get()->result_array('city_id');//echo '<pre>';print_r($data);exit;

        //*
        if ($isSort) {
            return $this->sortdata($data);
        }

        return $data;
        //*/
    }

    /**
     * 获取城市
     *
     * @param $cityId
     * @param string $field
     * @return mixed
     */
    public function getCityById($cityId, $field = '*')
    {
        $data = $this->db->select($field)->get_where('city', array('city_id' => $cityId))->row_array();

        return $data;
    }

    /**
     * 获取城市数量
     *
     * @param array $where
     * @return mixed
     */
    public function getCityCount(array $where)
    {
        $this->db->select('*')->from('city');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 添加城市
     *
     * @param array $data
     * @param $city_id
     */
    public function save(array $data, $city_id)
    {
        if ($city_id) {
            $this->db->where('city_id', $city_id);
            $this->db->update('city', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('city', $data);
        }
    }

    /**
     * 删除信息
     *
     * @param $cityId
     * @param int $operaType
     * @return bool
     */
    public function delete($cityId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('city_id', $cityId);
            $this->db->update('city', array('is_del' => 1));
        } else {
            $this->db->delete('city', array('city_id' => $cityId));
        }

        return true;
    }

    /**
     * 是否还有下级城市
     *
     * @param $city_id
     * @return bool
     */
    public function isAlone($city_id)
    {
        $num = $this->db->from('city')
            ->where('parent_id', $city_id)->where('is_del', '0')
            ->count_all_results();
        if ($num) //如果存在子类,则无法删除
            return false;
        return true;
    }

    /**
     * 获取常用地址
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getUseful($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('city_useful_addresse');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);

        $data = $this->db->get()->result_array();//echo '<pre>';print_r($data);exit;

        return $data;
    }

    /**
     * 获取常用地址数量
     *
     * @param array $where
     * @return mixed
     */
    public function getUsefulCount(array $where)
    {
        $this->db->select('*')->from('city_useful_addresse');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 获取常用地址 -- 通过ID
     *
     * @param $auId
     * @param string $field
     * @return mixed
     */
    public function getUsefulById($auId, $field = '*')
    {
        $data = $this->db->select($field)->get_where('city_useful_addresse', array('ua_id' => $auId))->row_array();

        return $data;
    }

    /**
     * 获取常用地址 -- 通过城市ID
     *
     * @param $cityId
     * @param $limit
     * @param $offset
     * @param string $field
     * @return mixed
     */
    public function getUsefulByCityId($cityId, $limit, $offset, $field = '*')
    {
        $data = $this->db->select($field)->get_where('city_useful_addresse', array('city_id' => $cityId), $limit, $offset)->result_array();

        return $data;

    }

    /**
     * 保存常用地址信息
     *
     * @param array $data
     * @param $uaId
     */
    public function usefulSave(array $data, $uaId)
    {
        if ($uaId) {
            $this->db->where('ua_id', $uaId);
            $this->db->update('city_useful_addresse', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('city_useful_addresse', $data);
        }
    }

    /**
     * 删除常用地址
     *
     * @param $uaId
     * @param int $operaType
     * @return bool
     */
    public function usefulDelete($uaId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('ua_id', $uaId);
            $this->db->update('city_useful_addresse', array('is_del' => 1));
        } else {
            $this->db->delete('city_useful_addresse', array('ua_id' => $uaId));
        }

        return true;
    }

    /**
     * 获取机场列表
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getAirport($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('city_airport');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);

        $data = $this->db->get()->result_array();//echo '<pre>';print_r($data);exit;

        return $data;
    }

    /**
     * 获取机场列表数量
     *
     * @param array $where
     * @return mixed
     */
    public function getAirportCount(array $where)
    {
        $this->db->select('*')->from('city_airport');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 获取机场信息 －－ 通过ID
     *
     * @param $id
     * @param string $field
     * @return mixed
     */
    public function getAirportById($id, $field = '*')
    {
        $data = $this->db->select($field)->get_where('city_airport', array('id' => $id))->row_array();

        return $data;
    }

    /**
     * 保存机场信息
     *
     * @param array $data
     * @param $id
     */
    public function airportSave(array $data, $id)
    {
        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('city_airport', $data);
        } else {
            //$data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('city_airport', $data);
        }
    }

    /**
     * @param $id
     * @param int $operaType
     * @return bool
     */
    public function airportDelete($id, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('id', $id);
            $this->db->update('city_airport', array('is_del' => 1));
        } else {
            $this->db->delete('city_airport', array('id' => $id));
        }

        return true;
    }

    /**
     * 将所有分类按正确位置排序
     *
     * @param $catArray
     * @param int $id
     * @return mixed
     */
    private static function sortdata($catArray, $id = 0)
    {
        static $formatCat = array();
        static $floor = 0;
        static $ancestor = 0;
        foreach ($catArray as $key => $val) {

            if ($val['parent_id'] == $id) {
                ($val['parent_id'] == 0) && $ancestor = $val['city_id'];
                //$val['cname'] = $val['cname'];
                $val['ancestor'] = $ancestor;
                $id && $formatCat[$id]['is_parent'] = true;
                $val['floor'] = $floor;
                $formatCat[$val['city_id']] = $val;
                unset($catArray[$key]);
                $floor++;
                self::sortdata($catArray, $val['city_id']);
                $floor--;
            }
        }
        return $formatCat;
    }
}
