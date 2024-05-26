<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $konten = $_POST['konten'];
    $kategori = $_POST['kategori'];
    $rating = $_POST['rating'];
    $tanggal_posting = date('Y-m-d');
    $id_admin = 1;
    $id_jenis_artikel = 1;

    $sql = "INSERT INTO artikel (judul, konten, kategori, rating, tanggal_posting, id_admin, id_jenis_artikel) 
            VALUES ('$judul', '$konten', '$kategori', '$rating', '$tanggal_posting', '$id_admin', '$id_jenis_artikel')";

    if ($conn->query($sql) === TRUE) {
        echo "Artikel berhasil ditambahkan! ";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
