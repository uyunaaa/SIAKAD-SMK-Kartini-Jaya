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
    die("\u26a0\ufe0f Data admin tidak ditemukan untuk ID: $admin_id");
}

$foto = $dadmin['foto'];
$nama = $dadmin['nama_admin'];

// Ambil data guru
$sql = "SELECT * FROM guru ORDER BY nama_lengkap ASC";
$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Data Guru</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

  <!-- Sidebar -->
  <aside class="w-64 bg-blue-900 text-white flex flex-col min-h-screen">
    <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>
    <div class="flex items-center p-4 border-b border-blue-700">
      <img src="../uploads/<?php echo $foto; ?>" class="w-10 h-10 rounded-full object-cover" alt="Foto Admin" />
      <div class="ml-3">
        <p class="font-semibold text-sm"><?php echo $nama; ?></p>
        <p class="text-xs text-blue-200">Admin</p>
      </div>
    </div>

    <nav class="mt-4 flex flex-col space-y-2 px-4 pb-4 flex-1 text-sm">
      <a href="dashboard.php" class="px-3 py-2 rounded hover:bg-blue-700">Dashboard</a>
      <a href="data_siswa.php" class="px-3 py-2 rounded hover:bg-blue-700">Kelola Data Siswa</a>
      <a href="data_guru.php" class="px-3 py-2 rounded bg-blue-700">Kelola Data Guru</a>
      <a href="data_jadwal.php" class="px-3 py-2 rounded hover:bg-blue-700">Kelola Jadwal</a>
      <a href="nilai_siswa.php" class="px-3 py-2 rounded hover:bg-blue-700">Lihat Nilai Siswa</a>
      <a href="pengaturan_akun.php" class="px-3 py-2 rounded hover:bg-blue-700">Pengaturan Akun</a>
      <a href="../logout.php" class="px-3 py-2 rounded hover:bg-red-600">Logout</a>
    </nav>
  </aside>

  <!-- Konten Utama -->
  <main class="flex-1 p-8">
    <h2 class="text-[#2f4a6f] font-semibold text-xl mb-6">Data Guru</h2>

    <!-- Tabel Data Guru -->
    <div class="overflow-x-auto bg-white shadow-md rounded p-4">
      <table class="min-w-full text-sm text-left text-gray-700 border">
        <thead class="bg-blue-50">
          <tr>
            <th class="py-2 px-3 border">No</th>
            <th class="py-2 px-3 border">Nama</th>
            <th class="py-2 px-3 border">NIP</th>
            <th class="py-2 px-3 border">Mata Pelajaran</th>
            <th class="py-2 px-3 border">No HP</th>
            <th class="py-2 px-3 border">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1;
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr class='border-t'>";
              echo "<td class='py-2 px-3 border'>{$no}</td>";
              echo "<td class='py-2 px-3 border'>{$row['nama_lengkap']}</td>";
              echo "<td class='py-2 px-3 border'>{$row['NIP']}</td>";
              echo "<td class='py-2 px-3 border'>{$row['MataP']}</td>";
              echo "<td class='py-2 px-3 border'>{$row['no_hp']}</td>";
              echo "<td class='py-2 px-3 border'>
                      <a href='edit_guru.php?id={$row['id']}' class='text-blue-600 hover:underline'>Edit</a> |
                      <a href='hapus_guru.php?id={$row['id']}' class='text-red-600 hover:underline' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                    </td>";
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
