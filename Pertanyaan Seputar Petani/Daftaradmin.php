<?php
session_start();
include '../Login/config.php';


$laporanPesan = "";
$whereClause = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handling Answer Posting
    if (isset($_POST['id_pertanyaan']) && isset($_POST['isi_jawaban'])) {
        $id_pertanyaan = $_POST['id_pertanyaan'];
        $isi_jawaban = $_POST['isi_jawaban'];

        $stmt = $conn->prepare("INSERT INTO jawaban (id_pertanyaan, id_pengguna, isi_jawaban, tanggal_posting) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iis", $id_pertanyaan, $logged_in_user_id, $isi_jawaban);

        if ($stmt->execute()) {
            echo "Jawaban berhasil diposting!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $kategori = $_GET['kategori'] ?? '';
    $keyword = $_GET['keyword'] ?? '';
    
    if (!empty($kategori)) {
        $whereClause .= " AND kategori = '$kategori'";
    }
    
    if (!empty($keyword)) {
        $whereClause .= " AND (isi_pertanyaan LIKE '%$keyword%' OR isi_jawaban LIKE '%$keyword%')";
    }
    $sql_pertanyaan = "SELECT pertanyaan.*, pengguna.username FROM pertanyaan JOIN pengguna ON pertanyaan.id_pengguna = pengguna.id WHERE 1 $whereClause ORDER BY pertanyaan.tanggal_posting DESC";
    

  }
  $sql_pertanyaan = "SELECT pertanyaan.*, pengguna.username FROM pertanyaan JOIN pengguna ON pertanyaan.id_pengguna = pengguna.id WHERE 1 $whereClause ORDER BY tanggal_posting DESC";
  $result_pertanyaan = $conn->query($sql_pertanyaan);
    
    if ($result_pertanyaan->num_rows > 0) {
        while ($pertanyaan = $result_pertanyaan->fetch_assoc()) {
        }
    } else {
        echo "Tidak ada pertanyaan yang ditemukan.";
    }
    
$sql_pertanyaan = "SELECT pertanyaan.*, pengguna.username FROM pertanyaan JOIN pengguna ON pertanyaan.id_pengguna = pengguna.id WHERE 1 $whereClause ORDER BY tanggal_posting DESC";
$result_pertanyaan = $conn->query($sql_pertanyaan);


    if (isset($_POST['like']) && isset($_POST['id_jawaban']) && isset($_POST['id_pengguna'])) {
        $id_jawaban = $_POST['id_jawaban'];
        $id_pengguna = $_POST['id_pengguna'];

        $sql_check_quality = "SELECT * FROM quality_point WHERE id_jawaban = ? AND id_pengguna = ?";
        $stmt_check_quality = $conn->prepare($sql_check_quality);
        $stmt_check_quality->bind_param("ii", $id_jawaban, $id_pengguna);
        $stmt_check_quality->execute();
        $result_check_quality = $stmt_check_quality->get_result();

        if ($result_check_quality->num_rows == 0) {
            $stmt_quality = $conn->prepare("INSERT INTO quality_point (id_pengguna, id_jawaban, jumlah_point, tanggal_pemberian) VALUES (?, ?, 2, CURDATE())");
            $stmt_quality->bind_param("ii", $id_pengguna, $id_jawaban);

            if ($stmt_quality->execute()) {
            } else {
                $stmt_quality->error;
            }
        } else {
        }

        $stmt_check_quality;
    }

    if (isset($_POST['unlike']) && isset($_POST['id_jawaban']) && isset($_POST['id_pengguna'])) {
        $id_jawaban = $_POST['id_jawaban'];
        $id_pengguna = $_POST['id_pengguna'];

        $stmt_delete_quality = $conn->prepare("DELETE FROM quality_point WHERE id_jawaban = ? AND id_pengguna = ?");
        $stmt_delete_quality->bind_param("ii", $id_jawaban, $id_pengguna);

        if ($stmt_delete_quality->execute()) {
        } else {
            $stmt_delete_quality->error;
        }

        $stmt_delete_quality;
    }

    if (isset($_POST['like']) && isset($_POST['id_pertanyaan']) && isset($_POST['id_pengguna'])) {
        $id_pertanyaan = $_POST['id_pertanyaan'];
        $id_pengguna = $_POST['id_pengguna'];

        $sql_check_quality = "SELECT * FROM quality_point WHERE id_pertanyaan = ? AND id_pengguna = ?";
        $stmt_check_quality = $conn->prepare($sql_check_quality);
        $stmt_check_quality->bind_param("ii", $id_pertanyaan, $id_pengguna);
        $stmt_check_quality->execute();
        $result_check_quality = $stmt_check_quality->get_result();

        if ($result_check_quality->num_rows == 0) {
            $stmt_quality = $conn->prepare("INSERT INTO quality_point (id_pengguna, id_pertanyaan, jumlah_point, tanggal_pemberian) VALUES (?, ?, 2, CURDATE())");
            $stmt_quality->bind_param("ii", $id_pengguna, $id_pertanyaan);

            if ($stmt_quality->execute()) {
            } else {
                $stmt_quality->error;
            }
        } else {
        }

        $stmt_check_quality;
    }

    if (isset($_POST['unlike']) && isset($_POST['id_pertanyaan']) && isset($_POST['id_pengguna'])) {
        $id_pertanyaan = $_POST['id_pertanyaan'];
        $id_pengguna = $_POST['id_pengguna'];

        $stmt_delete_quality = $conn->prepare("DELETE FROM quality_point WHERE id_pertanyaan = ? AND id_pengguna = ?");
        $stmt_delete_quality->bind_param("ii", $id_pertanyaan, $id_pengguna);

        if ($stmt_delete_quality->execute()) {
        } else {
            $stmt_delete_quality->error;
        }

        $stmt_delete_quality;
    }

    if (isset($_POST['id_pengguna']) && isset($_POST['alasan_laporan'])) {
        $id_pengguna = $_POST['id_pengguna'];
        $id_pertanyaan = isset($_POST['id_pertanyaan']) ? $_POST['id_pertanyaan'] : NULL;
        $alasan_laporan = $_POST['alasan_laporan'];

        $stmt = $conn->prepare("INSERT INTO laporan (id_pengguna, id_pertanyaan, alasan_laporan, tanggal_laporan) VALUES (?, ?, ?, ?, CURDATE())");
        $stmt->bind_param("iiis", $id_pengguna, $id_pertanyaan, $alasan_laporan);
        $stmt->execute();

        $laporanPesan = "Laporan berhasil dikirim!";
    }

    if (isset($_POST['id_pengguna']) && isset($_POST['alasan_laporan'])) {
        $id_pengguna = $_POST['id_pengguna'];
        $id_jawaban = isset($_POST['id_jawaban']) ? $_POST['id_jawaban'] : NULL;
        $alasan_laporan = $_POST['alasan_laporan'];

        $stmt = $conn->prepare("INSERT INTO laporan (id_pengguna, id_jawaban, alasan_laporan, tanggal_laporan) VALUES (?, ?, ?, ?, CURDATE())");
        $stmt->bind_param("iiis", $id_pengguna, $id_jawaban, $alasan_laporan);
        $stmt->execute();
        $stmt->close();

        $laporanPesan = "Laporan berhasil dikirim!";
    }

    if (isset($_POST['hapus_pertanyaan']) && isset($_POST['id_pertanyaan'])) {
        $id_pertanyaan = $_POST['id_pertanyaan'];

        $conn->begin_transaction();

        try {
            // Delete laporan related to jawaban of the pertanyaan
            $stmt = $conn->prepare("DELETE laporan FROM laporan JOIN jawaban ON laporan.id_jawaban = jawaban.id_jawaban WHERE jawaban.id_pertanyaan = ?");
            $stmt->bind_param("i", $id_pertanyaan);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            // Delete laporan related to the pertanyaan
            $stmt = $conn->prepare("DELETE FROM laporan WHERE id_pertanyaan = ?");
            $stmt->bind_param("i", $id_pertanyaan);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            // Delete quality_point related to jawaban of the pertanyaan
            $stmt = $conn->prepare("DELETE quality_point FROM quality_point JOIN jawaban ON quality_point.id_jawaban = jawaban.id_jawaban WHERE jawaban.id_pertanyaan = ?");
            $stmt->bind_param("i", $id_pertanyaan);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            // Delete quality_point related to the pertanyaan
            $stmt = $conn->prepare("DELETE FROM quality_point WHERE id_pertanyaan = ?");
            $stmt->bind_param("i", $id_pertanyaan);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            // Delete jawaban related to the pertanyaan
            $stmt = $conn->prepare("DELETE FROM jawaban WHERE id_pertanyaan = ?");
            $stmt->bind_param("i", $id_pertanyaan);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            // Delete the pertanyaan
            $stmt = $conn->prepare("DELETE FROM pertanyaan WHERE id_pertanyaan = ?");
            $stmt->bind_param("i", $id_pertanyaan);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            $conn->commit();
            echo "Pertanyaan berhasil dihapus!";
        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
        }
        $stmt->close();
    }
    if (isset($_POST['hapus_jawaban']) && isset($_POST['id_jawaban'])) {
        $id_jawaban = $_POST['id_jawaban'];
    
        $conn->begin_transaction();
    
        try {
            // Delete laporan related to the jawaban
            $stmt = $conn->prepare("DELETE FROM laporan WHERE id_jawaban = ?");
            $stmt->bind_param("i", $id_jawaban);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            // Delete quality_point related to the jawaban
            $stmt = $conn->prepare("DELETE FROM quality_point WHERE id_jawaban = ?");
            $stmt->bind_param("i", $id_jawaban);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            // Delete the jawaban
            $stmt = $conn->prepare("DELETE FROM jawaban WHERE id_jawaban = ?");
            $stmt->bind_param("i", $id_jawaban);
            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }
    
            $conn->commit();
            echo "Jawaban berhasil dihapus!";
        } catch (Exception $e) {
            $conn->rollback();
            echo $e->getMessage();
        }
        $stmt->close();
    }


