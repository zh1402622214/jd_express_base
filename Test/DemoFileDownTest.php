<?php
include_once("../vendor/autoload.php");

use Lop\Api\LopDomainClient;

use Lop\Api\Request\Test\DemoFileDownRequest;

$client = new LopDomainClient("https://uat-api.jdl.cn");
$request = new DemoFileDownRequest();
$request->addFileName("D:\\20201102181108.png");
$client->downLoadFile($request);

$fileNames = $request->getFileNames();
$fileName = array_shift($fileNames);
echo "file exists:".file_exists($fileName).PHP_EOL;
echo "fileSize:".filesize($fileName).PHP_EOL;
