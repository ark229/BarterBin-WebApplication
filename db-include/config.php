<?php
$host = 'us-cdbr-east-06.cleardb.net';
$username = 'b3588185a24fe4';
$password = 'ebdf00d5';
$dbname = 'heroku_24d833d07b8f60f';

$connection = new mysqli($host, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
