<?php

namespace Detecmedia\FritzboxConnector;

use Detecmedia\FritzboxConnector\Helper\PageHelper;

/**
 * Class Pages
 * @package Detecmedia\FritzboxConnector
 * @version $id$
 */
class Pages
{
    const BOX_USER = 'boxuser_edit';
    const DEFAULT = 'overview';
    const INDEX = 'index';
    const HOMENET = 'homeNet';

    private $pageHelper;

    private $vars;

    /**
     * Pages constructor.
     */
    public function __construct()
    {
        $this->pageHelper = new PageHelper();

    }

    public function getVar($const, $html)
    {
        if (!$this->vars) {
            $this->vars = $this->pageHelper->getVars($html);
        }

        return $this->vars[$const];
    }

    /**
     * Define page urls
     * @param $const
     * @param $html
     * @return mixed
     */
    public function getPage($const, $html)
    {
        //$pages = $this->pageHelper->getPages($html);
        $pages['overview'] = 'data.lua';
        $pages['index'] = 'index.lua';
        $pages['homeNet'] = 'data.lua';


        return $pages[$const];
    }
}