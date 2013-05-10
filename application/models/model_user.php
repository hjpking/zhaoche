<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-9
 * Time: 下午12:01
 * To change this template use File | Settings | File Templates.
 */
class model_user extends MY_Model
{
    /**
     * 获取用户信息
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getUser($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('user');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array();
    }

    /**
     * 获取用户信息　where in
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param $in
     * @return mixed
     */
    public function getUserWhereIn($limit = 20, $offset = 0, $field= '*', $in)
    {
        $this->db->select($field);
        $this->db->from('user');
        $this->db->where_in('uname', $in);
        $this->db->limit($limit, $offset);

        return $data = $this->db->get()->result_array();
    }

    /**
     * 获取用户数量
     *
     * @param array $where
     * @return mixed
     */
    public function getUserCount(array $where)
    {
        $this->db->select('*')->from('user');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 获取用户信息 -- 通过用户ID
     *
     * @param $uId
     * @param string $field
     * @param $where
     * @return mixed
     */
    public function getUserById($uId, $field = '*', $where = array())
    {
        $where['uid'] = $uId;
        $data = $this->db->select($field)->get_where('user', $where)->row_array();

        return $data;
    }

    /**
     * 获取用户信息 -- 通过用户名
     *
     * @param $uName
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getUserByName($uName, $field = '*', $where = array())
    {
        $where['uname'] = $uName;
        $data = $this->db->select($field)->get_where('user', $where)->row_array();

        return $data;
    }

    /**
     * 获取用户信息－－ 通过手机号码
     *
     * @param $phone
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getUserByPhone($phone, $field = '*', $where = array())
    {
        $where['phone'] = $phone;
        $data = $this->db->select($field)->get_where('user', $where)->row_array();

        return $data;
    }

    /**
     * 保存用户信息
     *
     * @param array $data
     * @param $uId
     * @return mixed
     */
    public function save(array $data, $uId = 0)
    {
        if ($uId) {
            $this->db->where('uid', $uId);
            return $this->db->update('user', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('user', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * 删除用户
     *
     * @param $uId
     * @param int $operaType
     * @return bool
     */
    public function delete($uId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('uid', $uId);
            $this->db->update('user', array('is_del' => 1));
        } else {
            $this->db->delete('user', array('uid' => $uId));
        }

        return true;
    }

    /**
     * 恢复用户
     *
     * @param $uId
     * @return mixed
     */
    public function restore($uId)
    {
        $this->db->where('uid', $uId);
        return $this->db->update('user', array('is_del' => 0));
    }

    /**
     * 获取用户发票
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getInvoice($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('user_invoice');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $data = $this->db->get()->result_array();
    }

    /**
     * 获取发票数量
     *
     * @param array $where
     * @return mixed
     */
    public function getInvoiceCount(array $where)
    {
        $this->db->select('*')->from('user_invoice');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 获取用户发票
     *
     * @param $uId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getInvoiceByUid($uId, $field = '*', $where = array())
    {
        $where['uid'] = $uId;
        $data = $this->db->select($field)->get_where('user_invoice', $where)->row_array();

        return $data;
    }

    /**
     * 删除发票
     *
     * @param $invoiceId
     * @param int $operaType
     * @return bool
     */
    public function invoiceDelete($invoiceId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('invoice_id', $invoiceId);
            $this->db->update('user_invoice', array('is_del' => 1));
        } else {
            $this->db->delete('user_invoice', array('invoice_id' => $invoiceId));
        }

        return true;
    }
}
