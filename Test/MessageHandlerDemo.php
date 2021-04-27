<?php
/**
 * Created by PhpStorm.
 * User: wanyong
 * Date: 2017/12/1
 * Time: 16:01
 */

include_once("../Lop/WebSocket/MessageProcessor.php");
use Lop\WebSocket\MessageProcessor;

class MessageHandlerDemo implements MessageProcessor
{

    public function onMessage($msg, $msgStatus,$client)
    {
        try{
//            echo "msgPayload: ".$msg->msgPayload->sellerOrderNo ."\n";
            echo "msgId: " . $msg->msgId . " msgName: " . $msg->msgName . " msgPayloadRaw: " . $msg->msgPayloadRaw . "  msgPayloadText: ". $msg->msgPayloadText. "\n";
            echo date( "Y-m-d H:i:s")." onMessage\n";
            //echo var_dump($client);
        }catch (Exception $e){
            error_log("处理消息出现异常:".$e);
            $msgStatus->isFail=true;  //服务端重新发送
        }
    }

}