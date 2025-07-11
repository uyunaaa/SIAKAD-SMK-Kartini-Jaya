<?php
include 'koneksi.php';

$username = $_POST['username'];
$new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

mysqli_query($koneksi, "UPDATE users SET Password = '$new_password' WHERE Username = '$username'");
echo "<script>alert('Password berhasil diubah!');window.location='index.php';</script>";
?>
