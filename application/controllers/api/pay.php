<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-4-1
 * Time: 上午11:11
 * To change this template use File | Settings | File Templates.
 */

class pay  extends MY_Controller
{
    public function index()
    {
        $amount = intval($this->input->get_post('amount'));
        $isInvoice = intval($this->input->get_post('is_invoice'));
        $postMode = intval($this->input->get_post('post_mode'));
        $payable = $this->input->get_post('payable');
        $content = $this->input->get_post('content');
        $mailingAddress = $this->input->get_post('mailing_address');
        $token = $this->input->get_post('token');
        $payType = $this->input->get_post('pay_type');

        log_message('PAYLOG', $payType.'-'.$amount.'-'.$token.'-'.print_r($_REQUEST,true));

        $response = array('code' => '0', 'msg' => '生成成功');

        do {
            if (empty ($amount) || empty ($token) || empty ($payType)) {
                $response = error(10001);//参数不全
                break;
            }

            if ($amount < 100) {
                $response = error(10038);//金额大小(低于1元)
                break;
            }

            $payChannel = config_item('pay_channel');
            if (!in_array($payType, $payChannel)) {
                $response = error(10040);//支付渠道不存在
                break;
            }

            $uInfo = $this->analyzeToken($token);
            if (!$uInfo) {
                $response = error(10011);//用户未登陆
                break;
            }

            $uId = $uInfo[0];
            if (!$uId) {
                $response = error(10009);//错误的token
                break;
            }

            $this->load->model('model_user', 'user');
            $uData = $this->user->getUserById($uId, 'uid, uname, password, realname, amount, sex, phone,binding_type, is_del, create_time');
            if (empty ($uData)) {
                $response = error(10007);//用户不存在
                break;
            }

            if ($uData['password'] != $uInfo[2]) {
                $response = error(10008);//密码错误
                break;
            }

            if ($uData['is_del'] == '1') {
                $response = error(10010);//用户已禁用
                break;
            }

            $payData = array(
                'uid' => $uData['uid'],
                'uname' => $uData['uname'],
                'pay_amount' => $amount,
                'pay_status' => '0',
                'source' => '0',
                'pay_type' => '1',
                'pay_channel' => '1',
                'opera_people' => '0',
                'is_post' => $isInvoice,
                'post_mode' => $postMode,
                'invoice' => $payable,
                'content' => $content,
                'post_address' => $mailingAddress,
                'post_status' => '0',
            );

            $this->load->model('model_pay', 'pay');
            $payId = $this->pay->savePay($payData);
            if (!$payId) {
                $response = error(10039);//生成订单失败
                break;
            }

            $requestData = array(
                'order_sn' => $payId,
                'order_time' => date('YmdHis', TIMESTAMP),
                'amount' => $amount,
                'desc' => 'descript',
                'time_out' => '',
            );
            $payType = strtolower($payType);
            switch ($payType) {
                case 'alipay':
                    $this->load->model('model_pay_alipay', 'mpay');
                    $html = $this->mpay->request( $requestData );
                    break;
                default:
                    $this->load->model('model_pay_unionpay', 'mpay');//p($this->mpay);
                    $html = $this->mpay->request( $requestData );
                    break;
            }

            //$html = $this->pay->request( $payData );

            $response['data'] = $html;
        } while (false);

        $this->json_output($response);
    }

    public function payBack()
    {
        $xmlPost = file_get_contents('php://input');
        if (empty ($xmlPost)) {
            $xmlPost = file_get_contents('php://input', 'r');
        }

        log_message("PAYLOG", print_r($_REQUEST, true)."\n\n".print_r($xmlPost, true)."\n\n\n");

        //$response = array('error' => '0', 'msg' => '支付成功', 'code' => 'pay_success');

        do {
            //未知的支付渠道
            $paymentChannel = strtolower($this->checkPaymentChannel());
            if (empty ($paymentChannel) || $paymentChannel == '') {
                //$response = error(30019);
                //break;
            }

            $this->load->model('model_pay', 'pay');
            $this->load->model("model_pay_{$paymentChannel}", 'payment_channel');
            //$this->load->model("model_pay_unionpay", 'payment_channel');
            $payResult = $this->payment_channel->response();

            //0 签名错误
            if ($payResult['status'] == '0') {
                $this->pay->savePay(array('pay_status' => '3'), $payResult['order_sn']);
                log_message("PAYLOG", print_r($_REQUEST, true)."\n\n".print_r($xmlPost, true).'sign_error!'."\n\n\n");
                break;
            }

            //2 订单支付失败
            if ($payResult['status'] == '2') {
                log_message("PAYLOG", print_r($_REQUEST, true)."\n\n".print_r($xmlPost, true).'order_failed!'."\n\n\n");
                break;
            }

            //未知的订单
            $orderInfo = $this->pay->getPayById($payResult['order_sn']);
            if (empty ($orderInfo)) {
                log_message("PAYLOG", print_r($_REQUEST, true)."\n\n".print_r($xmlPost, true).'unknown_order!'."\n\n\n");
                break;
            }

            //是已支付完成
            if ($orderInfo['pay_status'] == '1') {
                break;
            }

            //支付金额有误 此处判断用于金额是否相等，如果用户多支付了钱则不理会，直接更新订单成功
            if ($payResult['amount'] < ($orderInfo['pay_amount'])) {
                log_message("PAYLOG", print_r($_REQUEST, true)."\n\n".print_r($xmlPost, true).'amount_error!'."\n\n\n");
                break;
            }

            $this->pay->savePay(array('pay_status' => '1'), $payResult['order_sn']);
            //echo 'success';
        } while (false);

    }

    /**
     * 检查支付渠道
     *
     * @return string
     */
    private function checkPaymentChannel()
    {
        do {
            $notifyData = $this->input->get_post('notify_data');

            if (!empty ($notifyData)) {
                $payChannel = 'alipay';
            } else {
                $payChannel = 'unionpay';
            }
        } while (false);

        return $payChannel;
    }


    public function updatePay()
    {

    }
}