

<?php
require_once('config.php');

//$password_hash = password_hash($_POST["passwd"], PASSWORD_DEFAULT);
$sql = "INSERT INTO users (first_name, last_name, email, city, state_name, passwd, date_added)
        VALUES (?, ?, ?, ?, ?, ?, NOW());

        INSERT INTO items (needs, offers, date_added) VALUES (?, ?, NOW())";

//$stmt = $conn->stmt_init();
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

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $_POST["needs"],
    $_POST["offers"],
);

mysqli_stmt_execute($stmt);
header("Location: success.php");


?>

