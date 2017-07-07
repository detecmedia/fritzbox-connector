<?php

namespace Detecmedia\FritzboxConnector\Request;

/**
 * Class Logout
 * @package Detecmedia\FritzboxConnector\Request
 * @version $id$
 */
class Logout extends Request
{
    public function getPostVars(string $sid, string $const, string $html): array
    {
        $data = parent::getPostVars($sid, $const, $html);

        return array_merge($data, ['logout' => 1, 'no_sidrenew' => '']);
    }

}