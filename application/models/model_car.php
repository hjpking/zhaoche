<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-8
 * Time: 下午6:11
 * To change this template use File | Settings | File Templates.
 */
class model_car extends MY_Model
{
    /**
     * 获取车辆信息
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @param bool $isSort
     * @return array
     */
    public function getCar($limit = 20, $offset = 0, $field= '*', $where = null, $order = null, $isSort = true)
    {
        $this->db->select($field);
        $this->db->from('car_model');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        $data = $this->db->get()->result_array('car_id');
        if ($isSort) {
            return $this->sortdata($data);
        }
        return $data;
    }

    /**
     * 获取车辆信息 -- 通过ID
     *
     * @param $carId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getCarById($carId, $field = '*', $where = array())
    {
        $where['car_id'] = $carId;
        $data = $this->db->select($field)->get_where('car_model', $where)->row_array();

        return $data;
    }

    /**
     * 保存车辆信息
     *
     * @param array $data
     * @param int $carId
     */
    public function save(array $data, $carId = 0)
    {
        if ($carId) {
            $this->db->where('car_id', $carId);
            $this->db->update('car_model', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('car_model', $data);
        }
    }

    /**
     * 删除车辆信息
     *
     * @param $carId
     * @param int $operaType
     * @return bool
     */
    public function delete($carId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('car_id', $carId);
            $this->db->update('car_model', array('is_del' => 1));
        } else {
            $this->db->delete('car_model', array('car_id' => $carId));
        }

        return true;
    }

    /**
     * 获取车辆等级信息
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getCarLevel($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('car_level');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array('lid');
    }

    /**
     * 获取车辆等级信息 -- 通过ID
     *
     * @param $lId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getCarLevelById($lId, $field = '*', $where = array())
    {
        $where['lid'] = $lId;
        $data = $this->db->select($field)->get_where('car_level', $where)->row_array();

        return $data;
    }

    /**
     * 保存车辆等级信息
     *
     * @param array $data
     * @param int $lId
     */
    public function carLevelSave(array $data, $lId = 0)
    {
        if ($lId) {
            $this->db->where('lid', $lId);
            $this->db->update('car_level', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('car_level', $data);
        }
    }

    /**
     * 车辆等级信息删除
     *
     * @param $lId
     * @param int $operaType
     * @return bool
     */
    public function carLevelDelete($lId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('lid', $lId);
            $this->db->update('car_level', array('is_del' => 1));
        } else {
            $this->db->delete('car_level', array('lid' => $lId));
        }

        return true;
    }

    /**
     * 将所有分类按正确位置排序
     * @static
     * @param $catArray
     * @param int $id
     * @return array
     */
    private static function sortdata($catArray, $id = 0)
    {
        static $formatCat = array();
        static $floor = 0;
        static $ancestor = 0;
        foreach ($catArray as $key => $val) {

            if ($val['parent_id'] == $id) {
                ($val['parent_id'] == 0) && $ancestor = $val['car_id'];
                //$val['cname'] = $val['cname'];
                $val['ancestor'] = $ancestor;
                $id && $formatCat[$id]['is_parent'] = true;
                $val['floor'] = $floor;
                $formatCat[$val['car_id']] = $val;
                unset($catArray[$key]);
                $floor++;
                self::sortdata($catArray, $val['car_id']);
                $floor--;
            }
        }
        return $formatCat;
    }
}
