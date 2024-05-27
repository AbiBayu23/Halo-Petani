<?php
session_start();

// Ambil data login dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Koneksi ke database
$servername = "localhost";
$db_username = "root";
$db_password = "Perkasa23@rcm";
$dbname = "halopetani";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, username, password FROM pengguna WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Asumsikan password disimpan dalam bentuk hash
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Redirect ke halaman profil
        header('Location: ../Dashboard/DashboardUser.html');
        exit;
    } else {
        echo "Login failed";
    }
} else {
    echo "Login failed";
}

$stmt->close();
$conn->close();
?>
