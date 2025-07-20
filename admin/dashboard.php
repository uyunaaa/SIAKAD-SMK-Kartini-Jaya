<?php
session_start();

if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit;
}

include '../koneksi.php'; // koneksi DB

$admin_id = $_SESSION['UserID'];
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE id = '$admin_id'");
$admin = mysqli_fetch_assoc($query);


$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="flex min-h-screen bg-gray-100">

  
  <!-- Sidebar -->
 <!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
  <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>
  <div class="flex items-center p-4 border-b border-blue-700">
    <img src="uploads/<?php echo $foto; ?>" class="w-10 h-10 rounded-full object-cover" alt="Foto Admin" />
    <div class="ml-3">
      <p class="font-semibold text-sm md:text-base"><?php echo $nama; ?></p>
      <p class="text-xs text-blue-200">Admin</p>
    </div>
  </div>

  <nav class="mt-4 flex flex-col space-y-2 px-4 pb-4 flex-1 overflow-y-auto">
    <a href="dashboard.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
      <i class="fas fa-tachometer-alt w-4"></i><span>Dashboard</span>
    </a>
    <a href="data_siswa.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
      <i class="fas fa-users w-4"></i><span>Kelola Data Siswa</span>
    </a>
    <a href="data_guru.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
      <i class="fas fa-chalkboard-teacher w-4"></i><span>Kelola Data Guru</span>
    </a>
    <a href="data_jadwal.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
      <i class="fas fa-calendar-alt w-4"></i><span>Kelola Jadwal</span>
    </a>
    <a href="nilai_siswa.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
      <i class="fas fa-clipboard-list w-4"></i><span>Lihat Nilai Siswa</span>
    </a>
    <a href="pengaturan_akun.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
      <i class="fas fa-user-cog w-4"></i><span>Pengaturan Akun</span>
    </a>
    <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-600 text-sm">
      <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
    </a>
  </nav>
</aside>

  <!-- Main Content -->
  <main class="flex-1 p-6">
    <div class="mb-4">
      <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, Admin!</h2>
      <p class="text-gray-600 mt-1">Gunakan menu di sebelah kiri untuk mengelola sistem.</p>
    </div>

   <main class="flex-1 p-6 bg-gray-50 overflow-y-auto">
  <h1 class="text-xl font-bold text-[#2f3e5e] mb-6">Dashboard Admin</h1>

  <!-- Baris 1: Statistik -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Jumlah Siswa -->
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-sm font-medium text-gray-500">Total Siswa</h2>
      <p class="text-2xl font-semibold text-[#2f3e5e]">320</p>
    </div>
    <!-- Jumlah Guru -->
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-sm font-medium text-gray-500">Total Guru</h2>
      <p class="text-2xl font-semibold text-[#2f3e5e]">25</p>
    </div>
    <!-- Jumlah Kelas -->
    <div class="bg-white shadow rounded-lg p-4">
      <h2 class="text-sm font-medium text-gray-500">Total Kelas</h2>
      <p class="text-2xl font-semibold text-[#2f3e5e]">12</p>
    </div>
  </div>

  <!-- Baris 2: Akses Cepat -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="kelola_siswa.php" class="bg-blue-600 hover:bg-blue-700 text-white text-center p-4 rounded-lg shadow transition">
      Kelola Data Siswa
    </a>
    <a href="kelola_guru.php" class="bg-green-600 hover:bg-green-700 text-white text-center p-4 rounded-lg shadow transition">
      Kelola Data Guru
    </a>
    <a href="kelola_jadwal.php" class="bg-purple-600 hover:bg-purple-700 text-white text-center p-4 rounded-lg shadow transition">
      Kelola Jadwal Pelajaran
    </a>
  </div>

  <!-- Tambahan: Notifikasi atau Informasi -->
  <div class="mt-8 bg-white p-4 shadow rounded-lg">
    <h3 class="text-base font-semibold text-[#2f3e5e] mb-2">Informasi Terbaru</h3>
    <ul class="text-sm text-gray-600 list-disc pl-5 space-y-1">
      <li>Pendaftaran siswa baru dibuka sampai 30 Juli 2025.</li>
      <li>Ujian tengah semester dimulai 5 Agustus 2025.</li>
      <li>Mohon update data guru sebelum akhir bulan ini.</li>
    </ul>
  </div>
</main>
