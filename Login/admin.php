<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi koneksi ke database
$server = 'localhost';
$user = 'root';
$password = 'Perkasa23@rcm';
$nama_database = 'halopetani';

// Koneksi ke database
$conn = new mysqli($server, $user, $password, $nama_database);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data admin dari tabel
$sql = "SELECT id_admin, password FROM admin";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_admin'];
        $password = $row['password'];

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan hashed password ke dalam kolom baru
        $update_sql = "UPDATE admin SET hashed_password='$hashed_password' WHERE id_admin=$id";
        if ($conn->query($update_sql) === TRUE) {
            echo "Password berhasil di-hash dan disimpan kembali untuk id $id.<br>";
        } else {
            echo "Error updating record for id $id: " . $conn->error . "<br>";
        }
    }
} else {
    echo "Tidak ada data admin yang ditemukan.";
}

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Debug: Output the form data
echo "Form Data - Username: $username, Password: $password<br>";

// Lindungi dari SQL injection
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysqli_real_escape_string($conn, $username);

// Query untuk mengambil data admin dari database
$sql = "SELECT username, hashed_password FROM admin WHERE username=?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Debug: Check the query result
if ($result === false) {
    die("Execute failed: " . $stmt->error);
}

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['hashed_password'];

    // Verifikasi password
    if (password_verify($password, $hashed_password)) {
        // Login berhasil, redirect ke halaman admin
        session_start();
        $_SESSION['admin_username'] = $username;
        header("location: ./Dashboard/DashboardAdmin.html");
        exit();
    } else {
        echo "Password salah. <a href='login_admin.html'>Coba lagi</a>";
    }
} else {
    // Username tidak ditemukan
    echo "Username tidak ditemukan. <a href='login_admin.html'>Coba lagi</a>";
}

// Tutup koneksi
$conn->close();
?>
