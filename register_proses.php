<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email     = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_hp     = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $password  = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Data siswa tambahan
    $nis        = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nisn       = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $kelas      = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jurusan    = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $tahunmasuk = mysqli_real_escape_string($koneksi, $_POST['tahunmasuk']);

    // Validasi password
    if ($password !== $konfirmasi) {
        echo "<script>alert('Konfirmasi password tidak cocok!');history.back();</script>";
        exit;
    }

    // Cek apakah email atau NIS/NISN sudah terdaftar
    $cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
    $cek_nis = mysqli_query($koneksi, "SELECT * FROM siswa WHERE NIS = '$nis' OR NISN = '$nisn'");

    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar!');history.back();</script>";
        exit;
    }

    if (mysqli_num_rows($cek_nis) > 0) {
        echo "<script>alert('NIS atau NISN sudah digunakan!');history.back();</script>";
        exit;
    }

    // Buat hash password dan username dari email
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $username = explode('@', $email)[0];

    // 1. Simpan ke tabel users
    $query_user = "INSERT INTO users (nama_lengkap, username, email, no_hp, password, role, createdAt)
                   VALUES ('$nama', '$username', '$email', '$no_hp', '$hash', 'siswa', NOW())";

    if (mysqli_query($koneksi, $query_user)) {
        $user_id = mysqli_insert_id($koneksi);

        // 2. Simpan ke tabel siswa
        $query_siswa = "INSERT INTO siswa (UserID, NIS, NISN, Kelas, Jurusan, TahunMasuk)
                        VALUES ('$user_id', '$nis', '$nisn', '$kelas', '$jurusan', '$tahunmasuk')";

        if (mysqli_query($koneksi, $query_siswa)) {
         header("Location: index.php?status=success");
exit;

        } else {
            mysqli_query($koneksi, "DELETE FROM users WHERE id = $user_id");
            echo "<script>alert('Gagal menyimpan data siswa: " . mysqli_error($koneksi) . "');history.back();</script>";
        }
    } else {
        echo "<script>alert('Gagal menyimpan data user: " . mysqli_error($koneksi) . "');history.back();</script>";
    }
} else {
    echo "Akses tidak sah.";
}
?>
