<?php

namespace Detecmedia\FritzboxConnector\Request;

/**
 * Class Overview
 * @package Detecmedia\FritzboxConnector\Request
 * @version $id$
 */
class Overview extends Request
{
    public function getPostVars(string $sid, string $const, string $html): array
    {
        $data = parent::getPostVars($sid, $const, $html);

        return array_merge(
            $data,
            [
                'lang' => 'de',
                'page' => $const,
                'type' => 'all',
                'no_sidrenew' => 'Name',
            ]
        );
    }

}