<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../Dashboard/style.css">
</head>
<body>
    <nav>
        <div class="wrapper">
            <div class="logo"><a href='../Dashboard/DashboardAdmin.html'>HALO PETANI</a></div>
            <div class="menu">
                <ul>
                    <li><a href="../Dashboard/DashboardAdmin.html" class="tbl-biru">Beranda</a></li>
                    <li><a href="../Pertanyaan Seputar Petani/Daftaradmin.php" class="tbl-biru">Pertanyaan</a></li>
                    <li><a href="show_artikel.php" class="tbl-biru">Artikel</a></li>
                    <li><a href="#laporan" class="tbl-biru">Laporan</a></li>
                    <li><a href="../Login/Login.html" class="tbl-biru">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper">
    <h1>Artikel Halo Petani</h1>
    <div>
    <div class="wrapper">
    <a href="form_artikel.html" class="tbl-pink">Tambah Artikel Baru</a>
    <div>

    <div class="wrapper">
        <!-- Form untuk Tambah Artikel -->
        <section id="form-tambah-artikel">
            <div class="container">
                <li><a href="urutan_artikel.php">Artikel Terbaru</a></li>
                <li><a href="top_rating_artikel.php">Artikel Berdasarkan Rating</a></li> 
            </div>
        </section>
    </div>

    
    <section>
    <div class="container">
    <?php
    include '../Login/config.php';

    // Query untuk mengambil data artikel terbaru
    $query = "SELECT * FROM artikel ORDER BY tanggal_posting DESC ";
    $result = mysqli_query($conn, $query);

    // Periksa apakah query berhasil dieksekusi
    if (!$result) {
        echo "Query error: " . mysqli_error($conn);
        exit();
    }

    // Ambil data dan tampilkan di web
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div>";
        echo "<h2>" . $row['judul'] . "</h2>";
        echo "<p>" . $row['konten'] . "</p>";
        echo "<p>Tanggal Publikasi: " . $row['tanggal_posting'] . "</p>";
        echo "</div>";
    }

    // Tutup koneksi
    mysqli_close($conn);
    ?>
    </div>
    </section>
    
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
                    <p>Telp: 000000000101</p>
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

    <footer id="copyright">
        <div class="wrapper">
            &copy; 2024. <b>Halo Petani</b> All Rights Reserved.
        </div>
    </footer>
</body>
</html>
