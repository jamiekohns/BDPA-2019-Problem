<?php

require_once 'config/config.php';
require_once 'Client/CurlClient.php';

header('Content-type: text/plain');

$client = new CurlClient(
    $config['api_key'],
    'https://elections.api.hscc.bdpa.org/v1',
);


$response = $client->get('/elections')
    ->getResponse();

var_dump($response);