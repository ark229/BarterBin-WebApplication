<?php
require_once('config.php');
require_once('session.php');

$email = $_POST['email'];
$password = $_POST['passwd'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Verify the entered password against the stored hashed password
    if (password_verify($password, $row['passwd'])) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['email'] = $row['email'];
        header("Location: main.php");
    } else {
        header("Location: login.html?error=wrongpassword");
    }
} else {
    header("Location: login.html?error=nouser");
}

mysqli_close($conn);
