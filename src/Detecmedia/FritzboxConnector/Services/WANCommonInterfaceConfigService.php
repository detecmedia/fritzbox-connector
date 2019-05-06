<?php


namespace Detecmedia\FritzboxConnector\Services;


use Detecmedia\FritzboxConnector\connectors\Tr064Connector;
use SoapParam;

/**
 *
 * @method GetTotalBytesSent()
 * @method GetTotalBytesReceived()
 * @method GetTotalPacketsReceived()
 * @method GetTotalPacketsSent()
 * @method GetCommonLinkProperties()
 */
class WANCommonInterfaceConfigService extends Tr064Connector
{
    public function __construct($host, $user, $password)
    {
        $service = 'WANCommonInterfaceConfig:1';
        parent::__construct($host, $service, $user, $password);

    }

    /**
     * @param int $syncGroupIndex SyncGroupIndex can have a value from 0 .. TotalNumberSyncGroups-1
     * @return mixed
     */
    public function GetOnlineMonitor($syncGroupIndex = 0)
    {
        return $this->client->{"X_AVM-DE_GetOnlineMonitor"}(new SoapParam($syncGroupIndex, 'NewSyncGroupIndex'));
    }

}