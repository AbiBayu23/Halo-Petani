<?php
$server = "localhost";
$user = "root";
$password = "Perkasa23@rcm";
$nama_database = "halopetani";
$noHP="no_HP";

// Buat koneksi ke database
$conn = new mysqli($server, $user, $password, $nama_database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $noHP = $_POST['noHP'];

    // Cari user berdasarkan username dan noHP
    $stmt = $conn->prepare("SELECT password FROM pengguna WHERE username = ? AND noHP = ?");
    $stmt->bind_param("ss", $username, $noHP);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $hashed_password)) {
            header("Location: DashboardUser.html");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid username or No HP.";
    }

    $stmt->close();
}

$conn->close();
?>
