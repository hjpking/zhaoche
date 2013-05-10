<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-9
 * Time: 下午8:16
 * To change this template use File | Settings | File Templates.
 */
class model_staff extends MY_Model
{
    /**
     * 员工登陆
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function staffLogin($username, $password)
    {
        $field = 'staff_id, login_name, realname, password, phone, email, sex, depart_id, descr, is_del, id_card';
        $uInfo = $this->db->select($field)->get_where('staff', array('login_name' => $username, 'is_del' => '0'))->row_array();

        if (empty ($uInfo) || !is_array($uInfo)) {
            return false;
        }

        $password = md5(trim($password));
        if ($password != $uInfo['password']) {
             return false;
        }

        return $uInfo;
    }

    /**
     * 获取员工信息
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getStaff($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('staff');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array('staff_id');
        //return $this->sortdata($data);
    }

    /**
     * 获取员工数量
     *
     * @param array $where
     * @return mixed
     */
    public function getStaffCount(array $where)
    {
        $this->db->select('*')->from('staff');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 获取员工信息 -- 通过ID
     *
     * @param $staffId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getStaffById($staffId, $field = '*', $where = array())
    {
        $where['staff_id'] = $staffId;
        $data = $this->db->select($field)->get_where('staff', $where)->row_array();

        return $data;
    }

    /**
     * 保存员工信息
     *
     * @param array $data
     * @param $staffId
     */
    public function save(array $data, $staffId)
    {
        if ($staffId) {
            $this->db->where('staff_id', $staffId);
            return $this->db->update('staff', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('staff', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * 删除员工信息
     *
     * @param $staffId
     * @param int $operaType
     * @return bool
     */
    public function delete($staffId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('staff_id', $staffId);
            $this->db->update('staff', array('is_del' => 1));
        } else {
            $this->db->delete('staff', array('staff_id' => $staffId));
        }

        return true;
    }

    /**
     * 恢复员工
     *
     * @param $staffId
     * @return mixed
     */
    public function restore($staffId)
    {
        $this->db->where('staff_id', $staffId);
        return $this->db->update('staff', array('is_del' => 0));
    }

    /**
     * 获取部门信息
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getDepartment($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('department');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        $data = $this->db->get()->result_array('depart_id');
        return $this->sortdata($data);
    }

    /**
     * 获取部门数量
     *
     * @param array $where
     * @return mixed
     */
    public function getDepartmentCount(array $where)
    {
        $this->db->select('*')->from('department');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 获取部门信息 -- 通过ID
     *
     * @param $departId
     * @param string $field
     * @param $where
     * @return mixed
     */
    public function getDepartmentById($departId, $field = '*', $where = array())
    {
        $where['depart_id'] = $departId;
        $data = $this->db->select($field)->get_where('department', $where)->row_array();

        return $data;
    }

    /**
     * 保存部门
     *
     * @param array $data
     * @param $departId
     */
    public function departmentSave(array $data, $departId)
    {
        if ($departId) {
            $this->db->where('depart_id', $departId);
            $this->db->update('department', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('department', $data);
        }
    }

    /**
     * 删除部门
     *
     * @param $departId
     * @param int $operaType
     * @return bool
     */
    public function departmentDelete($departId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('depart_id', $departId);
            $this->db->update('department', array('is_del' => 1));
        } else {
            $this->db->delete('department', array('depart_id' => $departId));
        }

        return true;
    }

    /**
     * 检查部门是否存在
     *
     * @param $depart_id
     * @return bool
     */
    public function isAlone($depart_id)
    {
        return $this->db->from('department')->where('parent_id', $depart_id)->where('is_del', '0')->count_all_results();

        /*
        if (!$num) //如果存在子类,则无法删除
            return true;
        return false;
        */
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
                ($val['parent_id'] == 0) && $ancestor = $val['depart_id'];
                //$val['cname'] = $val['cname'];
                $val['ancestor'] = $ancestor;
                $id && $formatCat[$id]['is_parent'] = true;
                $val['floor'] = $floor;
                $formatCat[$val['depart_id']] = $val;
                unset($catArray[$key]);
                $floor++;
                self::sortdata($catArray, $val['depart_id']);
                $floor--;
            }
        }
        return $formatCat;
    }
}
