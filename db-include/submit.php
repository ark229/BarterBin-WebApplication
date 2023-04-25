
<?php

require_once('db-include/config.php');
require_once('db-include/session.php');


if (isset($_POST['submit'])) {
    // Get the form data
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $password = $conn->real_escape_string($_POST['password']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the data into the "users" table
    $sql = "INSERT INTO users (first_name, last_name, email, city, state, password, date_added)
            VALUES ('$first_name', '$last_name', '$email', '$city', '$state', '$hashed_password', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    if (isset($_SESSION['success_message'])) {
        echo "<p>" . $_SESSION['Registration is successful! Please Login.'] . "</p>";
        // Remove the session variable after displaying the message
        unset($_SESSION['success_message']);
    }
}

// Close the connection
$conn->close();
?>