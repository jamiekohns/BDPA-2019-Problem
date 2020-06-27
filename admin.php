<?php

require_once 'config/config.php';
require_once 'config/functions.php';

$error = null;

if (!isAuthorized(true)) {
    header('location: login.php');
}

if (isset($_POST['submit'])) {
    if ($_POST['password'] !== $_POST['password2']) {
        $error = 'Passwords do not match!';
    } else {
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // create PDO statement to INSERT into user table
        // execute statement (wrap in try-catch!)

        // create PDO statement to save a row to the access log with action = 'addUser'
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
            <h3>Create New User</h3>
            <form action="admin.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="password2">Confirm Password</label>
                    <input type="password2" class="form-control" id="password2" name="password2">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="fist_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name">
                </div>
                <div class="form-group">
                    <label for="user_type">Type</label>
                    <select name="user_type" id="user_type">
                        <option value="2">Voter</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </body>
</html>
