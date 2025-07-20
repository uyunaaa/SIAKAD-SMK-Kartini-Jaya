<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit();
}

$admin_id = $_SESSION['UserID'];
$qadmin = mysqli_query($koneksi, "SELECT * FROM admin WHERE id='$admin_id'");
$dadmin = mysqli_fetch_assoc($qadmin);
$foto = $dadmin['foto'] ?? 'default.png';
$nama = $dadmin['nama_admin'] ?? 'Admin';

// Ambil data nilai siswa
$query = "SELECT nilai.*, siswa.nama_lengkap, guru.nama_lengkap AS nama_guru 
          FROM nilai 
          JOIN siswa ON nilai.siswa_id = siswa.id 
          JOIN guru ON nilai.guru_id = guru.id";
$result = mysqli_query($koneksi, $query);
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
    <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-600 text-sm">
      <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
    </a>
  </nav>
</aside>


  <!-- Main Content -->
  <main class="flex-1 p-8">
    <h1 class="text-2xl font-bold text-blue-900 mb-6">📊 Lihat Nilai Siswa</h1>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
      <table class="min-w-full table-auto border border-gray-300 text-sm text-gray-700">
        <thead class="bg-blue-100">
          <tr>
            <th class="px-4 py-2 border">No</th>
            <th class="px-4 py-2 border">Nama Siswa</th>
            <th class="px-4 py-2 border">Mapel</th>
            <th class="px-4 py-2 border">Semester</th>
            <th class="px-4 py-2 border">Tugas</th>
            <th class="px-4 py-2 border">UTS</th>
            <th class="px-4 py-2 border">UAS</th>
            <th class="px-4 py-2 border">Nilai Akhir</th>
            <th class="px-4 py-2 border">Guru</th>
            <th class="px-4 py-2 border">Kelas</th>
            <th class="px-4 py-2 border">Tahun Ajaran</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='text-center'>";
            echo "<td class='border px-3 py-1'>{$no}</td>";
            echo "<td class='border px-3 py-1'>{$row['nama_lengkap']}</td>";
            echo "<td class='border px-3 py-1'>{$row['mapel']}</td>";
            echo "<td class='border px-3 py-1'>{$row['semester']}</td>";
            echo "<td class='border px-3 py-1'>{$row['tugas']}</td>";
            echo "<td class='border px-3 py-1'>{$row['uts']}</td>";
            echo "<td class='border px-3 py-1'>{$row['uas']}</td>";
            echo "<td class='border px-3 py-1 font-semibold'>{$row['nilai_akhir']}</td>";
            echo "<td class='border px-3 py-1'>{$row['nama_guru']}</td>";
            echo "<td class='border px-3 py-1'>{$row['kelas']}</td>";
            echo "<td class='border px-3 py-1'>{$row['tahun_ajaran']}</td>";
            echo "</tr>";
            $no++;
          }
          if ($no === 1) {
            echo "<tr><td colspan='11' class='text-center py-4 text-gray-500'>Belum ada data nilai.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>

</body>
</html>
