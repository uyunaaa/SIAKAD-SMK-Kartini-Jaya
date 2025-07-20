<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'guru') {
  header("Location: ../index.php");
  exit;
}

$guru_id = $_SESSION['UserID'];
$guru = mysqli_query($koneksi, "SELECT nama_lengkap, foto FROM guru WHERE UserID = '$guru_id'");
$dataGuru = mysqli_fetch_assoc($guru);
$nama = $dataGuru['nama_lengkap'] ?? 'Guru';
$foto = $dataGuru['foto'] ?? 'default.jpg';

// Ambil daftar kelas
$kelasList = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC");
$kelasTerpilih = $_GET['kelas'] ?? '';
$where = $kelasTerpilih ? "WHERE kelas = '$kelasTerpilih'" : '';
$siswa = mysqli_query($koneksi, "SELECT * FROM siswa $where ORDER BY nama_lengkap ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Siswa - SIAKAD SMK</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: "Inter", sans-serif; }
  </style>
</head>
<body class="bg-[#e8edf7] min-h-screen flex flex-col md:flex-row text-gray-800">

  <!-- Header Mobile -->
  <header class="md:hidden bg-blue-900 text-white flex justify-between items-center px-4 py-3 shadow">
    <div class="flex items-center space-x-3">
      <button id="menuToggleBtn"><i class="fas fa-bars text-xl"></i></button>
      <span class="font-semibold">SIAKAD SMK</span>
    </div>
  </header>

  <!-- Sidebar -->
 <aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>
    <div class="flex items-center p-4 border-b border-blue-700">
      <img src="uploads/<?php echo $foto; ?>" class="w-10 h-10 rounded-full object-cover" alt="Foto Guru" />
      <div class="ml-3">
        <p class="font-semibold text-sm md:text-base"><?php echo $nama; ?></p>
        <p class="text-xs text-blue-200">Guru</p>
      </div>
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
  <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-40 z-30 md:hidden"></div>

  <!-- Main Content -->
  <main id="mainContent" class="flex-1 p-6 mt-16 md:mt-0">
    <h2 class="text-xl font-semibold mb-4 text-[#2c3e5c]">Data Siswa</h2>

    <form method="get" class="mb-4">
      <label for="kelas" class="text-sm mr-2">Pilih Kelas:</label>
      <select name="kelas" id="kelas" class="border px-3 py-1 rounded text-sm">
        <option value="">Semua Kelas</option>
        <?php while ($kls = mysqli_fetch_assoc($kelasList)): ?>
          <option value="<?= $kls['kelas']; ?>" <?= $kelasTerpilih == $kls['kelas'] ? 'selected' : '' ?>>
            <?= $kls['kelas']; ?>
          </option>
        <?php endwhile; ?>
      </select>
      <button type="submit" class="ml-2 px-3 py-1 bg-blue-700 text-white rounded text-sm">Tampilkan</button>
    </form>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-blue-800 text-white">
          <tr>
            <th class="px-4 py-2">No</th>
            <th class="px-4 py-2">Nama Lengkap</th>
            <th class="px-4 py-2">Kelas</th>
            <th class="px-4 py-2">Jurusan</th>
            <th class="px-4 py-2">Tahun Masuk</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($siswa)): ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-4 py-2"><?= $no++; ?></td>
<td class="px-4 py-2"><?= $row['nama_lengkap']; ?></td>
<td class="px-4 py-2"><?= $row['Kelas']; ?></td>
<td class="px-4 py-2"><?= $row['Jurusan']; ?></td>
<td class="px-4 py-2"><?= $row['TahunMasuk']; ?></td>

            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>

  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const menuBtn = document.getElementById('menuToggleBtn');
    const mainContent = document.getElementById('mainContent');

    function toggleSidebar() {
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
    }

    menuBtn?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);
    mainContent?.addEventListener('click', () => {
      if (!sidebar.classList.contains('-translate-x-full')) toggleSidebar();
    });
  </script>
</body>
</html>
