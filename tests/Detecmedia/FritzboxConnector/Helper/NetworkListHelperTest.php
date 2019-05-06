<?php

namespace Detecmedia\FritzboxConnector\Helper;

use Detecmedia\FritzboxConnector\Model\Client;
use Detecmedia\FritzboxConnector\Model\ClientDetails;
use PHPUnit\Framework\TestCase;
use ReflectionException as ReflectionException;

/**
 * Class NetworkListHelperTest
 * @package Detecmedia\FritzboxConnector\Helper
 * @author Markus Potthast <markus.potthast@detecmedia.de>
 */
class NetworkListHelperTest extends TestCase
{
    /**
     * @var NetworkListHelper $fixture
     */
    private $fixture;

    /**
     * init test class
     */
    protected function setUp()
    {
        parent::setUp();
        $this->fixture = $this->createMyObj();
    }


    /**
     * get html page
     * @return bool|string
     */
    private function getHtml06_50()
    {
        return file_get_contents(__DIR__ . '/../Fixtures/06.50/network-overview.html');
    }

    /**
     * Gets mesh overview html
     * @return false|string
     */
    private function getHtml07_02()
    {
        return file_get_contents(__DIR__ . '/../Fixtures/07.02/mesh-overview.html');
    }

    /**
     * get test json
     * @return bool|string
     */
    private function getJson()
    {
        return file_get_contents(__DIR__ . '/../Fixtures//06.50/network-details.json');
    }

    /**
     * create network list helper object
     * @param array $mock
     * @return NetworkListHelper
     */
    private function createMyObj($mock = [])
    {
        return new NetworkListHelper($this->getHtml06_50());
    }

    /**
     * get client list from html overview page
     */
    public function testGetListHTML()
    {
        $networkClients = $this->fixture->getClientList();
        self::assertNotEmpty($networkClients);
        self::assertCount(17, $networkClients);
        self::assertInternalType('array', $networkClients);
        /** @var Client $element */
        $element = $networkClients[1];
        self::assertInstanceOf(Client::class, $element);
        self::assertEquals('landevice2711', $element->getUid());
        self::assertEquals('26F98E000000', $element->getName());
        self::assertEquals('http://192.168.4.104', $element->getUrl());
    }

    /**
     * test get client list from json
     */
    public function testGetListJson()
    {
        $json = $this->getJson();
        $this->fixture = new NetworkListHelper($json);
        $networkClients = $this->fixture->getClientList();
        self::assertNotEmpty($networkClients);
        self::assertCount(12, $networkClients);
        self::assertInternalType('array', $networkClients);
        $firstElement = $networkClients[0];
        $mac = $firstElement->getMac();
        self::assertInstanceOf(ClientDetails::class, $firstElement);
        self::assertNotEmpty($mac);
        self::assertEquals('B8:27:EB:01:01:01', $mac);
        self::assertEquals('192.168.0.7', $firstElement->getIpv4());
        self::assertEquals('pc1', $firstElement->getName());
        self::assertEquals('landevice2734', $firstElement->getUid());
    }

    /**
     * get client by device
     */
    public function testGetClientByDevice()
    {
        $clientByDevice = $this->fixture->getClientByDevice('landevice2720');
        self::assertEquals('Handy7', $clientByDevice->getName());
    }
}
