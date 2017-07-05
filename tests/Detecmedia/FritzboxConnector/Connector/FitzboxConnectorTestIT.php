<?php
use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use Detecmedia\FritzboxConnector\Pages;
use Detecmedia\FritzboxConnector\Request\Overview;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Tests\Detecmedia\MinkTestCase;

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
     */
    public function test(): void
    {
        $pages = new Pages();

        $clientMock = new Client(['base_uri' => '192.168.4.1']);
        $fixture = new FritzboxConnector($clientMock, $pages);
        $html = $fixture->login('markus', 'MaPo481312');

        $overview = new Overview($clientMock, $pages);
        $response = $fixture->send($overview, Pages::DEFAULT);

        $jsonArray = json_decode($response->getBody()->getContents(), true);
        $tmp = explode(' ', $jsonArray['data']['fritzos']['boxDate']);
        $tmp[0] = substr($tmp[0], 0, strrpos($tmp[0], ':'));
        date_default_timezone_set('Europe/Berlin');
        $date = implode(' ', $tmp);
        $currentTime = date('h:i d.m.Y');
        self::assertEquals(
            $currentTime,
            $date
        );
    }
}