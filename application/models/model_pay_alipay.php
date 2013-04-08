<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-4-1
 * Time: 下午4:37
 * To change this template use File | Settings | File Templates.
 */

class model_pay_alipay extends MY_Model
{
    public function request(array $data)
    {
        $arr = array(
            'partner' => ALIPAY_PARTNER,
            'seller' => ALIPAY_SELLER,
            'out_trade_no' => $data['order_sn'],
            'subject' => 'account_pay',
            'body' => 'pay',
            'total_fee' => $data['amount'],
            'notify_url' => ALIPAY_NOTIFY_URL,
        );


        $str = http_build_query($arr);
        $sign = $this->aliPaySign($str);

        //$string = "<result><is_success>T</is_success><content>" . $str . "</content><sign>" . $sign . "</sign></result>";
        $string = array(
            'content' => $str,
            'sign' => $sign,
        );
        //return $data['order_sn'];
        return $string;
    }

    public function response()
    {
        $notify_data = $this->input->get_post('notify_data');
        $sign = $this->input->get_post('sign');

        //*
        $notify_data = '<notify><seller_email>meiyi@meiyiad.com</seller_email><partner>2088901264408851</partner><payment_type>1</payment_type><buyer_email>18610687243</buyer_email><trade_no>2013040872796697</trade_no><buyer_id>2088702616318972</buyer_id><quantity>1</quantity><total_fee>0.01</total_fee><use_coupon>N</use_coupon><is_total_fee_adjust>Y</is_total_fee_adjust><price>0.01</price><out_trade_no>1</out_trade_no><gmt_create>2013-04-08 14:33:54</gmt_create><seller_id>2088901264408851</seller_id><subject>AA用车充值</subject><trade_status>WAIT_BUYER_PAY</trade_status><discount>0.00</discount></notify>';
        $sign = 'esc4zLKCwb09JH48wZpcj4rIqFYPnm1ZvI9muQwvBIekuPVRzJq8SL6Gw2qxac5XZVnoA5CtdEEqi/fjPWjycrhgrjDJzMLr4qRTpb7SvCLya3EWjdqnokWc9qUZ/CDZjLSAU84WkAKN9Ids/p+5mQJ5PsH9kMYPUjqDXU63AeQ=';
        $sign_type = 'RSA';
        //*/

        $rData = array();
        $isVerify = $this->aliPayVerify($notify_data, $sign);
        if (!$isVerify) {
            $rData['status'] = '0';
            return $rData;
        }

        //获取交易状态
        $trade_status = getDataForXML($notify_data , '/notify/trade_status');
        $nData = getDataForXML($notify_data , '/notify');



        $rData['merchant_id'] = $nData['partner'];
        $rData['order_sn'] = $nData['out_trade_no'];
        $rData['amount'] = $nData['total_fee'];
        $rData['bank_order_sn'] = $nData['trade_no'];
        $rData['buy_email'] = $nData['buyer_email'];
        $rData['pay_type'] = 'alipay';

        $rData['status'] = ($trade_status == "TRADE_FINISHED") ? 1 : 2;

        return $rData;
    }

    /**
     * RSA签名  签名用商户私钥，必须是没有经过pkcs8转换的私钥
     * 最后的签名，需要用base64编码
     *
     * @param $data 待签名数据
     * @return string 签名
     */
    private function aliPaySign($data)
    {
        //读取私钥文件
        $priKey = file_get_contents(APPPATH.'key/alipay/rsa_private_key.pem');

        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);

        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res);

        //释放资源
        openssl_free_key($res);

        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * RSA验签  验签用支付宝公钥
     *
     * @param $data 待签名数据
     * @param $sign 需要验签的签名
     * @return bool 验签是否通过 bool值
     */
    private function aliPayVerify($data, $sign)
    {
        //读取支付宝公钥文件
        $pubKey = file_get_contents(APPPATH.'key/alipay/alipay_public_key.pem');
        //echo $pubKey;exit;
        //转换为openssl格式密钥

        $res = openssl_get_publickey($pubKey);

        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($data, base64_decode($sign), $pubKey);
d($result);
        //释放资源
        openssl_free_key($res);

        //返回资源是否成功
        return $result;
    }

    /**
     * 通过节点路径返回字符串的某个节点值
     *
     * @param $res_data  XML 格式字符串
     * @param $node  节点
     * @return mixed 返回节点参数
     */
    function aliPayGetDataForXML($res_data,$node)
    {
        $xml = simplexml_load_string($res_data);
        $result = $xml->xpath($node);

        while(list( , $node) = each($result))
        {
            return $node;
        }
    }
}