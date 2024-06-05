<?php
include '../Login/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = htmlspecialchars($_POST['judul']);
    $konten = htmlspecialchars($_POST['konten']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $rating = $_POST['rating'];
    $tanggal_posting = date('Y-m-d');
    $id_admin = 1;
    
    $stmt = $conn->prepare("INSERT INTO artikel (judul, konten, kategori, rating, tanggal_posting, id_admin) 
                            VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssisi", $judul, $konten, $kategori, $rating, $tanggal_posting, $id_admin);

    if ($stmt->execute()) {
        echo "Artikel berhasil ditambahkan!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
