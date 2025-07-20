<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['UserID'])) {
  header("Location: ../index.php");
  exit;
}

$UserID = $_SESSION['UserID'];
$query = mysqli_query($koneksi, "SELECT * FROM guru WHERE UserID = '$UserID'"); // GANTI 'UserID' kalau perlu
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data guru tidak ditemukan.";
    exit;
}



// Variabel yang dipakai di sidebar:
$namaGuru = $data['nama_lengkap'];
$foto = $data['foto'] ?? 'default.jpg';
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

 <!-- Main content -->
<main class="flex-1 p-4 md:p-6 overflow-y-auto">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-lg font-bold text-[#2f3e5e]">Biodata</h1>
  </div>


 <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Biodata Guru</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-[#f1f7fb] min-h-screen p-4">
  <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6">


   <!-- Kartu Kiri (Foto + Biodata Guru) -->
<section class="bg-white rounded-xl p-6 w-full lg:w-1/3 shadow border border-slate-200 flex flex-col items-center">
  <!-- Foto Guru -->
  <img src="../uploads/<?php echo htmlspecialchars($data['foto'] ?? 'default.jpg'); ?>"
    alt="Foto Guru"
    class="rounded-2xl mb-4 w-[150px] h-[180px] object-cover border" />

  <!-- Nama, NIP, Jabatan -->
  <h2 class="text-sm font-semibold text-[#2f3e5e] mb-1"><?php echo htmlspecialchars($data['nama_lengkap']); ?></h2>
  <p class="text-xs text-[#6b7280]"><?php echo htmlspecialchars($data['NIP'] ?? '-'); ?></p>
  <p class="text-xs text-[#9ca3af] mb-4"><?php echo htmlspecialchars($data['jabatan'] ?? '-'); ?></p>

  <hr class="w-full border-[#e5e7eb] my-4" />

  <!-- Biodata Ringkas -->
  <div class="w-full text-xs text-[#6b7280] space-y-2">
    <div>
      <p>Tempat Lahir</p>
      <p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['tempat_lahir'] ?? '-'); ?></p>
    </div>
    <div>
      <p>Tanggal Lahir</p>
      <p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['tanggal_lahir'] ?? '-'); ?></p>
    </div>
    <div>
      <p>Jenis Kelamin</p>
      <p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['jenis_kelamin'] ?? '-'); ?></p>
    </div>
    <div>
      <p>Agama</p>
      <p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['agama'] ?? '-'); ?></p>
    </div>
    <div>
      <p>Status Kepegawaian</p>
      <p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['status_kepegawaian'] ?? '-'); ?></p>
    </div>
  </div>
</section>


    <!-- Kartu Kanan -->
    <div class="flex-1 space-y-6">

      <!-- Informasi Tambahan -->
     <!-- Informasi Tambahan -->
<section class="bg-white rounded-xl p-6 shadow border border-slate-200">
  <h2 class="text-base font-semibold text-slate-800 mb-4">Informasi Tambahan</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm text-[#6b7280]">
    <div>
      <p class="text-[#6b7280]">NUPTK</p>
      <p class="text-[#2f3e5e] font-semibold"><?php echo htmlspecialchars($data['nuptk'] ?? '-'); ?></p>
    </div>
    <div>
      <p class="text-[#6b7280]">TMT Mengajar</p>
      <p class="text-[#2f3e5e] font-semibold"><?php echo htmlspecialchars($data['tmt_mengajar'] ?? '-'); ?></p>
    </div>
    <div>
      <p class="text-[#6b7280]">Pendidikan Terakhir</p>
      <p class="text-[#2f3e5e] font-semibold"><?php echo htmlspecialchars($data['pendidikan_terakhir'] ?? '-'); ?></p>
    </div>
    <div>
      <p class="text-[#6b7280]">Golongan Darah</p>
      <p class="text-[#2f3e5e] font-semibold"><?php echo htmlspecialchars($data['golongan_darah'] ?? '-'); ?></p>
    </div>
  </div>
</section>

<!-- Informasi Orang Tua -->
<section class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 mt-6">
  <h2 class="text-base font-semibold text-slate-800 mb-4">Informasi Orang Tua</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
    <!-- Data Ayah -->
    <div class="space-y-3">
      <h3 class="text-sm font-semibold text-slate-700 border-b pb-1">Data Ayah</h3>
      <div class="flex justify-between"><span>Nama Ayah Kandung</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['nama_ayah'] ?? '-'); ?></span></div>
      <div class="flex justify-between"><span>NIK Ayah</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['nik_ayah'] ?? '-'); ?></span></div>
      <div class="flex justify-between"><span>Tanggal Lahir Ayah</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['tgl_lahir_ayah'] ?? '-'); ?></span></div>
      <div class="flex justify-between"><span>Pendidikan Terakhir</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['pendidikan_ayah'] ?? '-'); ?></span></div>
      <div class="flex justify-between"><span>Pekerjaan</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['pekerjaan_ayah'] ?? '-'); ?></span></div>
    </div>

    <!-- Data Ibu -->
    <div class="space-y-3">
      <h3 class="text-sm font-semibold text-slate-700 border-b pb-1">Data Ibu</h3>
      <div class="flex justify-between"><span>Nama Ibu Kandung</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['nama_ibu'] ?? '-'); ?></span></div>
      <div class="flex justify-between"><span>NIK Ibu</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['nik_ibu'] ?? '-'); ?></span></div>
      <div class="flex justify-between"><span>Tanggal Lahir Ibu</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['tgl_lahir_ibu'] ?? '-'); ?></span></div>
      <div class="flex justify-between"><span>Pendidikan Terakhir</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['pendidikan_ibu'] ?? '-'); ?></span></div>
      <div class="flex justify-between"><span>Pekerjaan</span><span class="font-medium text-gray-900"><?php echo htmlspecialchars($data['pekerjaan_ibu'] ?? '-'); ?></span></div>
    </div>
  </div>
</section>

 <div class="flex justify-end mt-6">
  <a href="edit_data_diri.php" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded px-4 py-2 flex items-center">
    <i class="fas fa-edit mr-2"></i>
    Edit Profil
  </a>
</div>


</body>
<script>
  const menuToggleBtn = document.getElementById('menuToggleBtn');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');

  function toggleSidebar() {
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
  }

  menuToggleBtn?.addEventListener('click', toggleSidebar);
  overlay?.addEventListener('click', toggleSidebar);
</script>

</html>

