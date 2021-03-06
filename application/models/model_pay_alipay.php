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
        /*
        $arr = array(
            'partner' => ALIPAY_PARTNER,
            'seller' => ALIPAY_SELLER,
            'out_trade_no' => $data['order_sn'],
            'subject' => 'account_pay',
            'body' => 'pay',
            'total_fee' => $data['amount'],
            'notify_url' => ALIPAY_NOTIFY_URL,
        );
        //*/

        $signData = "partner=" . "\"" . ALIPAY_PARTNER ."\"";
        $signData .= "&";
        $signData .= "seller=" . "\"" .ALIPAY_SELLER . "\"";
        $signData .= "&";
        $signData .= "out_trade_no=" . "\"" . $data['order_sn'] ."\"";
        $signData .= "&";
        $signData .= "subject=" . "\"" . 'account_pay' ."\"";
        $signData .= "&";
        $signData .= "body=" . "\"" . 'pay' ."\"";
        $signData .= "&";
        $signData .= "total_fee=" . "\"" . fPrice($data['amount']) ."\"";
        $signData .= "&";
        $signData .= "notify_url=" . "\"" . ALIPAY_NOTIFY_URL ."\"";

        $str = ($signData);
        $sign = $this->aliPaySign($str);

        //$string = "<result><is_success>T</is_success><content>" . $str . "</content><sign>" . $sign . "</sign></result>";
        $string = array(
            'content' => urlencode($str),
            'sign' => urlencode($sign),
        );
        //return $data['order_sn'];
        return $string;
    }

    public function response()
    {
        $notify_data = $_REQUEST['notify_data'];//$this->input->get_post('notify_data');
        $sign = $_REQUEST['sign'];//$this->input->get_post('sign');
        $sign_type = 'RSA';

        /*/
        $notify_data = '<notify><partner>2088901264408851</partner><discount>0.00</discount><payment_type>1</payment_type><subject>account_pay</subject><trade_no>2013050935789597</trade_no><buyer_email>18610687243</buyer_email><gmt_create>2013-05-09 18:23:44</gmt_create><quantity>1</quantity><out_trade_no>10000045</out_trade_no><seller_id>2088901264408851</seller_id><trade_status>TRADE_FINISHED</trade_status><is_total_fee_adjust>N</is_total_fee_adjust><total_fee>1.00</total_fee><gmt_payment>2013-05-09 18:23:45</gmt_payment><seller_email>meiyi@meiyiad.com</seller_email><gmt_close>2013-05-09 18:23:45</gmt_close><price>1.00</price><buyer_id>2088702616318972</buyer_id><use_coupon>N</use_coupon></notify>';
        $sign = 'qFZs+Fn3D7zHqzTfkfhfiFm0Vva7ydf/31IfRJnyIheO9AJuUCBYKc/9D6U2/iDPKN+wHt92LANACt8ZIzR/haONHEZXbiY3kTUDbNkX1WGhewjfcK+pqZTQKPiklIYM5C0Xl2Gg3yUQLiYociv68+zVOJQVkk9qbVAfa9cZdHo=';
        $sign_type = 'RSA';
        //*/

        $rData = array();
        $isVerify = $this->aliPayVerify('notify_data='.$notify_data, $sign);
        if (!$isVerify) {
            $rData['status'] = '0';
            return $rData;
        }

        //获取交易状态
        $nData = (array)$this->aliPayGetDataForXML($notify_data , '/notify');

        //p($nData);

        $data['merchant_id'] = $nData['partner'];
        $data['order_sn'] = $nData['out_trade_no'];
        $data['amount'] = ($nData['total_fee'] * 100);
        $data['bank_order_sn'] = $nData['trade_no'];
        $data['buy_email'] = $nData['buyer_email'];
        $data['pay_type'] = 'alipay';

        $data['status'] = ($nData['trade_status'] == "TRADE_FINISHED") ? 1 : 2;

        return $data;
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