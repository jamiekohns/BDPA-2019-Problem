<?php
declare(strict_types=1);

namespace App\Auth;

use App\Database\Db;

class Auth extends Db
{

    public function authenticate(string $username, string $password)
    {
        $query = $this->db->prepare()
    }
}