<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
</head>
<body>
    <h1>Selamat Datang di Website Kami</h1>
    <div>
    <a href="form_artikel.html">Tambah Artikel Baru</a> <hr>
    <div>

    <div>
         <nav>
            <ul>
                <li><a href="urutan_artikel.php">Artikel Terbaru</a></li> <hr>
               
            </ul>
        </nav>
    </div>

    
    <div>
         <nav>
            <ul>
                <li><a href="top_rating_artikel.php">Artikel Berdasarkan Rating</a></li> <hr>
               
            </ul>
        </nav>
    </div>


    <section>
        
    <?php include 'show_artikel.php'; ?>
        
    </section>
    

    ?>
</body>
</html>
