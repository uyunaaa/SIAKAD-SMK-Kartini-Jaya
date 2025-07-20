<?php
session_start();
include '../koneksi.php';

// Cek login admin
if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil data admin untuk sidebar
$admin_id = $_SESSION['UserID'];
$qadmin = mysqli_query($koneksi, "SELECT * FROM admin WHERE id='$admin_id'");
$dadmin = mysqli_fetch_assoc($qadmin);
$foto = $dadmin['foto'];
$nama = $dadmin['nama_admin'];

// Ambil data jadwal
$jadwal = mysqli_query($koneksi, "SELECT * FROM jadwal");
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
    <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-600 text-sm">
      <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
    </a>
  </nav>
</aside>

<!-- Konten utama -->
<div class="flex-1 p-6 md:ml-64">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold text-blue-800">Kelola Jadwal Pelajaran</h1>
    <a href="tambah_jadwal.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">
      ➕ Tambah Jadwal
    </a>
  </div>

  <div class="flex flex-col lg:flex-row gap-6">
    <!-- Tabel Jadwal -->
    <div class="w-full lg:w-3/4 bg-white shadow-md rounded-lg overflow-x-auto">
      <table class="min-w-full text-sm text-gray-700 border border-gray-200">
        <thead class="bg-blue-50 text-gray-800 text-left">
          <tr>
            <th class="px-4 py-3 border">No</th>
            <th class="px-4 py-3 border">Hari</th>
            <th class="px-4 py-3 border">Kelas</th>
            <th class="px-4 py-3 border">Jam</th>
            <th class="px-4 py-3 border">Kode</th>
            <th class="px-4 py-3 border">Mapel</th>
            <th class="px-4 py-3 border">JP</th>
            <th class="px-4 py-3 border">Ruang</th>
            <th class="px-4 py-3 border">Guru</th>
            <th class="px-4 py-3 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($jadwal)): ?>
          <tr class="hover:bg-gray-50 text-center">
            <td class="px-4 py-2 border"><?= $no++ ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($row['hari']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($row['kelas']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($row['jam']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($row['kode_kelas']) ?></td>
            <td class="px-4 py-2 border text-left"><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($row['jp']) ?></td>
            <td class="px-4 py-2 border"><?= htmlspecialchars($row['ruang_kelas']) ?></td>
            <td class="px-4 py-2 border text-left"><?= htmlspecialchars($row['guru']) ?></td>
            <td class="px-4 py-2 border">
              <a href="edit_jadwal.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a> |
              <a href="hapus_jadwal.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Sidebar Statistik -->
    <div class="w-full lg:w-1/4 flex flex-col gap-4">
      <div class="grid grid-cols-3 gap-4 text-center">
        <div class="bg-white shadow rounded-lg p-4">
          <div class="text-sm text-gray-500">Jadwal</div>
          <div class="text-xl font-bold text-blue-600">2</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
          <div class="text-sm text-gray-500">Guru</div>
          <div class="text-xl font-bold text-green-600">52</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
          <div class="text-sm text-gray-500">Mapel</div>
          <div class="text-xl font-bold text-purple-600">2</div>
        </div>
      </div>

      <div class="bg-white shadow rounded-lg p-4">
        <h2 class="text-md font-semibold mb-2">📅 Jadwal Hari Ini (<?= date("l") ?>)</h2>
        <ul class="text-sm space-y-2">
          <li><span class="font-bold text-blue-600">07:00 - 08:30</span> - Matematika (X RPL)</li>
          <li><span class="font-bold text-blue-600">08:30 - 10:00</span> - Bahasa Indonesia (X RPL)</li>
        </ul>
      </div>
    </div>
  </div>
</div>
