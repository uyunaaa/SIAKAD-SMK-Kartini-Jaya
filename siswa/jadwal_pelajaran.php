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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Jadwal Pelajaran - SIAKAD SMK</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 text-sm">

<!-- Header -->
<header class="flex items-center px-4 py-3 border-b border-gray-200 bg-white md:hidden">
  <div class="flex items-center gap-3">
    <button id="menuToggleBtn" class="text-[#2a6ad1] text-xl rounded focus:outline-none">
      <i class="fas fa-bars"></i>
    </button>
    <span class="text-sm font-semibold text-[#2a6ad1]">Jadwal Pelajaran</span>
  </div>
</header>

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>
    <div class="flex items-center p-4 border-b border-blue-700">
      <img src="https://storage.googleapis.com/a1aa/image/885a0fab-da6e-467e-10ea-fe39f1152dc7.jpg" class="w-10 h-10 rounded-full" alt="Foto Siswa" />
      <div class="ml-3">
        <p class="font-semibold"><?php echo $nama; ?></p>
        <p class="text-xs text-blue-200">Siswa</p>
      </div>
    </div>
    
    <nav class="flex-1 p-2 space-y-2 overflow-y-auto">
      <a href="dashboard.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
      <a href="biodata.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-id-card mr-2"></i> Biodata</a>
      <a href="jadwal_pelajaran.php" class="flex items-center px-3 py-2 rounded bg-blue-800"><i class="fas fa-calendar-alt mr-2"></i> Jadwal Pelajaran</a>
      <a href="cek_absensi.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-check-circle mr-2"></i> Cek Absensi</a>
      <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-poll mr-2"></i> Hasil Nilai Ujian</a>
      <a href="#" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-file-pdf mr-2"></i> Cetak PDF</a>
      <a href="../logout.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
    </nav>
  </aside>

  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 md:hidden"></div>

  <!-- Main Content -->
<main id="mainContent" class="flex-grow px-4 py-6 space-y-4 max-w-7xl mx-auto">
  <section class="bg-white rounded-xl p-4 sm:p-6 md:p-8 mb-8 shadow-sm">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">JADWAL MINGGUAN</h2>
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs sm:text-sm text-gray-700 border-separate border-spacing-y-1">
        <thead class="bg-[#F1F5F9] text-gray-700 font-semibold text-[10px] sm:text-xs">
          <tr>
            <th>HARI</th>
            <th>KODE KELAS</th>
            <th>MATA PELAJARAN</th>
            <th>JP</th>
            <th>RUANG KELAS</th>
            <th>GURU</th>
          </tr>
        </thead>
        <tbody class="text-[11px] sm:text-xs">
          <tr><td>Senin, 07:30 - 09:00</td><td>XI-RPL1</td><td>Pemrograman Dasar</td><td>2</td><td>Lab RPL</td><td>Bu Siti Rahmawati</td></tr>
          <tr><td>Senin, 09:30 - 11:00</td><td>XI-RPL1</td><td>Bahasa Inggris</td><td>2</td><td>Ruang 3.2</td><td>Pak Rudi Hartono</td></tr>
          <tr><td>Selasa, 08:00 - 09:40</td><td>XI-RPL1</td><td>Basis Data</td><td>2</td><td>Lab Komputer</td><td>Bu Ayu Lestari</td></tr>
          <tr><td>Selasa, 13:00 - 14:40</td><td>XI-RPL1</td><td>Produktif RPL</td><td>2</td><td>Lab RPL</td><td>Pak Fajar Nugraha</td></tr>
          <tr><td>Rabu, 07:30 - 10:00</td><td>XI-RPL1</td><td>Matematika</td><td>3</td><td>Ruang 2.1</td><td>Bu Yuliana Dewi</td></tr>
          <tr><td>Kamis, 08:00 - 09:40</td><td>XI-RPL1</td><td>Bahasa Indonesia</td><td>2</td><td>Ruang 2.2</td><td>Pak Taufik Hidayat</td></tr>
          <tr><td>Jumat, 07:30 - 09:00</td><td>XI-RPL1</td><td>PKN</td><td>2</td><td>Ruang 1.3</td><td>Bu Leni Marlina</td></tr>
          <tr><td>Jumat, 09:30 - 11:00</td><td>XI-RPL1</td><td>Simulasi Digital</td><td>2</td><td>Lab Multimedia</td><td>Pak Dede Suryana</td></tr>
        </tbody>
      </table>
    </div>
  </section>
</main>

<!-- Toggle Sidebar Script -->
<script>
  const menuToggleBtn = document.getElementById('menuToggleBtn');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const mainContent = document.getElementById('mainContent');

  function toggleSidebar() {
    const isOpen = !sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
  }

  menuToggleBtn.addEventListener('click', toggleSidebar);
  overlay.addEventListener('click', toggleSidebar);
  mainContent.addEventListener('click', () => {
    if (!sidebar.classList.contains('-translate-x-full')) toggleSidebar();
  });
</script>

</body>
</html>
