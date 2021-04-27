<?php


namespace Lop\Api\Plugin\Template;


use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\Plugin\LopPlugin;
use Lop\Api\Plugin\LopPluginTemplate;
use Lop\Api\Request\DomainAbstractRequest;

class OAuth2Template extends LopPluginTemplate
{
    private static $JSON_PARAM_KEY = "param_json";

    public function buildHeaderParams(DomainHttpParam $httpParam, DomainAbstractRequest $domainRequest, LopPlugin $lopPlugin)
    {
        // TODO: Implement buildHeaderParams() method.
    }

    public function buildUrlArgs(DomainHttpParam $httpParam, DomainAbstractRequest $domainRequest, LopPlugin $oAuth2Plugin)
    {
        $sysParams = array();
        $pmap = array();
        $pmap[self::$JSON_PARAM_KEY]=$httpParam->getBodyContent();
        $sysParams["method"]=$domainRequest->getApiMethod();
        $sysParams["access_token"]=$oAuth2Plugin->getAccessToken();
        $sysParams["app_key"]=$oAuth2Plugin->getAppKey();
        $sysParams["timestamp"]=urlencode($domainRequest->getTimestamp());
        $sysParams["v"]=$domainRequest->getVersion();
        $pmap=array_merge($pmap,$sysParams);

        $signResult = $this->sign($pmap, $oAuth2Plugin->getAppSecret());
        $httpParam->addUrlArg("LOP-DN", $domainRequest->getDomain());
        $pmap["timestamp"]=urlencode($pmap["timestamp"]);
        unset($pmap[self::$JSON_PARAM_KEY]);
        $httpParam->addUrlArg("sign", $signResult);
        $httpParam->addUrlArgs($pmap);
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