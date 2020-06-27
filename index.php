<?php

require_once 'config/config.php';
require_once 'config/functions.php';

if (!isAuthorized()) {
    // header('location: login.php');
}
