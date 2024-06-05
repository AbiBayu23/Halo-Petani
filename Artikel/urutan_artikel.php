<?php
include '../Login/config.php';

$query = "SELECT * FROM artikel ORDER BY tanggal_posting DESC ";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "Query error: " . mysqli_error($conn);
    exit();
}

while ($row = mysqli_fetch_assoc($result)) {
    echo "<div>";
    echo "<h2>" . $row['judul'] . "</h2>";
    echo "<p>" . $row['konten'] . "</p>";
    echo "<p>Tanggal Publikasi: " . $row['tanggal_posting'] . "</p>";
    echo "</div>";
}

mysqli_close($conn);
?>
