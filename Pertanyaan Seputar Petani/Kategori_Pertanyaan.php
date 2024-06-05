
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Pertanyaan Berdasarkan Kategori</title>
</head>
<body>
<style>
        <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
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
            color: #364f6b;
            text-align: center;
            font-family: 'Georgia', serif;
        }

        .pertanyaan,
        .jawaban {
            border: 1px solid
            #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fafafa;
        }

        .pertanyaan {
            background-color: #f0fff0;
        }

        .jawaban {
            background-color: #f9f9f9;
        }

        strong {
            color: #364f6b;
        }

        .image {
            max-width: 200px;
            max-height: 200px;
            display: block;
            margin: 10px 0;
        }

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            font-family: 'Arial', sans-serif;
        }

        input[type="submit"] {
            background-color: #364f6b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #364f6b;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .top-users {
         margin-top: 20px;
        }
    </style>
    </style>
    <h1>Hasil Pencaharian Berdasarkan Kategori</h1>
    <body>
<div class="container">
    <body></body>
    <div class="container"></div>
    <form action="" method="GET">
        <label for="kategori">Pilih Kategori:</label><br>
        <select id="kategori" name="kategori" required>
            <option value="">Semua Kategori</option>
            <option value="Sayuran">Sayuran</option>
            <option value="Buah-Buahan">Buah-Buahan</option>
            <option value="Biji-Bijian">Biji-Bijian</option>
        </select><br><br>
        <button type="submit">Cari</button>
    </form>

