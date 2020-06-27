<?php

require_once 'config/config.php';
require_once 'config/functions.php';

if (!isAuthorized()) {
    header('location: login.php');
}

?>
<html>
    <head>
        <title>Elections</title>
        <script src="js/jquery-3.5.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="css/style.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h1>Elections</h1>
            <hr>
            <h3>Welcome, <?=$_SESSION['user']['name']?></h3>
        </div>
    </body>
</html>
