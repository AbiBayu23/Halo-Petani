<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

$sql = "SELECT id_admin, password FROM admin";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_admin'];
        $password = $row['password'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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

$username = $_POST['username'];
$password = $_POST['password'];

echo "Form Data - Username: $username, Password: $password<br>";

$username = stripslashes($username);
$password = stripslashes($password);
$username = mysqli_real_escape_string($conn, $username);

$sql = "SELECT username, hashed_password FROM admin WHERE username=?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Execute failed: " . $stmt->error);
}

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['hashed_password'];

    if (password_verify($password, $hashed_password)) {
        session_start();
        $_SESSION['admin_username'] = $username;
        header("location: ../Dashboard/DashboardAdmin.html");
        exit();
    } else {
        echo "Password salah. <a href='login_admin.html'>Coba lagi</a>";
    }
} else {
    echo "Username tidak ditemukan. <a href='login_admin.html'>Coba lagi</a>";
}

$conn->close();
?>
