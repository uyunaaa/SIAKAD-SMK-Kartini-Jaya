<?php
session_start();
$namaSiswa = $_SESSION['nama_lengkap'] ?? 'Siswa';
$foto = $_SESSION['foto'] ?? 'default.png';
// echo '<pre>'; var_dump(session_id(), $_SESSION); echo '</pre>';
// exit;
// …then remove this as soon as you confirm it’s correct.


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
$kelas = $data ? $data['Kelas'] : "";

// Mapping hari dari bahasa Inggris ke Indonesia
// Ambil hari sekarang dan terjemahkan
$hariIni = date('l');
$hariMap = [
  'Sunday' => 'Minggu',
  'Monday' => 'Senin',
  'Tuesday' => 'Selasa',
  'Wednesday' => 'Rabu',
  'Thursday' => 'Kamis',
  'Friday' => 'Jumat',
  'Saturday' => 'Sabtu',
];
$hariSekarang = $hariMap[$hariIni];

// Query jadwal berdasarkan hari dan kelas siswa
$jadwalHariIni = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE hari = '$hariSekarang' AND kelas = '$kelas'");


// Debug output
// echo "Hari sekarang: " . $hariSekarang . "<br>";
// echo "Jumlah data jadwal: " . mysqli_num_rows($jadwalHariIni) . "<br>";
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hasil Nilai - SIAKAD SMKSS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: "Inter", sans-serif; }
  </style>
</head>
<body class="bg-[#e8edf7] min-h-screen flex flex-col md:flex-row text-gray-800">


  <!-- Header -->
  <header class="flex items-center px-4 py-3 border-b border-gray-200 bg-white md:hidden">
    <div class="flex items-center gap-3">
      <button id="menuToggleBtn" class="text-[#2a6ad1] text-xl rounded focus:outline-none">
        <i class="fas fa-bars"></i>
      </button>
      <span class="text-sm font-semibold text-[#2a6ad1]">Dashboard</span>
    </div>
  </header>

  <div class="flex min-h-screen">
   <!-- Sidebar -->
  <aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>
    <div class="flex items-center p-4 border-b border-blue-700">
      <img src="../uploads/<?php echo $foto; ?>" class="w-10 h-10 rounded-full object-cover" alt="Foto Siswa" />
      <div class="ml-3">
        <p class="font-semibold text-sm md:text-base"><?php echo $namaSiswa; ?></p>
        <p class="text-xs text-blue-200">Siswa</p>
      </div>
    </div>

    <nav class="mt-4 flex flex-col space-y-2 px-4 pb-4 flex-1 overflow-y-auto">
      <a href="dashboard.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-tachometer-alt w-4"></i><span>Dashboard</span>
      </a>
      <a href="biodata.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-id-card w-4"></i><span>Biodata</span>
      </a>
      <a href="jadwal_pelajaran.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-calendar-alt w-4"></i><span>Jadwal Pelajaran</span>
      </a>
       <a href="cek_absensi.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-check-circle w-4"></i><span>Cek Absensi</span>
      </a>
      <a href="hasil_nilai.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-[#3e537a] text-sm">
        <i class="fas fa-poll w-4"></i><span>Hasil Nilai</span>
      </a>
      <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 rounded hover:bg-red-600 text-sm">
        <i class="fas fa-sign-out-alt w-4"></i><span>Logout</span>
      </a>
    </nav>
  </aside>


      <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 md:hidden"></div>

  <!-- Main Content -->
