<?php


namespace Lop\Api\Plugin\Template;


use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\Plugin\LopPlugin;
use Lop\Api\Plugin\LopPluginTemplate;
use Lop\Api\Request\DomainAbstractRequest;
use Lop\Api\Util\HmacUtil;

class HmacTemplate extends LopPluginTemplate
{

    public function buildHeaderParams(DomainHttpParam $httpParam, DomainAbstractRequest $domainRequest, LopPlugin $lopPlugin)
    {
        $headerParams = array();
        $antiTamperMap = array();
        if($lopPlugin->antiTamper){
            $antiTamperMap["md5-content"]=md5($httpParam->getBodyContent());
        }
        if($lopPlugin->algorithm=="hmac-sha1"){
            $headerParams = HmacUtil::makeHmacHeaders($lopPlugin->username,$lopPlugin->pwd, "hmac-sha1", $antiTamperMap);
        }
        if($lopPlugin->algorithm=="md5-salt"){
            $headerParams = HmacUtil::makeHmacHeaders($lopPlugin->username,$lopPlugin->pwd, "md5-salt", $antiTamperMap);
        }
        $headerParams["LOP-DN"]=$domainRequest->getDomain();
        $httpParam->addHeaders($headerParams);
    }

    public function buildUrlArgs(DomainHttpParam $httpParam, DomainAbstractRequest $domainRequest, LopPlugin $lopPlugin)
    {
        // TODO: Implement buildUrlArgs() method.
    }

    public function buildUrlPath(DomainHttpParam $httpParam, DomainAbstractRequest $domainRequest, LopPlugin $lopPlugin)
    {
       $httpParam->setUrlPath($domainRequest->getApiMethod());
    }

}