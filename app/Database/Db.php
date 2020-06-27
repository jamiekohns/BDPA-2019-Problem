<?php

namespace App\Database;

use PDO;

class Db
{

    private $db;

    /**
     * Db constructor.
     * @param string $user database username
     * @param string $password database password
     * @param string $dbname which database to use
     * @param string $host databases host
     * @param int $port database port
     */
    public function __construct(string $user, string $password, string $dbname, string $host = 'localhost', $port = 3306)
    {
        try {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;port=%s',
                $host,
                $dbname,
                $port
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->db = new PDO($dsn, $user, $password, $options);
        } catch (\Exception $e) {
            die('ERROR: Could not connect to database');
        }
    }
}