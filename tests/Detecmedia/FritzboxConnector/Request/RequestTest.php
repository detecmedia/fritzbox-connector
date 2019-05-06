<?php

namespace Detecmedia\FritzboxConnector\Request;

use Detecmedia\FritzboxConnector\Pages;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

/**
 * Class RequestTest
 * @package Detecmedia\FritzboxConnector\Request
 * @version $id$
 */
class RequestTest extends TestCase
{
    /**
     * @var
     */
    private $fixture;

    /**
     *
     */
    protected function setUp()
    {
        $pages = $this->createObj(Pages::class);
        $pages->method('getPage')
            ->with(PAGES::INDEX, 'foobar')
            ->willReturn('index.lua');
        $this->fixture = new class($pages, 'GET') extends Request
        {
        };
    }

    /**
     *
     */
    public function testGetPostVars()
    {
        self::assertEquals(
            [
                'xhr' => 1,
                'sid' => 'foo',
            ],
            $this->fixture->getPostVars('foo', PAGES::INDEX, 'foobar')
        );
    }

    /**
     *
     */
    public function testGetGetVars()
    {
        self::assertEquals(
            [],
            $this->fixture->getGetVars()
        );
    }

    public function testMethod()
    {
        self::assertEquals(
            'GET',
            $this->fixture->getMethod()
        );
    }

    public function testGetUrl()
    {
        $this->fixture->getPostVars('foo', Pages::INDEX, 'foobar');
        self::assertEquals(
            'index.lua',
            $this->fixture->getUrl()
        );
    }

    /**
     * @param string $name
     * @return MockObject
     */
    private function createObj(string $name): MockObject
    {
        $clazz = $this->getMockBuilder($name);
        $clazz->disableOriginalConstructor();
        return $clazz->getMock();
    }
}
