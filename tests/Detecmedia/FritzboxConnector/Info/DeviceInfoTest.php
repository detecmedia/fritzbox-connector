<?php

namespace Detecmedia\FritzboxConnector\Info;

use PHPUnit\Framework\TestCase;

include_once '../../../../config/config.php';

class DeviceInfoTest extends TestCase
{

    public function testCTor()
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $dev = new DeviceInfo($fritzboxUrl, $fritzboxUser, $fritzboxPassword);
        print_r($dev->GetInfo());
        print_r("\n");
        print_r($dev->GetDeviceLog());print_r("\n");
        print_r($dev->GetSecurityPort());
        $this->assertTrue(true);
    }

}
