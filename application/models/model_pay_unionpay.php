<?php
/**
 * Created by JetBrains PhpStorm.
 * User: evan
 * Date: 13-4-1
 * Time: 下午4:39
 * To change this template use File | Settings | File Templates.
 */

class model_pay_unionpay extends MY_Model
{
    public $attributeArray = '';
    public $nodeArray = '';

    public function request(array $data)
    {
        $merchantOrderId 	= $data['order_sn'];//订单id
        $merchantOrderTime 	= $data['order_time'];//订单时间
        $merchantOrderAmt 	= $data['amount'];//订单金额
        $merchantOrderDesc 	= $data['desc'];//订单描述
        $transTimeout 		= $data['time_out'];//超时时间

        //订单成功后自动回馈地址
        $backEndUrl = UNIONPAY_NOTIFY_URL;

        $xml = $this->unionPayGetXml($merchantOrderId,$merchantOrderTime,$merchantOrderAmt,$merchantOrderDesc,$transTimeout,$backEndUrl);

        $recv = $this->submitByPost(UNIONPAY_SUBMIT_URL, $xml);
        $parse= $this->readXml($recv);
        $html = '';
        if ($parse) {
            //接收成功
            $nodeArray = $this->getNodeArray();
            $sign = $this->rsa($merchantOrderId, $merchantOrderTime,$merchantOrderAmt,$merchantOrderDesc,$transTimeout,$backEndUrl);

            $html = '<?xml version="1.0" encoding="utf-8" ?><upomp application="LanchPay.Req" version="1.0.0"><merchantId>'.UNIONPAY_MY_ID;
            $html .= '</merchantId><merchantOrderId>'.$merchantOrderId.'</merchantOrderId><merchantOrderTime>'.$merchantOrderTime;
            $html .= '</merchantOrderTime><sign>'.$sign.'</sign></upomp>';
            return $html;
        }

        return false;
    }

    public function response()
    {
        $xmlPost = file_get_contents('php://input');
        //若不想网络环境测试，可打开下行注释，进行单元测试，上面一行会报WARNING,不用理会

        // 解析获取到的xml
        $parse=$this->readXml($xmlPost);
        if ($parse) {
            //获取键值对
            $nodeArray = $this->getNodeArray();
            //验签
            $checkIdentifier = "transType=".$nodeArray['transType'].
                "&merchantId=".$nodeArray['merchantId'].
                "&merchantOrderId=".$nodeArray['merchantOrderId'].
                "&merchantOrderAmt=".$nodeArray['merchantOrderAmt'].
                "&settleDate=".$nodeArray['settleDate'].
                "&setlAmt=".$nodeArray['setlAmt'].
                "&setlCurrency=".$nodeArray['setlCurrency'].
                "&converRate=".$nodeArray['converRate'].
                "&cupsQid=".$nodeArray['cupsQid'].
                "&cupsTraceNum=".$nodeArray['cupsTraceNum'].
                "&cupsTraceTime=".$nodeArray['cupsTraceTime'].
                "&cupsRespCode=".$nodeArray['cupsRespCode'].
                "&cupsRespDesc=".$nodeArray['cupsRespDesc'].
                "&respCode=".$nodeArray['respCode'] ;
            $respCode=$this->checkSign($checkIdentifier,UNIONPAY_NOTIFY_PUBLIC_KEY,$nodeArray['sign']);

            $rData = array();
            if($respCode=='0000'){
                //验证成功，写相关处理代码
                $rData['merchant_id'] = $nodeArray['merchantId'];
                $rData['merchantOrderId'] = $nodeArray['merchantOrderId'];
                $rData['merchantOrderAmt'] = $nodeArray['merchantOrderAmt'];
                $rData['merchant_id'] = $nodeArray['merchantId'];
                $rData['merchant_id'] = $nodeArray['merchantId'];
            }
        }
        $data = '';
        return $data;
    }



