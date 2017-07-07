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
     * @var Pages
     */
    private $pages;

    private $url;

    /**
     * Request constructor.
     * @param ClientInterface $client
     * @param Pages $pages
     * @param string $method
     * @internal param PageHelper $pageHelper
     * @internal param Pages $pages
     */
    public function __construct(Pages $pages, $method = 'POST')
    {
        $this->pages = $pages;
        $this->method = $method;
    }

    /**
     * Gets Post vars
     * @param string $sid
     * @param string $const
     * @param string $html
     * @return array
     */
    public function getPostVars(string $sid, string $const, string $html): array
    {
        $this->url = $this->pages->getPage($const, $html);

        return [
            'xhr' => 1,
            'sid' => $sid,
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