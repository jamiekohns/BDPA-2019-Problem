<?php

session_start();

/**
 * Configuration variables will be represented by a single array of arrays
 */

$config = [
    'secret_key' => 'FIXME',
    'api_key' => 'FIXME',

    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'username' => 'FIXME',
        'password' => 'FIXME',
        'dbname' => 'FIXME',
    ],
];

define('SECRET_KEY', $config['secret_key']);

try {
    $db = new PDO(
        sprintf(
            'mysql:host=%s;dbname=%s;port=%s',
            $config['database']['host'],
            $config['database']['dbname'],
            $config['database']['port']
        ),
        $config['database']['username'],
        $config['database']['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (Exception $e) {
    die('ERROR: Could not connect to database!');
}
