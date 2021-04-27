<?php


namespace Lop\Api\Plugin;


class DomainHttpParam
{
    public static  $CHARSET_UTF8 = "UTF-8";
    /**
     * 头信息
     */
    private  $headers = array();
    /**
     * url?参数
     */
    private $urlArgs = array();
    /**
     * body请求
     */
    private $bodyArgs = array();

    /**
     * url信息
     */
    private  $urlPath = "";
    /**
     * body原始请求
     * @return
     */
    private $bodyOriginalArgs = null;
    /**
     * body请求JSON
     * @return
     */
    private  $bodyJsonArgs = "";

    public function getHeaders() {
        return $this->headers;
    }

    public function  getUrlArgs() {
        return $this->urlArgs;
    }

    public function addUrlArg($key,$value){
        if ($value!=null) {
            $this->urlArgs[$key]=$value;
        }
    }
    public function addUrlArgs($map=array()){
        if ($map!=null) {
            foreach ($map as $key=>$value){
                $this->urlArgs[$key]=$value;
            }
        }
    }

    public function addBodyArg($args){
        if ($args!=null) {
            $this->bodyOriginalArgs=args;
        }
    }
    public function addBodyArgs($argsList=array()){
        if ($argsList!=null && count($argsList)>0) {
            $this->bodyArgs=array_merge($this->bodyArgs,$argsList);
        }
    }

    public function addHeader($key,$value){
        if ($value!=null) {
            //$this->headers=array_merge($this->headers,array($key=>$value));
            $this->headers[$key]=$value;
        }
    }
    public function addHeaders($map=array()){
        if ($map!=null) {
            foreach ((array)$map as $key=>$value){
                //$this->headers=array_merge($this->headers,array($key=>$value));
                $this->headers[$key]=$value;
            }
        }
    }
    public function getBodyContentArray(){
        return $this->bodyArgs;
    }
    public function getBodyContent(){
        return json_encode($this->bodyArgs,true);
    }

    public function getUrlArgsQuery() {
        $params = $this->getUrlArgs();
        if ($params == null || count($params)<=0) {
            return null;
        }
        $query = "";
        foreach ($params as $k => $v) {
            $query .= $k."=".$v."&";
        }
        unset($k, $v);
        if(strlen($query)>0){
            $query = substr($query, 0, -1);
        }
        return $query;
    }

    public function getCharset(){
    }

    /**
     * @return mixed
     */
    public function getUrlPath()
    {
        return $this->urlPath;
    }

    /**
     * @param mixed $urlPath
     */
    public function setUrlPath($urlPath)
    {
        $this->urlPath = $urlPath;
    }




    /**
     * @param mixed $bodyJsonArgs
     */
    public function addBodyJsonArgs($bodyJsonArgs)
    {
        $this->bodyJsonArgs = $bodyJsonArgs;
    }

}