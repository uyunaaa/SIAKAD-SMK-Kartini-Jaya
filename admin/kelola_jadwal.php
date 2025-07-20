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
  <meta charset="UTF-8">
  <title>Kelola Jadwal | Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">

  <!-- Sidebar -->
  <aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>
    <div class="flex items-center p-4 border-b border-blue-700">
      <img src="../uploads/<?php echo $foto; ?>" class="w-10 h-10 rounded-full object-cover" alt="Foto Admin" />
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
      <a href="data_jadwal.php" class="flex items-center space-x-3 px-3 py-2 rounded bg-[#3e537a] text-sm">
        <i class="fas fa-calendar-alt w-4"></i><span>Kelola Jadwal</span>
      </a>
      <a href="nilai_siswa.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
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

  <!-- Konten utama -->
  <div class="flex-1 p-6 md:ml-64">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-blue-800">Kelola Jadwal Pelajaran</h1>
      <a href="tambah_jadwal.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ➕ Tambah Jadwal
      </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
      <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-blue-50">
          <tr>
            <th class="px-4 py-2 border">No</th>
            <th class="px-4 py-2 border">Hari</th>
            <th class="px-4 py-2 border">Kelas</th>
            <th class="px-4 py-2 border">Jam</th>
            <th class="px-4 py-2 border">Kode Kelas</th>
            <th class="px-4 py-2 border">Mapel</th>
            <th class="px-4 py-2 border">JP</th>
            <th class="px-4 py-2 border">Ruang</th>
            <th class="px-4 py-2 border">Guru</th>
            <th class="px-4 py-2 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($jadwal)): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2 border text-center"><?= $no++ ?></td>
            <td class="px-4 py-2 border"><?= $row['hari'] ?></td>
            <td class="px-4 py-2 border"><?= $row['kelas'] ?></td>
            <td class="px-4 py-2 border"><?= $row['jam'] ?></td>
            <td class="px-4 py-2 border"><?= $row['kode_kelas'] ?></td>
            <td class="px-4 py-2 border"><?= $row['mata_pelajaran'] ?></td>
            <td class="px-4 py-2 border text-center"><?= $row['jp'] ?></td>
            <td class="px-4 py-2 border"><?= $row['ruang_kelas'] ?></td>
            <td class="px-4 py-2 border"><?= $row['guru'] ?></td>
            <td class="px-4 py-2 border text-center">
              <a href="edit_jadwal.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a> |
              <a href="hapus_jadwal.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
