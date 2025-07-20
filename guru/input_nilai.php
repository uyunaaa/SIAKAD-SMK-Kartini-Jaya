<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['UserID']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../index.php");
    exit;
}

$guru_id = $_SESSION['UserID'];
$kelas = $_GET['kelas'] ?? '';
$mapel = $_GET['mapel'] ?? '';
$semester = $_GET['semester'] ?? 'Ganjil';
$tahun = date('Y') . '/' . (date('Y') + 1);

// Ambil data guru untuk sidebar
$guru = mysqli_query($koneksi, "SELECT * FROM guru WHERE UserID = '$guru_id'");
$dataGuru = mysqli_fetch_assoc($guru);
$nama = $dataGuru['nama_lengkap'] ?? 'Guru';
$foto = $dataGuru['foto'] ?? 'default.jpg';

// Ambil siswa berdasarkan kelas
$siswa = mysqli_query($koneksi, "SELECT id, nama_lengkap FROM siswa WHERE kelas = '$kelas'");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nilai'])) {
    foreach ($_POST['nilai'] as $id_siswa => $nilai) {
        $tugas = intval($nilai['tugas']);
        $uts = intval($nilai['uts']);
        $uas = intval($nilai['uas']);
        $nilai_akhir = round(($tugas + $uts + $uas) / 3, 2);

        mysqli_query($koneksi, "REPLACE INTO nilai 
            (siswa_id, mapel, semester, tugas, uts, uas, nilai_akhir, guru_id, kelas, tahun_ajaran) VALUES
            ('$id_siswa', '$mapel', '$semester', '$tugas', '$uts', '$uas', '$nilai_akhir', '$guru_id', '$kelas', '$tahun')
        ");
    }
    echo "<script>alert('Nilai berhasil disimpan!');</script>";
}
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
    body {
      font-family: "Inter", sans-serif;
    }
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
        <p class="font-semibold text-sm md:text-base"><?php echo $nama; ?></p>
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
  
  <!-- Main -->
  <main class="flex-1 p-6 overflow-x-auto">
    <h2 class="text-lg font-bold mb-4">Input Nilai - Kelas <?= htmlspecialchars($kelas) ?> | <?= htmlspecialchars($mapel) ?> | Semester <?= $semester ?></h2>

    <form method="POST">
      <table class="w-full bg-white border border-gray-300 text-sm">
        <thead class="bg-blue-100 text-left">
          <tr>
            <th class="p-2 border">No</th>
            <th class="p-2 border">Nama Siswa</th>
            <th class="p-2 border">Tugas</th>
            <th class="p-2 border">UTS</th>
            <th class="p-2 border">UAS</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($siswa)): ?>
          <tr class="hover:bg-gray-50">
            <td class="p-2 border"><?= $no++ ?></td>
            <td class="p-2 border"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
            <td class="p-2 border"><input type="number" name="nilai[<?= $row['id'] ?>][tugas]" class="w-full border px-2 py-1" min="0" max="100"></td>
            <td class="p-2 border"><input type="number" name="nilai[<?= $row['id'] ?>][uts]" class="w-full border px-2 py-1" min="0" max="100"></td>
            <td class="p-2 border"><input type="number" name="nilai[<?= $row['id'] ?>][uas]" class="w-full border px-2 py-1" min="0" max="100"></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Semua</button>
    </form>
  </main>
</div>

</body>
</html>


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
