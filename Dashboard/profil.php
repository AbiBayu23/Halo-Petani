<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('error' => 'User not logged in'));
    exit;
}

$user_id = $_SESSION['user_id'];

// Logging untuk debugging
error_log("User ID: " . $user_id);

include '../Login/config.php';

if ($conn->connect_error) {
    die(json_encode(array('error' => 'Connection failed: ' . $conn->connect_error)));
}

$sql = "SELECT username, email, noHP, tanggal_daftar FROM pengguna WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(array('error' => 'Prepare failed: ' . $conn->error)));
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die(json_encode(array('error' => 'Execute failed: ' . $stmt->error)));
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array('error' => 'No results found'));
}

$stmt->close();
$conn->close();
?>
