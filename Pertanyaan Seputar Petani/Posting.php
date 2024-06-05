<?php
session_start();
include '../Login/config.php';
date_default_timezone_set('Asia/Jakarta');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: ../Login/Login.html');
    exit;
}

$logged_in_user_id = $_SESSION['user_id'];
$logged_in_username = $_SESSION['username'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];
    $id_pengguna = $logged_in_user_id ;
    $tanggal_posting = date('Y-m-d : H:i:s');
    $foto = '';

    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "uploads/";
        $foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            echo "Maaf, file sudah ada.";
            $uploadOk = 0;
        }

        if ($_FILES["foto"]["size"] > 500000) { // Ukuran maksimal 500KB
            echo "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Maaf, file Anda tidak dapat diunggah.";
        } else {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                echo "File ". htmlspecialchars($foto). " telah diunggah.";
            } else {
                echo "Maaf, terjadi kesalahan saat mengunggah file Anda.";
            }
        }
    }

    $sql = "INSERT INTO pertanyaan (id_tanaman, id_pengguna, judul_pertanyaan, isi_pertanyaan, tanggal_posting, status_dilaporkan, foto, kategori) 
            VALUES (NULL, '$logged_in_user_id', '$judul', '$isi', '$tanggal_posting', 0, '$foto', '$kategori')";

    if ($conn->query($sql) === TRUE) {
        echo "Pertanyaan berhasil diposting!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<head lang="en">
<head> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posting Pertanyaan</title>
    <link rel="stylesheet" href="../Dashboard/style.css">
</head>
<style>
    body {
    font-family: 'Poppins', sans-serif;
    background-color: #fff;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

h1 {
    color: #364f6b;
    text-align: center;
    font-family: 'Poppins', sans-serif;
}

form {
    max-width: 600px;
    margin: 0 auto;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"],
input[type="file"],
textarea,
select {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

button[type="submit"] {
    background-color: #364f6b;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button[type="submit"]:hover {
    background-color: #364f6b;
}

.error-message {
    color: red;
    margin-bottom: 10px;
}
.top-users {
         margin-top: 20px;
        }

</style>
</head>
<body>
    <nav>
        <div class="wrapper">
            <div class="logo"><a href='../Dashboard/DashboardUser.html'>HALO PETANI</a></div>
            <div class="menu">
                <ul>
                    <li><a href="../Dashboard/DashboardUser.html" class="tbl-biru">Beranda</a></li>
                    <li><a href="../Dashboard/Profil.html" class="tbl-biru">Profil</a></li>
                    <li><a href="Daftar.php" class="tbl-biru">Pertanyaan</a></li>
                    <li><a href="../Pertanyaan Seputar Petani/Daftar.php" class="tbl-biru">Artikel</a></li>
                    <li><a href="../Login/Login.html" class="tbl-biru">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
    <nav>
         <div class="wrapper">
         <div class="logo"><a>Posting Pertanyaan</a></div>
            <div class="menu">
                <ul>
                    <li><a href="Daftar.php" class="tbl-biru">Daftar Pertanyaan</a></li>
                    <li><a href="Top_Ten.php" class="tbl-biru">Top Pengguna</a></li>
                </ul>
            </div>
        </div>
        </nav>
    <div class="top-users">
    <form action="posting.php" method="post" enctype="multipart/form-data">
        <label for="judul">Judul Pertanyaan:</label><br>
        <input type="text" id="judul" name="judul" required><br><br>

        <label for="isi">Isi Pertanyaan:</label><br>
        <textarea id="isi" name="isi" rows="5" cols="40" required></textarea><br><br>

        <label for="kategori">Kategori:</label><br>
        <select id="kategori" name="kategori" required>
            <option value="Kategori">Sayuran</option>
            <option value="Kategori">Buah-Buahan</option>
            <option value="Kategori">Biji-Bijian</option>
        </select><br><br>

        <label for="foto">Unggah Foto:</label><br>
        <input type="file" id="foto" name="foto"><br><br>

        <button type="submit">Posting Pertanyaan</button>
    </form>
    </div>
</div>
</body><footer id="kontak">
        <div class="wrapper">
            <div class="footer-container">
                <div class="footer-section">
                    <h3>Halo Petani</h3>
                    <p>Menyediakan layanan konsultasi berbayar selama 1 bulan</p>
                </div>
                <div class="footer-section">
                    <h3>About</h3>
                    <p>Website resmi yang menyediakan layanan untuk kepentingan petani</p>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Telp : 000000000101</p>
                    <p>Jl. Badak dan kaki tiga</p>
                    <p>Kode Pos: 666</p>
                </div>
                <div class="footer-section">
                    <h3>Social</h3>
                    <p><b>YouTube:</b> Halo Petani</p>
                </div>
            </div>
        </div>
        </footer>
    </div>
    
    <footer id="copyright">
        <div class="wrapper">
            &copy; 2024. <b>Halo Petani</b> All Rights Reserved.
        </div>
    </footer>

</html>
