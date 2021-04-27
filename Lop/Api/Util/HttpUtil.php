<?php


namespace Lop\Api\Util;


use Exception;

class HttpUtil
{
    private static $default_gzip_min_length = 102400;
    public static function curl($url,$headers=array(), $postBody = array(), $connectTimeout,$readTimeout)
    {
        $curlHeaders = array();
        foreach ((array)$headers as $key=>$value){
            array_push($curlHeaders,$key.":".$value);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($readTimeout>=0) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $readTimeout);
        }
        if ($connectTimeout>=0) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connectTimeout);
        }
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_array($postBody) && 0 < count($postBody)) {
            curl_setopt($ch, CURLOPT_POST,1);
            $postBodyString = json_encode($postBody,true);
            //如果请求体大于1K，启用gzip压缩
            // echo $postBodyString."\n";
            if (strlen($postBodyString) >= self::$default_gzip_min_length) {
                $postBodyString = gzencode($postBodyString);
                //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Encoding:gzip','Content-type: application/json'));
                array_merge($curlHeaders,array('Content-Encoding:gzip','Content-type: application/json'));
            }else{
                //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                array_merge($curlHeaders,array('Content-type: application/json'));
            }
            if(!empty($curlHeaders)){
                curl_setopt($ch, CURLOPT_HTTPHEADER, $curlHeaders);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postBodyString);
        }
        //支持gzip响应自动解压
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding:gzip'));
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "lop-sdk-php/1.0");
        //查看响应头
        //curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($response, $httpStatusCode);
            }
        }
        //echo "response header: ".$response;
        curl_close($ch);
        return $response;
    }

    public static function uploadFile($url,$headers=array(), $files=array())
    {
        //php 5.5以上的用法
        if (class_exists('\CURLFile')) {
            foreach ($files as $index => $file) {
                $postData['file[' . $index . ']'] = curl_file_create(
                    realpath($file),
                    mime_content_type($file),
                    basename($file)
                );
            }
        } else {
            foreach ($files as $index => $file) {
                $postData['file[' . $index . ']'] = '@'.realpath($file).";type=".mime_content_type($file).";filename=".basename($file);
            }
        }
        $ch = curl_init();
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }else{
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate'));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($response, $httpStatusCode);
            }
        }
        //echo "response header: ".$response;
        curl_close($ch);
        return $response;
    }

    public static function downLoadFile($url,$headers=array(),$fileNames)
    {

        $ch = curl_init();
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true );
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }else{
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        $fp_output = fopen($fileNames, 'w+');
        curl_setopt($ch, CURLOPT_FILE, $fp_output);
        curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($httpStatusCode);
            }
        }
        //echo "response header: ".$response;*/
        curl_close($ch);
        fclose($fp_output);
    }
}