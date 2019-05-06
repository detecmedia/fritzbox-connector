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
        $pattern = '/"(?<name>.*?)".*\{[^}]*": "(?<lua>.*?.lua)"[^}]*\}/';
        preg_match_all($pattern, $html, $matches);

        return $this->convert($matches);
    }

    /**
     * @param $matches
     * @return array
     */
    private function convert($matches): array
    {
        $convert = [];
        $iMax = count($matches['name']);
        for ($i = 0; $i < $iMax; $i++) {
            $convert[$matches['name'][$i]] = str_replace(
                '\/',
                '/',
                $matches[2][$i]
            );
        }

        return $convert;
    }

    public function getVars($html): array
    {
        $matches = [];
        $pattern = '/"(?<name>.*?)":.{0,1}"(?<value>.*?)"/';
        preg_match_all($pattern, $html, $matches);

        return $this->convert($matches);
    }

}