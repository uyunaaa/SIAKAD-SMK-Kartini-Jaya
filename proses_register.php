<?php
include 'koneksi.php';
session_start();

// Ambil data dari form
$nama           = $_POST['nama'];
$username       = $_POST['username'];
$email          = $_POST['email'];
$tanggal_lahir  = $_POST['tanggal_lahir'];
$no_hp          = $_POST['no_hp'];
$nis            = $_POST['nis'];
$nisn           = $_POST['nisn'];
$kelas          = $_POST['kelas'];
$jurusan        = $_POST['jurusan'];
$tahunmasuk     = $_POST['tahunmasuk'];
$password       = $_POST['password'];
$konfirmasi     = $_POST['konfirmasi'];

// Data orang tua
$nama_ayah       = $_POST['nama_ayah'];
$nik_ayah        = $_POST['nik_ayah'];
$tgl_lahir_ayah  = $_POST['tgl_lahir_ayah'];
$telp_ayah       = $_POST['telp_ayah'];
$email_ayah      = $_POST['email_ayah'];
$pendidikan_ayah = $_POST['pendidikan_ayah'];
$penghasilan_ayah = $_POST['penghasilan_ayah'];
$pekerjaan_ayah   = $_POST['pekerjaan_ayah'];

$nama_ibu        = $_POST['nama_ibu'];
$nik_ibu         = $_POST['nik_ibu'];
$tgl_lahir_ibu   = $_POST['tgl_lahir_ibu'];
$telp_ibu        = $_POST['telp_ibu'];
$email_ibu       = $_POST['email_ibu'];
$pendidikan_ibu  = $_POST['pendidikan_ibu'];
$penghasilan_ibu = $_POST['penghasilan_ibu'];
$pekerjaan_ibu   = $_POST['pekerjaan_ibu'];

// Validasi Password
if ($password !== $konfirmasi) {
  header('Location: register.php?status=password_salah');
  exit;
}

// Cek username
$cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
if (mysqli_num_rows($cek_user) > 0) {
  header('Location: register.php?status=username_ada');
  exit;
}

// Cek NIS & NISN
$cek_nis  = mysqli_query($koneksi, "SELECT * FROM siswa WHERE NIS = '$nis'");
if (mysqli_num_rows($cek_nis) > 0) {
  header('Location: register.php?status=nis_ada');
  exit;
}

$cek_nisn = mysqli_query($koneksi, "SELECT * FROM siswa WHERE NISN = '$nisn'");
if (mysqli_num_rows($cek_nisn) > 0) {
  header('Location: register.php?status=nisn_ada');
  exit;
}

// Enkripsi password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Upload foto
$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];
$path = "../uploads/" . $foto;

move_uploaded_file($tmp, $path);


// 1. Simpan ke tabel users
$query_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed', 'siswa')";
if (mysqli_query($koneksi, $query_user)) {
  $user_id = mysqli_insert_id($koneksi); // Ambil UserID terakhir

  // 2. Simpan ke tabel siswa
  $query_siswa = "INSERT INTO siswa (
    UserID, nama_lengkap, email, tanggal_lahir, no_hp, NIS, NISN, kelas, jurusan, tahunMasuk,
    nama_ayah, nik_ayah, tgl_lahir_ayah, telp_ayah, email_ayah, pendidikan_ayah, penghasilan_ayah, pekerjaan_ayah,
    nama_ibu, nik_ibu, tgl_lahir_ibu, telp_ibu, email_ibu, pendidikan_ibu, penghasilan_ibu, pekerjaan_ibu
  ) VALUES (
    '$user_id', '$nama', '$email', '$tanggal_lahir', '$no_hp', '$nis', '$nisn', '$kelas', '$jurusan', '$tahunmasuk',
    '$nama_ayah', '$nik_ayah', '$tgl_lahir_ayah', '$telp_ayah', '$email_ayah', '$pendidikan_ayah', '$penghasilan_ayah', '$pekerjaan_ayah',
    '$nama_ibu', '$nik_ibu', '$tgl_lahir_ibu', '$telp_ibu', '$email_ibu', '$pendidikan_ibu', '$penghasilan_ibu', '$pekerjaan_ibu'
  )";

  if (mysqli_query($koneksi, $query_siswa)) {
    header('Location: register.php?status=success');
    exit;
  } else {
    header('Location: register.php?status=error');
    exit;
  }

} else {
  header('Location: register.php?status=error');
  exit;
}
?>
