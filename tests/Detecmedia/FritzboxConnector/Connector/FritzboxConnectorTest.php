<?php

namespace Tests\Detecmedia\FritzboxConnector\Connector;

use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use Detecmedia\FritzboxConnector\Pages;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

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
    protected function setUp()
    {
        $this->fixture = $this->getFritzboxConnectorMock();
    }

    /**
     * @param array $mocks
     * @return FritzboxConnector
     */
    private function getFritzboxConnectorMock(array $mocks = []): FritzboxConnector
    {
        $pages = $this->createObj(Pages::class);

        $mock = new MockHandler([
            new Response(
                200,
                FritzboxConnector::getHeaders('http://example.com', true),
                $this->getLoginPage()
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['base_uri' => 'http://example.com', 'handler' => $handler]);

        foreach ($mocks as $key => $mock) {
            ${$key} = $mock;
        }

        return new FritzboxConnector($client, $pages);
    }

    /**
     * test fritzbox connector login.
     */
    public function testLogin()
    {
        $uiResponse = 'foo';
        $user = 'test-user';
        $parameters = FritzboxConnector::getHeaders('http://example.com', true);
        $formParams =
            [
                'response' => $uiResponse,
                'lp' => '',
                'username' => $user,
            ];

        $parameters ['form_params'] = $formParams;

        $mock = new MockHandler([
            new Response(
                200,
                $parameters,
                $this->getLoginPage()
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['base_uri' => 'http://example.com', 'handler' => $handler]);

        $fixture = $this->getFritzboxConnectorMock(['client' => $client]);

        $reflectionClass = new ReflectionClass($fixture);
        $reflectionPropertyChallenge = $reflectionClass->getProperty('challenge');
        $reflectionPropertyChallenge->setAccessible(true);
        $reflectionPropertyChallenge->setValue($fixture, 'foo');

        self::assertTrue($fixture->login('test-user', '*test*!'));
    }

    public function testMakeDots()
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

    /**
     * @throws ReflectionException
     */
    public function testGetChallenge()
    {
        $reflectionClass = new ReflectionClass($this->fixture);
        $getChallenge = $reflectionClass->getMethod('getChallenge');
        $getChallenge->setAccessible(true);

        $html = $this->getStartnPage();
        $result = $getChallenge->invokeArgs($this->fixture, [$html]);
        self::assertEquals(
            '108005d1',
            $result
        );
    }

    private function getLoginPage()
    {
        return file_get_contents(__DIR__ . '/../Fixtures/06.50/logedin-page.html');
    }

    private function getStartnPage()
    {
        return file_get_contents(__DIR__ . '/../Fixtures/06.50/login-page.html');
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

    private function createObj(string $class)
    {
        $clazz = $this->getMockBuilder($class);
        $clazz->disableOriginalConstructor();
        return $clazz->getMock();
    }
}
