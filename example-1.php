<?php

require_once 'config/config.php';

/**
 * Inline cURL requests
 */

// output plain text
header('Content-type: text/plain');


/**
 * List all elections in the system
 * https://electionshscc.docs.apiary.io/#/reference/0/elections-endpoint/list-all-elections-in-the-system
 */

// set up our cURL instance
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://elections.api.hscc.bdpa.org/v1/elections?");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "key: " . $config['api_key'],
    "content-type: application/json",));

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

//var_dump($info);
//var_dump($response);
//
//exit;

/**
 * Return data about an election
 * https://electionshscc.docs.apiary.io/#/reference/0/election-endpoint/return-data-about-an-election
 */

$elections = json_decode($response, true);
$firstElection = array_shift($elections['elections']);
$electionId = $firstElection['election_id'];

// set up our cURL instance
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://elections.api.hscc.bdpa.org/v1/election/" . $electionId);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "key: " . $config['api_key'],
    "content-type: application/json",));

$response = curl_exec($ch);
curl_close($ch);

var_dump(json_decode($response, true));