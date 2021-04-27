<?php
namespace Lop\Api\Domain;
use Lop\Api\Util\RequestCheckUtil;
use JsonSerializable;

class DemoOAuth implements JsonSerializable
{
    private  $pin;
    private  $userPin;
    private  $name;
    /**
     * @return mixed
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @param mixed $pin
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
    }

    /**
     * @return mixed
     */
    public function getUserPin()
    {
        return $this->userPin;
    }

    /**
     * @param mixed $userPin
     */
    public function setUserPin($userPin)
    {
        $this->userPin = $userPin;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'pin' => $this->pin,
            'userPin' => $this->userPin,
            'name' => $this->name
        ];
    }

    public function check() {
        RequestCheckUtil::checkString($this->pin,"pin");
        RequestCheckUtil::checkString($this->userPin,"userPin");
        RequestCheckUtil::checkString($this->name,"name");
    }
}