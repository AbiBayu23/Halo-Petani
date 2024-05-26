<?php
// Database configuration
$dbHost = 'localhost:8111'; // atau IP server database
$dbUsername = 'root'; // username database
$dbPassword = 'fayalahw17904'; // password database
$dbName = 'hallofarmer'; // nama database

// Create database connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

