<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(array('error' => 'User not logged in'));
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['id'];

// Establish database connection (replace with your database credentials)
$servername = "localhost";
$username = "root";
$password = "Perkasa23@rcm";
$dbname = "halopetani";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user's data
$sql = "SELECT username, email, noHP, tanggal_daftar FROM pengguna WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Output data of the first row
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array('error' => 'No results found'));
}

$stmt->close();
$conn->close();
?>
