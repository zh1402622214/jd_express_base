<?php
/**
 * 处理消息后的返回消息处理结果对象。
 */

namespace Lop\WebSocket\Domain;
class LopMsgStatus
{

    public $msgId;
    //是否处理失败
    public $isFail = false;
    //处理失败原因
    public $reason;

    public function __construct($msgId)
    {
        $this->msgId = $msgId;
    }
}