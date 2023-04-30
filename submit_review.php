<?php
include 'config.php';

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
