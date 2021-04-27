<?php
include_once("../vendor/autoload.php");

use Lop\Api\Plugin\Factory\OAuth2PluginFactory;
use Lop\Api\Plugin\Template\OAuth2Template;
use Lop\Api\Plugin\DomainHttpParam;
use Lop\Api\LopDomainClient;

use Lop\Api\Request\Test\DemoOAuth2Request;
use Lop\Api\Domain\DemoOAuth;

$client = new LopDomainClient("http://test.lop-gateway.jd.com");
$request = new DemoOAuth2Request();
$domainDemo = new DemoOAuth();
$domainDemo->setPin("pin");
$domainDemo->setUserPin("userPin");
$domainDemo->setName("name");
$request->setDemoOAuth($domainDemo);
$request->setUseJosAuth(true);
$lopPlugin = OAuth2PluginFactory::produceLopPlugin($client->getServerUrl(), "appKey", "appSecret", "refreshToken");
$request->addLopPlugin($lopPlugin);
$resp = $client->execute($request);
echo json_encode($resp);
echo PHP_EOL;