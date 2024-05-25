<?php
// Konfigurasi koneksi database
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
    $user = $_POST['username'];
    $noHP = $_POST['noHP'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Simpan user baru ke database
    $stmt = $conn->prepare("INSERT INTO pengguna (username, noHP, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $noHP, $password);

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
