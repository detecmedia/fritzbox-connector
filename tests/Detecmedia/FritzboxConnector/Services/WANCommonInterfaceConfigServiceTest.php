<?php

namespace Detecmedia\FritzboxConnector\Services;

use PHPUnit\Framework\TestCase;

include_once '../../../../config/config.php';

class WANCommonInterfaceConfigServiceTest extends TestCase
{
    public function testCTor()
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $wan = new WANCommonInterfaceConfigService($fritzboxUrl,$fritzboxUser,$fritzboxPassword);
        print_r($wan->GetOnlineMonitor());
        $this->assertTrue(true);
    }


}
