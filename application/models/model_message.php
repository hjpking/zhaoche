<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-12
 * Time: 下午12:07
 * To change this template use File | Settings | File Templates.
 */
class model_message extends MY_Model
{
    /**
     * 获取消息信息
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getMessage($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('message');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $this->db->get()->result_array();
        //return $this->sortData($data);
    }

    /**
     * 获取消息信息数量
     *
     * @param array $where
     * @return mixed
     */
    public function getMessageCount(array $where)
    {
        $this->db->select('*')->from('message');
        $this->db->where($where);
        return $this->db->count_all_results();
    }


    /**
     * 获取消息信息 －－ 通过ID
     *
     * @param $mId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getMessageById($mId, $field = '*', $where = array())
    {
        $where['mid'] = $mId;
        $data = $this->db->select($field)->get_where('message', $where)->row_array();

        return $data;
    }

    /**
     * 获取分类消息
     *
     * @param $cId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getMessageBycId($cId, $field = '*', $where = array())
    {
        $where['cid'] = $cId;
        $data = $this->db->select($field)->get_where('message', $where)->result_array();

        return $data;
    }

    /**
     * 删除司机消息分类
     *
     * @param $mId
     * @param int $operaType
     * @return bool
     */
    public function delete($mId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('mid', $mId);
            $this->db->update('message', array('is_del' => 1));
        } else {
            $this->db->delete('message', array('mid' => $mId));
        }

        return true;
    }

    /**
     * 保存消息分类
     *
     * @param array $data
     * @param $mId
     */
    public function save(array $data, $mId)
    {
        if ($mId) {
            $this->db->where('mid', $mId);
            $this->db->update('message', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('message', $data);
        }
    }












    /**
     * 获取消息分类信息
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getMessageCategory($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('message_category');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        $data = $this->db->get()->result_array('cid');
        return $this->sortData($data);
    }

    /**
     * 获取消息分类信息 －－ 通过ID
     *
     * @param $categoryId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getMessageCategoryById($categoryId, $field = '*', $where = array())
    {
        $where['cid'] = $categoryId;
        $data = $this->db->select($field)->get_where('message_category', $where)->row_array();

        return $data;
    }

    /**
     * 删除司机消息分类
     *
     * @param $categoryId
     * @param int $operaType
     * @return bool
     */
    public function categoryDelete($categoryId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('cid', $categoryId);
            $this->db->update('message_category', array('is_del' => 1));
        } else {
            $this->db->delete('message_category', array('cid' => $categoryId));
        }

        return true;
    }

    /**
     * 保存消息分类
     *
     * @param array $data
     * @param $categoryId
     */
    public function categorySave(array $data, $categoryId)
    {
        if ($categoryId) {
            $this->db->where('cid', $categoryId);
            $this->db->update('message_category', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('message_category', $data);
        }
    }

    /**
     * 是否还有下级分类
     *
     * @param $category_id
     * @return bool
     */
    public function categoryIsAlone($category_id)
    {
        $num = $this->db->from('message_category')
            ->where('parent_id', $category_id)
            ->count_all_results();
        if ($num) //如果存在子类,则无法删除
            return false;
        return true;
    }


    /**
     * 记录发送消息记录
     *
     * @param array $data
     * @return mixed
     */
    public function send(array $data)
    {
        $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
        return $this->db->insert('message_send_record', $data);
    }

    /**
     * 获取消息发送记录
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getMessageSendRecord($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('message_send_record');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);
        //$this->db->group_by('style_no');

        return $this->db->get()->result_array();
    }

    /**
     * 获取消息发送记录数量
     *
     * @param array $where
     * @return mixed
     */
    public function getMessageSendRecordCount(array $where = null)
    {
        $this->db->select('*')->from('message_send_record');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 将所有分类按正确位置排序
     *
     * @param $catArray
     * @param int $id
     * @return mixed
     */
    private static function sortData($catArray, $id = 0)
    {
        static $formatCat = array();
        static $floor = 0;
        static $ancestor = 0;
        foreach ($catArray as $key => $val) {

            if ($val['parent_id'] == $id) {
                ($val['parent_id'] == 0) && $ancestor = $val['cid'];
                //$val['cname'] = $val['cname'];
                $val['ancestor'] = $ancestor;
                $id && $formatCat[$id]['is_parent'] = true;
                $val['floor'] = $floor;
                $formatCat[$val['cid']] = $val;
                unset($catArray[$key]);
                $floor++;
                self::sortdata($catArray, $val['cid']);
                $floor--;
            }
        }
        return $formatCat;
    }
}
