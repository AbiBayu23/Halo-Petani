<?php
include 'config.php';


$conn = new mysqli($server, $user, $password, $nama_database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $noHP = $_POST['noHP'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO pengguna (username, email, noHP, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user, $email, $noHP, $password);

    if ($stmt->execute()) {
        header("Location: login_pengguna.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
