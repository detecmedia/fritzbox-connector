<?php

namespace Detecmedia\FritzboxConnector\Configs;

use Detecmedia\FritzboxConnector\Services\WANCommonInterfaceConfigService;
use PHPUnit\Framework\TestCase;

include_once '../../../../config/config.php';

class DeviceConfigTest extends TestCase
{

    /**
     * @throws \SoapFault
     */
    public function testCTor()
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $dev = new DeviceConfig($fritzboxUrl, $fritzboxUser, $fritzboxPassword);
        print_r($dev->GetPersistentData());
        print_r("\n");
        //print_r($dev->ConfigurationFinished());
        print_r("\n");

        $this->assertTrue(true);
    }
}
