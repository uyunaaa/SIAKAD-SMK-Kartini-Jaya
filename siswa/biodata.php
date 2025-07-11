<?php
session_start();
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Biodata SIAKAD</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: "Inter", sans-serif;
    }
  </style>
</head>

<body class="bg-[#edf2f9] min-h-screen flex flex-col md:flex-row overflow-x-hidden">

  <!-- Sidebar -->
  <aside id="sidebar"
    class="w-64 bg-blue-900 text-white flex flex-col fixed md:static inset-y-0 left-0
                transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-40">
    <div class="p-4 font-bold text-lg border-b border-blue-700">SIAKAD SMK</div>

    <div class="flex items-center p-4 border-b border-blue-700">
      <!-- 7. Tampilkan foto user -->
      <img src="../uploads/<?php echo htmlspecialchars($foto); ?>"
        class="w-10 h-10 rounded-full"
        alt="Foto Siswa" />
      <div class="ml-3">
        <!-- 8. Tampilkan nama lengkap -->
        <p class="font-semibold"><?php echo htmlspecialchars($nama); ?></p>
        <p class="text-xs text-blue-200">Siswa</p>
      </div>
    </div>

    <nav class="flex-1 p-2 space-y-2 overflow-y-auto">
      <?php
      // helper untuk class aktif
      function navItem($file, $label, $icon)
      {
        global $halaman;
        $active = $halaman === $file
          ? 'bg-blue-800 pointer-events-none'
          : 'hover:bg-blue-800';
        return "<a href=\"$file\" class=\"flex items-center px-3 py-2 rounded $active\">
                    <i class=\"fas fa-$icon mr-2\"></i> $label
                  </a>";
      }
      ?>
      <?php echo navItem('dashboard.php',       'Dashboard',     'tachometer-alt'); ?>
      <?php echo navItem('biodata.php',         'Biodata',       'id-card'); ?>
      <?php echo navItem('jadwal_pelajaran.php', 'Jadwal Pelajaran', 'calendar-alt'); ?>
      <?php echo navItem('cek_absensi.php',     'Cek Absensi',   'check-circle'); ?>
      <?php echo navItem('nilai.php',           'Hasil Nilai Ujian', 'poll'); ?>
      <?php echo navItem('cetak_pdf.php',       'Cetak PDF',     'file-pdf'); ?>
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
        <img src="../uploads/<?php echo htmlspecialchars($foto); ?>"
          class="rounded-2xl mb-3 w-[150px] h-[180px] object-cover"
          alt="Foto Siswa" />
        <h2 class="text-sm font-semibold text-[#2f3e5e]">
          <?php echo htmlspecialchars($data['nama_lengkap']); ?>
        </h2>
        <p class="text-xs text-[#6b7280] mb-2">
          <?php echo htmlspecialchars($data['NIS']); ?>
        </p>
        <p class="text-xs text-[#9ca3af] mb-6">
          <?php echo htmlspecialchars($data['Jurusan']); ?>
        </p>
        <hr class="w-full border-[#e5e7eb] mb-6" />

        <div class="w-full space-y-3 text-xs text-[#6b7280]">
          <div class="flex items-center gap-2">
            <i class="far fa-envelope text-[10px]"></i>
            <span><?php echo htmlspecialchars($data['email']); ?></span>
          </div>
          <div class="flex items-center gap-2">
            <i class="fas fa-phone-alt text-[10px]"></i>
            <span><?php echo htmlspecialchars($data['no_hp']); ?></span>
          </div>
        </div>

        <hr class="w-full border-[#e5e7eb] my-6" />

        <div class="w-full text-xs text-[#6b7280] space-y-3">
          <div>
            <p>Tempat Lahir</p>
            <p class="font-semibold text-[#2f3e5e]">
              <?php echo htmlspecialchars($data['tempat_lahir']); ?>
            </p>
          </div>
          <div>
            <p>Jenis Kelamin</p>
            <p class="font-semibold text-[#2f3e5e]">
              <?php echo htmlspecialchars($data['jenis_kelamin']); ?>
            </p>
          </div>
          <div>
            <p>Tanggal Lahir</p>
            <p class="font-semibold text-[#2f3e5e]">
              <?php echo htmlspecialchars($data['tanggal_lahir']); ?>
            </p>
          </div>
          <div>
            <p>Agama</p>
            <p class="font-semibold text-[#2f3e5e]">
              <?php echo htmlspecialchars($data['agama']); ?>
            </p>
          </div>
          <div>
            <p>Kelas</p>
            <p class="font-semibold text-[#2f3e5e]">
              <?php echo htmlspecialchars($data['Kelas']); ?>
            </p>
          </div>
        </div>

        <button class="mt-6 w-full bg-[#22305a] text-white text-xs font-semibold py-2 rounded">
          Edit Biodata
        </button>
      </section>

      <!-- Kartu kanan -->
      <section class="flex-1 space-y-4">
        <div class="bg-white rounded-xl p-3 flex gap-4 text-xs text-[#6b7280] font-normal">
          <button class="bg-[#f9fafb] text-[#2f3e5e] rounded-md px-3 py-1">Details</button>
          <button class="px-3 py-1 rounded-md">Data Orangtua</button>
        </div>
        <div class="bg-white rounded-xl p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3
                    gap-y-3 gap-x-6 text-xs text-[#6b7280]">

          <div class="flex justify-between">
            <span>NISN</span>
            <span class="text-[#2f3e5e] font-semibold">
              <?php echo htmlspecialchars($data['NISN']); ?>
            </span>
          </div>

          <div class="flex justify-between">
            <span>Tahun Masuk</span>
            <span class="text-[#2f3e5e] font-semibold">
              <?php echo htmlspecialchars($data['TahunMasuk']); ?>
            </span>
          </div>

          <div class="flex justify-between">
            <span>Alamat</span>
            <span class="text-[#2f3e5e] font-semibold">
              <?php echo htmlspecialchars($data['alamat']); ?>
            </span>
          </div>

          <div class="flex justify-between">
            <span>Provinsi</span>
            <span class="text-[#2f3e5e] font-semibold">Kepulauan Riau</span>
          </div>

          <div class="flex justify-between">
            <span>Kota</span>
            <span class="text-[#2f3e5e] font-semibold">Kota Batam</span>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>

</html>