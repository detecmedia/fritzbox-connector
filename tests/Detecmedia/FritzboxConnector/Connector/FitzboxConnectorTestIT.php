<?php
use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Tests\Detecmedia\MinkTestCase;

/**
 * Class TestTest
 * @package ${NAMESPACE}
 * @version $id$
 */
class FitzboxConnectorTestIT extends TestCase
{
    public function test()
    {
        $clientMock = new Client(['base_uri' => '192.168.4.1']);
        $fixture = new FritzboxConnector($clientMock);
        $fixture->login('markus', 'MaPo481312');
        self::assertTrue(true);
    }
}