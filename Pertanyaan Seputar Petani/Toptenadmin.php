<?php
include '../Login/config.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posting Pertanyaan</title>
    <link rel="stylesheet" href="../Dashboard/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        ol {
            list-style-type: none;
            padding: 0;
        }
        .username {
            color: #364f6b;
            font-weight: bold;
        }
        .total-point {
            color: #364f6b;
        }
        .top-users {
         margin-top: 20px;
        }

    </style>
</head>
<body>
    <nav>
        <div class="wrapper">
            <div class="logo"><a href='../Dashboard/DashboardAdmin.html'>HALO PETANI</a></div>
            <div class="menu">
                <ul>
                <li><a href="../Dashboard/DashboardAdmin.html" class="tbl-biru">Beranda</a></li>
                    <li><a href="../Pertanyaan Seputar Petani/Daftaradmin.php" class="tbl-biru">Pertanyaan</a></li>
                    <li><a href="../Artikel/show_artikel.php" class="tbl-biru">Artikel</a></li>
                    <li><a href="#artikel" class="tbl-biru">Laporan</a></li>
                    <li><a href="../Login/Login.html" class="tbl-biru">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
     
    <div class="container">
    <nav>
        <div class="wrapper">
            <div class="logo"><a> Top Pengguna</a></div>
                <div class="menu">
                    <ul>
                        <li><a href="Daftaradmin.php" class="tbl-biru">Daftar Pertanyaan</a></li>
                    </ul>
                </div>
    </nav>

    <div>

    </div>
    <div class="top-users">
    <?php

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }


    $sql = "SELECT pengguna.username, SUM(quality_point.jumlah_point) AS total_point
            FROM pengguna
            JOIN quality_point ON pengguna.id = quality_point.id_pengguna
            GROUP BY pengguna.id
            ORDER BY total_point DESC
            LIMIT 10";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ol>";
        $nomor = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<li><span class='username'>" . $nomor . ". " . $row["username"] . "</span> - Total Poin: <span class='total-point'>" . $row["total_point"] . "</span></li>";
            $nomor++;
        }
        echo "</ol>";
    } else {
        echo "Tidak ada pengguna yang ditemukan.";
    }

    $conn->close();
    ?>
    </div>
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
                    <p><b>YouTube:</b> Hallo Petani</p>
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
