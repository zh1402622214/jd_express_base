<?php


namespace Lop\Api\Request\Test;
use Lop\Api\LopDomainClient;
use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\Plugin\Entity\HmacPlugin;
use Lop\Api\Plugin\Entity\OAuth2Plugin;
use Lop\Api\Plugin\Factory\HmacPluginFactory;
use Lop\Api\Plugin\Template\HmacTemplate;
use Lop\Api\Plugin\Template\OAuth2Template;
use Lop\Api\Request\DomainAbstractRequest;

class DemoHmacRequest extends DomainAbstractRequest
{
    private $certKey;
    private $domainHttpParam;
    /**
     * @return mixed
     */
    public function getCertKey()
    {
        return $this->certKey;
    }

    /**
     * @param mixed $certKey
     */
    public function setCertKey($certKey)
    {
        $this->certKey = $certKey;
    }
    public function getApiMethod() {
        return "/certService/queryCertKey";
    }
    public function getDomain(){
        return "cert-gateway.jd.com";
    }

    public function getBodyObject(){
        $apiParams = array();
        $apiParams[0] = $this->certKey;
        return $apiParams;
    }

    public function buildDomainHttpParam(LopDomainClient $client)
    {
        $httpParam = new DomainHttpParam();
        $lopPluginList = $this->getLopPluginList();
        foreach ($lopPluginList as $index => $lopPlugin) {
            if($lopPlugin instanceof HmacPlugin){
                $lopPluginTemplate = new HmacTemplate();
                $lopPluginTemplate->buildHttpParams($httpParam,$this,$lopPlugin);
            }
            if($lopPlugin instanceof OAuth2Plugin){
                $lopPluginTemplate = new OAuth2Template();
                $lopPluginTemplate->buildHttpParams($httpParam,$this,$lopPlugin);
            }
        }
        if ($this->domainHttpParam != null) {
            $httpParam->addHeaders($this->domainHttpParam->getHeaders());
            $httpParam->addUrlArgs($this->domainHttpParam->getUrlArgs());
        }
        $this->domainHttpParam=$httpParam;
        return $httpParam;
    }

    public function getDomainHttpParam()
    {
       return $this->domainHttpParam;
    }

    public function setDomainHttpParam($domainHttpParam)
    {
        return $this->domainHttpParam=$domainHttpParam;
    }


}