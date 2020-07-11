<?php

require_once 'config/config.php';
require_once 'config/functions.php';

if (!isAuthorized()) {
    header('location: login.php');
}
// This is a comment
?>
<html>
    <head>
        <title>Elections</title>
        <script src="public/js/jquery-3.5.1.min.js"></script>
        <script src="public/js/bootstrap.min.js"></script>
        <link href="public/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="public/css/style.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>Elections</h1>
            <hr>
            <h3>Welcome, <?=$_SESSION['user']['name']?></h3>
        </div>
    </body>
</html>
