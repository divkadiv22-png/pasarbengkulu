<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pasarbengkulu";
$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error){ die("Koneksi gagal: ".$conn->connect_error); }
session_start();
if(!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];
?>