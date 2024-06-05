<?php
session_start();
include '../Login/config.php';
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: ../Login/Login.html');
    exit;
}

$logged_in_user_id = $_SESSION['user_id'];
$logged_in_username = $_SESSION['username'];

$laporanPesan = "";
$whereClause = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_pertanyaan']) && isset($_POST['isi_jawaban'])) {
        $id_pertanyaan = $_POST['id_pertanyaan'];
        $isi_jawaban = $_POST['isi_jawaban'];

        $stmt = $conn->prepare("INSERT INTO jawaban (id_pertanyaan, id_pengguna, isi_jawaban, tanggal_posting) VALUES (?, ?, ?, CURDATE())");
        $stmt->bind_param("iis", $id_pertanyaan, $logged_in_user_id, $isi_jawaban);

        if ($stmt->execute()) {
            echo "Jawaban berhasil diposting!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['like']) && isset($_POST['id_jawaban']) && isset($_POST['id_pengguna'])) {
        $id_jawaban = $_POST['id_jawaban'];
        $id_pengguna = $_POST['id_pengguna'];

        $sql_check_quality = "SELECT * FROM quality_point WHERE id_jawaban = ? AND id_pengguna = ?";
        $stmt_check_quality = $conn->prepare($sql_check_quality);
        $stmt_check_quality->bind_param("ii", $id_jawaban, $id_pengguna);
        $stmt_check_quality->execute();
        $result_check_quality = $stmt_check_quality->get_result();

        if ($result_check_quality->num_rows == 0) {
            $stmt_quality = $conn->prepare("INSERT INTO quality_point (id_pengguna, id_jawaban, jumlah_point, tanggal_pemberian) VALUES (?, ?, 2, CURDATE())");
            $stmt_quality->bind_param("ii", $id_pengguna, $id_jawaban);

            if ($stmt_quality->execute()) {
                echo "Like berhasil!";
            } else {
                echo "Error: " . $stmt_quality->error;
            }
        }
        $stmt_check_quality->close();
    }

    if (isset($_POST['unlike']) && isset($_POST['id_jawaban']) && isset($_POST['id_pengguna'])) {
        $id_jawaban = $_POST['id_jawaban'];
        $id_pengguna = $_POST['id_pengguna'];

        $stmt_delete_quality = $conn->prepare("DELETE FROM quality_point WHERE id_jawaban = ? AND id_pengguna = ?");
        $stmt_delete_quality->bind_param("ii", $id_jawaban, $id_pengguna);

        if ($stmt_delete_quality->execute()) {
            echo "Unlike berhasil!";
        } else {
            echo "Error: " . $stmt_delete_quality->error;
        }
        $stmt_delete_quality->close();
    }

    if (isset($_POST['like']) && isset($_POST['id_pertanyaan']) && isset($_POST['id_pengguna'])) {
        $id_pertanyaan = $_POST['id_pertanyaan'];
        $id_pengguna = $_POST['id_pengguna'];

        $sql_check_quality = "SELECT * FROM quality_point WHERE id_pertanyaan = ? AND id_pengguna = ?";
        $stmt_check_quality = $conn->prepare($sql_check_quality);
        $stmt_check_quality->bind_param("ii", $id_pertanyaan, $id_pengguna);
        $stmt_check_quality->execute();
        $result_check_quality = $stmt_check_quality->get_result();

        if ($result_check_quality->num_rows == 0) {
            $stmt_quality = $conn->prepare("INSERT INTO quality_point (id_pengguna, id_pertanyaan, jumlah_point, tanggal_pemberian) VALUES (?, ?, 2, CURDATE())");
            $stmt_quality->bind_param("ii", $id_pengguna, $id_pertanyaan);

            if ($stmt_quality->execute()) {
                echo "Like berhasil!";
            } else {
                echo "Error: " . $stmt_quality->error;
            }
        }
        $stmt_check_quality->close();
    }

    if (isset($_POST['unlike']) && isset($_POST['id_pertanyaan']) && isset($_POST['id_pengguna'])) {
        $id_pertanyaan = $_POST['id_pertanyaan'];
        $id_pengguna = $_POST['id_pengguna'];

        $stmt_delete_quality = $conn->prepare("DELETE FROM quality_point WHERE id_pertanyaan = ? AND id_pengguna = ?");
        $stmt_delete_quality->bind_param("ii", $id_pertanyaan, $id_pengguna);

        if ($stmt_delete_quality->execute()) {
            echo "Unlike berhasil!";
        } else {
            echo "Error: " . $stmt_delete_quality->error;
        }
        $stmt_delete_quality->close();
    }

    if (isset($_POST['submit_laporan'])) {
        $id_pengguna = $logged_in_user_id;
        $alasan_laporan = $_POST['alasan_laporan'] ?? '';

        if (isset($_POST['id_pertanyaan'])) {
            $id_pertanyaan = $_POST['id_pertanyaan'];
            $stmt = $conn->prepare("INSERT INTO laporan (id_pengguna, id_pertanyaan, alasan_laporan, tanggal_laporan) VALUES (?, ?, ?, CURDATE())");
            $stmt->bind_param("iis", $id_pengguna, $id_pertanyaan, $alasan_laporan);
        } else if (isset($_POST['id_jawaban'])) {
            $id_jawaban = $_POST['id_jawaban'];
            $stmt = $conn->prepare("INSERT INTO laporan (id_pengguna, id_jawaban, alasan_laporan, tanggal_laporan) VALUES (?, ?, ?, CURDATE())");
            $stmt->bind_param("iis", $id_pengguna, $id_jawaban, $alasan_laporan);
        }

        if ($stmt->execute()) {
            $laporanPesan = "Laporan berhasil dikirim!";
        } else {
            $laporanPesan = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $kategori = $_GET['kategori'] ?? '';
    $keyword = $_GET['keyword'] ?? '';
    
    if (!empty($kategori)) {
        $whereClause .= " AND kategori = '$kategori'";
    }
    
    if (!empty($keyword)) {
        $whereClause .= " AND (isi_pertanyaan LIKE '%$keyword%' OR isi_jawaban LIKE '%$keyword%')";
    }
}

$sql_pertanyaan = "SELECT pertanyaan.*, pengguna.username FROM pertanyaan JOIN pengguna ON pertanyaan.id_pengguna = pengguna.id WHERE 1 $whereClause ORDER BY tanggal_posting DESC";
$result_pertanyaan = $conn->query($sql_pertanyaan);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pertanyaan</title>
    <link rel="stylesheet" href="../Dashboard/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            font-family: 'Georgia', serif;
        }

        .pertanyaan,
        .jawaban {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fafafa;
        }

        .pertanyaan {
            background-color: #f0fff0;
        }

        .jawaban {
            background-color: #f9f9f9;
        }

        strong {
            color: #364f6b;
        }

        .image {
            max-width: 200px;
            max-height: 200px;
            display: block;
            margin: 10px 0;
        }

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            font-family: 'Arial', sans-serif;
        }

        input[type="submit"] {
            background-color: #364f6b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #364f6b;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .top-users {
            margin-top: 20px;
        }
    </style>
    <script>
        function tampilkanFormLaporan(id) {
            const formLaporan = document.getElementById('form-laporan-' + id);
            formLaporan.style.display = (formLaporan.style.display === 'none') ? 'block' : 'none';
        }
    </script>
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
                <li><a href="../Artikel/showartikeluser.php" class="tbl-biru">Artikel</a></li>
                <li><a href="../Login/Login.html" class="tbl-biru">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <nav>
        <div class="wrapper">
            <div class="logo"><a>Daftar Pertanyaan</a></div>
            <div class="menu">
                <ul>
                    <li><a href="Posting.php" class="tbl-biru">Posting Pertanyaan</a></li>
                    <li><a href="Top_Ten.php" class="tbl-biru">Top Pengguna</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="top-users">
        <form method="GET" action="Daftar.php">
            <input type="text" name="keyword" placeholder="Cari pertanyaan...">
            <select name="kategori">
                <option value="">Semua Kategori</option>
                <option value="Kategori1">Kategori 1</option>
                <option value="Kategori2">Kategori 2</option>
                <option value="Kategori3">Kategori 3</option>
            </select>
            <input type="submit" value="Cari">
        </form>
    </div>
    <?php
    if (!empty($laporanPesan)) {
        echo "<script>alert('" . addslashes($laporanPesan) . "');</script>";
    }

    if ($result_pertanyaan->num_rows > 0) {
        while ($pertanyaan = $result_pertanyaan->fetch_assoc()) {
            echo "<div class='pertanyaan'>";
            echo "<strong>Username:</strong> " . htmlspecialchars($pertanyaan["username"]) . "<br>";
            echo "<strong>Tanggal:</strong> " . htmlspecialchars($pertanyaan["tanggal_posting"]) . "<br>";
            echo "<strong>Kategori:</strong> " . htmlspecialchars($pertanyaan["kategori"]) . "<br>";
            echo "<strong>Pertanyaan:</strong> " . htmlspecialchars($pertanyaan["isi_pertanyaan"]) . "<br>";
            if (!empty($pertanyaan["foto"])) {
                $foto_base64 = base64_encode($pertanyaan["foto"]);
                echo "<strong>Foto:</strong><br><img src='data:image/jpeg;base64,{$foto_base64}' alt='Foto Pertanyaan' class='image'><br>";
            }

            $sql_jawaban = "SELECT jawaban.*, pengguna.username FROM jawaban JOIN pengguna ON jawaban.id_pengguna = pengguna.id WHERE id_pertanyaan = " . $pertanyaan["id_pertanyaan"];
            $result_jawaban = $conn->query($sql_jawaban);

            if ($result_jawaban->num_rows > 0) {
                while ($jawaban = $result_jawaban->fetch_assoc()) {
                    echo "<div class='jawaban'>";
                    echo "<strong>Jawaban:</strong> " . htmlspecialchars($jawaban["isi_jawaban"]) . "<br>";
                    echo "<strong>Oleh:</strong> " . htmlspecialchars($jawaban["username"]) . "<br>";
                    echo "<strong>Tanggal:</strong> " . htmlspecialchars($jawaban["tanggal_posting"]) . "<br>";
                    echo "</div>";
                }
            } else {
                echo "<div class='jawaban'><strong>Belum ada jawaban</strong></div>";
            }

            echo "<button onclick='tampilkanFormLaporan(" . $pertanyaan["id_pertanyaan"] . ")'>Laporkan Pertanyaan</button>";
            echo "<form id='form-laporan-" . $pertanyaan["id_pertanyaan"] . "' action='Daftar.php' method='POST' style='display:none;'>";
            echo "<input type='hidden' name='id_pertanyaan' value='" . $pertanyaan["id_pertanyaan"] . "'>";
            echo "<textarea name='alasan_laporan' placeholder='Alasan laporan...'></textarea>";
            echo "<input type='submit' name='submit_laporan' value='Kirim Laporan'>";
            echo "</form>";

            echo "</div>";
        }
    } else {
        echo "Tidak ada pertanyaan yang ditemukan.";
    }

    $conn->close();
    ?>
</div>
<div class="footer">
    &copy; 2023 HALO PETANI. All Rights Reserved.
</div>
</body>
</html>