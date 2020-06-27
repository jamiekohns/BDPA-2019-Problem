<?php

require_once 'config/config.php';
require_once 'config/functions.php';

$error = null;

if (isset($_POST['submit'])) {
    if (login($db, $_POST['username'], $_POST['password'])) {
        header('location: index.php');
    } else {
        $error = 'Username or password not found!';
    }
}

?>

<html>
    <head>
        <title>Login</title>
        <script src="public/js/jquery-3.5.1.min.js"></script>
        <script src="public/js/bootstrap.min.js"></script>
        <link href="public/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="public/css/style.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <?php
                if ($error) {
                    echo '<div class="alert alert-danger">'.$error.'</div>';
                }
            ?>
            <form action="login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="form-group">
                    <label for="password">Username</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </body>
</html>
