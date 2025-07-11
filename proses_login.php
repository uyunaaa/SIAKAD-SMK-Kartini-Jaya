if ($data && password_verify($password, $data['Password'])) {
    $_SESSION['login'] = true;
    $_SESSION['UserID'] = $data['UserID']; // ✅ Tambahkan ini
    $_SESSION['username'] = $data['Username'];
    $_SESSION['role'] = $data['Role'];
    $_SESSION['nama'] = $data['Nama_Lengkap'];

    // Arahkan berdasarkan role
    if ($data['Role'] === 'siswa') {
        header("Location: siswa/dashboard.php");
    } elseif ($data['Role'] === 'guru') {
        header("Location: guru/dashboard.php");
    } elseif ($data['Role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        echo "<script>alert('Role tidak dikenali.'); window.location='index.php';</script>";
    }
}
