<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Ambil data siswa dari sesi jika belum ada
  if (!isset($_SESSION['siswa'])) {
    $_SESSION['siswa'] = $_POST;
    // Redirect agar form ortu muncul setelah form siswa
    header("Location: register_ortu.php");
    exit;
  }

  // Simpan data ortu + siswa ke database
  $siswa = $_SESSION['siswa'];
  $ortu = $_POST;

  // Koneksi ke database
  $koneksi = new mysqli("localhost", "root", "", "siakad_smk_kartini_jaya");

  if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
  }

  // Simpan ke tabel users
 if (empty($siswa['username'])) {
  die("Username tidak ditemukan dalam session. Pastikan Anda mengisi form siswa terlebih dahulu.");
}
$username = $siswa['username'];
// Cek apakah username sudah ada
$check = $koneksi->prepare(...)("SELECT * FROM users WHERE Username = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Username sudah dipakai
    echo "<script>alert('Username \"$username\" sudah digunakan. Silakan pilih username lain.'); window.history.back();</script>";
    exit;
}

  $password = password_hash($siswa['password'], PASSWORD_DEFAULT);
  $role = "siswa";
  $koneksi->query("INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");

  // Ambil ID user terakhir
  $user_id = $koneksi->insert_id;

  // Simpan ke tabel siswa
  $stmt = $koneksi->prepare("INSERT INTO siswa (UserID, nama_lengkap, NIS, NISN, tanggal_lahir, jenis_kelamin, Kelas, Jurusan, agama, no_hp, email, alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param(
    "isssssssssss",
    $UserID,
    $siswa['nama_lengkap'],
    $siswa['NIS'],
    $siswa['nNISN'],
    $siswa['tanggal_lahir'],
    $siswa['jenis_kelamin'],
    $siswa['Kelas'],
    $siswa['jurusan'],
    $siswa['agama'],
    $siswa['no_hp'],
    $siswa['email'],
    $siswa['alamat']
  );
  $stmt->execute();

  // Simpan ke tabel ortu
  $stmt2 = $koneksi->prepare("INSERT INTO ortu (user_id, nama_ayah, nik_ayah, tgl_lahir_ayah, telp_ayah, email_ayah, pendidikan_ayah, penghasilan_ayah, pekerjaan_ayah, nama_ibu, nik_ibu, tgl_lahir_ibu, telp_ibu, email_ibu, pendidikan_ibu, penghasilan_ibu, pekerjaan_ibu) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt2->bind_param(
    "issssssssssssssss",
    $user_id,
    $ortu['nama_ayah'],
    $ortu['nik_ayah'],
    $ortu['tgl_lahir_ayah'],
    $ortu['telp_ayah'],
    $ortu['email_ayah'],
    $ortu['pendidikan_ayah'],
    $ortu['penghasilan_ayah'],
    $ortu['pekerjaan_ayah'],
    $ortu['nama_ibu'],
    $ortu['nik_ibu'],
    $ortu['tgl_lahir_ibu'],
    $ortu['telp_ibu'],
    $ortu['email_ibu'],
    $ortu['pendidikan_ibu'],
    $ortu['penghasilan_ibu'],
    $ortu['pekerjaan_ibu']
  );
  $stmt2->execute();

  // Bersihkan session
  unset($_SESSION['siswa']);

  // Redirect ke index.php
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Form Data Orang Tua</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen">
  <!-- Panel Kiri -->
  <div class="md:w-2/5 w-full h-64 md:h-auto relative">
    <img src="https://storage.googleapis.com/a1aa/image/c973f1b2-64f7-44fe-10d0-3e54091e6a3e.jpg"
         alt="Gedung SMK"
         class="absolute inset-0 w-full h-full object-cover opacity-60 -z-10" />
    <div class="absolute inset-0 bg-blue-800 bg-opacity-70 z-0"></div>
    <div class="relative z-10 flex flex-col justify-center items-start px-6 py-8 h-full text-white">
      <h1 class="text-2xl font-bold mb-2">SIAKAD SMK Kartini Jaya</h1>
      <p class="mb-4 text-sm">Sistem Informasi Akademik</p>
      <ul class="space-y-2 text-xs">
        <li>🗓 Kalender Akademik 2024/2025</li>
        <li>📢 Pengumuman Ujian Semester</li>
        <li>📘 Daftar Ulang Tahun Ajaran Baru</li>
      </ul>
    </div>
  </div>

  <!-- Form -->
  <div class="bg-white p-8 rounded shadow-md w-full">
    <h2 class="text-xl font-semibold mb-6">Form Data Orang Tua</h2>
    <form method="post">
      <div class="grid grid-cols-2 gap-6">
        <!-- Data Ayah -->
        <div><label class="block font-semibold">Nama Ayah:</label><input type="text" name="nama_ayah" required class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">NIK Ayah:</label><input type="text" name="nik_ayah" required class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Tanggal Lahir Ayah:</label><input type="date" name="tgl_lahir_ayah" required class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">No. Telp. Ayah:</label><input type="text" name="telp_ayah" class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Email Ayah:</label><input type="email" name="email_ayah" class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Pendidikan Terakhir Ayah:</label><input type="text" name="pendidikan_ayah" class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Penghasilan Ayah:</label><input type="text" name="penghasilan_ayah" class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Pekerjaan Ayah:</label><input type="text" name="pekerjaan_ayah" class="w-full border px-3 py-2 rounded" /></div>

        <!-- Data Ibu -->
        <div><label class="block font-semibold">Nama Ibu:</label><input type="text" name="nama_ibu" required class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">NIK Ibu:</label><input type="text" name="nik_ibu" required class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Tanggal Lahir Ibu:</label><input type="date" name="tgl_lahir_ibu" required class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">No. Telp. Ibu:</label><input type="text" name="telp_ibu" class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Email Ibu:</label><input type="email" name="email_ibu" class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Pendidikan Terakhir Ibu:</label><input type="text" name="pendidikan_ibu" class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Penghasilan Ibu:</label><input type="text" name="penghasilan_ibu" class="w-full border px-3 py-2 rounded" /></div>
        <div><label class="block font-semibold">Pekerjaan Ibu:</label><input type="text" name="pekerjaan_ibu" class="w-full border px-3 py-2 rounded" /></div>
      </div>

      <div class="flex justify-between mt-8">
        <a href="register.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Sebelumnya</a>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Daftar Akun</button>
      </div>
    </form>
  </div>
</body>
</html>