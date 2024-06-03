<?php
session_start();
include '../Login/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header('Location: ../Login/Login.html');
    exit;
}

$logged_in_user_id = $_SESSION['user_id'];
$logged_in_username = $_SESSION['username'];

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

    if (isset($_POST['id_jawaban']) && isset($_POST['nilai']) && isset($_POST['id_pengguna'])) {
        $id_jawaban = $_POST['id_jawaban'];
        $nilai = $_POST['nilai'];
        $id_pengguna = $_POST['id_pengguna'];

        $sql_check_rating = "SELECT * FROM rating_jawaban WHERE id_jawaban = ? AND id_pengguna = ?";
        $stmt_check_rating = $conn->prepare($sql_check_rating);
        $stmt_check_rating->bind_param("ii", $id_jawaban, $id_pengguna);
        $stmt_check_rating->execute();
        $result_check_rating = $stmt_check_rating->get_result();

        if ($result_check_rating->num_rows > 0) {
            $stmt_update_rating = $conn->prepare("UPDATE rating_jawaban SET nilai = ?, tanggal_rating = CURDATE() WHERE id_jawaban = ? AND id_pengguna = ?");
            $stmt_update_rating->bind_param("iii", $nilai, $id_jawaban, $id_pengguna);

            if ($stmt_update_rating->execute()) {
                echo "Rating berhasil diperbarui!";
            } else {
                echo "Error: " . $stmt_update_rating->error;
            }
            $stmt_update_rating->close();
        } else {
            $stmt_insert_rating = $conn->prepare("INSERT INTO rating_jawaban (id_jawaban, id_pengguna, nilai, tanggal_rating) VALUES (?, ?, ?, CURDATE())");
            $stmt_insert_rating->bind_param("iii", $id_jawaban, $id_pengguna, $nilai);

            if ($stmt_insert_rating->execute()) {
                echo "Rating berhasil diberikan!";
            } else {
                echo "Error: " . $stmt_insert_rating->error;
            }
            $stmt_insert_rating->close();
        }

        $stmt_check_rating->close();
    }

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

        $stmt_check_quality->close();
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

        $stmt_delete_quality->close();
    }

    if (isset($_POST['id_pengguna']) && isset($_POST['alasan_laporan'])) {
        $id_pengguna = $_POST['id_pengguna'];
        $id_pertanyaan = isset($_POST['id_pertanyaan']) ? $_POST['id_pertanyaan'] : NULL;
        $id_jawaban = isset($_POST['id_jawaban']) ? $_POST['id_jawaban'] : NULL;
        $alasan_laporan = $_POST['alasan_laporan'];

        $stmt = $conn->prepare("INSERT INTO laporan (id_pengguna, id_pertanyaan, id_jawaban, alasan_laporan, tanggal_laporan) VALUES (?, ?, ?, ?, CURDATE())");
        $stmt->bind_param("iiis", $id_pengguna, $id_pertanyaan, $id_jawaban, $alasan_laporan);
        $stmt->execute();
        $stmt->close();

        $laporanPesan = "Laporan berhasil dikirim!";
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

        .rating {
            direction: rtl;
            unicode-bidi: bidi-override;
            font-size: 1.5em;
            display: inline-block;
        }

        .rating input {
            display: none;
        }

        .rating label {
            color: #ddd;
            float: right;
        }

        .rating input:checked~label,
        .rating input:checked~label~label {
            color: #f5b301;
        }

        .rating label:hover,
        .rating label:hover~label {
            color: #ffdd44;
        }

        .result-rating {
            color: #f5b301;
            font-size: 1.5em;
        }

        .result-rating .star {
            color: #ddd;
        }

        .result-rating .filled {
            color: #f5b301;
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
        <div class="logo"><a href='../Dashboard/DashboardUser.html'>HALO PETANI</a></div>
        <div class="menu">
            <ul>
                <li><a href="../Dashboard/DashboardUser.html" class="tbl-biru">Beranda</a></li>
                <li><a href="../Dashboard/Profil.html" class="tbl-biru">Profil</a></li>
                <li><a href="Daftar.php" class="tbl-biru">Pertanyaan</a></li>
                <li><a href="../Artikel/showartikeluser.php" class="tbl-biru">Artikel</a></li>
                <li><a href="../Login/Login.html" class="tbl-biru">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <nav>
        <div class="wrapper">
            <div class="logo"><a>Daftar Pertanyaan</a></div>
            <div class="menu">
                <ul>
                    <li><a href="Posting.php" class="tbl-biru">Posting Pertanyaan</a></li>
                    <li><a href="Top_Ten.php" class="tbl-biru">Top Pengguna</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="top-users">
        <form method="GET" action="Daftar.php">
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

    function display_stars($rating) {
        $stars = "";
        for ($i = 1; $i <= 5; $i++) {
            $stars .= ($i <= $rating) ? "<span class='star filled'>★</span>" : "<span class='star'>★</span>";
        }
        return $stars;
    }

    if ($result_pertanyaan->num_rows > 0) {
        while ($pertanyaan = $result_pertanyaan->fetch_assoc()) {
            echo "<strong>Username:</strong> " . htmlspecialchars($pertanyaan["username"]) . "<br>";
            echo "<div class='pertanyaan'>";
            echo "<strong>Pertanyaan:</strong> " . htmlspecialchars($pertanyaan["isi_pertanyaan"]) . "<br>";
            echo "<strong>Tanggal:</strong> " . htmlspecialchars($pertanyaan["tanggal_posting"]) . "<br>";
            if (!empty($pertanyaan["foto"])) {
                $foto_base64 = base64_encode($pertanyaan["foto"]);
                echo "<strong>Foto:</strong><br><img src='data:image/jpeg;base64,{$foto_base64}' alt='Foto Pertanyaan' class='image'><br>";
            }
            echo "<strong>Kategori:</strong> " . htmlspecialchars($pertanyaan["kategori"]) . "<br>";

            $sql_jawaban = "SELECT jawaban.*, pengguna.username AS jawaban_username FROM jawaban JOIN pengguna ON jawaban.id_pengguna = pengguna.id WHERE id_pertanyaan = " . $pertanyaan["id_pertanyaan"];
            $result_jawaban = $conn->query($sql_jawaban);

            if ($result_jawaban->num_rows > 0) {
                while ($jawaban = $result_jawaban->fetch_assoc()) {
                    echo "<div class='jawaban'>";
                    echo "<strong>Username :</strong> " . htmlspecialchars($jawaban["jawaban_username"]) . "<br>";
                    echo "<strong>Jawaban:</strong> " . htmlspecialchars($jawaban["isi_jawaban"]) . "<br>";
                    echo "<strong>Tanggal:</strong> " . htmlspecialchars($jawaban["tanggal_posting"]) . "<br>";

                    $sql_avg_rating = "SELECT AVG(nilai) as average_rating FROM rating_jawaban WHERE id_jawaban = " . $jawaban["id_jawaban"];
                    $result_avg_rating = $conn->query($sql_avg_rating);

                    if ($result_avg_rating->num_rows > 0) {
                        $row_avg_rating = $result_avg_rating->fetch_assoc();
                        $average_rating = $row_avg_rating['average_rating'];
                        echo "<div class='result-rating'>" . display_stars(round($average_rating)) . " (" . round($average_rating, 2) . ")</div>";
                    }

                    // Handle likes
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

                    // Handle ratings
                     echo "<form method='POST' action='Daftar.php'>";
                        echo "<input type='hidden' name='id_jawaban' value='" . $jawaban["id_jawaban"] . "'>";
                        echo "<input type='hidden' name='id_pengguna' value='$logged_in_user_id'>";
                        echo "<div class='rating'>";
                        echo "<input type='radio' id='star5-" . $jawaban["id_jawaban"] . "' name='nilai' value='5'><label for='star5-" . $jawaban["id_jawaban"] . "'>★</label>";
                        echo "<input type='radio' id='star4-" . $jawaban["id_jawaban"] . "' name='nilai' value='4'><label for='star4-" . $jawaban["id_jawaban"] . "'>★</label>";
                        echo "<input type='radio' id='star3-" . $jawaban["id_jawaban"] . "' name='nilai' value='3'><label for='star3-" . $jawaban["id_jawaban"] . "'>★</label>";
                        echo "<input type='radio' id='star2-" . $jawaban["id_jawaban"] . "' name='nilai' value='2'><label for='star2-" . $jawaban["id_jawaban"] . "'>★</label>";
                        echo "<input type='radio' id='star1-" . $jawaban["id_jawaban"] . "' name='nilai' value='1'><label for='star1-" . $jawaban["id_jawaban"] . "'>★</label>";
                        echo "</div>";
                        echo "<input type='submit' value='Rate'>";
                        echo "</form>";
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
            echo "</div>"; // pertanyaan
        }
    } else {
        echo "Belum ada pertanyaan.";
    }
    ?>
</div>
</body>
</html>
