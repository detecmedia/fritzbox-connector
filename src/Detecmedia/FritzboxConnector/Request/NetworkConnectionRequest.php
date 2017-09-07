<?php

namespace Detecmedia\FritzboxConnector\Request;

/**
 * Class Network
 * @package Detecmedia\FritzboxConnector\Request
 * @author Markus Potthast <markus.potthast@detecmedia.de>
 * @version $id$
 */
class NetworkConnectionRequest extends Request
{
    public function getPostVars(string $sid, string $const, string $html): array
    {
        $data = parent::getPostVars($sid, $const, $html);

        return array_merge(
            $data,
            [
                'page' => $const,
                'no_sidrenew' => '',
                'type' => 'all'
            ]
        );
    }
}
