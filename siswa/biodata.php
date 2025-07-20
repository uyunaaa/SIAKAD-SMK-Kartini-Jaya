
<?php
session_start();

// ✅ Lokasi koneksi.php relatif ke folder siswa/
include '../koneksi.php';

// ✅ Gunakan nama session yang benar (disesuaikan)
if (!isset($_SESSION['UserID'])) {
  header('Location: ../index.php');
  exit;
}

$user_id = $_SESSION['UserID']; // Harus sama dengan yang kamu simpan saat login

$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE UserID = $user_id");
$data = mysqli_fetch_assoc($query);

require_once '../koneksi.php';

// 1. Redirect jika belum login
if (! isset($_SESSION['UserID'])) {
  header('Location: ../index.php');
  exit;
}

// 2. Ambil dan casting UserID
$UserID = intval($_SESSION['UserID']);

// 3. Query data siswa
$sql    = "SELECT * FROM siswa WHERE UserID = $UserID LIMIT 1";
$result = mysqli_query($koneksi, $sql);

// 4. Jika tidak ada data, redirect atau tampilkan pesan
if (!$result || mysqli_num_rows($result) === 0) {
  // misal kembali ke dashboard
  header('Location: dashboard.php?error=notfound');
  exit;
}

$data = mysqli_fetch_assoc($result);

// 5. Siapkan variabel untuk view
$nama = $data['nama_lengkap'];
$foto = !empty($data['foto']) ? $data['foto'] : 'default.jpg';

// 6. Untuk highlight menu
$halaman = basename($_SERVER['PHP_SELF']);


?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Biodata - SIAKAD SMK</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: "Inter", sans-serif; }
  </style>
</head>
<body class="bg-[#e8edf7] min-h-screen flex flex-col md:flex-row text-gray-800">

  <!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
  <!-- Logo -->
  <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>

  <!-- Profil Siswa -->
  <div class="flex items-center p-4 border-b border-blue-700">
    <img src="../uploads/<?php echo htmlspecialchars($foto); ?>" class="w-10 h-10 rounded-full object-cover" alt="Foto Siswa" />
    <div class="ml-3">
      <p class="font-semibold text-sm md:text-base"><?php echo htmlspecialchars($nama); ?></p>
      <p class="text-xs text-blue-200">Siswa</p>
    </div>
  </div>

  <!-- Menu Navigasi -->
  <nav class="mt-4 flex flex-col space-y-2 px-4 pb-4 flex-1 overflow-y-auto text-sm">
    <a href="dashboard.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'dashboard.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-tachometer-alt w-4"></i><span>Dashboard</span>
    </a>
    <a href="biodata.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'biodata.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-id-card w-4"></i><span>Biodata</span>
    </a>
    <a href="jadwal_pelajaran.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'jadwal_pelajaran.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-calendar-alt w-4"></i><span>Jadwal Pelajaran</span>
    </a>
    <a href="cek_absensi.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'cek_absensi.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-check-circle w-4"></i><span> Cek Absensi</span>
    </a>
    <a href="hasil_nilai.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-blue-800 <?php echo ($halaman === 'hasil_nilai.php') ? 'bg-blue-800 pointer-events-none' : ''; ?>">
      <i class="fas fa-poll w-4"></i><span>Hasil Nilai</span>
    </a>
    <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-600">
      <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
    </a>
  </nav>
</aside>

<!-- Header Mobile -->
<header class="md:hidden flex items-center justify-between bg-blue-900 text-white px-4 py-3 shadow">
  <div class="flex items-center space-x-3">
    <button id="menuToggleBtn" class="text-white text-2xl focus:outline-none">
      <i class="fas fa-bars"></i>
    </button>
    <span class="font-semibold text-base">SIAKAD SMK</span>
  </div>
</header>

