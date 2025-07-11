<?php
session_start();
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

  <!DOCTYPE html>
  <html lang="id">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIAKAD SMK - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
      body {
        font-family: 'Inter', sans-serif;
      }

      #menuToggleBtn {
        padding: 6px 10px;
        margin-left: 0;
        margin-top: 2px;
      }
    </style>
  </head>

<body class="bg-gray-100 text-sm">

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
        <img src="s.jpg" class="w-10 h-10 rounded-full" alt="Foto Siswa" />
        <div class="ml-3">
          <p class="font-semibold"><?php echo $nama; ?></p>
          <p class="text-xs text-blue-200">Siswa</p>
        </div>
      </div>

      <nav class="flex-1 p-2 space-y-2 overflow-y-auto">
        <a href="dashboard.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800">
          <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>
        <a href="biodata.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800">
          <i class="fas fa-id-card mr-2"></i> Biodata
        </a>
        <a href="jadwal_pelajaran.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800">
          <i class="fas fa-calendar-alt mr-2"></i> Jadwal Pelajaran
        </a>
        <a href="cek_absensi.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800">
          <i class="fas fa-check-circle mr-2"></i> Cek Absensi
        </a>
        <a href="nilai.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800">
          <i class="fas fa-poll mr-2"></i> Hasil Nilai Ujian
        </a>
        <a href="cetak_pdf.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800">
          <i class="fas fa-file-pdf mr-2"></i> Cetak PDF
        </a>
        <a href="../logout.php" class="flex items-center px-3 py-2 rounded hover:bg-blue-800">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </nav>
    </aside>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-30 md:hidden"></div>

    <!-- Konten -->
    <main id="mainContent" class="flex-grow px-4 py-6 space-y-4 max-w-7xl mx-auto">
      <!-- Pengumuman -->
      <section class="bg-white rounded-md p-4 flex items-start gap-2 text-sm">
        <i class="fas fa-bullhorn text-red-500"></i>
        <div>
          <p class="font-semibold text-red-500 uppercase">Pengumuman</p>
          <ul class="list-disc list-inside text-blue-700">
            <li><a href="#" class="hover:underline">Kalender TA 2024/2025</a></li>
          </ul>
        </div>
        <button class="ml-auto text-blue-400 hover:text-blue-700">&times;</button>
      </section>

      <!-- Greeting -->
      <section class="bg-white rounded-md p-4 flex items-center gap-4">
        <img src="https://storage.googleapis.com/a1aa/image/2c640ee9-28f1-476a-a931-5c7f504a6193.jpg" class="w-12 h-12 rounded-full" alt="Avatar" />
        <div>
          <p class="font-semibold">Hi, <?php echo $nama . " ($nis)"; ?></p>
          <p>Saat ini kamu berada di semester Genap 2024/2025 (Minggu #14). <em class="text-blue-600">Tetap semangat!</em></p>
        </div>
      </section>

      <!-- Jadwal & Notifikasi -->
      <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 bg-white p-4 rounded-md">
          <h2 class="font-bold mb-3">Jadwal Hari Ini (<?php echo $hariSekarang; ?>)</h2>
          <div class="space-y-2">
            <?php if (mysqli_num_rows($jadwalHariIni) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($jadwalHariIni)): ?>
                <div>
                  <p class="font-semibold"><?php echo $row['mata_pelajaran']; ?></p>
                  <p class="text-sm text-gray-500"><?php echo $row['kelas'] . " | " . $row['jam'] . " | " . $row['ruang_kelas']; ?></p>

                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <p class="text-sm text-gray-500">Tidak ada jadwal hari ini.</p>
            <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- IPK & Tagihan -->
      <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-md">
          <h2 class="font-bold mb-2">Indeks Prestasi Kumulatif</h2>
          <canvas id="ipkChart" width="150" height="150"></canvas>
        </div>
        <div class="bg-white p-4 rounded-md">
          <h2 class="font-bold mb-2">Dokumen</h2>
          <a href="#" class="text-blue-700 flex items-center gap-1"><i class="fas fa-file-alt"></i> Panduan Penggunaan Email</a>
        </div>
      </section>
    </main>
  </div>


  <!-- Chart.js -->
  <script>
    const ctx = document.getElementById('ipkChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['IPK', 'Sisa'],
        datasets: [{
          data: [1.98, 4 - 1.98],
          backgroundColor: ['#2a6ad1', '#e2e8f0'],
          borderWidth: 0
        }]
      },
      options: {
        cutout: '70%',
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: false
          }
        }
      }
    });
  </script>

  <!-- Toggle Sidebar Script -->
  <script>
    const menuToggleBtn = document.getElementById('menuToggleBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const mainContent = document.getElementById('mainContent');

    function toggleSidebar() {
      const isOpen = !sidebar.classList.contains('-translate-x-full');
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
    }

    menuToggleBtn.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
    mainContent.addEventListener('click', () => {
      if (!sidebar.classList.contains('-translate-x-full')) toggleSidebar();
    });
  </script>

</body>

</html>