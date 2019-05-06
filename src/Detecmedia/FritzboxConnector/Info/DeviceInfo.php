<?php


namespace Detecmedia\FritzboxConnector\Info;


use Detecmedia\FritzboxConnector\connectors\Tr064Connector;

/**
 * Class DeviceInfo
 * @package Detecmedia\FritzboxConnector\Info
 * @method GetInfo() Argument list of action GetInfo
 * @method GetDeviceLog() Argument list of action GetDeviceLog
 * @method GetSecurityPort() Argument list of action GetSecurityPort
 */
class DeviceInfo extends Tr064Connector
{
    public function __construct(string $host, string $user , string $password )
    {
        $service = 'DeviceInfo:1';
        parent::__construct($host, $service, $user, $password);
    }

}