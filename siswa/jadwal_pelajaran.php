<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../index.php");
    exit;
}

include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE Username = '$username'");
$data = mysqli_fetch_assoc($query);

$nama = $data ? htmlspecialchars($data['Nama_Lengkap']) : "Data tidak ditemukan";
$nis  = $data ? $data['Username'] : "-";
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Jadwal Pelajaran - SIAKAD SMK</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: "Inter", sans-serif; }
  </style>
</head>
<body class="bg-[#e8edf7] min-h-screen flex flex-col md:flex-row text-gray-800">


<!-- Header Mobile -->
<header class="md:hidden flex items-center justify-between bg-blue-900 text-white px-4 py-3 shadow">
  <div class="flex items-center space-x-3">
    <button id="menuToggleBtn" class="text-white text-2xl focus:outline-none">
      <i class="fas fa-bars"></i>
    </button>
    <span class="font-semibold text-base">Jadwal Pelajaran</span>
  </div>
</header>

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>
    <div class="flex items-center p-4 border-b border-blue-700">
      <img src="https://storage.googleapis.com/a1aa/image/885a0fab-da6e-467e-10ea-fe39f1152dc7.jpg" class="w-10 h-10 rounded-full object-cover" alt="Foto Siswa" />
      <div class="ml-3">
        <p class="font-semibold"><?php echo $nama; ?></p>
        <p class="text-xs text-blue-200">Siswa</p>
      </div>
    </div>

  
  <!-- Menu Navigasi -->
  <nav class="mt-4 flex flex-col space-y-2 px-4 pb-4 flex-1 overflow-y-auto text-sm">
    <a href="dashboard.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'dashboard.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-tachometer-alt w-4"></i><span>Dashboard</span>
    </a>
    <a href="biodata.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'biodata.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-id-card w-4"></i><span>Biodata</span>
    </a>
    <a href="jadwal_pelajaran.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'jadwal_pelajaran.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-calendar-alt w-4"></i><span>Jadwal Pelajaran</span>
    </a>
    <a href="cek_absensi.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'cek_absensi.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-check-circle w-4"></i><span> Cek Absensi</span>
    </a>
    <a href="hasil_nilai.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'hasil_nilai.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-poll w-4"></i><span>Hasil Nilai</span>
    </a>
    <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-600">
      <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
    </a>
  </nav>
</aside>

  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 md:hidden"></div>

  <!-- Main Content -->
  <main id="mainContent" class="flex-1 p-6 mt-16 md:mt-0 max-w-7xl mx-auto">
    <h2 class="text-xl font-semibold mb-4 text-[#2c3e5c]">Jadwal Mingguan</h2>
    <div class="bg-white rounded-lg shadow overflow-x-auto p-4">
      <table class="min-w-full text-left text-sm border-separate border-spacing-y-1">
        <thead class="bg-blue-100 text-gray-700 font-semibold">
          <tr>
            <th class="px-4 py-2">Hari</th>
            <th class="px-4 py-2">Kelas</th>
            <th class="px-4 py-2">Mata Pelajaran</th>
            <th class="px-4 py-2">JP</th>
            <th class="px-4 py-2">Ruang</th>
            <th class="px-4 py-2">Guru</th>
          </tr>
        </thead>
        <tbody class="text-gray-800">
          <tr><td class="px-4 py-2">Senin, 07:30 - 09:00</td><td>XI-RPL1</td><td>Pemrograman Dasar</td><td>2</td><td>Lab RPL</td><td>Bu Siti Rahmawati</td></tr>
          <tr><td class="px-4 py-2">Senin, 09:30 - 11:00</td><td>XI-RPL1</td><td>Bahasa Inggris</td><td>2</td><td>Ruang 3.2</td><td>Pak Rudi Hartono</td></tr>
          <tr><td class="px-4 py-2">Selasa, 08:00 - 09:40</td><td>XI-RPL1</td><td>Basis Data</td><td>2</td><td>Lab Komputer</td><td>Bu Ayu Lestari</td></tr>
          <tr><td class="px-4 py-2">Selasa, 13:00 - 14:40</td><td>XI-RPL1</td><td>Produktif RPL</td><td>2</td><td>Lab RPL</td><td>Pak Fajar Nugraha</td></tr>
          <tr><td class="px-4 py-2">Rabu, 07:30 - 10:00</td><td>XI-RPL1</td><td>Matematika</td><td>3</td><td>Ruang 2.1</td><td>Bu Yuliana Dewi</td></tr>
          <tr><td class="px-4 py-2">Kamis, 08:00 - 09:40</td><td>XI-RPL1</td><td>Bahasa Indonesia</td><td>2</td><td>Ruang 2.2</td><td>Pak Taufik Hidayat</td></tr>
          <tr><td class="px-4 py-2">Jumat, 07:30 - 09:00</td><td>XI-RPL1</td><td>PKN</td><td>2</td><td>Ruang 1.3</td><td>Bu Leni Marlina</td></tr>
          <tr><td class="px-4 py-2">Jumat, 09:30 - 11:00</td><td>XI-RPL1</td><td>Simulasi Digital</td><td>2</td><td>Lab Multimedia</td><td>Pak Dede Suryana</td></tr>
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- Sidebar Toggle Script -->
<script>
  const menuToggleBtn = document.getElementById('menuToggleBtn');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const mainContent = document.getElementById('mainContent');

  function toggleSidebar() {
    
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
  }

  menuToggleBtn?.addEventListener('click', toggleSidebar);
  overlay?.addEventListener('click', toggleSidebar);
  mainContent?.addEventListener('click', () => {
    if (!sidebar.classList.contains('-translate-x-full')) toggleSidebar();
  });
</script>

</body>
</html>
