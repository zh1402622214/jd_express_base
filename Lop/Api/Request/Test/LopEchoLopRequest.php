<?php
namespace Lop\Api\Request\Test;

use Lop\Api\LopDomainClient;
use Lop\Api\Request\DomainAbstractRequest;
use Lop\Api\Util\RequestCheckUtil;

/**
* test
*/
class LopEchoLopRequest extends DomainAbstractRequest {
   /**
    * 测试报文消息
    */
    private $msg;


    public function setMsg($msg){
        $this->msg=$msg;
    }

    public function getMsg(){
         return $this->msg;
    }


    public function getApiMethodName(){
        return "jingdong.test.echo";
    }

    public function getAppJsonParams() {
        $apiParams = array();
        $apiParams[0] = $this->msg;
        return json_encode($apiParams);
    }

    public function getBodyObject(){
        $apiParams = array();
        $apiParams[0] = $this->msg;
        echo json_encode($apiParams);
        echo PHP_EOL;
        return $apiParams;
    }

    public function getDomain(){
        return "test.domain";
    }
    
    public function check() {
        RequestCheckUtil::checkNotNull($this->msg,"msg");

       RequestCheckUtil::checkString($this->msg,"msg");
    }

    public function getApiMethod()
    {
        // TODO: Implement getApiMethod() method.
    }

    public function buildDomainHttpParam(LopDomainClient $client)
    {
        // TODO: Implement buildDomainHttpParam() method.
    }

    public function getDomainHttpParam()
    {
        // TODO: Implement getDomainHttpParam() method.
    }
}