<?php


namespace Lop\Api\Domain;


use JsonSerializable;

class CertKey implements JsonSerializable
{
    /**
     * 主键
     */
    private  $id;

        /**
         * 组code
         */
    private  $groupCode;

        /**
         * 证书别名
         */
    private  $certAlias;

        /**
         * 证书格式
         */
    private  $certFormat;

        /**
         * 证书使用方
         */
    private  $certUser;

        /**
         * 证书所属合作伙伴
         */
    private  $partner;

        /**
         * 过期时间
         */
    private  $expireTime;

        /**
         * 证书类型 1公钥 2私钥
         */
    private  $certType;
        /**
         * 证书文件名称
         */
    private  $certFileName;

        /**
         * 创建时间
         */
    private  $createTime;

        /**
         * 创建者
         */
    private  $createUser;

        /**
         * 更新时间
         */
    private $updateTime;

        /**
         * 更新者
         */
    private  $updateUser;

        /**
         * 是否删除
         */
    private  $isDelete;

        /**
         * 证书内容
         */
    private  $content;
        /**
         * 证书密码
         */
    private  $certPassword;

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'groupCode' => $this->groupCode,
            'certAlias' => $this->certAlias,
            'certFormat' => $this->certFormat,
            'certUser' => $this->certUser,
            'partner' => $this->partner,
            'expireTime' => $this->expireTime,
            'certType' => $this->certType,
            'certFileName' => $this->certFileName,
            'createTime' => $this->createTime,
            'createUser' => $this->createUser,
            'updateTime' => $this->updateTime,
            'updateUser' => $this->updateUser,
            'isDelete' => $this->isDelete,
            'content' => $this->content,
            'certPassword' => $this->certPassword
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getGroupCode()
    {
        return $this->groupCode;
    }

    /**
     * @param mixed $groupCode
     */
    public function setGroupCode($groupCode)
    {
        $this->groupCode = $groupCode;
    }

    /**
     * @return mixed
     */
    public function getCertAlias()
    {
        return $this->certAlias;
    }

    /**
     * @param mixed $certAlias
     */
    public function setCertAlias($certAlias)
    {
        $this->certAlias = $certAlias;
    }

    /**
     * @return mixed
     */
    public function getCertFormat()
    {
        return $this->certFormat;
    }

    /**
     * @param mixed $certFormat
     */
    public function setCertFormat($certFormat)
    {
        $this->certFormat = $certFormat;
    }

    /**
     * @return mixed
     */
    public function getCertUser()
    {
        return $this->certUser;
    }

    /**
     * @param mixed $certUser
     */
    public function setCertUser($certUser)
    {
        $this->certUser = $certUser;
    }

    /**
     * @return mixed
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @param mixed $partner
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;
    }

    /**
     * @return mixed
     */
    public function getExpireTime()
    {
        return $this->expireTime;
    }

    /**
     * @param mixed $expireTime
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
    }

    /**
     * @return mixed
     */
    public function getCertType()
    {
        return $this->certType;
    }

    /**
     * @param mixed $certType
     */
    public function setCertType($certType)
    {
        $this->certType = $certType;
    }

    /**
     * @return mixed
     */
    public function getCertFileName()
    {
        return $this->certFileName;
    }

    /**
     * @param mixed $certFileName
     */
    public function setCertFileName($certFileName)
    {
        $this->certFileName = $certFileName;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return mixed
     */
    public function getCreateUser()
    {
        return $this->createUser;
    }

    /**
     * @param mixed $createUser
     */
    public function setCreateUser($createUser)
    {
        $this->createUser = $createUser;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param mixed $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }

    /**
     * @return mixed
     */
    public function getUpdateUser()
    {
        return $this->updateUser;
    }

    /**
     * @param mixed $updateUser
     */
    public function setUpdateUser($updateUser)
    {
        $this->updateUser = $updateUser;
    }

    /**
     * @return mixed
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }

    /**
     * @param mixed $isDelete
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCertPassword()
    {
        return $this->certPassword;
    }

    /**
     * @param mixed $certPassword
     */
    public function setCertPassword($certPassword)
    {
        $this->certPassword = $certPassword;
    }



}