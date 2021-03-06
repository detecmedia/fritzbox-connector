<?php

namespace Detecmedia\FritzboxConnector\Request;

use Detecmedia\FritzboxConnector\Pages;
use PHPUnit\Framework\TestCase;

/**
 * Class LogoutTest
 * @package Detecmedia\FritzboxConnector\Request
 * @version $id$
 */
class LogoutTest extends TestCase
{

    public function testGetPostVars()
    {
        $pagesMock = $this->createMock(Pages::class);
        $fixtures = new Logout($pagesMock);
        self::assertEquals(
            [
                'xhr' => 1,
                'sid' => 'foo',
                'logout' => 1,
                'no_sidrenew' => '',
            ],
            $fixtures->getPostVars('foo', Pages::INDEX, 'foobar'));
    }
}
