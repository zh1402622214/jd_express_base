<?php


namespace Lop\Api\Request\Test;


use Lop\Api\LopDomainClient;
use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\Plugin\Factory\OAuth2PluginFactory;
use Lop\Api\Plugin\Template\OAuth2Template;
use Lop\Api\Request\DomainAbstractRequest;
use Lop\Api\Util\RequestCheckUtil;

class DemoOAuth2Request extends DomainAbstractRequest
{
    private  $demoOAuth;
    private $domainHttpParam;
    /**
     * @return mixed
     */
    public function getDemoOAuth()
    {
        return $this->demoOAuth;
    }

    /**
     * @param mixed $demoOAuth
     */
    public function setDemoOAuth($demoOAuth)
    {
        $this->demoOAuth = $demoOAuth;
    }
    public function getApiMethod() {
        return "/testSensField";
    }
    public function getDomain(){
        return "test.jlop.com";
    }

    public function getBodyObject(){
        $apiParams = array();
        $apiParams[0] = $this->demoOAuth;
        return $apiParams;
    }

    public function check() {
        RequestCheckUtil::checkObject($this->demoOAuth,"demoOAuth");
        if(isset($this->demoOAuth) && method_exists($this->demoOAuth,"check")) {
            $this->demoOAuth->check();
        }
    }

    public function buildDomainHttpParam(LopDomainClient $client)
    {
        $httpParam = new DomainHttpParam();
        $lopPlugin = OAuth2PluginFactory::produceLopPlugin($client->getServerUrl(),
            "2530e1fa591240368a43e9557489c0dd",
            "11a361f91dfe48a39a2ad80c08bb3592",
            "a091088ea69a45d28a0852df0ff34307");
        $lopPluginTemplate = new OAuth2Template();
        $lopPluginTemplate->buildHttpParams($httpParam,$this,$lopPlugin);
        $this->domainHttpParam=$httpParam;
        return $httpParam;
    }

    public function getDomainHttpParam()
    {
        return $this->domainHttpParam;
    }

}