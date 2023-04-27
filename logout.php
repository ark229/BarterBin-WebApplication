<?php
require_once('config.php');
require_once('session.php');

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or any other page you prefer
header("Location: login.php");
