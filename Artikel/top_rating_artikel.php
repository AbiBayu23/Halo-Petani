<?php
// Koneksi ke database
include 'config.php';

// Query untuk mengambil data artikel dan mengurutkannya berdasarkan rating
$query = "SELECT * FROM artikel ORDER BY rating DESC";
$result = mysqli_query($conn, $query);

// Loop untuk menampilkan data artikel
while ($row = mysqli_fetch_assoc($result)) {
    echo "<div>";
    echo "<h2>" . $row['judul'] . "</h2>";
    echo "<p>" . $row['konten'] . "</p>";
    echo "<p>Rating: " . $row['rating'] . "</p>";
    echo "</div>";
}
?>
