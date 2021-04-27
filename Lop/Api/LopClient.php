<?php
namespace Lop\Api;
use CURLFile;
use Exception;

class LopClient
{
    public $serverUrl = "http://api.jd.com/routerjson";
    public $accessToken;
    public $connectTimeout = 0;
    public $readTimeout = 0;
    public $appKey;
    public $appSecret;
    public $version = "2.0";
    public $format = "json";
    private $charset_utf8 = "UTF-8";
    private $json_param_key = "param_json";
    private $default_gzip_min_length = 102400;

    protected function generateSign($params)
    {
        ksort($params);
        $stringToBeSigned = $this->appSecret;
        foreach ($params as $k => $v) {
            if ("@" != substr($v, 0, 1)) {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $this->appSecret;
        //echo "stringToBeSigned: ".$stringToBeSigned."\n";
        return strtoupper(md5($stringToBeSigned));
    }

    public function curl($url, $postFields = null, $fileUpload = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->readTimeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
        }
        if ($this->connectTimeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_array($postFields) && 0 < count($postFields)) {
            $postBodyString = "";
            if (!$fileUpload) {
                foreach ($postFields as $k => $v) {
                    $postBodyString .= $v . "&";
                }
                unset($k, $v);
            }
            curl_setopt($ch, CURLOPT_POST,true);
            //文件上传用multipart/form-data，否则用www-form-urlencoded
            if ($fileUpload) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            } else {
                //如果请求体大于1K，启用gzip压缩
                $postBodyString = substr($postBodyString, 0, -1);
                // echo $postBodyString."\n";
                if (strlen($postBodyString) >= $this->default_gzip_min_length) {
                    $postBodyString = gzencode($postBodyString);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Encoding:gzip','Content-type: application/json'));
                }else{
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postBodyString);
            }
        }
        //支持gzip响应自动解压
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding:gzip'));
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
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

    public function execute($request, $access_token = null)
    {
        //组装系统参数
        if (null != $access_token) {
            $sysParams["access_token"] = $access_token;
        }
        $sysParams["app_key"] = $this->appKey;
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["v"] = $this->version;
        $sysParams["timestamp"] = date("Y-m-d H:i:s");


        //组装需要签名的参数
        $signParams = array();
        foreach ($sysParams as $sysParamKey => $sysParamValue) {
            $signParams[$sysParamKey] = $sysParamValue;
        }
        unset($sysParamKey, $sysParamValue);
        //获取业务参数
        $apiParams = $request->getAppJsonParams();
        //echo "apiParams: ".$apiParams;
        $signParams[$this->json_param_key] = $apiParams;

        //签名
        $sysParams["sign"] = $this->generateSign($signParams);
        //系统参数放入GET请求串
        $requestUrl = $this->serverUrl . "?";
        //ksort($sysParams);
        $count = count($sysParams);
        $index = 0;
        foreach ($sysParams as $sysParamKey => $sysParamValue) {
            $index++;
            $requestUrl .= "$sysParamKey=" . urlencode($sysParamValue);
            if ($index < $count) {
                $requestUrl .= "&";
            }
        }
        unset($sysParamKey, $sysParamValue);
        //echo "url: ".$requestUrl."\n";
        $postParams = array();
        $postParams[$this->json_param_key] = $apiParams;
        $fileUpload = false;
        //文件上传请求
        if (method_exists($request, "getFileParams")) {
            $fileParams = $request->getFileParams();
            if (is_array($fileParams) && count($fileParams) > 0) {
                $fileUpload = true;
                foreach ($fileParams as $fieldKey => $filePath) {
                    $postParams[$fieldKey] = new CURLFile($filePath);
                }
                unset($fieldKey, $filePath);
            }
        }

        //发起HTTP请求
        try {
            $resp = $this->curl($requestUrl, $postParams, $fileUpload);
        } catch (Exception $e) {
            printf($e->getMessage());

            return null;
        }

        //解析JD返回结果
        $respWellFormed = false;
        if ("json" == $this->format) {
            $respObject = json_decode($resp,true);
            if (null !== $respObject) {
                $respWellFormed = true;
                foreach ($respObject as $propKey => $propValue) {
                    $respObject = $propValue;
                }
            }
            //echo $respObject;
        } else if ("xml" == $this->format) {
            $respObject = @simplexml_load_string($resp);
            if (false !== $respObject) {
                $respWellFormed = true;
            }
        }

        //返回的HTTP文本不是标准JSON或者XML，记下错误日志
        if (false === $respWellFormed) {
            // $this->logCommunicationError($sysParams["method"], $requestUrl, "HTTP_RESPONSE_NOT_WELL_FORMED", $resp);
            $result = null;
            // $result->code = 0;
            //$result->msg = "HTTP_RESPONSE_NOT_WELL_FORMED";
            return $result;
        }


        return $respObject;
    }


}