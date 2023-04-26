<!DOCTYPE html>

<?php

require_once('config.php');
require_once('session.php');

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$city = $_POST['city'];
$state_name = $_POST['state_name'];
$passwd = $_POST['passwd'];

// Hash the password for security
$hashed_password = password_hash($passwd, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (first_name, last_name, email, city, state_name, passwd, date_added) VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $first_name, $last_name, $email, $city, $state_name, $hashed_password);
header("Location: /login.php"); // Redirect to the login page after successful registration


//if ($stmt->execute()) {
// header("Location: /login.php"); // Redirect to the login page after successful registration
//} else {
//echo "Error: " . $sql . "<br>" . $conn->error;
//}

$stmt->close();
$conn->close();


?>