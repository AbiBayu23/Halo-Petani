<?php
include 'config.php';

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
