<?php
include_once("../vendor/autoload.php");
use Lop\Api\Util\HttpUtil;
use Lop\Api\Util\SM3Util;
use Lop\Api\Util\HmacUtil;

/* echo HttpUtil::curl("https://www.jd.com",null,null,0,0,false);

$sm3 = SM3Util::encrypt('7f839daf81774f8fb78c33aa7f387787access_token7db4793403b54ea9a69f3a8143c5270capp_key33957aea3d5e4cc19894aa396724ebddmethod/WaybillJosService/receiveOrderInfoparam_json[{"JosPin":"jd123","AppKey":"a123123","SalePlat":"0030001","CustomerCode":"010K000","OrderId":"DO201111000361","ThrOrderId":null,"SenderName":"张三","SenderAddress":"北京北京朝阳区大成国际中心","SenderMobile":"13100000001","ReceiveName":"李四","ReceiveAddress":"北京北京大兴区京东大厦","ReceiveMobile":"13200000001","PackageCount":1,"Weight":4,"Vloumn":0.15,"PromiseTimeType":1,"BoxCodeList":null,"GuaranteeValue":0,"GuaranteeValueAmount":0}]timestamp2020-09-10 10:28:49v2.07f839daf81774f8fb78c33aa7f387787');


// 输出 66c7f0f462eeedd9d1f2d46bdc10e4e24167c4875cf2f7a2297da02b8f4ba8e0
echo $sm3; */
$encryptText="66c7f0f462eeedd9d1f2d46bdc10e4e24167c4875cf2f7a2297da02b8f4ba8e0";
$secret="67c4875cf2f7a2297";
echo HmacUtil::encrypt($encryptText, $secret, "HMacSHA1"). "\n";
echo HmacUtil::encrypt($encryptText, $secret, "HMacMD5"). "\n";
echo HmacUtil::encrypt($encryptText, $secret, "HMacSHA256"). "\n";
echo HmacUtil::encrypt($encryptText, $secret, "HMacSHA512"). "\n";

