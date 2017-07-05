<?php

namespace Detecmedia\FritzboxConnector\Connector;

use Detecmedia\FritzboxConnector\Pages;
use Detecmedia\FritzboxConnector\Request\RequestInteface as FritzboxRequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

/**
 * Class FritzboxConnector
 * @package Detecmedia\FritzboxConnector\Connector
 * @version $id$
 */
class FritzboxConnector
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string session id
     */
    private $sid;

    private $html;

    /**
     * @var Pages
     */
    private $pages;

    /**
     * FritzboxConnector constructor.
     * @param Client|ClientInterface $client
     * @param Pages $pages
     */
    public function __construct(Client $client, Pages $pages)
    {
        $this->client = $client;
        $this->pages = $pages;
    }

    /**
     * login in fritzbox
     * @param string $user
     * @param string $password
     * @return bool
     * @throws RuntimeException
     */
    public function login(string $user, string $password): bool
    {
        $cookie_path = sys_get_temp_dir() . "/gazeta.cookie";
        $cookie_jar = new CookieJar();
        $params = $this->getHeaders($this->client->getConfig('base_uri'));
        $responseBefore = $this->client->request('GET', $this->client->getConfig('base_uri'), $params);
        $html = $responseBefore->getBody()->getContents();
        $challenge = $this->getChallenge($html);
        $uiResponse = $this->getUiResp($challenge, $password);

        $formParams =
            [
                'response' => $uiResponse,
                'lp' => '',
                'username' => $user,
            ];

        $params ['form_params'] = $formParams;
        $responseAfter = $this->client->request('POST', $this->client->getConfig('base_uri'), $params);

        $this->html = $responseAfter->getBody()->getContents();

        $this->sid = $this->pages->getVar('sid', $this->html);

        return false;
    }

    /**
     * @param string $str
     * @return string
     */
    private function makeDots(string $str): string
    {
        $newStr = '';
        for ($i = 0, $iMax = strlen($str); $i < $iMax; $i++) {
            if (255 < ord($str[$i])) {
                $newStr .= '.';
            } else {
                $newStr .= $str[$i];
            }
        }

        return $newStr;
    }

    /**
     * @param string $challenge
     * @param string $password
     * @return string
     * @internal param $html
     */
    private function getUiResp(string $challenge, string $password): string
    {
        $dotPass = $this->makeDots($password);
        $resp = $challenge . '-' . $dotPass;
        $resp = mb_convert_encoding($resp, 'UTF-16LE', 'UTF-8');

        return $challenge . '-' . md5($resp);
    }

    private function getChallenge(string $html)
    {
        $matches = [];
        $pattern = '/"challenge": "(?P<value>.*?)",/';
        preg_match($pattern, $html, $matches);

        return $matches['value'];
    }

    /**
     * @inheritdoc
     * @throws GuzzleException
     */
    final public function send(FritzboxRequestInterface $request, $const)
    {
        $params = $this->getHeaders($this->client->getConfig('base_uri'));

        if ('POST' === $request->getMethod()) {
            $params ['form_params'] = $request->getPostVars($this->sid, $const, $this->html);
        }

        $client = $this->client;
        $response = $client->request(
            $request->getMethod(),
            $client->getConfig('base_uri') . '/' . $request->getUrl(),
            $params
        );

        return $response;
    }

    /**
     * Gets simulate browser headers.
     * @return array
     */
    private function getHeaders($host): array
    {
        return [
            // 'cookies' => $cookie_jar,
            'headers' => [
                'Accept-Language' => 'de-DE,ru;q=0.8,en-US;q=0.6,en;q=0.4',
                'Host' => $host,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, sdch',
                'Upgrade-Insecure-Requests' => 1,
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) ' .
                    'Chrome/53.0.2785.143 Safari/537.36',
                'Referer' => $host,
            ],
            'allow_redirects' => false,
            'http_errors' => true,
            'connect_timeout' => 15,
            'timeout' => 15,
            'debug' => true,
        ];
    }
}