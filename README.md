# fritzbox-connector
**Test with FRITZ!Box 6490 Cable (lgi)**

**Connect with Fritzbox FRITZ!OS:07.02**

```php
<?php
require 'bootstrap.php';
require 'vendor/autoload.php';
require 'src/Detecmedia/FritzboxConnector/Pages.php';

use Detecmedia\FritzboxConnector\Pages;
use Detecmedia\FritzboxConnector\Connector\FritzboxConnector;
use GuzzleHttp\Client;

$pages = new Pages();

$clientMock = new Client(['base_uri' => $fritzboxUrl]);
$connector = new FritzboxConnector($clientMock, $pages, ['debug' => true]);

if (!$connector->connect()) {
    throw new \RuntimeException('not connecting with box');
}
if (!$connector->login($fritzboxUser, $fritzboxPassword)) {
    throw new \RuntimeException('not logged in in box');
}

/**
Request
*/
$connector->logout();

```

**Overview request**
```php
/** @var RequestInterface $overview */
$overview = new Overview($pages);
/** @var ResponseInterface $response */
$response = $fixture->send($overview, Pages::DEFAULT);
/** @var array[][] $jsonArray */
$jsonArray = json_decode($response->getBody()->getContents(), true);
echo $jsonArray['data']['fritzos'];

```

**NetworkOverview request**
```php
/** @var RequestInterface $network */
$network = new NetworkRequest($pages);
/** @var ResponseInterface $response */
$response = $fixture->send($network, Pages::HOMENET);
/** @var Client[] $clients */
$clients = (new NetworkListHelper($response->getBody()->getContents()))->getClientList();

```

**NetworkDetails request**
```php
/** @var RequestInterface $network */
$network = new NetworkRequest($pages);
/** @var ResponseInterface $response */
$response = $fixture->send($network, Pages::HOMENET);
/** @var ClientDetails[] $clients */
$clients = (new NetworkListHelper($response->getBody()->getContents()))->getClientList();

```