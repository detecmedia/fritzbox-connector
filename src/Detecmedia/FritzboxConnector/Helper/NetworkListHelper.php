<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 05.09.17
 * Time: 18:51
 */

namespace Detecmedia\FritzboxConnector\Helper;


use Detecmedia\FritzboxConnector\Model\Client;
use DOMDocument;
use DOMElement;

class NetworkListHelper
{
    /** @var Client[] $clients */
    private $clients = [];

    /** @var  string $html */
    private $html;

    /**
     * NetworkListHelper constructor.
     * @param $html
     */
    public function __construct($html)
    {
        $this->html = $html;
    }

    /**
     * @param string $html
     * @return Client[]
     */
    public function getClientList(): array
    {
        $clients = [];
        $dom = new DOMDocument();
        $dom->loadHTML($this->html);
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
                            $client->setLink($get_array['lnk']);
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
                                $client->setDevice($get_array['dev']);
                            }
                        }
                    }
                }
            }

        }
        $this->clients = $clients;
        return $clients;
    }

    public function getClientByDevice($deviceName): Client
    {
        if (!$this->clients) {
            $this->getClientList();
        }
        $clientByDevice = null;
        foreach ($this->clients as $client) {
            if ($client->getDevice() === $deviceName) {
                $clientByDevice = $client;
                break;
            }
        }
        return $clientByDevice;
    }
}