$sql_pertanyaan = "SELECT pertanyaan.*, pengguna.username FROM pertanyaan JOIN pengguna ON pertanyaan.id_pengguna = pengguna.id ORDER BY tanggal_posting DESC";
$result_pertanyaan = $conn->query($sql_pertanyaan);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pertanyaan</title>
    <link rel="stylesheet" href="../Dashboard/style.css">
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
    <script>
        function tampilkanFormLaporan(id) {
            const formLaporan = document.getElementById('form-laporan-' + id);
            formLaporan.style.display = (formLaporan.style.display === 'none') ? 'block' : 'none';
        }
    </script>
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
            <div class="logo"><a> Daftar Pertanyaan</a></div>
                <div class="menu">
                    <ul>
                        <li><a href="Toptenadmin.php" class="tbl-biru">Top Ten</a></li>
                    </ul>
                </div>
    </nav>
    <div class="top-users">
        <form method="GET" action="Daftaradmin.php">
            <input type="text" name="keyword" placeholder="Cari pertanyaan...">
            <select name="kategori">
                <option value="">Semua Kategori</option>
                <option value="Kategori1">Kategori 1</option>
                <option value="Kategori2">Kategori 2</option>
                <option value="Kategori3">Kategori 3</option>
            </select>
            <input type="submit" value="Cari">
        </form>
    </div>
    <?php
    if (!empty($laporanPesan)) {
        echo "<script>alert('" . addslashes($laporanPesan) . "');</script>";
    }


    if ($result_pertanyaan->num_rows > 0) {
        while ($pertanyaan = $result_pertanyaan->fetch_assoc()) {
            $id_pertanyaan = $pertanyaan['id_pertanyaan'];
            
            echo "<strong>Username:</strong> " . htmlspecialchars($pertanyaan["username"]) . "<br>";
            echo "<strong>Tanggal:</strong> " . htmlspecialchars($pertanyaan["tanggal_posting"]) . "<br>";
            echo "<strong>Kategori:</strong> " . htmlspecialchars($pertanyaan["kategori"]) . "<br>";
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
                    echo "<span class='like-count'>" . $likes . " likes</span>";
                    echo "</div>";
                    }
                echo "</form>";
        
            

            $sql_jawaban = "SELECT jawaban.*, pengguna.username AS jawaban_username FROM jawaban JOIN pengguna ON jawaban.id_pengguna = pengguna.id WHERE id_pertanyaan = " . $pertanyaan["id_pertanyaan"];
            $result_jawaban = $conn->query($sql_jawaban);

            if ($result_jawaban->num_rows > 0) {
                while ($jawaban = $result_jawaban->fetch_assoc()) {
                    $id_jawaban = $jawaban['id_jawaban'];
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
                        echo "<span class='like-count'>" . $likes . " likes</span>";
                        echo "</div>";
                    }
                    echo "</form>";
                        echo "<input type='hidden' name='id_jawaban' value='" . $jawaban["id_jawaban"] . "'>";
                        echo "<input type='hidden' name='id_pengguna' value='$logged_in_user_id'>";
                        

                        echo "<form method='post' action=''>";
                        echo "<input type='hidden' name='id_jawaban' value='" . $id_jawaban . "'>";
                        echo "<input type='submit' name='hapus_jawaban' value='Hapus Jawaban'>";
                        echo "</div>";
                        
                        
                }
                
            } else {
                echo "Belum ada jawaban.";
            }
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='id_pertanyaan' value='" . $id_pertanyaan . "'>";
            echo "<input type='submit' name='hapus_pertanyaan' value='Hapus Pertanyaan'>";
            echo "</div>";
        }
    } else {
        echo "Belum ada pertanyaan.";
    }
    ?>
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
                    <p><b>YouTube:</b> Halo Petani</p>
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
