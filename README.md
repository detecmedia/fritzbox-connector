# fritzbox-connector
Connect with Fritzbox FRITZ!OS:06.50

```php
$pages = new Pages();

$clientMock = new Client(['base_uri' => $fritzboxUrl]);
$connector = new FritzboxConnector($clientMock, $pages, ['debug' => true]);

if (!$connector->connect()) {
    throw new \RuntimeException('not connecting with box');
}
if (!$connector->login($fritzboxUser, $fritzboxPassword)) {
    throw new \RuntimeException('not logged in in box');
}

$overview = new Overview($pages);
$response = $connector->send($overview, Pages::DEFAULT);

$jsonArray = json_decode($response->getBody()->getContents(), true);
echo $jsonArray['data']['fritzos']['boxDate'];
$connector->logout();

```

