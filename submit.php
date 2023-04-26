<?php
require_once('config.php');

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
    $_POST["passwd"]
);

mysqli_stmt_execute($stmt);

// Get the user_id of the newly inserted user
$user_id = mysqli_insert_id($conn);

// Insert needs and offers data into the "items" table
$sql2 = "INSERT INTO items (user_id, needs, offers, date_added)
         VALUES (?, ?, ?, NOW())";

$stmt2 = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt2, $sql2);

mysqli_stmt_bind_param(
    $stmt2,
    "iss",
    $user_id,
    $_POST["needs"],
    $_POST["offers"]
);

mysqli_stmt_execute($stmt2);

header("Location: success.php");
