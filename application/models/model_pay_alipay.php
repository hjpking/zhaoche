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
            'out_trade_no' => $data['pay_id'],
            'subject' => 'account_pay',
            'body' => 'pay',
            'total_fee' => $data['amount'],
            'notify_url' => ALIPAY_NOTIFY_URL,
        );

        $str = http_build_query($arr);
        $sign = $this->aliPaySign($str);

        $string = "<result><is_success>T</is_success><content>" . $str . "</content><sign>" . $sign . "</sign></result>";

        return $string;
    }

    public function response()
    {
        $notify_data = $this->input->get_post('notify_data');
        $sign = $this->input->get_post('sign');

        $isVerify = verify($notify_data, $sign);
        if (!$isVerify) {
            break;
        }

        //获取交易状态
        $trade_status = getDataForXML($notify_data , '/notify/trade_status');
        if($trade_status != "TRADE_FINISHED"){
            break;
            //echo "success";

            //在此处添加您的业务逻辑，作为收到支付宝交易完成的依据
        }


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
        $priKey = file_get_contents(APPPATH.'key/rsa_private_key.pem');

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
        $pubKey = file_get_contents('alipay_public_key.pem');

        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);

        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);

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