    private function unionPayGetXml($merchantOrderId, $merchantOrderTime,$merchantOrderAmt,$merchantOrderDesc,$transTimeout,$backEndUrl)
    {

        $merchantPublicCert = $this->getPublicKeyBase64(UNIONPAY_MY_PUBLIC_KEY);
        // echo  $merchantPublicCert;
        $merchantId = UNIONPAY_MY_ID;
        $merchantName=UNIONPAY_MY_NAME;
        $strForSign = "merchantName=" . $merchantName .
            "&merchantId=" . $merchantId .
            "&merchantOrderId=" . $merchantOrderId .
            "&merchantOrderTime=" . $merchantOrderTime.
            "&merchantOrderAmt=" . $merchantOrderAmt .
            "&merchantOrderDesc=" . $merchantOrderDesc.
            "&transTimeout=" .$transTimeout;
        //echo $strForSign;

        $sign = $this->sign($strForSign, UNIONPAY_MY_PRIVATE_KEY, UNIONPAY_MY_PRIKEY_PASSWORD);

        $attrArray = array("application" => "SubmitOrder.Req", "version" => "1.0.0");
        $nodeArray = array("merchantName" => $merchantName,
            "merchantId" => $merchantId,
            "merchantOrderId" => $merchantOrderId,
            "merchantOrderTime" => $merchantOrderTime,
            "merchantOrderAmt"=>$merchantOrderAmt,
            "merchantOrderDesc"=>$merchantOrderDesc,
            "transTimeout"=>$transTimeout,
            "backEndUrl"=>$backEndUrl,
            "sign" => $sign,
            "merchantPublicCert" => $merchantPublicCert);
        $result = $this->writeXml($attrArray, $nodeArray);
        return $result;
    }

    public static function sign($data, $privateKeyPath, $privateKeyPassword)
    {
        $dateMd5 = md5($data, true);
        $p12cert = array();
        $fd = fopen($privateKeyPath, 'r');
        $p12buf = fread($fd, filesize($privateKeyPath));
        fclose($fd);
        if (openssl_pkcs12_read($p12buf, $p12cert, $privateKeyPassword)) {
            $private_key = $p12cert['pkey'];
            //私钥加密
            openssl_private_encrypt($dateMd5, $crypted, $private_key);
            return base64_encode($crypted);
        } else {
            return "";
        }
    }

    private function rsa($merchantOrderId, $merchantOrderTime,$merchantOrderAmt,$merchantOrderDesc,$transTimeout,$backEndUrl)
    {
        $merchantPublicCert = $this->getPublicKeyBase64(UNIONPAY_MY_PUBLIC_KEY);
        $merchantId = UNIONPAY_MY_ID;
        $merchantName=UNIONPAY_MY_NAME;
        $strForSign = "merchantId=" . $merchantId .
            "&merchantOrderId=" . $merchantOrderId .
            "&merchantOrderTime=" . $merchantOrderTime;
        $sign = $this->sign($strForSign, UNIONPAY_MY_PRIVATE_KEY, UNIONPAY_MY_PRIKEY_PASSWORD);
        return $sign;
    }

    public function readXml($xml)
    {
        $xml_parser = xml_parser_create();
        if (!xml_parse($xml_parser, $xml, true)) {
            xml_parser_free($xml_parser);
            return false;
        } else {
            // 创建xml对象
            $document = new DOMDocument("1.0", "utf-8");
            $document->loadXML($xml);
            $document->formatOutput = true;
            // 返回根节点
            $rootElement = $document->documentElement;
            // 根节点子节点集合
            $rootNodeList = $rootElement->childNodes;
            // 获取根节点 及其属性值
            for ($i = 0; $i < $rootElement->attributes->length; $i++) {
                $value = $rootElement->attributes->item($i)->value;
                $key = $rootElement->attributes->item($i)->name;
                // 存放进数组
                $this->attributeArray[$key] = $value;
            }
            for ($i = 0; $i < $rootNodeList->length; $i++) {
                $rootNode = $rootNodeList->item($i);

                if ($rootNode->nodeName == "#text") {
                    continue;
                } else {
                    // 判断子节点是否是叶节点
                    $key = $rootNode->nodeName;
                    $value = $rootNode->nodeValue;
                    // 存放进数组
                    $this->nodeArray[$key] = $value;
                }
            }
            return true;
        }
    }

