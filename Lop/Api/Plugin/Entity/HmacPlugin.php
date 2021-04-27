<?php


namespace Lop\Api\Plugin\Entity;


use Lop\Api\Plugin\LopPlugin;

class HmacPlugin implements LopPlugin
{
    /**
     * 是否支持防篡改
     */
    public  $antiTamper;
    public  $version;
    public  $username;
    public  $pwd;
    public  $algorithm;
}