<?php

namespace Detecmedia\FritzboxConnector\Request;

interface RequestInteface
{
    /**
     * Gets response
     * @return string
     */
    public function getResponse(): string;

    public function getMethod();

    public function getPostVars(string $sid, string $const, string $html);

    public function getUrl();
}