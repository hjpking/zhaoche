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

        log_message('PAYLOG', $payType.'-'.$amount.'-'.$token);

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
                'order_sn' => '10001002',
                'order_time' => date('YmdHis', TIMESTAMP),
                'amount' => 10,
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
        log_message("PAYLOG",$xmlPost);
            log_message("PAYLOG", print_r($_SERVER,true)."\n".print_r($_GET,true)."\n".print_r($_POST,true)."\n\n\n");

        $response = array('error' => '0', 'msg' => '支付成功', 'code' => 'pay_success');

        do {
            //未知的支付渠道
            $paymentChannel = strtolower($this->checkPaymentChannel());
            if (empty ($paymentChannel) || $paymentChannel == '') {
                //$response = error(30019);
                //break;
            }

            $this->load->model('model_order', 'order');
            //$this->load->model("model_pay_{$paymentChannel}", 'payment_channel');
            $this->load->model("model_pay_unionpay", 'payment_channel');
            $payResult = $this->payment_channel->response();
exit;
            //2 签名错误
            if ($payResult['status'] == 2) {
                $data = array('is_pay' => 2, 'paid' => 0, 'need_pay' => $payResult['amount'], 'status' => 1, 'defray_type' => $payResult['pay_channel']);
                $this->order->updateOrderByOrderSn($data, $payResult['order_sn']);

                $response = error(30020);
                $response['order_sn'] = $payResult['order_sn'];
                break;
            }

            //3 订单支付失败
            if ($payResult['status'] == 3) {
                $data = array('is_pay' => 2, 'paid' => 0, 'need_pay' => $payResult['amount'], 'status' => 1, 'defray_type' => $payResult['pay_channel']);
                $this->order->updateOrderByOrderSn($data, $payResult['order_sn']);

                $response = error(30021);
                $response['order_sn'] = $payResult['order_sn'];
                break;
            }

            //未知的订单
            $orderInfo = $this->order->getOrderByOrderSn($payResult['order_sn']);
            if (empty ($orderInfo)) {
                $response = error(30022);
                $response['order_sn'] = $payResult['order_sn'];
                break;
            }

            //是已支付完成
            if ($orderInfo['is_pay'] == '1') {
                $response['order_sn'] = $payResult['order_sn'];
                break;
            }
//d(($payResult['amount'] >= ($orderInfo['after_discount_price'] - $orderInfo['paid'])));exit;
            //支付金额有误 此处判断用于，如果用户多支付了钱，更新订单成功
            //if (($orderInfo['after_discount_price'] - $orderInfo['paid']) != $payResult['amount']) {
            if ($payResult['amount'] < ($orderInfo['after_discount_price'] - $orderInfo['paid'])) {
                $data = array('is_pay' => 2, 'paid' => 0, 'need_pay' => $payResult['amount'], 'status' => 1, 'defray_type' => $payResult['pay_channel']);
                $this->order->updateOrderByOrderSn($data, $payResult['order_sn']);

                $response = error(30023);
                $response['order_sn'] = $payResult['order_sn'];
                break;
            }

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
        $payChannel = '';

        do {
            $merchantId = $this->input->get_post('p1_MerId');
            $yeePayMerchantId = config_item('yeepay_merchant_id');
            if ( ($merchantId !== false) && ($merchantId == $yeePayMerchantId) )
            {
                $payChannel = 'unionpay';
            }

            $merchantId = $this->input->get_post('seller_id');
            $aliPayMerchantId = config_item('alipay_merchant_id');
            if ( ($merchantId !== false) && $merchantId == $aliPayMerchantId) {
                $payChannel = 'Alipay';
            }

        } while (false);

        return $payChannel;
    }
}