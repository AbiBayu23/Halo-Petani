<?php
session_start();

// Ambil data login dari form
$username = $_POST['username'];
$password = $_POST['password'];

include 'config.php';

$sql = "SELECT id, username, password FROM pengguna WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Debugging
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password . "<br>";
    echo "Hashed Password: " . $row['password'] . "<br>";

    // Asumsikan password disimpan dalam bentuk hash
    if (password_verify($_POST['password'], $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Redirect ke halaman profil
        header('Location: ../Dashboard/DashboardUser.html');
        exit;
    } else {
        echo "Password salah";
    }
} else {
    echo "Login gagal";
}

$stmt->close();
$conn->close();
?>
