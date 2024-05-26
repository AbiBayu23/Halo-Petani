<?php
include 'konfigurasi_pdo.php';
// ID artikel yang akan diperbarui
$id_artikel = 1;
// Data baru untuk kategori dan rating
$new_kategory = "Edukasi";
$new_rating = 4;

$sql = "UPDATE arti SET category = :category, rating = :rating WHERE id = :id";
$stmt = $conn->prepare($sql);

// Bind parameter ke pernyataan yang telah dipersiapkan
$stmt->bindParam(':category', $new_category);
$stmt->bindParam(':rating', $new_rating);
$stmt->bindParam(':id', $article_id);

// Menjalankan pernyataan
$stmt->execute();

echo "Artikel berhasil diperbarui!";
?>
