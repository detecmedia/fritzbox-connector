<?php


namespace Detecmedia\FritzboxConnector\connectors;


use SoapClient;
use SoapFault;

class Tr064Connector
{
    /**
     * @var SoapClient
     */
    protected $client;

    protected $services = [
        'WANCommonInterfaceConfig:1' => 'wancommonifconfig1',
        'X_AVM-DE_OnTel:1' => 'x_contact',
        'DeviceConfig:1' => 'deviceconfig',
        'DeviceInfo:1' => 'deviceinfo',
        'X_AVM-DE_Homeauto:1' => 'x_homeauto'
    ];

    /**
     * Tr064Connector constructor.
     * @param string $host
     * @param string $service
     * @param string $user
     * @param string $password
     * @param int $port
     * @throws SoapFault
     */
    public function __construct(string $host, string $service, string $user = '', string $password = '', int $port = 49000)
    {
        $this->client = new SoapClient(
            null,
            [
                'location' => 'http://' . $host . ':' . $port . '/upnp/control/' . $this->services[$service],
                'uri' => "urn:dslforum-org:service:" . $service,
                'noroot' => True,
                'login' => 'markus',
                'password' => 'MaPo481312!'

            ]
        );
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->client, $name], $arguments);
    }


}