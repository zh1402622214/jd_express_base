<?php
/**
 * 消息处理接口，需要实现该接口提供具体的消息处理
 */
namespace Lop\WebSocket;
interface MessageProcessor
{
    /**
     * @param $msg  type: /Lop/WebSocket/Domain/LopMsg
     * @param $msgStatus 处理结果，如果$msgStatus->isFail=true，
     * 消息通道将会择机重发消息；否则，消息通道认为消息处理成功。
     * type: /Lop/WebSocket/Domain/LopMsgStatus
     * @return mixed
     */
    public function onMessage($msg, $msgStatus,$client);
}