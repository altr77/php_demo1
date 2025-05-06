<?php
// Enable error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = 'ec2-13-203-219-124.ap-south-1.compute.amazonaws.com';
$user = 'admin';
$password = 'StrongPassword123';
$database = 'oesm';

// Create connection
$con = new mysqli($host, $user, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
