<?php
require_once('config.php');


$password_plain = $_POST["passwd"];

// Insert user data into the users table
$sql = "INSERT INTO users (first_name, last_name, email, city, state_name, passwd, date_added)
        VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $_POST["first_name"],
    $_POST["last_name"],
    $_POST["email"],
    $_POST["city"],
    $_POST["state_name"],
    $password_plain
);

mysqli_stmt_execute($stmt);
$user_id = mysqli_insert_id($conn); // Get the ID of the newly created user

// Insert needs into the needs table
$needs = explode(",", $_POST["needs"]);
foreach ($needs as $need) {
    $need = trim($need); // Remove any extra spaces
    $sql = "INSERT INTO needs (user_id, needs, date_added) VALUES (?, ?, NOW())";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $need);
    mysqli_stmt_execute($stmt);
}

// Insert offers into the offers table
$offers = explode(",", $_POST["offers"]);
foreach ($offers as $offer) {
    $offer = trim($offer); // Remove any extra spaces
    $sql = "INSERT INTO offers (user_id, offers, date_added) VALUES (?, ?, NOW())";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $offer);
    mysqli_stmt_execute($stmt);
}

header("Location: success.php");
