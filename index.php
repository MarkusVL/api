<?php
error_reporting(1);
date_default_timezone_set('Europe/Warsaw');

include_once 'autoloader.php';

use Core\DuckyApi;

$api = new DuckyApi();
echo $api->getAllUsers();

