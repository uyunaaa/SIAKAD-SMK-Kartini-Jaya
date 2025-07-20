<?php
session_start();
include '../koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil data admin untuk sidebar
$admin_id = $_SESSION['UserID'];
$qadmin = mysqli_query($koneksi, "SELECT * FROM admin WHERE id='$admin_id'");
$dadmin = mysqli_fetch_assoc($qadmin);

if (!$dadmin) {
    die("⚠️ Data admin tidak ditemukan untuk ID: $admin_id");
}

$foto = $dadmin['foto'];
$nama = $dadmin['nama_admin'];

// Ambil semua data nilai siswa + nama siswa + nama guru
$query = mysqli_query($koneksi, "
  SELECT 
    n.*, 
    s.nama_lengkap AS nama_siswa, 
    g.nama_lengkap AS nama_guru
  FROM nilai n
  LEFT JOIN siswa s ON n.siswa_id = s.id
  LEFT JOIN guru g ON n.guru_id = g.id
  ORDER BY n.tahun_ajaran DESC, n.semester ASC, s.nama_lengkap
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Nilai Siswa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 flex">

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
      <a href="nilai_siswa.php" class="flex items-center space-x-3 px-3 py-2 rounded bg-[#3e537a] text-sm">
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

  <!-- Konten Utama -->
  <main class="flex-1 ml-64 p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">📊 Data Nilai Siswa</h1>

    <div class="overflow-auto bg-white rounded-lg shadow p-4">
      <table class="min-w-full border border-gray-300 text-sm">
        <thead class="bg-blue-100 text-left">
          <tr>
            <th class="border px-3 py-2">No</th>
            <th class="border px-3 py-2">Nama Siswa</th>
            <th class="border px-3 py-2">Kelas</th>
            <th class="border px-3 py-2">Mapel</th>
            <th class="border px-3 py-2">Guru</th>
            <th class="border px-3 py-2">Semester</th>
            <th class="border px-3 py-2">Tahun</th>
            <th class="border px-3 py-2">Tugas</th>
            <th class="border px-3 py-2">UTS</th>
            <th class="border px-3 py-2">UAS</th>
            <th class="border px-3 py-2">Nilai Akhir</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($row = mysqli_fetch_assoc($query)) {
              echo "<tr class='hover:bg-gray-50 text-center'>";
              echo "<td class='border px-3 py-2'>{$no}</td>";
              echo "<td class='border px-3 py-2 text-left'>{$row['nama_siswa']}</td>";
              echo "<td class='border px-3 py-2'>{$row['kelas']}</td>";
              echo "<td class='border px-3 py-2 text-left'>{$row['mapel']}</td>";
              echo "<td class='border px-3 py-2 text-left'>{$row['nama_guru']}</td>";
              echo "<td class='border px-3 py-2'>{$row['semester']}</td>";
              echo "<td class='border px-3 py-2'>{$row['tahun_ajaran']}</td>";
              echo "<td class='border px-3 py-2'>{$row['tugas']}</td>";
              echo "<td class='border px-3 py-2'>{$row['uts']}</td>";
              echo "<td class='border px-3 py-2'>{$row['uas']}</td>";
              echo "<td class='border px-3 py-2 font-semibold text-green-600'>{$row['nilai_akhir']}</td>";
              echo "</tr>";
              $no++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
