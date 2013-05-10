<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-3-10
 * Time: 上午1:03
 * To change this template use File | Settings | File Templates.
 */
class model_competence_correspond extends MY_Model
{
    /**
     * 获取用户权限
     *
     * @param $staffId
     * @param string $field
     * @param array $where
     * @param null $key
     * @return mixed
     */
    public function getUserCompetence($staffId, $field = '*', $where = array(), $key = null)
    {
        $where['staff_id'] = $staffId;
        $data = $this->db->select($field)->get_where('competence_correspond', $where)->result_array($key);

        return $data;
    }

    /**
     * 保存用户权限
     *
     * @param array $data
     * @param $staffId
     */
    public function save(array $data, $staffId)
    {
        $this->db->delete('competence_correspond', array('staff_id' => $staffId));//先清空权限，再往里插入数据。

        $iData = array();
        foreach ($data as $v) {
            $iData[] = array('staff_id' => $staffId, 'competence_id' => $v, 'create_time' => date('Y-m-d H:i:s', TIMESTAMP));
        }

        $this->db->insert_batch('competence_correspond', $iData);
    }

}
