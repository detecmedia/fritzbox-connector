<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 05.09.17
 * Time: 18:54
 */

namespace Detecmedia\FritzboxConnector\Helper;

use Detecmedia\FritzboxConnector\Model\Client;
use PHPUnit\Framework\TestCase;

class NetworkListHelperTest extends TestCase
{
    /** @var  NetworkListHelper $fixture */
    private $fixture;

    protected function setUp()
    {
        parent::setUp();
        $this->fixture = $this->createMyObj();
    }


    private function getHtml()
    {
        return file_get_contents(__DIR__ . '/../Fixtures/network-overview.html');
    }

    private function createMyObj($mock = []) {
        return  new NetworkListHelper($this->getHtml());
    }

    public function testGetList()
    {
        $networkClients = $this->fixture->getClientList();
        self::assertNotEmpty($networkClients);
        self::assertCount(17, $networkClients);
        self::assertInternalType('array', $networkClients);
        $firstElement = $networkClients[0];
        self::assertInstanceOf(Client::class, $firstElement);
    }

    public function testGetClientByDevice()
    {

        $clientByDevice = $this->fixture->getClientByDevice('landevice2720');
        self::assertEquals('Handy7',$clientByDevice->getName());
    }
}
