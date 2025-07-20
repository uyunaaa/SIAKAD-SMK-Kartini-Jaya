<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

if ($query && mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);

    if (password_verify($password, $data['password'])) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        // Arahkan ke dashboard sesuai peran
        if ($data['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($data['role'] == 'guru') {
            header("Location: guru/dashboard.php");
        } elseif ($data['role'] == 'siswa') {
            header("Location: siswa/dashboard.php");
        } else {
            echo "Peran tidak dikenali.";
        }
    } else {
        echo "Password salah.";
    }
} else {
    echo "Username tidak ditemukan.";
}
?>
