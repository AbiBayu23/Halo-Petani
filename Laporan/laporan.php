<?php
include '../Login/config.php';

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql_jawaban = "SELECT laporan.id_laporan, 
                      pertanyaan.isi_pertanyaan, 
                      jawaban.isi_jawaban, 
                      laporan.alasan_laporan, 
                      pengguna.username 
               FROM laporan 
               LEFT JOIN jawaban ON laporan.id_jawaban = jawaban.id_jawaban 
               LEFT JOIN pertanyaan ON jawaban.id_pertanyaan = pertanyaan.id_pertanyaan 
               LEFT JOIN pengguna ON jawaban.id_pengguna = pengguna.id 
               WHERE laporan.id_jawaban IS NOT NULL";

$result_jawaban = $conn->query($sql_jawaban);

$sql_pertanyaan = "SELECT laporan.id_laporan, 
                        pertanyaan.isi_pertanyaan, 
                        laporan.alasan_laporan, 
                        pengguna.username 
                   FROM laporan 
                   LEFT JOIN pertanyaan ON laporan.id_pertanyaan = pertanyaan.id_pertanyaan 
                   LEFT JOIN pengguna ON pertanyaan.id_pengguna = pengguna.id 
                   WHERE laporan.id_pertanyaan IS NOT NULL";

$result_pertanyaan = $conn->query($sql_pertanyaan);

if ($result_jawaban->num_rows > 0) {
    $jawaban_exist = true;
} else {
    $jawaban_exist = false;
}

if ($result_pertanyaan->num_rows > 0) {
    $pertanyaan_exist = true;
} else {
    $pertanyaan_exist = false;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pertanyaan dan Jawaban</title>
    <link rel="stylesheet" href="../Dashboard/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
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
            color: #003366;
            text-align: center;
            font-family: 'Georgia', serif;
        }

        h2 {
            color: #003366;
        }

        .laporan {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .pertanyaan {
            background-color: #e0f2f1;
        }

        .jawaban {
            background-color: #fff8e1;
        }

        strong {
            color: #003366;
        }

        .image {
            max-width: 200px;
            max-height: 200px;
            display: block;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #003366;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<nav>
        <div class="wrapper">
            <div class="logo"><a href='DashboardAdmin.html'>HALO PETANI</a></div>
            <div class="menu">
                <ul>
                    <li><a href="DashboardAdmin.html" class="tbl-biru">Beranda</a></li>
                    <li><a href="../Pertanyaan Seputar Petani/Daftaradmin.php" class="tbl-biru">Pertanyaan</a></li>
                    <li><a href="../Artikel/show_artikel.php" class="tbl-biru">Artikel</a></li>
                    <li><a href="../Laporan/laporan.php" class="tbl-biru">Laporan</a></li>
                    <li><a href="../Login/Login.html" class="tbl-biru">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <nav>
        <div class="wrapper">
            <div class="logo"><a>Laporan Pengguna</a></div>

        
    </nav>
        <?php if ($jawaban_exist): ?>
            <h2>Laporan untuk Jawaban:</h2>
            <?php while ($row = $result_jawaban->fetch_assoc()): ?>
                <div class="laporan jawaban">
                    <strong>Username Pengguna:</strong> <?= $row["username"] ?><br>
                    <strong>Pertanyaan:</strong> <?= htmlspecialchars($row["isi_pertanyaan"]) ?><br>
                    <strong>Jawaban:</strong> <?= htmlspecialchars($row["isi_jawaban"]) ?><br>
                    <strong>Alasan Laporan:</strong> <?= htmlspecialchars($row["alasan_laporan"]) ?><br>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <h2>Tidak ada laporan untuk Jawaban.</h2>
        <?php endif; ?>
        
        <?php if ($pertanyaan_exist): ?>
            <h2>Laporan untuk Pertanyaan:</h2>
            <?php while ($row = $result_pertanyaan->fetch_assoc()): ?>
                <div class="laporan pertanyaan">
                    <strong>Username Pengguna:</strong> <?= $row["username"] ?><br>
                    <strong>Pertanyaan:</strong> <?= htmlspecialchars($row["isi_pertanyaan"]) ?><br>
                    <strong>Alasan Laporan:</strong> <?= htmlspecialchars($row["alasan_laporan"]) ?><br>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <h2>Tidak ada laporan untuk Pertanyaan.</h2>
        <?php endif; ?>
    </div>
</body>
<footer id="kontak">
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