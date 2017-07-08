<?php

namespace Tests\Detecmedia\FritzboxConnector\Connector;

use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use Detecmedia\FritzboxConnector\Pages;
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
     * Test get default page
     * @throws GuzzleException
     * @throws RuntimeException
     * @throws AssertionFailedError
     */
    public function test()
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $pages = new Pages();

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
    }
}