<?php
$koneksi = mysqli_connect("localhost", "root", "", "siakad_smk_kartini_jaya");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
