<?php

namespace Lop\Api\Util;
use Lop\Api\Algs\SM3;

class SM3Util
{
    public static function encrypt($message)
    {
        $sm3 = new SM3\SM3($message);
        return strtoupper((string)$sm3);
    }

}