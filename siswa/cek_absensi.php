<?php
session_start();

// Cek login & role
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

// Ambil data absensi siswa
$absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE Username = '$username' ORDER BY Tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Absensi Siswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-100 text-sm">

<!-- Header -->
<header class="flex items-center px-4 py-3 border-b border-gray-200 bg-white md:hidden">
  <div class="flex items-center gap-3">
    <button id="menuToggleBtn" class="text-[#2a6ad1] text-xl rounded focus:outline-none">
      <i class="fas fa-bars"></i>
    </button>
    <span class="text-sm font-semibold text-[#2a6ad1]">Cek Absensi</span>
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
      <a href="jadwal_pelajaran.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-calendar-alt mr-2"></i> Jadwal Pelajaran</a>
      <a href="cek_absensi.php" class="flex items-center px-3 py-2 rounded bg-blue-800"><i class="fas fa-check-circle mr-2"></i> Cek Absensi</a>
       <a href="cetak_pdf.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"> <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
      </a>
      <a href="nilai.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-poll mr-2"></i> Nilai Ujian</a>
      <a href="../logout.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
    </nav>
  </aside>

  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 md:hidden"></div>

  <!-- Konten Utama -->
  <main id="mainContent" class="flex-grow px-4 py-6 space-y-4 max-w-7xl mx-auto">
    <section class="bg-white p-4 rounded-md shadow">
      <h2 class="text-lg font-bold mb-4">Riwayat Absensi</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full border text-sm text-left">
          <thead class="bg-blue-100 text-gray-700">
            <tr>
              <th class="px-4 py-2 border">Tanggal</th>
              <th class="px-4 py-2 border">Keterangan</th>
              <th class="px-4 py-2 border">Waktu Input</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($absensi) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($absensi)): ?>
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2 border"><?php echo $row['Tanggal']; ?></td>
                  <td class="px-4 py-2 border"><?php echo $row['Keterangan']; ?></td>
                  <td class="px-4 py-2 border"><?php echo $row['Waktu_Input']; ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="3" class="text-center py-4 text-gray-500">Belum ada data absensi.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<!-- Toggle Sidebar Script -->
<script>
  const menuToggleBtn = document.getElementById('menuToggleBtn');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const mainContent = document.getElementById('mainContent');

  function toggleSidebar() {
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
