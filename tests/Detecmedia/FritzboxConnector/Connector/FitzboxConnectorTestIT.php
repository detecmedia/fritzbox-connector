<?php

namespace Tests\Detecmedia\FritzboxConnector\Connector;

use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use Detecmedia\FritzboxConnector\Helper\NetworkListHelper;
use Detecmedia\FritzboxConnector\Model\Client as NetworkClient;
use Detecmedia\FritzboxConnector\Model\ClientDetails as NetworkClientDetais;
use Detecmedia\FritzboxConnector\Pages;
use Detecmedia\FritzboxConnector\Request\NetworkConnectionRequest;
use Detecmedia\FritzboxConnector\Request\NetworkRequest;
use Detecmedia\FritzboxConnector\Request\Overview;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class TestTest
 * @package Tests\Detecmedia\FritzboxConnector\Connector;
 * @version $id$
 */
class FitzboxConnectorTestIT extends TestCase
{
    /**
     * Test Overview
     * @throws GuzzleException
     * @throws RuntimeException
     * @throws AssertionFailedError
     */
    public function testOverview()
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $pages = new Pages();
        if ($fritzboxUrl === null) {
            $this->fail('fritzbox url is null');
        }
        $clientMock = new Client(['base_uri' => $fritzboxUrl]);
        $fixture = new FritzboxConnector($clientMock, $pages, ['debug' => true]);
        if (!$fixture->connect()) {
            $this->fail('not connecting with box');
        }
        if (!$fixture->login($fritzboxUser, $fritzboxPassword)) {
            $this->fail('not logged in in box');
        }

        $overview = new Overview($pages);
        $response = $fixture->send($overview, Pages::DEFAULT);

        $jsonArray = json_decode($response->getBody()->getContents(), true);
        self::assertArrayHasKey('boxDate', $jsonArray['data']['fritzos']);

        self::assertTrue($fixture->logout());
    }/**
     * Test NetworkOverview
     * @throws GuzzleException
     * @throws RuntimeException
     * @throws AssertionFailedError
     */
    public function testNetworkOverview()
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $pages = new Pages();
        if ($fritzboxUrl === null) {
            $this->fail('fritzbox url is null');
        }
        $clientMock = new Client(['base_uri' => $fritzboxUrl]);
        $fixture = new FritzboxConnector($clientMock, $pages, ['debug' => true]);
        if (!$fixture->connect()) {
            $this->fail('not connecting with box');
        }
        if (!$fixture->login($fritzboxUser, $fritzboxPassword)) {
            $this->fail('not logged in in box');
        }

        $network = new NetworkRequest($pages);
        $response = $fixture->send($network, Pages::HOMENET);

        $clients = (new NetworkListHelper($response->getBody()->getContents()))->getClientList();
        self::assertInternalType('array', $clients);
        $firstElement = $clients[0];
        self::assertInstanceOf(NetworkClient::class, $firstElement);

        self::assertTrue($fixture->logout());
    }/**
     * Test NetworkDetails
     * @throws GuzzleException
     * @throws RuntimeException
     * @throws AssertionFailedError
     */
    public function testNetworkDetails()
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $pages = new Pages();
        if ($fritzboxUrl === null) {
            $this->fail('fritzbox url is null');
        }
        $clientMock = new Client(['base_uri' => $fritzboxUrl]);
        $fixture = new FritzboxConnector($clientMock, $pages, ['debug' => true]);
        if (!$fixture->connect()) {
            $this->fail('not connecting with box');
        }
        if (!$fixture->login($fritzboxUser, $fritzboxPassword)) {
            $this->fail('not logged in in box');
        }

        $networkConnection = new NetworkConnectionRequest($pages);
        $response = $fixture->send($networkConnection,Pages::NETDEV);
        $content = $response->getBody()->getContents();

        $clients = (new NetworkListHelper($content))->getClientList();
        self::assertInternalType('array', $clients);
        $firstElement = $clients[0];
        self::assertInstanceOf(NetworkClientDetais::class, $firstElement);


        self::assertTrue($fixture->logout());
    }
}