<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'siswa') {
  header("Location: ../index.php");
  exit;
}

$UserID = $_SESSION['UserID'];

// Ambil data siswa
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE UserID = '$UserID'");
$data = mysqli_fetch_assoc($query);
$namaSiswa = $data['nama_lengkap'] ?? 'Siswa';
$foto = $data['foto'] ?? 'default.jpg';
$kelas = $data['Kelas'] ?? '-';

// Hari sekarang (Indonesia)
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

// Ambil jadwal berdasarkan kelas & hari
$jadwalHariIni = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE hari = '$hari' AND kelas = '$kelas'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SIAKAD SMK - Dashboard Siswa</title>
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
      <img src="../uploads/<?php echo $foto; ?>" class="w-10 h-10 rounded-full object-cover" alt="Foto Siswa" />
      <div class="ml-3">
        <p class="font-semibold text-sm md:text-base"><?php echo $namaSiswa; ?></p>
        <p class="text-xs text-blue-200">Siswa</p>
      </div>
    </div>

    <nav class="mt-4 flex flex-col space-y-2 px-4 pb-4 flex-1 overflow-y-auto">
      <a href="dashboard.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-tachometer-alt w-4"></i><span>Dashboard</span>
      </a>
      <a href="biodata.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-id-card w-4"></i><span>Biodata</span>
      </a>
      <a href="jadwal_pelajaran.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-calendar-alt w-4"></i><span>Jadwal Pelajaran</span>
      </a>
       <a href="cek_absensi.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-check-circle w-4"></i><span>Cek Absensi</span>
      </a>
      <a href="hasil_nilai.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-poll w-4"></i><span>Hasil Nilai</span>
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
    <h2 class="text-xl font-semibold mb-4 text-[#2c3e5c]">Dashboard Siswa</h2>
         <section class="bg-white rounded-md p-4 flex items-center gap-4 mb-4">
  <img src="../uploads/<?php echo $foto; ?>" class="w-14 h-14 rounded-full object-cover border border-gray-300" alt="Foto Siswa" />
  <div class="flex flex-col">
    <p class="font-semibold text-base text-gray-800">
      Hi, <?php echo $namaSiswa . " (" . ($data['NIS'] ?? '-') . ")"; ?>
    </p>
    <p class="text-sm text-gray-600 leading-snug">
      Saat ini kamu berada di semester <strong>Genap 2024/2025</strong> (Minggu #14).<br>
      <span class="text-blue-600 font-medium">Tetap semangat!</span>
    </p>
  </div>
</section>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-[#20345a] text-white p-4 rounded-lg">
        <p class="text-sm">Kelas</p>
        <h3 class="text-2xl font-bold"><?php echo $kelas; ?></h3>
      </div>
      <div class="bg-[#3a7edb] text-white p-4 rounded-lg">
        <p class="text-sm">Hari Ini: <?php echo $hari; ?></p>
        <h3 class="text-lg font-semibold">
          <?php 
            if ($jadwalHariIni && mysqli_num_rows($jadwalHariIni) > 0) {
              $row = mysqli_fetch_assoc($jadwalHariIni);
              echo $row['jam'] . " | " . $row['mata_pelajaran'] . " - " . $row['ruang_kelas'];
            } else {
              echo "Tidak Ada";
            }
          ?>
        </h3>
      </div>
      <div class="bg-[#5cbf8f] text-white p-4 rounded-lg">
        <p class="text-sm">Status Nilai</p>
        <h3 class="text-2xl font-bold">Cek Hasil</h3>
      </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
      <h3 class="text-sm font-semibold mb-2 text-blue-800">Jadwal Hari Ini (<?php echo $hari; ?>)</h3>
      <?php
      mysqli_data_seek($jadwalHariIni, 0);
      if ($jadwalHariIni && mysqli_num_rows($jadwalHariIni) > 0): ?>
        <ul class="list-disc list-inside text-sm text-gray-700">
          <?php while ($row = mysqli_fetch_assoc($jadwalHariIni)): ?>
            <li><?php echo $row['jam']; ?> | <?php echo $row['mata_pelajaran']; ?> (<?php echo $row['ruang_kelas']; ?>)</li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-gray-500 text-sm">Tidak ada jadwal hari ini.</p>
      <?php endif; ?>
    </div>

    <!-- Pengumuman -->
    <div class="bg-white p-4 rounded-lg border border-[#e76f51] text-[#e76f51] my-6">
      <div class="font-semibold mb-2"><i class="fas fa-bullhorn mr-1"></i>Pengumuman</div>
      <ul class="list-disc list-inside text-sm">
        <li>Ujian akhir semester akan dimulai 5 Agustus 2025.</li>
        <li>Pastikan biodata dan foto sudah lengkap.</li>
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
