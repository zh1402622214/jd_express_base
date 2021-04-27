<?php


namespace Lop\Api\Plugin\Template;


use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\Plugin\LopPlugin;
use Lop\Api\Plugin\LopPluginTemplate;
use Lop\Api\Request\DomainAbstractRequest;

class FileUploadTemplate extends LopPluginTemplate
{

    public function buildHeaderParams(DomainHttpParam $httpParam, DomainAbstractRequest $domainRequest, LopPlugin $lopPlugin)
    {
        // TODO: Implement buildHeaderParams() method.
    }

    public function buildUrlArgs(DomainHttpParam $httpParam, DomainAbstractRequest $domainRequest, LopPlugin $oAuth2Plugin)
    {
        $sysParams = array();
        $sysParams["access_token"]=$oAuth2Plugin->getAccessToken();
        $sysParams["app_key"]=$oAuth2Plugin->getAppKey();
        $sysParams["method"]=$domainRequest->getApiMethod();
        $sysParams["timestamp"]=$domainRequest->getTimestamp();
        //$sysParams["timestamp"]=urlencode("2020-11-06 22:03:00");
        $sysParams["v"]=$domainRequest->getVersion();

        $signResult = $this->sign($sysParams, $oAuth2Plugin->getAppSecret());
        $httpParam->addUrlArg("LOP-DN", $domainRequest->getDomain());
        $httpParam->addUrlArg("sign", $signResult);

        $sysParams["timestamp"]=urlencode($sysParams["timestamp"]);
        $httpParam->addUrlArgs($sysParams);
    }

    private function sign($pmap=array(), $appSecret){
        ksort($pmap);
        $sb = $appSecret;
        foreach ($pmap as $k=>$v){
            if ("@" != substr($v, 0, 1)) {
                $sb .= $k.$v;
            }
        }
        unset($k,$v);
        $sb .= $appSecret;
        $result = strtoupper(md5($sb));
        return $result;
    }

    public function buildUrlPath(DomainHttpParam $httpParam, DomainAbstractRequest $domainRequest, LopPlugin $lopPlugin)
    {
        $httpParam->setUrlPath($domainRequest->getApiMethod());
    }
}