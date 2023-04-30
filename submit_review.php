<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $name = $_POST['name'];
    $review = $_POST['review'];

    $stmt = $conn->prepare("INSERT INTO reviews (rating, name, review) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $rating, $name, $review);

    if ($stmt->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
