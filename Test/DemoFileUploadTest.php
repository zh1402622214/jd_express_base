<?php
include_once("../vendor/autoload.php");

use Lop\Api\LopDomainClient;
use Lop\Api\Plugin\Factory\HmacPluginFactory;
use Lop\Api\Plugin\Factory\OAuth2PluginFactory;

use Lop\Api\Request\Test\DemoFileUploadRequest;

$client = new LopDomainClient("https://uat-api.jdl.cn");
$request = new DemoFileUploadRequest();
$request->addFileName("D:\\20201102181107.png");
$lopOauthPlugin = OAuth2PluginFactory::produceLopPlugin($client->getServerUrl(),"appKey", "appSecret", "refreshToken");
$request->addLopPlugin($lopOauthPlugin);
$resp = $client->uploadFile($request);
echo json_encode($resp);
echo PHP_EOL;