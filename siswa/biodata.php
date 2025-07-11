<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['UserID'])) {
  header("Location: ../login.php");
  exit;
}

$UserID = $_SESSION['UserID'];
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE UserID = '$UserID'");
$data = mysqli_fetch_assoc($query);

// Atur foto default jika kosong
$foto = !empty($data['foto']) ? $data['foto'] : 'default.jpg';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Biodata SIAKAD</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet"/>
  <style>body { font-family: "Inter", sans-serif; }</style>
</head>
<body class="bg-[#edf2f9] min-h-screen flex flex-col md:flex-row overflow-x-hidden">

  <!-- Sidebar -->
<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
  <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>

  <div class="flex items-center p-4 border-b border-blue-700">
    <img src="https://storage.googleapis.com/a1aa/image/885a0fab-da6e-467e-10ea-fe39f1152dc7.jpg" class="w-10 h-10 rounded-full" alt="Foto Siswa" />
    <div class="ml-3">
      <p class="font-semibold"><?php echo $nama; ?></p>
      <p class="text-xs text-blue-200">Siswa</p>
    </div>
  </div>

  <nav class="flex-1 p-2 space-y-2 overflow-y-auto">
    <a href="dashboard.php" class="flex items-center px-3 py-2 rounded <?php echo ($halaman == 'dashboard.php') ? 'bg-blue-800 pointer-events-none' : 'hover:bg-blue-800'; ?>">
      <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
    </a>
    <a href="biodata.php" class="flex items-center px-3 py-2 rounded <?php echo ($halaman == 'biodata.php') ? 'bg-blue-800 pointer-events-none' : 'hover:bg-blue-800'; ?>">
      <i class="fas fa-id-card mr-2"></i> Biodata
    </a>
    <a href="jadwal_pelajaran.php" class="flex items-center px-3 py-2 rounded <?php echo ($halaman == 'jadwal_pelajaran.php') ? 'bg-blue-800 pointer-events-none' : 'hover:bg-blue-800'; ?>">
      <i class="fas fa-calendar-alt mr-2"></i> Jadwal Pelajaran
    </a>
    <a href="cek_absensi.php" class="flex items-center px-3 py-2 rounded <?php echo ($halaman == 'cek_absensi.php') ? 'bg-blue-800 pointer-events-none' : 'hover:bg-blue-800'; ?>">
      <i class="fas fa-check-circle mr-2"></i> Cek Absensi
    </a>
    <a href="nilai.php" class="flex items-center px-3 py-2 rounded <?php echo ($halaman == 'nilai.php') ? 'bg-blue-800 pointer-events-none' : 'hover:bg-blue-800'; ?>">
      <i class="fas fa-poll mr-2"></i> Hasil Nilai Ujian
    </a>
    <a href="cetak_pdf.php" class="flex items-center px-3 py-2 rounded <?php echo ($halaman == 'cetak_pdf.php') ? 'bg-blue-800 pointer-events-none' : 'hover:bg-blue-800'; ?>">
      <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
    </a>
    <a href="../logout.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800">
      <i class="fas fa-sign-out-alt mr-2"></i> Logout
    </a>
  </nav>
</aside>

  <!-- Main content -->
  <main class="flex-1 p-4 md:p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-lg font-normal text-[#2f3e5e]">Biodata</h1>
      <span class="text-xs text-[#6b7280] italic">/ Biodata</span>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
      <!-- Kartu kiri -->
      <section class="bg-white rounded-xl p-6 w-full lg:max-w-md flex flex-col items-center">
        <img src="../uploads/<?php echo $foto; ?>" class="rounded-2xl mb-3 w-[150px] h-[180px] object-cover" alt="Foto Siswa"/>
        <h2 class="text-sm font-semibold text-[#2f3e5e]"><?php echo $data['nama_lengkap']; ?></h2>
        <p class="text-xs text-[#6b7280] mb-2"><?php echo $data['NIS']; ?></p>
        <p class="text-xs text-[#9ca3af] mb-6"><?php echo $data['Jurusan']; ?></p>
        <hr class="w-full border-[#e5e7eb] mb-6" />
        <div class="w-full space-y-3 text-xs text-[#6b7280]">
          <div class="flex items-center gap-2"><i class="far fa-envelope text-[10px]"></i><span><?php echo $data['email']; ?></span></div>
          <div class="flex items-center gap-2"><i class="fas fa-phone-alt text-[10px]"></i><span><?php echo $data['no_hp']; ?></span></div>
        </div>
        <hr class="w-full border-[#e5e7eb] my-6" />
        <div class="w-full text-xs text-[#6b7280] space-y-3">
          <div><p>Tempat Lahir</p><p class="font-semibold text-[#2f3e5e]"><?php echo $data['tempat_lahir']; ?></p></div>
          <div><p>Jenis Kelamin</p><p class="font-semibold text-[#2f3e5e]"><?php echo $data['jenis_kelamin']; ?></p></div>
          <div><p>Tanggal Lahir</p><p class="font-semibold text-[#2f3e5e]"><?php echo $data['tanggal_lahir']; ?></p></div>
          <div><p>Agama</p><p class="font-semibold text-[#2f3e5e]"><?php echo $data['agama']; ?></p></div>
          <div><p>Kelas</p><p class="font-semibold text-[#2f3e5e]"><?php echo $data['Kelas']; ?></p></div>
        </div>
        <button class="mt-6 w-full bg-[#22305a] text-white text-xs font-semibold py-2 rounded">Edit Biodata</button>
      </section>

      <!-- Kartu kanan -->
      <section class="flex-1 space-y-4">
        <div class="bg-white rounded-xl p-3 flex gap-4 text-xs text-[#6b7280] font-normal">
          <button class="bg-[#f9fafb] text-[#2f3e5e] rounded-md px-3 py-1">Details</button>
          <button class="px-3 py-1 rounded-md">Data Orangtua</button>
        </div>
        <div class="bg-white rounded-xl p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-6 text-xs text-[#6b7280]">
          <div class="flex justify-between"><span>NISN</span><span class="text-[#2f3e5e] font-semibold"><?php echo $data['NISN']; ?></span></div>
          <div class="flex justify-between"><span>Tahun Masuk</span><span class="text-[#2f3e5e] font-semibold"><?php echo $data['TahunMasuk']; ?></span></div>
          <div class="flex justify-between"><span>Alamat</span><span class="text-[#2f3e5e] font-semibold"><?php echo $data['alamat']; ?></span></div>
          <div class="flex justify-between"><span>Provinsi</span><span class="text-[#2f3e5e] font-semibold">Kepulauan Riau</span></div>
          <div class="flex justify-between"><span>Kota</span><span class="text-[#2f3e5e] font-semibold">Kota Batam</span></div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>
