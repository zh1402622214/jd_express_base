<?php


namespace Lop\Api\Util;



use Lop\WebSocket\Sync\Request\Exception;
use Lop\Api\Plugin\Enum\EncryptAlgorithm;

class HmacUtil
{
    public static function getServerTime() {
        return gmdate("D, d M Y H:i:s", time())." GMT";
    }

    /*
     * 获取Hmac签名头信息，调用方需要将此方法生成的头信息放到HTTP头信息中
     * @param $userName 签名用户名
     * @param $secret 签名密钥
     * @param $algorithm 签名算法
     * @param $extendSignProperties 签名扩展属性，可选
     * @return array
     */
    public static function makeHmacHeaders($userName,$secret,$algorithm,array $extendSignProperties){
        if($userName==null || $secret ==null || $algorithm==null ){
            throw new Exception("用户名/密码/签名算法都不能为空");
        }
        $result = array();
        $xdate = self::getServerTime();
        //$xdate = "Thu, 13 Feb 2020 15:53:56";
        $txt="X-Date: " . $xdate;
        $headers="X-Date";
        $result["X-Date"]=$xdate;
        if (!empty($extendSignProperties)) {
            foreach ($extendSignProperties as $key => $value) {
                $txt = $txt . "\n" . $key . ": " . $value;
                $headers = $headers . " " . $key;
                $result[$key]=$value;
            }
        }
        $sign=null;
        if ($algorithm == "hmac-sha1") {
            //hmac-sha1算法获取签名串方法//对摘要进行base64编码
            $sign = base64_encode(hash_hmac("sha1",$txt,$secret,true));
        }else if ($algorithm == "md5-salt") {
            //md5-salt算法获取签名串方法
            $sign = md5($secret.$txt.$secret);
        }else
        {
            throw new Exception("不支持的签名算法:".$algorithm);
        }
        $result["Authorization"]="hmac username=\"".$userName."\", algorithm=\"".$algorithm."\", headers=\"".$headers."\",signature=\"".$sign."\"";
        return $result;
    }
    
    /**
     * 获取hamc签名摘要
     */
    public static function encrypt($encryptText, $secret,$algorithm) {
        $sign=null;
        if ($algorithm == "hmac-sha1") {
            //hmac-sha1算法获取签名串方法//对摘要进行base64编码
            $sign = base64_encode(hash_hmac("sha1",$encryptText,$secret,true));
        }else if ($algorithm == EncryptAlgorithm::HMacSHA1) {
            //md5-salt算法获取签名串方法
            $sign = base64_encode(hash_hmac("sha1",$encryptText,$secret,true));
        }else if ($algorithm == EncryptAlgorithm::HMacMD5) {
            //md5-salt算法获取签名串方法
            $sign = base64_encode(hash_hmac("md5",$encryptText,$secret,true));
        }else if ($algorithm == EncryptAlgorithm::HMacSHA256) {
            //md5-salt算法获取签名串方法
            $sign = base64_encode(hash_hmac("sha256",$encryptText,$secret,true));
        }else if ($algorithm == EncryptAlgorithm::HMacSHA512) {
            //md5-salt算法获取签名串方法
            $sign = base64_encode(hash_hmac("sha512",$encryptText,$secret,true));
        }else
        {
            throw new Exception("不支持的签名算法:".$algorithm);
        }
        return $sign ;
    }
}