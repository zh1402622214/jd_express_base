<?php
include_once("../vendor/autoload.php");
use Lop\Api\LopClient;
use Lop\Api\Request\Test\LopEchoLopRequest;

//测试demo
$c = new LopClient();
$c->appKey = "*****";
$c->appSecret = "*****";
$c->accessToken = "*****";
$c->serverUrl = "*****";  //网关入口地址
$request = new LopEchoLopRequest();
$request->setMsg("test");
    //参数校验 
$request->check();
$response = $c->execute($request, $c->accessToken);
printf(json_encode($response, JSON_UNESCAPED_UNICODE));

