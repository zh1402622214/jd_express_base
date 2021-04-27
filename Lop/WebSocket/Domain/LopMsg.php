<?php

namespace Lop\WebSocket\Domain;


class LopMsg
{
    public $pin;
    public $msgId;
    public $msgName;
    public $msgPayloadRaw;
    public $msgPayload;
    public $msgPayloadText;
}