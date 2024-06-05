<?php
include '../Login/config.php';

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql_jawaban = "SELECT laporan.id_laporan, 
                      pertanyaan.judul_pertanyaan, 
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
                        pertanyaan.judul_pertanyaan, 
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
    <div class="container">
        <h1>Laporan Pertanyaan dan Jawaban</h1>
        <?php if ($jawaban_exist): ?>
            <h2>Laporan untuk Jawaban:</h2>
            <?php while ($row = $result_jawaban->fetch_assoc()): ?>
                <div class="laporan jawaban">
                    <strong>Username Pengguna:</strong> <?= $row["username"] ?><br>
                    <strong>Judul Pertanyaan:</strong> <?= htmlspecialchars($row["judul_pertanyaan"]) ?><br>
                    <strong>Isi Jawaban:</strong> <?= htmlspecialchars($row["isi_jawaban"]) ?><br>
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
                    <strong>Judul Pertanyaan:</strong> <?= htmlspecialchars($row["judul_pertanyaan"]) ?><br>
                    <strong>Alasan Laporan:</strong> <?= htmlspecialchars($row["alasan_laporan"]) ?><br>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <h2>Tidak ada laporan untuk Pertanyaan.</h2>
        <?php endif; ?>
    </div>
</body>
</html>
