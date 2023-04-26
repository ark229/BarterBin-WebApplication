<?php
require_once('config.php');

//$password_hash = password_hash($_POST["passwd"], PASSWORD_DEFAULT);
$sql = "INSERT INTO contactmessage (fullname, email, messages, date_added)
        VALUES (?, ?, ?, NOW())";


$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);


mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $_POST["fullname"],
    $_POST["email"],
    $_POST["messages"],

);


mysqli_stmt_execute($stmt);
header("Location: contact-success.php");
