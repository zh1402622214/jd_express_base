<?php
namespace Lop\Api;
use Exception;
use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\Request\DomainAbstractRequest;
use Lop\Api\Request\DomainFileAbstractRequest;
use Lop\Api\Util\HttpUtil;

class LopDomainClient
{
    public $format = "json";
    private $serverUrl = "";
    /**
     * LopDomainClient constructor.
     * @param string $format
     */
    public function __construct($serverUrl)
    {
        $this->serverUrl = $serverUrl;
    }

    public function execute(DomainAbstractRequest $request)
    {
        //发起HTTP请求
        try {
            $httpParam = $request->buildDomainHttpParam($this);
            if($request->getUseJosAuth()){
                $httpParam->addHeader("X-UseJosAuth","true");
            }
            $resp = HttpUtil::curl($this->getRequestUrl($httpParam),$httpParam->getHeaders(),$httpParam->getBodyContentArray(),0,0);
        } catch (Exception $e) {
            printf($e->getMessage());
            return null;
        }
        //解析JD返回结果
        $respWellFormed = false;
        if ("json" == $this->format) {
            $respObject = json_decode("[".$resp."]",true);
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

    public function uploadFile(DomainFileAbstractRequest $request)
    {
        //发起HTTP请求
        try {
            $httpParam = $request->buildDomainHttpParam($this);
            $resp = HttpUtil::uploadFile($this->getRequestUrl($httpParam),$httpParam->getHeaders(),$request->getFileNames());
        } catch (Exception $e) {
            printf($e->getMessage());
            return null;
        }
        //解析JD返回结果
        $respWellFormed = false;
        if ("json" == $this->format) {
            $respObject = json_decode("[".$resp."]",true);
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

    public function downLoadFile(DomainFileAbstractRequest $request)
    {
        //发起HTTP请求
        try {
            $fileNames = $request->getFileNames();
            $httpParam = $request->buildDomainHttpParam($this);
            HttpUtil::downLoadFile($this->getRequestUrl($httpParam),$httpParam->getHeaders(),array_shift($fileNames));
        } catch (Exception $e) {
            printf($e->getMessage());
            throw $e;
        }
    }

    private function getRequestUrl(DomainHttpParam $httpParam) {
        $url = $this->serverUrl;
        $urlPath = $httpParam->getUrlPath();
        if(!empty($urlPath)){
            $url = $url.$urlPath;
        }
        $urlArgsQuery = $httpParam->getUrlArgsQuery();
        if(!empty($urlArgsQuery)){
            $url = $url."?".$urlArgsQuery;
        }
        return $url;
    }

    /**
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

}