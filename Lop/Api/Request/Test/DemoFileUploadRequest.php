<?php


namespace Lop\Api\Request\Test;


use Lop\Api\LopDomainClient;
use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\Plugin\Entity\HmacPlugin;
use Lop\Api\Plugin\Entity\OAuth2Plugin;
use Lop\Api\Plugin\Template\FileUploadTemplate;
use Lop\Api\Plugin\Template\HmacTemplate;
use Lop\Api\Plugin\Template\OAuth2Template;
use Lop\Api\Request\DomainFileAbstractRequest;

class DemoFileUploadRequest extends DomainFileAbstractRequest
{
    private $domainHttpParam;

    public function getApiMethod() {
        return "/lop/mulfile/upload";
    }
    public function getDomain(){
        return "las.im.jd.com";
    }





    public function buildDomainHttpParam(LopDomainClient $client)
    {
        $httpParam = new DomainHttpParam();
        /*$lopPlugin = OAuth2PluginFactory::produceLopPlugin($client->getServerUrl(),
            "2530e1fa591240368a43e9557489c0dd",
            "11a361f91dfe48a39a2ad80c08bb3592",
            "a091088ea69a45d28a0852df0ff34307");
        $lopPluginTemplate = new OAuth2Template();
        $lopPluginTemplate->buildHttpParams($httpParam,$this,$lopPlugin);*/

        //$lopPluginTemplate = new FileUploadTemplate();
        //$lopPluginTemplate->buildHttpParams($httpParam,$this,null);
        $httpParam->addHeader("LOP-DN", $this->getDomain());
        $httpParam->setUrlPath($this->getApiMethod());
        $lopPluginList = $this->getLopPluginList();
        foreach ($lopPluginList as $index => $lopPlugin) {
            if($lopPlugin instanceof OAuth2Plugin){
                $lopPluginTemplate = new FileUploadTemplate();
                $lopPluginTemplate->buildHttpParams($httpParam,$this,$lopPlugin);
            }
        }
        $this->domainHttpParam=$httpParam;
        return $httpParam;
    }

    public function getDomainHttpParam()
    {
        return $this->domainHttpParam;
    }

    public function getBodyObject()
    {
        // TODO: Implement getBodyObject() method.
    }
}