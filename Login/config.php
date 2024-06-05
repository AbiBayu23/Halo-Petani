<?php 
$server = "localhost";
$user = "root";
$password = "Perkasa23@rcm";
$nama_database = "Halo Petani";

$conn = mysqli_connect ($server, $user, $password, $nama_database);

if(  !$conn ) {
    die("Gagal terhubung dengan database: " .mysqli_connect_error () );
}