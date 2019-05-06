<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 05.09.17
 * Time: 18:51
 */

namespace Detecmedia\FritzboxConnector\Helper;


use Detecmedia\FritzboxConnector\Model\Client;
use Detecmedia\FritzboxConnector\Model\ClientDetails;
use DOMDocument;
use DOMElement;

class NetworkListHelper
{
    /** @var Client[]|ClientDetails[] $clients */
    private $clients = [];

    /** @var  string $content */
    private $content;

    /**
     * NetworkListHelper constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @return ClientDetails[]|Client[]
     */
    public function getClientList(): array
    {

        if (!$this->isJson($this->content)) {
            $this->clients = $this->getClientListFromHtml($this->content);
        } else {
            $this->clients = $this->getClientListFormJson($this->content);
        }

        return $this->clients;
    }

    private function getClientListFormJson($json)
    {
        $clients = [];

        $jsonArray = json_decode($json, true);
        foreach ($jsonArray['data']['active'] as $client) {
            $clients[] = new ClientDetails($client);
        }

        return $clients;
    }

    private function getClientListFromHtml(string $content)
    {
        $clients = [];
        $dom = new DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($content);
        $trNotes = $dom->getElementsByTagName('tr');

        /** @var DOMElement $trNote */
        foreach ($trNotes as $trNote) {
            $tdNotes = $trNote->getElementsByTagName('td');
            /** @var DOMElement $tdNote */
            $client = new Client();

            foreach ($tdNotes as $tdNote) {
                $className = $tdNote->getAttribute('class');
                if ($className === 'name') {
                    $clients[] = $client;
                    $client->setName($tdNote->getAttribute('title'));
                    if ($aNotes = $tdNote->getElementsByTagName('a')) {
                        /** @var DOMElement $aNote */
                        foreach ($aNotes as $aNote) {
                            $href = $aNote->getAttribute('href');
                            $get_string = parse_url($href, PHP_URL_QUERY);
                            parse_str($get_string, $get_array);
                            $client->setUrl($get_array['lnk']);
                        }
                    }
                }
                if ($className === 'details') {
                    if ($aNotes = $tdNote->getElementsByTagName('a')) {
                        /** @var DOMElement $aNote */
                        foreach ($aNotes as $aNote) {
                            $href = $aNote->getAttribute('href');
                            $get_string = parse_url($href, PHP_URL_QUERY);
                            parse_str($get_string, $get_array);
                            if (array_key_exists('dev', $get_array)) {
                                $client->setUid($get_array['dev']);
                            }
                        }
                    }
                }
            }

        }
        return $clients;
    }

    public function getClientByDevice($deviceName): Client
    {
        if (!$this->clients) {
            $this->getClientList();
        }
        $clientByDevice = null;
        foreach ($this->clients as $client) {
            if ($client->getUid() === $deviceName) {
                $clientByDevice = $client;
                break;
            }
        }
        return $clientByDevice;
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}