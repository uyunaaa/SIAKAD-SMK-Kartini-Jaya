<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}
?>

<h2>Selamat Datang, Admin!</h2>
<p>Halo <?php echo $_SESSION['username']; ?>, kamu login sebagai <b><?php echo $_SESSION['role']; ?></b>.</p>

<a href="../logout.php">Logout</a>

