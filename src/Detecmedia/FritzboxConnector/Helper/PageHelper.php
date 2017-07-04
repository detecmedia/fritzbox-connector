<?php

namespace Detecmedia\FritzboxConnector\Helper;

/**
 * Class PageHelper
 * @package Detecmedia\FritzboxConnector\Helper
 * @version $id$
 */
class PageHelper
{
    public function getPages($html): array
    {
        $matches = [];
        $pattern = '/"(?<name>.*?)".*\{[^}]*"(.*)": "(?<lua>.*?).lua"[^}]*\}/';
        preg_match_all($pattern, $html, $matches);

        return $this->convert($matches);
    }

    private function convert($matches)
    {
        $convert = [];
        $iMax = count($matches['name']);
        for ($i = 0; $i < $iMax; $i++) {
            $convert[$matches['name'][$i]] = $matches['lua'][$i];
        }

        return $convert;
    }
}