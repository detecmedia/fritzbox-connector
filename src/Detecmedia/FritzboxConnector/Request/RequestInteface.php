<?php

namespace Detecmedia\FritzboxConnector\Request;

interface RequestInteface
{
    public function getMethod();

    public function getPostVars(string $sid, string $const, string $html);

    public function getUrl();
}