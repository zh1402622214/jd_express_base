<?php
/**
 * Created by PhpStorm.
 * User: wanyong
 * Date: 2017/12/1
 * Time: 12:11
 */

namespace Lop\WebSocket\Sync;

use Lop\WebSocket\BaseLopMsgClient;
use Lop\WebSocket\MessageProcessor;
use Lop\WebSocket\Sync\Request\Client;

class LopMsgClient extends BaseLopMsgClient
{
    private $client;
    private $wsListener;
    private $messageProcessor;



    public function __construct($appKey, $appSecret, $groupName, $options = array())
    {
        parent::__construct($appKey, $appSecret, $groupName);
        $this->client = new Client($options);
    }


    public function setMessageHandle(MessageProcessor $messageProcessor)
    {
        $this->messageProcessor=$messageProcessor;
        $this->wsListener = new LopMsgListener($messageProcessor,$this);
        $this->wsListener->setAppKey($this->appKey);
        $this->wsListener->setAppSecret($this->appSecret);
        $this->wsListener->setGroupName($this->groupName);
        //注册监听器
        $this->client->onMessage([$this->wsListener, "onMessage"]);
        $this->client->onPong([$this->wsListener, "onPong"]);
        $this->client->onDisconnected([$this->wsListener, "onDisconnected"]);
    }

    public function connect($uri)
    {
        $this->client->setServerUrl($uri);
        $this->client->connect($this->assembleUri($uri));
        $this->wsListener->setUri($uri);
        return true;
    }

    public function close()
    {
        return $this->client->close();
    }

    public function startRun()
    {
        if (isset($this->wsListener)) {
            $this->client->run();
        }
    }
}