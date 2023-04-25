<?php
$host = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database_name';

$connection = new mysqli($host, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
