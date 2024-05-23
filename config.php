<?php 
$server = "localhost";
$user = "root";
$password = "Perkasa23@rcm";
$nama_database = "Halo Petani";

$db = mysqli_connect ($server, $user, $password, $nama_database);

if(  !$db ) {
    die("Gagal terhubung dengan database: " .mysqli_connect_error () );
}