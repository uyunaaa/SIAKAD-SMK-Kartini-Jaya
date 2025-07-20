<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['UserID'])) {
  header("Location: ../index.php");
  exit;
}

$UserID = $_SESSION['UserID'];

// Ambil info guru (nama dan foto)
$guruData = mysqli_query($koneksi, "SELECT nama_lengkap, foto FROM guru WHERE UserID = '$UserID'");
$data = mysqli_fetch_assoc($guruData);
$namaGuru = $data['nama_lengkap'] ?? 'Guru';
$foto = $data['foto'] ?? 'default.jpg';

// Map hari dalam bahasa Indonesia
$guru_id = $_SESSION['UserID'];

$hariIni = date('l');
$mapHari = [
  'Monday' => 'Senin',
  'Tuesday' => 'Selasa',
  'Wednesday' => 'Rabu',
  'Thursday' => 'Kamis',
  'Friday' => 'Jumat',
  'Saturday' => 'Sabtu',
  'Sunday' => 'Minggu',
];
$hari = $mapHari[$hariIni];

$namaGuru = $data['nama_lengkap'];
$jadwal_hari_ini = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE hari = '$hari' AND guru = '$namaGuru'");
$kelas_diajar = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM jadwal WHERE guru = '$namaGuru'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SIAKAD SMK - Dashboard Guru</title>
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
      <span class="font-semibold text-base">SIAKAD SMK</span>
    </div>
  </header>

  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>
    <div class="flex items-center p-4 border-b border-blue-700">
      <img src="uploads/<?php echo $foto; ?>" class="w-10 h-10 rounded-full object-cover" alt="Foto Guru" />
      <div class="ml-3">
        <p class="font-semibold text-sm md:text-base"><?php echo $namaGuru; ?></p>
        <p class="text-xs text-blue-200">Guru</p>
      </div>
    </div>

    <nav class="mt-4 flex flex-col space-y-2 px-4 pb-4 flex-1 overflow-y-auto">
      <a href="dashboard.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-tachometer-alt w-4"></i><span>Dashboard</span>
      </a>
      <a href="kelola_data_diri.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-user-cog w-4"></i><span>Kelola Data Diri</span>
      </a>
      <a href="input_nilai.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-edit w-4"></i><span>Input & Cetak Nilai</span>
      </a>
      <a href="data_siswa.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-users w-4"></i><span>Data Siswa</span>
      </a>
      <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-600 text-sm">
        <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
      </a>
    </nav>
  </aside>

  <!-- Overlay -->
  <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>

  <!-- Main Content -->
  <main id="mainContent" class="flex-1 p-6 mt-16 md:mt-0">
    <h2 class="text-xl font-semibold mb-4 text-[#2c3e5c]">Dashboard Guru</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-[#20345a] text-white p-4 rounded-lg">
        <p class="text-sm">Jumlah Kelas Diampu</p>
        <h3 class="text-2xl font-bold"><?php echo mysqli_num_rows($kelas_diajar); ?> Kelas</h3>
      </div>
      <div class="bg-[#3a7edb] text-white p-4 rounded-lg">
        <p class="text-sm">Hari Ini: <?php echo $hari; ?></p>
        <h3 class="text-lg font-semibold">
          <?php 
            if ($jadwal_hari_ini && mysqli_num_rows($jadwal_hari_ini) > 0) {
              $row = mysqli_fetch_assoc($jadwal_hari_ini);
              echo $row['jam'] . " | " . $row['mata_pelajaran'] . " - " . $row['kelas'];
            } else {
              echo "Tidak Ada";
            }
          ?>
        </h3>
      </div>
      <div class="bg-[#5cbf8f] text-white p-4 rounded-lg">
        <p class="text-sm">Nilai Terinput</p>
        <h3 class="text-2xl font-bold">85%</h3>
      </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
      <h3 class="text-sm font-semibold mb-2 text-blue-800">Jadwal Mengajar Hari Ini (<?php echo $hari; ?>)</h3>
      <?php
      mysqli_data_seek($jadwal_hari_ini, 0); // reset pointer
      if ($jadwal_hari_ini && mysqli_num_rows($jadwal_hari_ini) > 0): ?>
        <ul class="list-disc list-inside text-sm text-gray-700">
          <?php while ($row = mysqli_fetch_assoc($jadwal_hari_ini)): ?>
            <li><?php echo $row['jam']; ?> | <?php echo $row['mata_pelajaran']; ?> - <?php echo $row['kelas']; ?> (<?php echo $row['ruang_kelas']; ?>)</li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-gray-500 text-sm">Tidak ada jadwal mengajar hari ini.</p>
      <?php endif; ?>
    </div>

    <!-- Kelas yang Diajar -->
    <div class="bg-white p-4 rounded-lg shadow">
      <h3 class="text-sm font-semibold mb-2 text-blue-800">Kelas yang Diajar</h3>
      <div class="flex flex-wrap gap-2">
        <?php if ($kelas_diajar && mysqli_num_rows($kelas_diajar) > 0): ?>
          <?php mysqli_data_seek($kelas_diajar, 0); ?>
          <?php while ($kls = mysqli_fetch_assoc($kelas_diajar)): ?>
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
              <?php echo $kls['kelas']; ?>
            </span>
          <?php endwhile; ?>
        <?php else: ?>
          <span class="text-gray-500 text-sm">Tidak ada kelas ditemukan.</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Pengumuman -->
    <div class="bg-white p-4 rounded-lg border border-[#e76f51] text-[#e76f51] my-6">
      <div class="font-semibold mb-2"><i class="fas fa-bullhorn mr-1"></i>Pengumuman</div>
      <ul class="list-disc list-inside text-sm">
        <li>Input nilai terakhir tanggal 22 Juli 2025.</li>
        <li>Rapat wali kelas 20 Juli 2025 pukul 13.00 WIB.</li>
      </ul>
    </div>
  </main>

  <!-- Script Toggle Sidebar -->
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
