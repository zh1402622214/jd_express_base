<?php


namespace Lop\Api\Request\Test;


use Lop\Api\LopDomainClient;
use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\Request\DomainFileAbstractRequest;

class DemoFileDownRequest extends DomainFileAbstractRequest
{
    private $domainHttpParam;

    public function getApiMethod() {
        return "/lop/file/download";
    }
    public function getDomain(){
        return "lop.cloud.sdkfile.com";
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
        $fileNames = $this->getFileNames();
        $httpParam->addUrlArg("LOP-DN", $this->getDomain());
        $httpParam->setUrlPath($this->getApiMethod());
        $httpParam->addHeader("filename",basename(array_shift($fileNames)));

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