<?php

namespace Detecmedia\FritzboxConnector\Helper;

use PHPUnit\Framework\TestCase;

/**
 * Class PageHelperTest
 * @package Detecmedia\FritzboxConnector\Helper
 * @version $id$
 */
class PageHelperTest extends TestCase
{
    /**
     * @var PageHelper
     */
    private $fixture;

    protected function setUp(): void
    {
        $this->fixture = new PageHelper();
    }

    /**
     * Test get pages
     */
    public function testGetPages(): void
    {
        $fixture = $this->fixture;
        $html = $this->getHtmlFixture();
        $pages = $fixture->getPages($html);
        self::assertTrue(array_key_exists('rootReboot', $pages));
        self::assertEquals(
            'wlan/pp_qrcode.lua',
            $pages['pp_qrcode']
        );
    }

    /**
     * Test get vars
     */
    public function testGetVars(): void
    {
        $fixture = $this->fixture;
        $html = $this->getHtmlFixture();
        $vars = $fixture->getVars($html);
        self::assertArrayHasKey('username', $vars);
        self::assertArrayHasKey('sid', $vars);
        self::assertArrayHasKey('lang', $vars);
        self::assertEquals('testuser', $vars['username']);
    }

    /**
     * Gets test html.
     * @return bool|string
     */
    private function getHtmlFixture()
    {
        return file_get_contents(__DIR__ . '/../Fixtures/logedin-page.html');
    }
}
