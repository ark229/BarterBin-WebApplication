

<?php

require_once('db-include/config.php');
require_once('db-include/session.php');

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$city = $_POST['city'];
$state = $_POST['state_name'];
$password = $_POST['passwd'];

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if the user already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo "User already exists with this email.";
} else {
    // Insert the new user into the database
    $sql = "INSERT INTO users (first_name, last_name, email, city, state_name, passwd, date_added) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$first_name, $last_name, $email, $city, $state, $hashed_password]);

    if ($result) {
        echo "Registration successful. Please <a href='login.php'>Login</a>.";
    } else {
        echo "Registration failed. Please try again.";
    }
}

//mysqli_commit($conn);
//Close the connection
$conn->close();

?>

