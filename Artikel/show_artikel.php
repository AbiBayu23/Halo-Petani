<?php
include 'config.php';

// Ambil data artikel dari database
$sql = "SELECT judul, konten, kategori, rating, tanggal_posting FROM artikel";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data dari setiap baris
    while($row = $result->fetch_assoc()) {
        echo "<h1>" . $row["judul"] . "</h1>";
        echo "<p>" . $row["konten"] . "</p>";
        echo "<p>Tanggal Posting: " . $row["tanggal_posting"] . "</p>";
        echo "<p><strong>Kategori:</strong> " . $row["kategori"] . "</p>";
        echo "<p><strong>Rating:</strong> " . $row["rating"] . "</p>";
    }
} else {
    echo "Belum ada artikel.";
}

// Tutup koneksi
$conn->close();
?>
