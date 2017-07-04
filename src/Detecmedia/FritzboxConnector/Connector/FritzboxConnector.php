<?php

namespace Detecmedia\FritzboxConnector\Connector;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
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
     * @var string
     */
    private $resp;

    /**
     * FritzboxConnector constructor.
     * @param ClientInterface $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * login in fritzbox
     * @throws RuntimeException
     * @throws GuzzleException
     */
    public function login(string $user, string $password): bool
    {
        $cookie_path = sys_get_temp_dir() . "/gazeta.cookie";
        $cookie_jar = new CookieJar();
        $params = [
            // 'cookies' => $cookie_jar,
            'headers' => [
                'Accept-Language' => 'de-DE,ru;q=0.8,en-US;q=0.6,en;q=0.4',
                'Host' => 'http://192.168.4.1',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, sdch',
                'Upgrade-Insecure-Requests' => 1,
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) ' .
                    'Chrome/53.0.2785.143 Safari/537.36',
                'Referer' => 'http://192.168.4.1/',
            ],
            'allow_redirects' => false,
            'http_errors' => true,
            'connect_timeout' => 15,
            'timeout' => 15,
            'debug' => true,
        ];
        $responseBefore = $this->client->request('GET', 'http://192.168.4.1', $params);
        $html = $responseBefore->getBody()->getContents();
        //file_put_contents(__DIR__.'/test.html', $html);
        $challenge = $this->getChallenge($html);
        $uiResponse = $this->getUiResp($challenge, $password);

        $formParams =
            [
                'response' => $uiResponse,
                'lp' => '',
                'username' => $user,
            ];

        $params ['form_params'] = $formParams;
        $responseAfter = $this->client->request('POST', 'http://192.168.4.1', $params);
        $html = $responseAfter->getBody()->getContents();
        file_put_contents(__DIR__ . '/test.html', $html);

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
     * @param $html
     * @param $password
     * @return string
     */
    private function getUiResp($challenge, $password): string
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
}