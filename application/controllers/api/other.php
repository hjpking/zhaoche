<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-3-26
 * Time: 下午4:25
 * To change this template use File | Settings | File Templates.
 */

class other extends MY_Controller
{
    /**
     * 获取司机消息
     */
    public function getNotice()
    {
        $chauffeurId = intval($this->input->get_post('chauffeur_id'));

        $start = intval($this->input->get_post('limit'));
        $number = intval($this->input->get_post('offset'));

        $limit = 50;
        $offset = 0;
        $start && $limit = $start;
        $number && $offset = $number;

        $response = array('code' => '0', 'msg' => '获取成功');

        do {
            if (empty ($chauffeurId)) {
                $response = error(10001);//参数不全
                break;
            }

            $field = 'id, title, content, create_time';
            $this->load->model('model_message', 'message');
            $where = array(
                'recipient_id' => $chauffeurId,
                'user_type' => '2',
            );

            $data = $this->message->getMessageSendRecord($limit, $offset, $field, $where);
            $response['data'] = $data;
        } while (false);

        $this->json_output($response);
    }

    /**
     * 版本检测
     */
    public function versionCheck()
    {
        $response = array('code' => '0', 'msg' => '检测成功');

        $this->json_output($response);
    }
}