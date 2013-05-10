<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-15
 * Time: 下午11:09
 * To change this template use File | Settings | File Templates.
 */
class model_card extends MY_Model
{
    /**
     * 获取卡
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getCard($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('card');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);

        $data = $this->db->get()->result_array();//echo '<pre>';print_r($data);exit;

        return $data;
    }

    /**
     * 获取卡数量
     *
     * @param array $where
     * @return mixed
     */
    public function getCardCount(array $where)
    {
        $this->db->select('*')->from('card');
        $this->db->where($where);
        return $this->db->count_all_results();
    }

    /**
     * 获取用户卡信息
     *
     * @param $uId
     * @param string $field
     * @param array $where
     * @return mixed
     */
    public function getCardByuId($uId, $field = '*', $where = array())
    {
        $where['uid'] = $uId;
        $data = $this->db->select($field)->get_where('card', $where)->row_array();

        return $data;
    }

    /**
     * 卡保存
     *
     * @param array $data
     * @param $cardId
     */
    public function cardSave(array $data, $cardId = 0)
    {
        if ($cardId) {
            $this->db->where('card_id', $cardId);
            $this->db->update('card', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('card', $data);
        }
    }

    /**
     * 卡删除
     *
     * @param $cardId
     * @param int $operaType
     * @return bool
     */
    public function cardDelete($cardId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('card_no', $cardId);
            $this->db->update('card', array('is_del' => 1));
        } else {
            $this->db->delete('card', array('card_no' => $cardId));
        }

        return true;
    }

    /**
     * 获取卡模型
     *
     * @param int $limit
     * @param int $offset
     * @param string $field
     * @param null $where
     * @param null $order
     * @return mixed
     */
    public function getCardModel($limit = 20, $offset = 0, $field= '*', $where = null, $order = null)
    {
        $this->db->select($field);
        $this->db->from('card_model');
        $where && $this->db->where($where);
        $order && $this->db->order_by($order);
        $this->db->limit($limit, $offset);

        $data = $this->db->get()->result_array('model_id');//echo '<pre>';print_r($data);exit;

        return $data;
    }

    /**
     * 获取卡模型 -- 通过ID
     *
     * @param $modelId
     * @param string $field
     * @return mixed
     */
    public function getCardModelById($modelId, $field = '*')
    {
        $data = $this->db->select($field)->get_where('card_model', array('model_id' => $modelId))->row_array();

        return $data;
    }

    /**
     * 保存卡模型
     *
     * @param array $data
     * @param $modelId
     */
    public function cardModelSave(array $data, $modelId)
    {
        if ($modelId) {
            $this->db->where('model_id', $modelId);
            $this->db->update('card_model', $data);
        } else {
            $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);
            $this->db->insert('card_model', $data);
        }
    }

    /**
     * 删除卡模型
     *
     * @param $modelId
     * @param int $operaType
     * @return bool
     */
    public function cardModelDelete($modelId, $operaType = 1)
    {
        if ($operaType) {
            $this->db->where('model_id', $modelId);
            $this->db->update('card_model', array('is_del' => 1));
        } else {
            $this->db->delete('card_model', array('model_id' => $modelId));
        }

        return true;
    }
}
