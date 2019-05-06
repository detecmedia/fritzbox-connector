<?php


namespace Detecmedia\FritzboxConnector\Configs;


use Detecmedia\FritzboxConnector\connectors\Tr064Connector;

/**
 * Class DeviceConfig
 * @package Detecmedia\FritzboxConnector\Configs
 * @method GetPersistentData();
 * @method ConfigurationFinished();
 * @method Reboot()
 */
class DeviceConfig extends Tr064Connector
{
    public function __construct(string $host, string $user, string $password)
    {
        $service = 'DeviceConfig:1';
        parent::__construct($host, $service, $user, $password);
    }


}