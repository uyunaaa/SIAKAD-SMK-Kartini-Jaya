<?php
session_start();
include '../koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil data admin
$admin_id = $_SESSION['UserID'];
$qadmin = mysqli_query($koneksi, "SELECT * FROM admin WHERE id='$admin_id'");
$dadmin = mysqli_fetch_assoc($qadmin);

if (!$dadmin) {
    die("⚠️ Data admin tidak ditemukan untuk ID: $admin_id");
}

$foto = $dadmin['foto'];
$nama = $dadmin['nama_admin'];

// Ambil parameter pencarian
$nip = isset($_GET['nip']) ? $_GET['nip'] : '';

// Query data guru
$sql = !empty($nip)
    ? "SELECT * FROM guru WHERE NIP LIKE '%$nip%' ORDER BY nama_lengkap ASC"
    : "SELECT * FROM guru ORDER BY nama_lengkap ASC";
$result = mysqli_query($koneksi, $sql);
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
    <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-600 text-sm">
      <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
    </a>
  </nav>
</aside>

  <!-- Konten Utama -->
  <main class="flex-1 p-8">
    <h2 class="text-2xl font-semibold text-blue-900 mb-6">Kelola Data Guru</h2>

    <!-- Form Pencarian -->
    <form method="GET" class="mb-6">
      <div class="flex flex-wrap items-center gap-2">
        <label for="nip" class="text-sm text-blue-900">Cari berdasarkan NIP:</label>
        <input type="text" name="nip" id="nip" value="<?= htmlspecialchars($nip) ?>"
               class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Masukkan NIP guru...">
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 transition">Cari</button>
      </div>
    </form>

    <!-- Tabel Guru -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
      <table class="min-w-full text-sm text-gray-800">
        <thead class="bg-blue-100 text-blue-900">
          <tr>
            <th class="py-3 px-4 border">No</th>
            <th class="py-3 px-4 border">Nama</th>
            <th class="py-3 px-4 border">NIP</th>
            <th class="py-3 px-4 border">Mata Pelajaran</th>
            <th class="py-3 px-4 border">No HP</th>
            <th class="py-3 px-4 border text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php 
          $no = 1;
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr class='hover:bg-gray-50'>";
              echo "<td class='py-2 px-4'>{$no}</td>";
              echo "<td class='py-2 px-4'>{$row['nama_lengkap']}</td>";
              echo "<td class='py-2 px-4'>{$row['NIP']}</td>";
              echo "<td class='py-2 px-4'>{$row['MataPelajaran']}</td>";
              echo "<td class='py-2 px-4'>{$row['no_hp']}</td>";
              echo "<td class='py-2 px-4 text-center'>
                      <a href='edit_guru.php?id={$row['id']}' class='text-blue-600 hover:underline'>Edit</a> |
                      <a href='hapus_guru.php?id={$row['id']}' class='text-red-600 hover:underline' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                    </td>";
              echo "</tr>";
              $no++;
          }

          if ($no === 1) {
              echo "<tr><td colspan='6' class='text-center py-4 text-gray-500'>Data tidak ditemukan.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>