<main id="mainContent" class="flex-grow px-4 py-6 space-y-4 max-w-7xl mx-auto">
  <section class="bg-white rounded-xl p-4 sm:p-6 md:p-8 mb-8 shadow-sm">
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-xl font-bold text-slate-700 mb-4">Hasil Nilai Ujian</h1>
   <div class="overflow-x-auto">
  <table class="w-full text-left text-xs sm:text-sm text-gray-700 border-separate border-spacing-y-1">


        <thead class="bg-slate-200 text-slate-700">
          <tr>
            <th class="border px-3 py-2">Hari / Jam</th>
            <th class="border px-3 py-2">Kelas</th>
            <th class="border px-3 py-2">Mata Pelajaran</th>
            <th class="border px-3 py-2">SKS</th>
            <th class="border px-3 py-2">Ruang</th>
            <th class="border px-3 py-2">Guru</th>
            <th class="border px-3 py-2">Nilai</th>
          </tr>
        </thead>
        <tbody class="text-slate-600">
          <tr>
            <td class="border px-3 py-2">Senin, 09:30 - 11:00</td>
            <td class="border px-3 py-2">XI-RPL1</td>
            <td class="border px-3 py-2">Bahasa Inggris</td>
            <td class="border px-3 py-2 text-center">2</td>
            <td class="border px-3 py-2">Ruang 3.2</td>
            <td class="border px-3 py-2">Pak Rudi Hartono</td>
            <td class="border px-3 py-2 text-center">B</td>
          </tr>
          <tr class="bg-slate-50">
            <td class="border px-3 py-2">Selasa, 08:00 - 09:40</td>
            <td class="border px-3 py-2">XI-RPL1</td>
            <td class="border px-3 py-2">Basis Data</td>
            <td class="border px-3 py-2 text-center">2</td>
            <td class="border px-3 py-2">Lab Komputer</td>
            <td class="border px-3 py-2">Bu Ayu Lestari</td>
            <td class="border px-3 py-2 text-center">A</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">Selasa, 13:00 - 14:40</td>
            <td class="border px-3 py-2">XI-RPL1</td>
            <td class="border px-3 py-2">Produktif RPL</td>
            <td class="border px-3 py-2 text-center">2</td>
            <td class="border px-3 py-2">Lab RPL</td>
            <td class="border px-3 py-2">Pak Fajar Nugraha</td>
            <td class="border px-3 py-2 text-center">A</td>
          </tr>
          <tr class="bg-slate-50">
            <td class="border px-3 py-2">Rabu, 07:30 - 10:00</td>
            <td class="border px-3 py-2">XI-RPL1</td>
            <td class="border px-3 py-2">Matematika</td>
            <td class="border px-3 py-2 text-center">3</td>
            <td class="border px-3 py-2">Ruang 2.1</td>
            <td class="border px-3 py-2">Bu Yuliana Dewi</td>
            <td class="border px-3 py-2 text-center">C</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">Kamis, 08:00 - 09:40</td>
            <td class="border px-3 py-2">XI-RPL1</td>
            <td class="border px-3 py-2">Bahasa Indonesia</td>
            <td class="border px-3 py-2 text-center">2</td>
            <td class="border px-3 py-2">Ruang 2.2</td>
            <td class="border px-3 py-2">Pak Taufik Hidayat</td>
            <td class="border px-3 py-2 text-center">B</td>
          </tr>
          <tr class="bg-slate-50">
            <td class="border px-3 py-2">Jumat, 07:30 - 09:00</td>
            <td class="border px-3 py-2">XI-RPL1</td>
            <td class="border px-3 py-2">PKN</td>
            <td class="border px-3 py-2 text-center">2</td>
            <td class="border px-3 py-2">Ruang 1.3</td>
            <td class="border px-3 py-2">Bu Leni Marlina</td>
            <td class="border px-3 py-2 text-center">B+</td>
          </tr>
          <tr>
            <td class="border px-3 py-2">Jumat, 09:30 - 11:00</td>
            <td class="border px-3 py-2">XI-RPL1</td>
            <td class="border px-3 py-2">Simulasi Digital</td>
            <td class="border px-3 py-2 text-center">2</td>
            <td class="border px-3 py-2">Lab RPL</td>
            <td class="border px-3 py-2">Bu Siti Rahmawati</td>
            <td class="border px-3 py-2 text-center">A-</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
