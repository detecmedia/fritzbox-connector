<?php

namespace Tests\Detecmedia\FritzboxConnector\Connector;

use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class FritzboxConnectorTest.
 * @author Makus Potthast <markus.potthast@detecmedia.de>
 * @package Tests\Detecmedia\FritzboxConnector\Connector
 * @version $id$
 */
class FritzboxConnectorTest extends TestCase
{
    private $fixture;

    /**
     * init test
     */
    protected function setUp(): void
    {
        $clientMock = new Client();
        $fixture = new FritzboxConnector($clientMock, 'http://192.168.4.1');
        $this->fixture = $fixture;
    }


    /**
     * @var
     */
    /**
     * test fritzbox connector login.
     */
    public function testLogin(): void
    {
        $fixture = $this->fixture;
        $fixture->login('test', '*test*!');
        self::assertTrue(false);
    }

    public function testMakeDots(): void
    {
        $reflectionClass = new ReflectionClass($this->fixture);
        $makeDots = $reflectionClass->getMethod('makeDots');
        $makeDots->setAccessible(true);
        $return = $makeDots->invokeArgs($this->fixture, ['!"§$%&/(*^']);
        self::assertEquals(
            '!"§$%&/(*^',
            $return
        );
    }

    public function testGetChallenge()
    {
        $reflectionClass = new ReflectionClass($this->fixture);
        $getChallenge = $reflectionClass->getMethod('getChallenge');
        $getChallenge->setAccessible(true);

        $html = $this->getLoginPage();
        self::assertEquals(
            '108005d1',
            $getChallenge->invokeArgs($this->fixture, [$html])
        );
    }

    private function getLoginPage()
    {
        return file_get_contents(__DIR__ . '/../Fixtures/login-page.html');
    }

    public function testGetUiResp()
    {
        $reflectionClass = new ReflectionClass($this->fixture);
        $getUiResp = $reflectionClass->getMethod('getUiResp');
        $getUiResp->setAccessible(true);

        $args = ['1a9112a1', 'MaPo481312'];
        self::assertEquals(
            '1a9112a1-fe28eb72fcdf3c2db0403f7f787cf0c1',
            $getUiResp->invokeArgs($this->fixture, $args)
        );
    }
}
