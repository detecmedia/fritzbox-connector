<?php
use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use Detecmedia\FritzboxConnector\Pages;
use Detecmedia\FritzboxConnector\Request\Overview;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

/**
 * Class TestTest
 * @package ${NAMESPACE}
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
    public function test(): void
    {
        global $fritzboxUrl, $fritzboxUser, $fritzboxPassword;
        $pages = new Pages();

        $clientMock = new Client(['base_uri' => $fritzboxUrl]);
        $fixture = new FritzboxConnector($clientMock, $pages, ['debug' => true]);
        if (!$fixture->login($fritzboxUser, $fritzboxPassword)) {
            $this->fail('not logged in in box');
        }

        $overview = new Overview($pages);
        $response = $fixture->send($overview, Pages::DEFAULT);

        $jsonArray = json_decode($response->getBody()->getContents(), true);
        $tmp = explode(' ', $jsonArray['data']['fritzos']['boxDate']);
        $tmp[0] = substr($tmp[0], 0, strrpos($tmp[0], ':'));
        date_default_timezone_set('Europe/Berlin');
        $date = implode(' ', $tmp);
        $currentTime = date('H:i d.m.Y');
        self::assertEquals(
            $currentTime,
            $date
        );
        self::assertTrue($fixture->logout());
    }
}