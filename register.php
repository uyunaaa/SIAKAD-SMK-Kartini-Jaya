<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Form Data Siswa</title>
  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="flex flex-col md:flex-row min-h-screen">
  <!-- Panel Kiri -->
  <div class="md:w-2/5 w-full h-64 md:h-auto relative">
    <img src="https://storage.googleapis.com/a1aa/image/c973f1b2-64f7-44fe-10d0-3e54091e6a3e.jpg"
         alt="Gedung SMK"
         class="absolute inset-0 w-full h-full object-cover opacity-60 -z-10" />
    <div class="absolute inset-0 bg-blue-800 bg-opacity-70 z-0"></div>
    <div class="relative z-10 flex flex-col justify-center items-start px-6 py-8 h-full text-white">
      <h1 class="text-2xl font-bold mb-2">SIAKAD SMK Kartini Jaya</h1>
      <p class="mb-4 text-sm">Sistem Informasi Akademik</p>
      <ul class="space-y-2 text-xs">
        <li>🗓 Kalender Akademik 2024/2025</li>
        <li>📢 Pengumuman Ujian Semester</li>
        <li>📘 Daftar Ulang Tahun Ajaran Baru</li>
      </ul>
    </div>

  </div>

  <!-- Form Kanan -->
  <div class="w-full md:w-3/5 bg-white p-8">
    <h2 class="text-xl font-semibold mb-6">Form Data Siswa</h2>

    <form action="register_ortu.php" method="post" enctype="multipart/form-data">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- NIS -->
        <div>
          <label class="block font-semibold mb-1" for="nis">NIS:</label>
          <input type="text" name="nis" id="nis" required class="w-full border px-3 py-2 rounded" />
        </div>

        <!-- NISN -->
        <div>
          <label class="block font-semibold mb-1" for="nisn">NISN:</label>
          <input type="text" name="nisn" id="nisn" required class="w-full border px-3 py-2 rounded" />
        </div>

        <!-- Nama Lengkap -->
        <div>
          <label class="block font-semibold mb-1" for="nama_lengkap">Nama Lengkap:</label>
          <input type="text" name="nama_lengkap" id="nama_lengkap" required class="w-full border px-3 py-2 rounded" />
        </div>

        <div>
          <label class="block font-semibold mb-1" for="username">Username:</label>
          <input type="text" name="username" id="username" required 
         class="w-full border px-3 py-2 rounded" />
        </div>

        <!-- Tempat Lahir -->
        <div>
          <label class="block font-semibold mb-1" for="tempat_lahir">Tempat Lahir:</label>
          <input type="text" name="tempat_lahir" id="tempat_lahir" required class="w-full border px-3 py-2 rounded" />
        </div>

        <!-- Tanggal Lahir -->
        <div>
          <label class="block font-semibold mb-1" for="tanggal_lahir">Tanggal Lahir:</label>
          <input type="date" name="tanggal_lahir" id="tanggal_lahir" required class="w-full border px-3 py-2 rounded" />
        </div>

        <!-- Jenis Kelamin -->
        <div>
          <label class="block font-semibold mb-1" for="jenis_kelamin">Jenis Kelamin:</label>
          <select name="jenis_kelamin" id="jenis_kelamin" required class="w-full border px-3 py-2 rounded">
            <option value="">Pilih</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
          </select>
        </div>

        <!-- Kelas -->
        <div>
          <label class="block font-semibold mb-1" for="kelas">Kelas:</label>
          <select name="kelas" id="kelas" required class="w-full border px-3 py-2 rounded">
            <option value="">Pilih</option>
            <option value="X">X</option>
            <option value="XI">XI</option>
            <option value="XII">XII</option>
          </select>
        </div>

        <!-- Jurusan -->
        <div>
          <label class="block font-semibold mb-1" for="jurusan">Jurusan:</label>
          <select name="jurusan" id="jurusan" required class="w-full border px-3 py-2 rounded">
            <option value="">Pilih</option>
            <option value="AKUNTANSI">AKUNTANSI</option>
            <option value="TEKNIK OTOMOTIF">TEKNIK OTOMOTIF</option>
            <option value="TEKNIK PERMESINAN">TEKNIK PERMESINAN</option>
            <option value="LAYANAN KESEHATAN">LAYANAN KESEHATAN</option>
            <option value="DESAIN KOMUNIKASI VISUAL">DESAIN KOMUNIKASI VISUAL</option>
          </select>
        </div>

        <!-- Agama -->
        <div>
          <label class="block font-semibold mb-1" for="agama">Agama:</label>
          <select name="agama" id="agama" required class="w-full border px-3 py-2 rounded">
            <option value="">Pilih</option>
            <option value="Islam">Islam</option>
            <option value="Kristen">Kristen</option>
            <option value="Katolik">Katolik</option>
            <option value="Hindu">Hindu</option>
            <option value="Budha">Budha</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>

        <!-- No HP -->
        <div>
          <label class="block font-semibold mb-1" for="no_hp">No HP:</label>
          <input type="text" name="no_hp" id="no_hp" required class="w-full border px-3 py-2 rounded" />
        </div>

        <!-- Email -->
        <div>
          <label class="block font-semibold mb-1" for="email">Email:</label>
          <input type="email" name="email" id="email" required class="w-full border px-3 py-2 rounded" />
        </div>

         <!-- Alamat -->
        <div>
          <label class="block font-semibold mb-1" for="alamat">Alamat:</label>
          <input type="text" name="alamat" id="alamat" required 
         class="w-full border px-3 py-2 rounded" />
        </div>

        <!-- Foto -->
        <div>
          <label class="block font-semibold mb-1" for="foto">Foto Siswa:</label>
          <input type="file" name="foto" id="foto" accept="image/*" class="w-full border px-3 py-2 rounded" />
        </div>

        <!-- Password -->
        <div class="relative">
          <label class="block font-semibold mb-1" for="password">Password:</label>
          <input type="password" name="password" id="password" required class="w-full border px-3 py-2 rounded pr-10" />
          <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-8 text-sm text-gray-500">👁️</button>
        </div>

        <!-- Konfirmasi Password -->
        <div class="relative">
          <label class="block font-semibold mb-1" for="konfirmasi_password">Konfirmasi Password:</label>
          <input type="password" name="konfirmasi_password" id="konfirmasi_password" required class="w-full border px-3 py-2 rounded pr-10" />
          <button type="button" onclick="togglePassword('konfirmasi_password')" class="absolute right-3 top-8 text-sm text-gray-500">👁️</button>
        </div>
        
<div class="mt-6 text-center">
  <button type="submit"
          class="w-fit bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded mx-auto block">
    Selanjutnya
  </button>

  <p class="mt-4 text-sm text-gray-600">
    Sudah punya akun?
    <a href="index.php" class="text-blue-600 hover:underline font-semibold">Login di sini</a>
  </p>
</div>



  <!-- Script untuk toggle password -->
  <script>
    function togglePassword(id) {
      const input = document.getElementById(id);
      input.type = input.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