    // 循环写入xml中的各个节点值(无list节点)
    public static function writeXml($attrArrays, $nodeArrays)
    {
        // 创建xml对象
        $document = new DOMDocument("1.0", "utf-8");
        $document->formatOutput = false;
        // 创建并添加根节点
        $root = $document->createElement("upomp");
        //根节点添加属性
        $id = array_keys($attrArrays);
        for ($i = 0; $i < count($id); $i++) {
            $attribute = $document->createAttribute($id[$i]);
            $attribute->appendChild($document->createTextNode($attrArrays[$id[$i]]));
            $root->appendChild($attribute);
        }
        $document->appendChild($root);
        //添加子节点
        $id = array_keys($nodeArrays);
        for ($i = 0; $i < count($id); $i++) {
            $element = $document->createElement($id[$i]);
            $element->appendChild($document->createTextNode($nodeArrays[$id[$i]]));
            $root->appendChild($element);
        }
        return $document->saveXML();
    }

    public function getNodeArray()
    {
        return $this->nodeArray;
    }

    public function getAttributeArray()
    {
        return $this->attributeArray;
    }

    public function checkSign($data, $publicKeyPath, $cryptedStr)
    {
        $base64Sign = base64_decode($cryptedStr);
        $dateMd5 = md5($data, true);
        $fd1 = fopen($publicKeyPath, 'r');
        $p12buf1 = fread($fd1, filesize($publicKeyPath));
        fclose($fd1);
        $pem = chunk_split(base64_encode($p12buf1), 64, "\n");
        $pem = "-----BEGIN CERTIFICATE-----\n" . $pem . "-----END CERTIFICATE-----\n";
        //获取公钥
        $pem = openssl_pkey_get_public($pem);
        if (openssl_public_decrypt($base64Sign, $crypted, $pem)) {
            //验证签名信息
            if ($crypted == $dateMd5) {
                return "0000";
            } else {
                return "0001";
            }
        } else {
            return "9999";
        }
    }
    public function getPublicKeyBase64($publicKeyPath)
    {
        $fd2 = fopen($publicKeyPath, 'r');
        $p12buf2 = fread($fd2, filesize($publicKeyPath));
        fclose($fd2);
        $pem = base64_encode($p12buf2);
        return $pem;
    }

    function submitByPost($url, $post_string, $timeout = 30)
    {

        $post_string = urlencode($post_string);
        $URL_Info = parse_url($url);
        if (empty($URL_Info["port"]))
            $port = 80;
        else
            $port = $URL_Info["port"];
        if (($fsock = fsockopen($URL_Info["host"], $port, $errno, $errstr, $timeout)) <
            0)
            return "建立通讯连接失败";
        $in = "POST " . $URL_Info["path"] . " HTTP/1.0\r\n";
        $in .= "Accept: */*\r\n";
        $in .= "Host: " . $URL_Info["host"] . "\r\n";
        $in .= "Content-type: text/plain\r\n";
        $in .= "Content-Length: " . strlen($post_string) . "\r\n";
        $in .= "Connection: Close\r\n\r\n";
        $in .= $post_string . "\r\n\r\n";

        //$file = fopen("/var/www/kxgerror/error.txt","a");
        //fwrite($file,$in.chr(13).chr(10).chr(13).chr(10));
        //fclose($file);

        if (!@fwrite($fsock, $in, strlen($in))) {
            fclose($fsock);
            return "发送报文失败";
        }

        $out = "";
        while ($buff = fgets($fsock, 2048)) {
            $out .= $buff;
        }
        fclose($fsock);
        $pos = strpos($out, "\r\n\r\n");
        $head = substr($out, 0, $pos); //http head
        $status = substr($head, 0, strpos($head, "\r\n")); //http status line
        $status_arr = explode(" ", $status, 3);
        if ($status_arr[1] == 200) {
            $body = substr($out, $pos + 4, strlen($out) - ($pos + 4)); //page body
            $body = urldecode($body);
        } else {
            return "http " . $status_arr[1];
        }
        return $body;
    }
}