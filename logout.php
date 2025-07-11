<?php
session_start();         // Mulai sesi
session_destroy();       // Hapus semua data sesi (logout)
header("Location: index.php"); // Arahkan kembali ke halaman login
exit;
?>
