<?php

namespace Tests\Detecmedia\FritzboxConnector\Connector;

use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use Detecmedia\FritzboxConnector\Helper\NetworkListHelper;
use Detecmedia\FritzboxConnector\Pages;
use Detecmedia\FritzboxConnector\Request\NetworkRequest;
use Detecmedia\FritzboxConnector\Request\Overview;
use Detecmedia\FritzboxConnector\Model\Client as NetworkClient;
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
     * Test
     * @throws GuzzleException
     * @throws RuntimeException
     * @throws AssertionFailedError
     */
    public function test()
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
        $networkHome = new NetworkRequest($pages);
        $response = $fixture->send($networkHome, Pages::HOMENET);

        $clients = (new NetworkListHelper($response->getBody()->getContents()))->getClientList();
        self::assertInternalType('array', $clients);
        $firstElement = $clients[0];
        self::assertInstanceOf(NetworkClient::class, $firstElement);

        self::assertTrue($fixture->logout());
    }
}