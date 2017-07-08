<?php

namespace Detecmedia\FritzboxConnector\Helper;

/**
 * Class SessionHelper
 * @package Detecmedia\FritzboxConnector\Helper
 * @version $id$
 */
class SessionHelper
{
    /**
     * Get session from html.
     * @param string $html
     * @return string
     */
    public function getSession(string $html): string
    {
        $matches = [];
        $pattern = '/"sid": "(?<sid>.*?)"/';
        preg_match($pattern, $html, $matches);

        return $matches['sid'];
    }
}