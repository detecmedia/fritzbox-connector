<?php


namespace Detecmedia\FritzboxConnector\Autos;


use Detecmedia\FritzboxConnector\connectors\Tr064Connector;

/**
 * Class Homeauto
 * @package Tests\Detecmedia\FritzboxConnector\Autos
 * @method GetInfo()
 * @method GetGenericDeviceInfos()
 */
class Homeauto extends Tr064Connector
{

    public function __construct(string $host, string $user, string $password)
    {
        $service = 'X_AVM-DE_Homeauto:1';
        parent::__construct($host, $service, $user, $password);
    }
}