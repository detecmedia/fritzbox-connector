<?php

namespace Detecmedia\FritzboxConnector\Autos;

use PHPUnit\Framework\TestCase;



include_once '../../../../config/config.php';

class HomeautoTest extends TestCase
{

    /**
     * @throws \SoapFault
     */
    public function test__construct()
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $home = new Homeauto($fritzboxUrl, $fritzboxUser, $fritzboxPassword);
        print_r($home->GetInfo());
        print_r("\n");
        print_r($home->GetGenericDeviceInfos(new \SoapParam('1','NewIndex')));
        print_r("\n");

        $this->assertTrue(true);
    }
}
