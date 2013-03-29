<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-15
 * Time: 下午2:58
 * To change this template use File | Settings | File Templates.
 */

class model_other extends MY_Model
{
    /**
     * 保存Token
     *
     * @param $data
     * @return mixed
     */
    public function saveToken($data)
    {
        $data['create_time'] = date('Y-m-d H:i:s', TIMESTAMP);

        $tokenKey = $data['token_key'];
        $tokenData = $this->getTokenByTokenKey($tokenKey);

        if ($tokenData) {
            $this->db->where('token', $data['token']);
            return $this->db->update('token_restore', array('token' => $data['token'], 'create_time' => $data['create_time']));
        }

        $this->db->insert('token_restore', $data);
        return $this->db->insert_id();
    }

    /**
     * 获取Token
     *
     * @param $tokenKey
     * @return mixed
     */
    public function getTokenByTokenKey($tokenKey)
    {
        return $this->db->get_where('token_restore', array('token_key' => $tokenKey))->row_array();
    }
}