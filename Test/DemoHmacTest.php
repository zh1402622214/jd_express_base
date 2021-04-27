
<?php
include_once("../vendor/autoload.php");

use Lop\Api\LopDomainClient;

use Lop\Api\Plugin\Factory\HmacPluginFactory;
use Lop\Api\Request\Test\DemoHmacRequest;
use Lop\Api\Domain\CertKey;

echo gmdate("D, d M Y H:i:s", time())." GMT";
$client = new LopDomainClient("http://test.lop-gateway.jd.com");
$request = new DemoHmacRequest();
$domainDemo = new CertKey();
$domainDemo->setId(12);
$request->setCertKey($domainDemo);

$lopPlugin = HmacPluginFactory::produceLopPlugin(true,"username","pwd","hmac-sha1");
$request->addLopPlugin($lopPlugin);

$resp = $client->execute($request);
echo json_encode($resp,JSON_UNESCAPED_UNICODE);
echo PHP_EOL;