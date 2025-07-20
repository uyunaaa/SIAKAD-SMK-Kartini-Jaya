<?php
include 'koneksi.php';
if (isset($_GET['username'])) {
  $username = mysqli_real_escape_string($koneksi, $_GET['username']);
  $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
  echo mysqli_num_rows($cek) > 0 ? 'ada' : 'kosong';
}
?>
