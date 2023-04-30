<?php
$servername = 'us-cdbr-east-06.cleardb.net';
$username = 'b3588185a24fe4';
$password = 'ebdf00d5';
$dbname = 'heroku_24d833d07b8f60f';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
