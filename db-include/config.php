<?php
$host = 'us-cdbr-east-06.cleardb.net';
$username = 'b3588185a24fe4';
$password = 'ebdf00d5';
$dbname = 'heroku_24d833d07b8f60f';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connection = new mysqli($host, $username, $password, $dbname);


// Check the connection
if ($conn->connect_error) {
    // Define your custom error message here
    $errorMessage = "We're sorry, but we're unable to connect to the database at the moment. Please try again later.";

    // You can log the error message for debugging purposes
    error_log("Connection failed: " . $conn->connect_error);

    // Display the custom error message to the user
    echo $errorMessage;
    exit;
}
