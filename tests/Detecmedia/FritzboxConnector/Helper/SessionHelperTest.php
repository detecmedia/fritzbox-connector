<?php

namespace Detecmedia\FritzboxConnector\Helper;

use PHPUnit\Framework\TestCase;

/**
 * Class SessionHelperTest
 * @package Detecmedia\FritzboxConnector\Helper
 * @version $id$
 */
class SessionHelperTest extends TestCase
{
    /**
     * Tests get session id
     */
    public function testGetSession(): void
    {
        $fixture = new SessionHelper();
        $html = file_get_contents(__DIR__ . '/../Fixtures/logedin-page.html');
        self::assertEquals(
            '32b0159f04a49e84',
            $fixture->getSession($html)
        );
    }
}
