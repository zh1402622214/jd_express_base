<?php


namespace Lop\Api\Plugin\Factory;


use Lop\Api\Plugin\LopPluginFactory;
use Lop\Api\Plugin\Entity\OAuth2Plugin;
use Lop\Api\Util\HttpUtil;
use Lop\WebSocket\Sync\Request\Exception;

class OAuth2PluginFactory implements LopPluginFactory
{
    public static $accessTokenMap = array();
    public static function produceLopPlugin($serverUrl,$appKey,$appSecret,$refreshToken){
        if(array_key_exists($refreshToken,OAuth2PluginFactory::$accessTokenMap)){
            return OAuth2PluginFactory::$accessTokenMap[$refreshToken];
        }
        $oAuth2Plugin = new OAuth2Plugin();
        $oAuth2Plugin->appKey=$appKey;
        $oAuth2Plugin->appSecret=$appSecret;
        $oAuth2Plugin->refreshToken=$refreshToken;
        $oAuth2Plugin->version="v1";
        if (empty($serverUrl)) {
            throw new Exception("serverUrl can not be null");
        }
        self::refreshToken($serverUrl,$oAuth2Plugin,"oauth.jdwl.com");
        OAuth2PluginFactory::$accessTokenMap[$refreshToken]=$oAuth2Plugin;
        return $oAuth2Plugin;
    }

    /**
     * 有些认证不需要访问网关以返回accessToken，可以用这个方法生成oauthToken插件
     * @param $appKey
     * @param $appSecret
     * @param $refreshToken
     * @return OAuth2Plugin
     */
    public static function produceSampleLopPlugin($appKey,$appSecret,$accessToken){
        $oAuth2Plugin = new OAuth2Plugin();
        $oAuth2Plugin->appKey=$appKey;
        $oAuth2Plugin->appSecret=$appSecret;
        $oAuth2Plugin->accessToken=$accessToken;
        $oAuth2Plugin->version="v1";
        return $oAuth2Plugin;
    }
    /**
     * 封装根据 refreshToken刷新个新的token
     * @return
     */
    private static function refreshToken($baseUrl,OAuth2Plugin $plugin,$oauthLopDn){
        $timestamp = gmdate("D, d M Y H:i:s", time())." GMT";
        $url=$baseUrl."/oauth/refresh_token_ext?LOP-DN=".$oauthLopDn
                ."&app_key=".$plugin->appKey
                ."&refresh_token=".$plugin->refreshToken."&timestamp=".urlencode($timestamp);
        $param = $plugin->appSecret."app_key".$plugin->appKey."refresh_token".$plugin->refreshToken."timestamp".$timestamp.$plugin->appSecret;
        $sign = strtoupper(md5($param));
        $url=$url."&sign=".$sign;
        $resp = HttpUtil::curl($url,null,null,0,0);

        $respObj = json_decode($resp);
        if(!empty($respObj->success) && $respObj->success){
            if ($respObj->model != null) {
                $plugin->accessToken=$respObj->model->accessToken;
            }else{
                throw new Exception("not get refreshToken");
            }
        }else{
            if(!empty($respObj->errMsg)){
                throw new Exception($respObj->errMsg);
            }
            if(!empty($respObj->error_response->en_desc)){
                throw new Exception($respObj->error_response->en_desc);
            }

        }
    }
}