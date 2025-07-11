<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrasi SIAKAD SMK Kartini Jaya</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row">

  <!-- Panel Kiri -->
  <div class="hidden md:flex flex-col w-1/3 bg-blue-700 text-white relative">
    <div class="p-8 z-10">
      <h1 class="text-3xl font-bold mb-2">SIAKAD</h1>
      <h2 class="text-lg mb-6">SMK Kartini Jaya</h2>
      <h3 class="font-semibold text-sm mb-2">Informasi Pendaftaran</h3>
      <p class="text-sm">Tahun Ajaran 2025/2026</p>
      <p class="text-xs italic mb-1">Gelombang 3 s.d. 31 Juli 2025</p>
      <p class="text-sm mb-1">Biaya Registrasi: <span class="font-bold">Rp 0</span></p>
      <p class="text-sm mb-6">Uang Pangkal: <span class="font-bold">Rp 5.650.000</span></p>
      <p class="text-xs leading-tight">Informasi selengkapnya, <span class="underline cursor-pointer">klik di sini</span></p>
    </div>
    <img src="https://storage.googleapis.com/a1aa/image/2f9c89b9-fa72-4fb9-e43e-7415e6ef2b4c.jpg"
         alt="Gedung sekolah"
         class="absolute inset-0 w-full h-full object-cover opacity-20" />
  </div>

  <!-- Panel Kanan -->
  <div class="flex-1 flex justify-center items-start pt-20 px-6 md:px-20">
    <div class="w-full max-w-md">
      <h1 class="text-xl font-bold mb-6">Registrasi Siswa Baru</h1>

      <!-- Tab Login/Register -->
      <div class="flex space-x-6 mb-6 border-b border-gray-300">
        <a href="index.php" class="text-xs text-gray-600 pb-1 hover:text-blue-600">Login</a>
        <span class="text-xs text-black font-semibold border-b-2 border-blue-500 pb-1">Register</span>
      </div>

      <!-- Form Registrasi -->
      <form action="register_proses.php" method="POST" class="space-y-4">

        <!-- Nama Lengkap -->
        <input name="nama" type="text" placeholder="Nama Lengkap" required
               class="w-full border border-blue-400 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" />

        <!-- Email -->
        <input name="email" type="email" placeholder="Email Aktif" required
               class="w-full bg-gray-100 rounded px-4 py-2 text-sm border border-transparent focus:outline-none focus:ring-1 focus:ring-blue-500" />

        <!-- No HP -->
        <div class="flex items-center bg-gray-100 rounded border border-transparent focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
          <span class="text-xs text-gray-700 px-3 select-none">+62</span>
          <input name="no_hp" type="tel" placeholder="Nomor WhatsApp" required
                 class="flex-1 bg-transparent text-sm py-2 pr-4 focus:outline-none" />
        </div>

        <!-- NIS -->
        <input name="nis" type="text" placeholder="NIS" required
               class="w-full border border-blue-400 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" />

        <!-- NISN -->
        <input name="nisn" type="text" placeholder="NISN" required
               class="w-full border border-blue-400 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" />

        <!-- Kelas -->
        <input name="kelas" type="text" placeholder="Kelas (contoh: X)" required
               class="w-full border border-blue-400 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" />

        <!-- Jurusan -->
        <input name="jurusan" type="text" placeholder="Jurusan (contoh: RPL / DKV)" required
               class="w-full border border-blue-400 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" />

        <!-- Tahun Masuk -->
        <input name="tahunmasuk" type="number" placeholder="Tahun Masuk (contoh: 2025)" required
               class="w-full border border-blue-400 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" />

        <!-- Password -->
        <div class="relative">
          <input name="password" type="password" placeholder="Password" required
                 class="w-full bg-gray-100 rounded px-4 py-2 text-sm border border-transparent focus:outline-none focus:ring-1 focus:ring-blue-500" />
          <button type="button" class="absolute inset-y-0 right-3 flex items-center text-gray-500">
            <i class="fas fa-eye"></i>
          </button>
        </div>

        <!-- Konfirmasi Password -->
        <div class="relative">
          <input name="konfirmasi" type="password" placeholder="Konfirmasi Password" required
                 class="w-full bg-gray-100 rounded px-4 py-2 text-sm border border-transparent focus:outline-none focus:ring-1 focus:ring-blue-500" />
          <button type="button" class="absolute inset-y-0 right-3 flex items-center text-gray-500">
            <i class="fas fa-eye"></i>
          </button>
        </div>

        <!-- Tombol Submit -->
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md text-sm">
          Daftar Akun
        </button>
      </form>

      <!-- Footer Link -->
      <p class="mt-6 text-xs text-gray-700">
        Sudah punya akun?
        <a href="index.php" class="text-blue-600 font-semibold hover:underline">Login di sini</a><br />
        atau <a href="forgot.php" class="text-blue-600 font-semibold hover:underline">Lupa kata sandi</a>
      </p>
    </div>
  </div>
</body>
</html>
