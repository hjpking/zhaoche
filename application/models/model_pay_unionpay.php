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
    public function request()
    {

    }

    public function response()
    {
        return $data;
    }



    private function unionPayGetXml($merchantOrderId, $merchantOrderTime,$merchantOrderAmt,$merchantOrderDesc,$transTimeout,$backEndUrl)
    {

        $merchantPublicCert = SecretUtils::getPublicKeyBase64(MY_public_key);
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
        $sign = SecretUtils::sign($strForSign, MY_private_key, MY_prikey_password);

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
        $result = XmlUtils::writeXml($attrArray, $nodeArray);
        return $result;
    }

    private function rsa($merchantOrderId, $merchantOrderTime,$merchantOrderAmt,$merchantOrderDesc,$transTimeout,$backEndUrl)
    {
        $merchantPublicCert = SecretUtils::getPublicKeyBase64(MY_public_key);
        $merchantId = UNIONPAY_MY_ID;
        $merchantName=UNIONPAY_MY_NAME;
        $strForSign = "merchantId=" . $merchantId .
            "&merchantOrderId=" . $merchantOrderId .
            "&merchantOrderTime=" . $merchantOrderTime;
        $sign = SecretUtils::sign($strForSign, MY_private_key, MY_prikey_password);
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
    public static function checkSign($data, $publicKeyPath, $cryptedStr)
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
    public static function getPublicKeyBase64($publicKeyPath)
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