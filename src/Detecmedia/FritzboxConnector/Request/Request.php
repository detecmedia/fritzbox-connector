<?php

namespace Detecmedia\FritzboxConnector\Request;

use Detecmedia\FritzboxConnector\Helper\PageHelper;
use Detecmedia\FritzboxConnector\Pages;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * Class Request
 * @package Detecmedia\FritzboxConnector\Request
 * @version $id$
 */
abstract class Request implements RequestInteface
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var string
     */
    private $method;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Pages
     */
    private $pages;

    /**
     * @var PageHelper
     */
    private $pageHelper;

    private $url;

    /**
     * Request constructor.
     * @param ClientInterface $client
     * @param Pages $pages
     * @param string $method
     * @internal param PageHelper $pageHelper
     * @internal param Pages $pages
     */
    public function __construct(ClientInterface $client, Pages $pages, $method = 'POST')
    {
        $this->client = $client;
        $this->pages = $pages;
        $this->method = $method;
    }

    /**
     * Gets Post vars
     * @param string $sid
     * @return array
     */
    public function getPostVars(string $sid, $const, $html): array
    {
        $this->url = $this->pages->getPage($const, $html);

        return [
            'xhr' => 1,
            'sid' => $sid,
            'lang' => 'de',
            'page' => $const,
            'type' => 'all',
            'no_sidrenew' => 'Name',
        ];
    }

    public function getGetVars(): array
    {
        return [];
    }

    /**
     * Gets Method.
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Gets Url.
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     * @throws RuntimeException
     */
    public function getResponse(): string
    {
        return $this->response->getBody()->getContents();
    }

}