<!-- Overlay -->
<div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>


  <!-- Main content -->
  <main class="flex-1 p-4 md:p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-lg font-normal text-[#2f3e5e]">Biodata</h1>

    </div>

 <!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Halaman Biodata Siswa</title>
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

    <!-- Kartu Kiri (Foto + Biodata Siswa) -->
    <section class="bg-white rounded-xl p-6 w-full lg:w-1/3 shadow border border-slate-200 flex flex-col items-center">
      <img src="../uploads/<?php echo htmlspecialchars($data['foto'] ?? 'default.jpg'); ?>"
        alt="Foto Siswa"
        class="rounded-2xl mb-4 w-[150px] h-[180px] object-cover border" />

      <h2 class="text-sm font-semibold text-[#2f3e5e] mb-1"><?php echo htmlspecialchars($data['nama_lengkap']); ?></h2>
      <p class="text-xs text-[#6b7280]"><?php echo htmlspecialchars($data['NIS'] ?? '-'); ?></p>
      <p class="text-xs text-[#9ca3af] mb-4"><?php echo htmlspecialchars($data['Jurusan'] ?? '-'); ?></p>

      <hr class="w-full border-[#e5e7eb] my-4" />

      <div class="w-full space-y-2 text-xs text-[#6b7280]">
        <div class="flex items-center gap-2"><i class="far fa-envelope text-[10px]"></i><span><?php echo htmlspecialchars($data['email']); ?></span></div>
        <div class="flex items-center gap-2"><i class="fas fa-phone-alt text-[10px]"></i><span><?php echo htmlspecialchars($data['no_hp']); ?></span></div>
      </div>

      <hr class="w-full border-[#e5e7eb] my-4" />

      <div class="w-full text-xs text-[#6b7280] space-y-2">
        <div><p>Tempat Lahir</p><p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['tempat_lahir']); ?></p></div>
        <div><p>Jenis Kelamin</p><p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['jenis_kelamin']); ?></p></div>
        <div><p>Tanggal Lahir</p><p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['tanggal_lahir']); ?></p></div>
        <div><p>Agama</p><p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['agama']); ?></p></div>
        <div><p>Kelas</p><p class="font-semibold text-[#2f3e5e]"><?php echo htmlspecialchars($data['Kelas']); ?></p></div>
      </div>

      <a href="mt-6 w-full bg-[#22305a] text-white text-xs font-semibold py-2 rounded text-center"></a>
    </section>

    <!-- Kartu Kanan -->
    <div class="flex-1 space-y-6">

      <!-- Informasi Tambahan -->
      <section class="bg-white rounded-xl p-6 shadow border border-slate-200">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Informasi Tambahan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm text-[#6b7280]">
          <div><p class="text-[#6b7280]">NISN</p><p class="text-[#2f3e5e] font-semibold"><?php echo htmlspecialchars($data['NISN']); ?></p></div>
          <div><p class="text-[#6b7280]">Tahun Masuk</p><p class="text-[#2f3e5e] font-semibold"><?php echo htmlspecialchars($data['TahunMasuk']); ?></p></div>
          <div><p class="text-[#6b7280]">Alamat</p><p class="text-[#2f3e5e] font-semibold"><?php echo htmlspecialchars($data['alamat']); ?></p></div>
          <div><p class="text-[#6b7280]">Golongan Darah</p><p class="text-[#2f3e5e] font-semibold"><?php echo htmlspecialchars($data['golongan_darah'] ?? '-'); ?></p></div>
        </div>


      </section>

      <!-- Informasi Orang Tua -->
      <section class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Informasi Orang Tua</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
          <!-- Data Ayah -->
          <div class="space-y-3">
            <h3 class="text-sm font-semibold text-slate-700 border-b pb-1">Data Ayah</h3>
            <div class="flex justify-between"><span>Nama Ayah</span><span class="font-medium text-gray-900">Wan Hendri</span></div>
            <div class="flex justify-between"><span>NIK Ayah</span><span class="font-medium text-gray-900">-</span></div>
            <div class="flex justify-between"><span>Tanggal Lahir Ayah</span><span class="font-medium text-gray-900">-</span></div>
            <div class="flex justify-between"><span>No. Telp. Ayah</span><span class="font-medium text-gray-900">+62895336712775</span></div>
            <div class="flex justify-between"><span>Email Ayah</span><span class="font-medium text-gray-900">-</span></div>
            <div class="flex justify-between"><span>Pendidikan Terakhir Ayah</span><span class="font-medium text-gray-900">SMA / sederajat</span></div>
            <div class="flex justify-between"><span>Penghasilan Ayah</span><span class="font-medium text-gray-900">-</span></div>
            <div class="flex justify-between"><span>Pekerjaan Ayah</span><span class="font-medium text-gray-900">Karyawan Swasta</span></div>
          </div>
          <!-- Data Ibu -->
          <div class="space-y-3">
            <h3 class="text-sm font-semibold text-slate-700 border-b pb-1">Data Ibu</h3>
            <div class="flex justify-between"><span>Nama Ibu Kandung</span><span class="font-medium text-gray-900">Nurhidayah</span></div>
            <div class="flex justify-between"><span>NIK Ibu</span><span class="font-medium text-gray-900">-</span></div>
            <div class="flex justify-between"><span>Tanggal Lahir Ibu</span><span class="font-medium text-gray-900">-</span></div>
            <div class="flex justify-between"><span>No. Telp. Ibu</span><span class="font-medium text-gray-900">+6282171557936</span></div>
            <div class="flex justify-between"><span>Email Ibu</span><span class="font-medium text-gray-900">-</span></div>
            <div class="flex justify-between"><span>Pendidikan Terakhir Ibu</span><span class="font-medium text-gray-900">SMA / sederajat</span></div>
            <div class="flex justify-between"><span>Penghasilan Ibu</span><span class="font-medium text-gray-900">-</span></div>
            <div class="flex justify-between"><span>Pekerjaan Ibu</span><span class="font-medium text-gray-900">Lainnya</span></div>
          </div>
        </div>
      </section>
    </div>
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
