<?php
/**
 * Created by PhpStorm.
 * User: wanyong
 * Date: 2017/12/1
 * Time: 10:58
 */

namespace Lop\WebSocket;
abstract class BaseLopMsgClient
{
    protected $appKey;
    protected $appSecret;
    protected $groupName;
    protected $timestamp;
    private $key_appkey = "app_key";
    private $key_group = "group";
    private $key_timestamp = "timestamp";
    private $key_sign = "sign";

    public function __construct($appKey, $appSecret, $groupName)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->groupName = $groupName;
        $this->timestamp = date("Y-m-d H:i:s");
    }

    function __destruct()
    {
        $this->close();
    }

    protected function assembleUri($uri)
    {
        $maskAsk = "?";
        if(strpos($uri, '?')){
            $maskAsk = "&";
        }
        return str_replace(" ", "%20", $uri . $maskAsk . $this->key_appkey . "=" . $this->appKey . "&" . $this->key_group . "=" . $this->groupName . "&" . $this->key_timestamp . "=" . $this->timestamp . "&" . $this->key_sign . "=" . $this->genSign());
        
    }

    protected function genSign()
    {
        $stringToBeSigned = $this->appSecret . $this->key_appkey . $this->appKey . $this->key_group . $this->groupName . $this->key_timestamp . $this->timestamp . $this->appSecret;
        return strtoupper(md5($stringToBeSigned));
    }

    public abstract function connect($uri);

    public abstract function close();
}