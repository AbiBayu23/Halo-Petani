<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['admin_username'])) {
    header("location: login_admin.html");
    exit();
}

// Halaman admin hanya bisa diakses jika sudah login
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Selamat datang, <?php echo $_SESSION['admin_username']; ?></h2>
    <p>Ini adalah halaman dashboard admin.</p>
</body>
</html>
