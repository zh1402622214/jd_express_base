<?php
namespace Lop\Api\Plugin\Entity;
use Lop\Api\Plugin\LopPlugin;

class HmacPartnerPlugin implements LopPlugin
{
    public $algorithm;           //算法
    public $appKey;              //appKey，通过appKey服务端会获取保存的appSecret
    public $isvAppSecret;
    public $version;
    public $customizeSignList;
    
    
}

