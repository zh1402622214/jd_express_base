<?php
namespace Lop\Api\Plugin\Template;

use Lop\Api\Plugin\LopPluginTemplate;
use Lop\Api\Util\HmacUtil;
use Lop\Api\Plugin\Enum\HmacSourceType;
use Lop\Api\Plugin\Enum\EncryptAlgorithm;
use Lop\Api\Algs\SM3;

class HmacPartnerTemplate extends LopPluginTemplate
{
    
    public function buildHeaderParams($httpParam, $domainRequest, $lopPlugin)
    {
        $headerParams = array();
        $headerParams["LOP-DN"]=$domainRequest->getDomain();
        $httpParam->addHeaders($headerParams);
        
    }
    
    public function buildUrlArgs($httpParam, $domainRequest, $lopPlugin)
    {
        $httpParam->addUrlArg("timestamp",self::getTimeStamp($lopPlugin));
        $httpParam->addUrlArg("app_key",$lopPlugin->appKey);
        $httpParam->addUrlArg("algorithm",$lopPlugin->algorithm);
        $httpParam->addUrlArg("v",$domainRequest->getVersion());
        $httpParam->addUrlArg("sign", self::getSign($lopPlugin));
    }
    
    public function buildUrlPath($httpParam, $domainRequest, $lopPlugin)
    {
        $httpParam->setUrlPath($domainRequest->getApiMethod());
    }
    
    private function getTimeStamp($lopPlugin){
        $customizeSignList = $lopPlugin->customizeSignList;
        foreach ($customizeSignList as $key => $customizeSign) {
            if (strcmp("timestamp",$customizeSign->key)==0 && $customizeSign->sourceType == HmacSourceType::UrlArgs){
                return $customizeSign->value;
            }
        }
        
        return "";
    }
    
    private function getSign($hmacPlugin){
        $sign = "";
        $signList = $hmacPlugin->customizeSignList;
        $signTxt = "";
        if ($hmacPlugin->algorithm == EncryptAlgorithm::HMacSHA1
            || $hmacPlugin->algorithm == EncryptAlgorithm::HMacMD5
            || $hmacPlugin->algorithm == EncryptAlgorithm::HMacSHA256
            || $hmacPlugin->algorithm == EncryptAlgorithm::HMacSHA512) {
            
            $secret = "";
            foreach ($signList as $key => $customizeSign) {
                if (strcmp("isv_app_secret",$customizeSign->key)==0 && $customizeSign->sourceType == HmacSourceType::SystemVar){
                    $secret = $customizeSign->value;
                    //continue;
                }
                $signTxt = $signTxt.$customizeSign->value;
            }
            $sign = HmacUtil::encrypt($signTxt, $secret, $hmacPlugin->algorithm);
        }else if ($hmacPlugin->algorithm == EncryptAlgorithm::md5_salt) {
            foreach ($signList as $key => $customizeSign) {
                $signTxt = $signTxt.$customizeSign->value;
            }
            $sign = md5($signTxt);
        }else if ($hmacPlugin->algorithm == EncryptAlgorithm::sm3_salt) {
            foreach ($signList as $key => $customizeSign) {
                $signTxt = $signTxt.$customizeSign->value;
            }
            $sm3 = new SM3\SM3($signTxt);
            $sign = strtoupper((string)$sm3);
        }
        return $sign;
    }
    
}

