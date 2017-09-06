<?php
namespace Detecmedia\FritzboxConnector\Model;

class Client
{
    private $name;
    private $link;
    private $device;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Client
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $link
     * @return Client
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $device
     * @return Client
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDevice()
    {
        return $this->device;
    }


}