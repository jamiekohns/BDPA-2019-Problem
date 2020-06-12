<?php

/**
 * Inline cURL requests
 */

header('Content-type: text/plain');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://elections.api.hscc.bdpa.org/v1/elections?");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "key: 51b6bdc3-c24e-471c-a81e-5487438c6651",
    "content-type: application/json",));

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

var_dump($info);
var_dump(json_decode($response, true));