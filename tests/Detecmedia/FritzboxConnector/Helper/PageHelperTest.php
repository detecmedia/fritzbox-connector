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
     * Test get pages
     */
    public function testGetPages()
    {
        $fixture = new PageHelper();
        $html = file_get_contents(__DIR__ . '/../Fixtures/logedin-page.html');
        $pages = $fixture->getPages($html);
        self::assertTrue(array_key_exists('rootReboot', $pages));
    }
}
