<?php
include_once("../vendor/autoload.php");
include_once("MessageHandlerDemo.php");
use Lop\WebSocket\Sync\LopMsgClient;
//测试demo
#$server_url = "wss://uat-jms.jdwl.com/json"; // UAT
#$server_url = "wss://jms.jdwl.com/json";  //线上
#$server_url="ws://11.91.143.177/json";
$server_url="wss://uat-cloud-jms.jdl.cn/json";
date_default_timezone_set("PRC");
$client = new LopMsgClient("d8fa999f57444b4593eff3331dc25f55", "8137890af2ef409f865a621f8bd0f346", "default");

$client->setMessageHandle(new MessageHandlerDemo(),$client);
$client->connect($server_url);
//启动运行
$client->startRun();


