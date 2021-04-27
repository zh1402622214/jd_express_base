<?php


namespace Lop\Api\Request;


use Lop\Api\LopDomainClient;

abstract  class DomainAbstractRequest
{
    protected  $version = "2.0";
    protected  $useJosAuth = false;
    protected  $lopPluginList =  array();

    public function getTimestamp() {
        return gmdate("D, d M Y H:i:s", time())." GMT";
    }


    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }


    public abstract function getApiMethod();

    public function getOtherParams() {
        return "";
    }
    /**
     * 获取系统级参数
     *
     * @return key=参数名, value=参数值
     */
    public function getSysParams(){
        return array();
    }
    /**
     * 获取JSON格式封装的应用级参数
     *
     * @return
     * @throws
     */
    public function getAppJsonParams(){
        return "";
    }

    /**
     * @return bool
     */
    public function getUseJosAuth()
    {
        return $this->useJosAuth;
    }

    /**
     * @param bool $useJosAuth
     */
    public function setUseJosAuth($useJosAuth)
    {
        $this->useJosAuth = $useJosAuth;
    }

    /**
     * @return array
     */
    public function getLopPluginList()
    {
        return $this->lopPluginList;
    }

    /**
     * @param array $lopPluginList
     */
    public function setLopPluginList($lopPluginList)
    {
        $this->lopPluginList = $lopPluginList;
    }

    /**
     * @param array $lopPlugin
     */
    public function addLopPlugin($lopPlugin)
    {
        array_push($this->lopPluginList,$lopPlugin);
    }


    public abstract function getDomain();

    public abstract function getBodyObject();

    public abstract  function  buildDomainHttpParam(LopDomainClient $client);

    public abstract  function  getDomainHttpParam();
}