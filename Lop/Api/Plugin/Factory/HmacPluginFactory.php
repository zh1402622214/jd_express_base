<?php


namespace Lop\Api\Plugin\Factory;


use Lop\Api\Plugin\LopPluginFactory;
use Lop\Api\Plugin\Entity\HmacPlugin;

class HmacPluginFactory implements LopPluginFactory
{
    public static function produceLopPlugin($antiTamper,$username,$pwd,$algorithm){
        $plugin = new HmacPlugin();
        $plugin->algorithm=$algorithm;
        $plugin->antiTamper=$antiTamper;
        $plugin->username=$username;
        $plugin->pwd=$pwd;
        $plugin->version="v5";
        return $plugin;
    }
}