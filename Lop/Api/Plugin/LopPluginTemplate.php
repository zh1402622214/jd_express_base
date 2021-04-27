<?php


namespace Lop\Api\Plugin;


use Lop\Api\Request\DomainAbstractRequest;

abstract class LopPluginTemplate
{
    public abstract function buildHeaderParams(DomainHttpParam $httpParam,DomainAbstractRequest $domainRequest,LopPlugin $lopPlugin);
    public abstract function buildUrlArgs(DomainHttpParam $httpParam,DomainAbstractRequest $domainRequest,LopPlugin $lopPlugin);
    public abstract function buildUrlPath(DomainHttpParam $httpParam,DomainAbstractRequest $domainRequest,LopPlugin $lopPlugin);
    public function buildContent(DomainHttpParam $httpParam,DomainAbstractRequest $domainRequest,LopPlugin $lopPlugin){
        if($domainRequest->getBodyObject()!=null){
            $httpParam->addBodyArgs($domainRequest->getBodyObject());
        }
        if($domainRequest->getAppJsonParams()!=null){
            $httpParam->addBodyJsonArgs($domainRequest->getAppJsonParams());
        }
    }

    public function buildHttpParams(DomainHttpParam $httpParam,DomainAbstractRequest $domainRequest,LopPlugin $lopPlugin){
        $this->buildContent($httpParam, $domainRequest,$lopPlugin);
        $this->buildHeaderParams($httpParam, $domainRequest,$lopPlugin);
        $this->buildUrlArgs($httpParam, $domainRequest,$lopPlugin);
        $this->buildUrlPath($httpParam, $domainRequest,$lopPlugin);
    }
}