</body>
</html>
    <?php
    include '../Login/config.php';

    $laporanPesan = "";
    $whereClause = "";
    $kategori = $_GET['kategori'] ?? '';

    if (!empty($kategori)) {
        $whereClause .= " WHERE kategori = '$kategori'";
    }

    $sql_pertanyaan = "SELECT pertanyaan.*, pengguna.username 
                        FROM pertanyaan 
                        JOIN pengguna ON pertanyaan.id_pengguna = pengguna.id 
                        $whereClause 
                        ORDER BY pertanyaan.tanggal_posting DESC";

    $result_pertanyaan = $conn->query($sql_pertanyaan);
    if (!empty($laporanPesan)) {
        echo "<script>alert('" . addslashes($laporanPesan) . "');</script>";
    }
    if ($result_pertanyaan && $result_pertanyaan->num_rows > 0) {
        while ($pertanyaan = $result_pertanyaan->fetch_assoc()) {
            echo "<strong>Username:</strong> " . htmlspecialchars($pertanyaan["username"]) . "<br>";
            echo "<strong>Judul Pertanyaan:</strong> " . htmlspecialchars($pertanyaan["judul_pertanyaan"]) . "<br>";
            echo "<strong>Kategori:</strong> " . htmlspecialchars($pertanyaan["kategori"]) . "<br>";
            echo "<strong>Tanggal:</strong> " . htmlspecialchars($pertanyaan["tanggal_posting"]) . "<br>";
            echo "<div class='pertanyaan'>";
            echo "<strong>Pertanyaan:</strong> " . htmlspecialchars($pertanyaan["isi_pertanyaan"]) . "<br>";
            if (!empty($pertanyaan["foto"])) {
                $foto_base64 = base64_encode($pertanyaan["foto"]);
                echo "<strong>Foto:</strong><br><img src='data:image/jpeg;base64,{$foto_base64}' alt='Foto Pertanyaan' class='image'><br>";
            }
            
            $sql_quality_point = "SELECT COUNT(*) as likes FROM quality_point WHERE id_pertanyaan = " . $pertanyaan["id_pertanyaan"];
                $result_quality_point = $conn->query($sql_quality_point);
                $likes = 0;
                if ($result_quality_point && $result_quality_point->num_rows > 0) {
                    $row_quality_point = $result_quality_point->fetch_assoc();
                    $likes = $row_quality_point['likes'];
                }
                    
                $stmt_check_quality = $conn->prepare("SELECT COUNT(*) as num_likes FROM quality_point WHERE id_pertanyaan = ? AND id_pengguna = ?");
                $stmt_check_quality->bind_param("ii", $pertanyaan["id_pertanyaan"], $logged_in_user_id);
                $stmt_check_quality->execute();
                $result_check_quality = $stmt_check_quality->get_result();

                echo "<form method='POST' class='like-form'>";
                echo "<input type='hidden' name='id_pertanyaan' value='" . $pertanyaan["id_pertanyaan"] . "'>";
                echo "<input type='hidden' name='id_pengguna' value='$logged_in_user_id'>";
                    
                if ($result_check_quality) {
                    $row_check_quality = $result_check_quality->fetch_assoc();
                    $num_likes = $row_check_quality['num_likes'];

                    echo "<div id='like-container-" . $pertanyaan["id_pertanyaan"] . "'>";
                    if ($num_likes == 0) {
                        echo "<button type='submit' name='like' value='like' class='like-button' data-pertanyaan-id='" . $pertanyaan["id_pertanyaan"] . "'>Like &#128077;</button>";
                    } else {
                        echo "<button type='submit' name='unlike' value='unlike' class='unlike-button' data-pertanyaan-id='" . $pertanyaan["id_pertanyaan"] . "'>Unlike &#128078;</button>";
                    }
                    echo "<span class='like-count'>" . $likes . " likes</span>";
                    echo "</div>";
                    }
                echo "</form>";
        
            

            $sql_jawaban = "SELECT jawaban.*, pengguna.username AS jawaban_username FROM jawaban JOIN pengguna ON jawaban.id_pengguna = pengguna.id WHERE id_pertanyaan = " . $pertanyaan["id_pertanyaan"];
            $result_jawaban = $conn->query($sql_jawaban);

            if ($result_jawaban->num_rows > 0) {
                while ($jawaban = $result_jawaban->fetch_assoc()) {
                    echo "<div class='jawaban'>";
                    echo "<strong>Username :</strong> " . htmlspecialchars($jawaban["jawaban_username"]) . "<br>";
                    echo "<strong>Tanggal:</strong> " . htmlspecialchars($jawaban["tanggal_posting"]) . "<br>";
                    echo "<strong>Jawaban:</strong> " . htmlspecialchars($jawaban["isi_jawaban"]) . "<br>";
                    

                    $sql_quality_point = "SELECT COUNT(*) as likes FROM quality_point WHERE id_jawaban = " . $jawaban["id_jawaban"];
                    $result_quality_point = $conn->query($sql_quality_point);
                    $likes = 0;
                    if ($result_quality_point && $result_quality_point->num_rows > 0) {
                        $row_quality_point = $result_quality_point->fetch_assoc();
                        $likes = $row_quality_point['likes'];
                    }
                    
                    $stmt_check_quality = $conn->prepare("SELECT COUNT(*) as num_likes FROM quality_point WHERE id_jawaban = ? AND id_pengguna = ?");
                    $stmt_check_quality->bind_param("ii", $jawaban["id_jawaban"], $logged_in_user_id);
                    $stmt_check_quality->execute();
                    $result_check_quality = $stmt_check_quality->get_result();

                    echo "<form method='POST' class='like-form'>";
                    echo "<input type='hidden' name='id_jawaban' value='" . $jawaban["id_jawaban"] . "'>";
                    echo "<input type='hidden' name='id_pengguna' value='$logged_in_user_id'>";
                    
                    if ($result_check_quality) {
                        $row_check_quality = $result_check_quality->fetch_assoc();
                        $num_likes = $row_check_quality['num_likes'];

                        echo "<div id='like-container-" . $jawaban["id_jawaban"] . "'>";
                        if ($num_likes == 0) {
                            echo "<button type='submit' name='like' value='like' class='like-button' data-jawaban-id='" . $jawaban["id_jawaban"] . "'>Like &#128077;</button>";
                        } else {
                            echo "<button type='submit' name='unlike' value='unlike' class='unlike-button' data-jawaban-id='" . $jawaban["id_jawaban"] . "'>Unlike &#128078;</button>";
                        }
                        echo "<span class='like-count'>" . $likes . " likes</span>";
                        echo "</div>";
                    }
                    echo "</form>";
                        ?>
                        <button onclick="tampilkanFormLaporan(<?php echo $pertanyaan['id_pertanyaan']; ?>)">Laporkan</button>
                        <form id="form-laporan-<?php echo $pertanyaan['id_pertanyaan']; ?>" method="POST" style="display: none;">
                        <input type="hidden" name="id_pertanyaan" value="<?php echo $pertanyaan['id_pertanyaan']; ?>">
                        <textarea name="alasan_laporan" placeholder="Deskripsikan laporan Anda"></textarea>
                        <input type="submit" name="submit_laporan" value="Kirim Laporan">
                        </form>
                        <?php
                        echo "</div>";
                        
                }
                
            } else {
                echo "Belum ada jawaban.";
            }
            ?>
            <div class="post-answer">
                <h3>Berikan Jawaban:</h3>
                <form method="POST">
                    <input type="hidden" name="id_pertanyaan" value="<?php echo $pertanyaan['id_pertanyaan']; ?>">
                    <textarea name="isi_jawaban" required></textarea>
                    <input type="submit" value="Post Jawaban">
                </form>
            </div>
            <button onclick="tampilkanFormLaporan(<?php echo $pertanyaan['id_pertanyaan']; ?>)">Laporkan</button>
            <form id="form-laporan-<?php echo $pertanyaan['id_pertanyaan']; ?>" method="POST" style="display: none;">
                <input type="hidden" name="id_pertanyaan" value="<?php echo $pertanyaan['id_pertanyaan']; ?>">
                <textarea name="laporan" placeholder="Deskripsikan laporan Anda"></textarea>
                <input type="submit" name="submit_laporan" value="Kirim Laporan">
            </form>
            <?php
            echo "</div>";
        }
    } else {
        echo "Belum ada pertanyaan.";
    }
    ?>

</body>
</html>
