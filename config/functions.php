<?php

function isAuthorized()
{
    if (session_status() == PHP_SESSION_ACTIVE) {
        if (!isset($_SESSION['token']) || !isset($_SESSION['user'])) {
            return false;
        }

        $token = md5(SECRET_KEY . $_SESSION['user']['username']);

        if ($token === $_SESSION['token']) {
            return true;
        }
    }

    return false;
}

function login(PDO $db, string $username, string $password)
{
    $loginSth = $db->prepare('SELECT * FROM `user` 
      LEFT JOIN `user_type` ON (`user_type`.`id` = `user`.`user_type_id`)
      WHERE `user`.`username` = :username AND `user`.`password_hash` = :password');

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $loginSth->execute([
        ':username' => $username,
        ':password' => $hash,
    ]);
    $auth = $loginSth->fetch(PDO::FETCH_ASSOC);

    if ($auth['username'] === $username) {
        $accessLogSth = $db->prepare('INSERT INTO `access_log` (`user_id`, `action`, `ip_address`, `data`)
          VALUES (:user_id, :action, :ip_address, :data)');
        $accessLogSth->execute([
            ':user_id' => $auth['id'],
            ':action' => 'login',
            ':ip_address' => $_SERVER['REMOTE_ADDR'],
            ':data' => json_encode([
                'referrer' => $_SERVER['HTTP_REFERER'],
            ])
        ]);

        $_SESSION['token'] = md5(SECRET_KEY . $_SESSION['username']);
        $_SESSION['user'] = [
            'id' => $auth['id'],
            'username' => $auth['username'],
            'name' => $auth['first_name'] . ' ' . $auth['last_name'],
            'type' => $auth
        ];
    }

    return false;
}