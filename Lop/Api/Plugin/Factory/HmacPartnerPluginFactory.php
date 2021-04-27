<?php


namespace Lop\Api\Plugin\Factory;


use Lop\Api\Plugin\LopPluginFactory;
use Lop\Api\Plugin\Entity\HmacPartnerPlugin;
use Lop\Api\Plugin\Entity\HmacCustomizeSign;
use Lop\Api\Plugin\Enum\HmacSourceType;
use Lop\Api\Plugin\Enum\EncryptAlgorithm;
use Lop\Api\util\HmacUtil;

class HmacPartnerPluginFactory implements LopPluginFactory
{
    /* public static function produceLopPlugin($antiTamper,$username,$pwd,$algorithm){
        $plugin = new HmacPlugin();
        $plugin->algorithm=$algorithm;
        $plugin->antiTamper=$antiTamper;
        $plugin->username=$username;
        $plugin->pwd=$pwd;
        $plugin->version="v5";
        return $plugin;
    } */
    
    public static function produceLopPlugin($isvAppSecret, $appKey, $request,$algorithm){
        $plugin = new HmacPartnerPlugin();
        $plugin->isvAppSecret = $isvAppSecret;
        $plugin->appKey = $appKey;
        $customizeSignList = array();
        
        $isvAppSecretSign = new HmacCustomizeSign();
        $isvAppSecretSign->key = "isv_app_secret";
        $isvAppSecretSign->value = $isvAppSecret;
        $isvAppSecretSign->sourceType = HmacSourceType::SystemVar;
        $customizeSignList[] = $isvAppSecretSign;
        
        $appKeyFixedSign = new HmacCustomizeSign();
        $appKeyFixedSign->key = "app_key";
        $appKeyFixedSign->value = "app_key";
        $appKeyFixedSign->sourceType = HmacSourceType::FixedConstant;
        $customizeSignList[] = $appKeyFixedSign;
        
        $appKeyArgsSign = new HmacCustomizeSign();
        $appKeyArgsSign->key = "app_key";
        $appKeyArgsSign->value = $appKey;
        $appKeyArgsSign->sourceType = HmacSourceType::UrlArgs;
        $customizeSignList[] = $appKeyArgsSign;
        
        
        $methodFixedSign = new HmacCustomizeSign();
        $methodFixedSign->key = "method";
        $methodFixedSign->value = "method";
        $methodFixedSign->sourceType = HmacSourceType::FixedConstant;
        $customizeSignList[] = $methodFixedSign;
        
        $reqUriSign = new HmacCustomizeSign();
        $reqUriSign->key = "req_uri";
        $reqUriSign->value = $request->getApiMethod();
        $reqUriSign->sourceType = HmacSourceType::SystemVar;
        $customizeSignList[] = $reqUriSign;
        
        $paramJsonSign = new HmacCustomizeSign();
        $paramJsonSign->key = "param_json";
        $paramJsonSign->value = "param_json";
        $paramJsonSign->sourceType = HmacSourceType::FixedConstant;
        $customizeSignList[] = $paramJsonSign;
        
        
        $requestBodySign = new HmacCustomizeSign();
        $requestBodySign->key = "request_body";
        $requestBodySign->value = json_encode($request->getBodyObject(),true);
        $requestBodySign->sourceType = HmacSourceType::SystemVar;
        $customizeSignList[] = $requestBodySign;
        
        $timestampFixedSign = new HmacCustomizeSign();
        $timestampFixedSign->key = "timestamp";
        $timestampFixedSign->value = "timestamp";
        $timestampFixedSign->sourceType = HmacSourceType::FixedConstant;
        $customizeSignList[] = $timestampFixedSign;
        
        $timestampArgsSign = new HmacCustomizeSign();
        $timestampArgsSign->key = "timestamp";
        $timestampArgsSign->value = HmacUtil::getServerTime();
        $timestampArgsSign->sourceType = HmacSourceType::UrlArgs;
        $customizeSignList[] = $timestampArgsSign;
        
        $vFixedSign = new HmacCustomizeSign();
        $vFixedSign->key = "v";
        $vFixedSign->value = "v";
        $vFixedSign->sourceType = HmacSourceType::FixedConstant;
        $customizeSignList[] = $vFixedSign;
        
        $vArgsSign = new HmacCustomizeSign();
        $vArgsSign->key = "v";
        $vArgsSign->value = $request->getVersion();
        $vArgsSign->sourceType = HmacSourceType::UrlArgs;
        $customizeSignList[] = $vArgsSign;
        
        $isvAppSecretLastSign = new HmacCustomizeSign();
        $isvAppSecretLastSign->key = "isv_app_secret";
        $isvAppSecretLastSign->value = $isvAppSecret;
        $isvAppSecretLastSign->sourceType = HmacSourceType::SystemVar;
        $customizeSignList[] = $isvAppSecretLastSign;
        
        $plugin->version = "1.0";
        if($algorithm==null){
            $plugin->algorithm = EncryptAlgorithm::md5_salt;
        }else{
            $plugin->algorithm = $algorithm;
        }
        $plugin->customizeSignList = $customizeSignList;
        return $plugin;
    }
}