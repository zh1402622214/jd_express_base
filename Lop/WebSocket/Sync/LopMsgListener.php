<?php
/**
 * Created by PhpStorm.
 * User: wanyong
 * Date: 2017/12/1
 * Time: 15:24
 */

namespace Lop\WebSocket\Sync;
use Lop\WebSocket\Domain\LopMsg;
use Lop\WebSocket\Domain\LopMsgStatus;
use Lop\WebSocket\Sync\Request\Exception;

class LopMsgListener
{
    private $messageProcessor;
    private $appKey;
    private $appSecret;
    private $groupName;
    private $uri;
    private $lopClient;
    private $count = 1;
    public function __construct($messageProcessor,$lopClient)
    {
        $this->messageProcessor = $messageProcessor;
        $this->lopClient = $lopClient;
    }

    /**
     * 收到服务器消息后的回调函数
     * @param $client
     * @param $frame
     */
    public function onMessage($data, $client)
    {
        echo $data."2222====11111\n";
        if (is_string($data)) {
            //echo "data: " . $data . "\n";
            $msg = new LopMsg();
            try {
                $msgMap = json_decode($data);
                if (is_array($msgMap)) {
                    echo 'isArray'."\n";
                    if (isset($msgMap["pin"])) {
                        $msg->pin = $msgMap["pin"];
                    }
                    if (isset($msgMap["msgId"])) {
                        $msg->msgId = $msgMap["msgId"];
                    }
                    if (isset($msgMap["msgName"])) {
                        $msg->msgName = $msgMap["msgName"];
                    }
                    if (isset($msgMap["msgPayload"])) {
                        $msg->msgPayloadRaw = $msgMap["msgPayload"];
                        $msg->msgPayloadText = $msgMap["msgPayload"];
                        $msg->msgPayload = json_decode($msg->msgPayloadRaw);
                    }
                } elseif (is_object($msgMap)) {
                    echo 'is_object"'.$data."\n";
                    if (isset($msgMap->pin)) {
                        $msg->pin = $msgMap->pin;
                    }
                    if (isset($msgMap->msgId)) {
                        $msg->msgId = $msgMap->msgId;
                    }
                    if (isset($msgMap->msgName)) {
                        $msg->msgName = $msgMap->msgName;
                    }
                    if (isset($msgMap->msgPayload)) {
                        $msg->msgPayloadRaw = $msgMap->msgPayload;
                        $msg->msgPayloadText = $msgMap->msgPayload;
                        $msg->msgPayload = json_decode($msg->msgPayloadRaw);
                    }
                } else {
                    $msg->msgPayloadRaw = $data;
                    $msg->msgPayloadText = $data;
                }
            } catch (\Exception $e) {
                $msg->msgPayloadRaw = $data;
            }
            $msgStatus = new LopMsgStatus($msg->msgId);
            try {
                //处理消息
                if (isset($this->messageProcessor)) {
                    $this->messageProcessor->onMessage($msg, $msgStatus,$this->lopClient);
                    //服务端收不到confirm 消息，会重发。
                    if (!$msgStatus->isFail) {
                        $this->confirm($client, $msgStatus);
                    }
                }
            } catch (\Exception $e) {
                //echo $e->getMessage();
                error_log( date( "Y-m-d H:i:s")." 解析服务端报文:".$data."出现异常:".$e);
            }
        }
    }

    private function confirm($client, $msgStatus)
    {
        $client->send($this->getConfirmMessage($msgStatus),"text");
    }

    private function getConfirmMessage($msgStatus)
    {
        $content = array();
        if (isset($msgStatus->msgId)) {
            $content["msgId"] = $msgStatus->msgId;
            $content["name"] = "commit";
        }
        if ($msgStatus->isFail && isset($msgStatus->reason)) {
            $content["reason"] = $msgStatus->reason;
        }
        return json_encode($content);
    }

    /**
     * 收到服务器消息后的回调函数
     * @param $client
     * @param $frame
     */
    public function onPong($data, $client){
        echo date( "Y-m-d H:i:s")." pong ".$data."\n";
    }

    /**
     * 收到服务器消息后的回调函数
     * @param $client
     * @param $frame
     */
    public function onDisconnected($data,$client){
        $lopClient = new LopMsgClient($this->appKey,$this->appSecret, $this->groupName);
        $lopClient->setMessageHandle($this->messageProcessor);
        while(true){
            if($this->count>60){
                $this->count=1;
            }
            usleep(1000*1000*$this->count++);
            try{
                if($lopClient->connect($this->uri)){
                    break;
                }
            }catch (Exception $e){
                error_log( date( "Y-m-d H:i:s")." ".$this->appKey." 重新连接服务端出现异常:".$e);
            }
        }
        $lopClient->startRun();
    }

    /**
     * @param mixed $appKey
     */
    public function setAppKey($appKey): void
    {
        $this->appKey = $appKey;
    }

    /**
     * @param mixed $appSecret
     */
    public function setAppSecret($appSecret): void
    {
        $this->appSecret = $appSecret;
    }

    /**
     * @param mixed $groupName
     */
    public function setGroupName($groupName): void
    {
        $this->groupName = $groupName;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri): void
    {
        $this->uri = $uri;
    }

}