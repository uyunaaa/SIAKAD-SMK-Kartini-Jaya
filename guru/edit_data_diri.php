<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Biodata Guru</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-[#f1f5f9] min-h-screen flex flex-col">
  <main class="flex-1 p-4 md:p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-[#2f3e5e]">Edit Biodata Guru</h1>
      <a href="#" class="text-blue-600 text-sm font-semibold hover:underline">
        <i class="fas fa-arrow-left mr-1"></i>
        Kembali
      </a>
    </div>

    <form class="bg-white rounded-xl shadow p-6 space-y-6 text-sm text-gray-700">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1 font-medium">NUPTK</label>
          <input type="text" class="w-full rounded border border-gray-300 px-3 py-2" placeholder="Masukkan NUPTK" value="">
        </div>
        <div>
          <label class="block mb-1 font-medium">TMT Mengajar</label>
          <input type="date" class="w-full rounded border border-gray-300 px-3 py-2" value="">
        </div>
        <div>
          <label class="block mb-1 font-medium">Pendidikan Terakhir</label>
          <input type="text" class="w-full rounded border border-gray-300 px-3 py-2" placeholder="S1, S2, dll" value="">
        </div>
        <div>
          <label class="block mb-1 font-medium">Golongan Darah</label>
          <input type="text" class="w-full rounded border border-gray-300 px-3 py-2" placeholder="A, B, O, AB" value="">
        </div>
      </div>

      <hr class="my-6">
      <h2 class="text-lg font-semibold text-gray-800">Informasi Orang Tua</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Data Ayah -->
        <div class="space-y-4">
          <h3 class="text-sm font-semibold text-slate-700 border-b pb-1">Data Ayah</h3>
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="text" placeholder="Nama Ayah">
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="text" placeholder="NIK Ayah">
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="date" placeholder="Tanggal Lahir Ayah">
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="text" placeholder="Pendidikan Terakhir Ayah">
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="text" placeholder="Pekerjaan Ayah">
        </div>

        <!-- Data Ibu -->
        <div class="space-y-4">
          <h3 class="text-sm font-semibold text-slate-700 border-b pb-1">Data Ibu</h3>
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="text" placeholder="Nama Ibu Kandung">
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="text" placeholder="NIK Ibu">
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="date" placeholder="Tanggal Lahir Ibu">
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="text" placeholder="Pendidikan Terakhir Ibu">
          <input class="w-full border border-gray-300 px-3 py-2 rounded" type="text" placeholder="Pekerjaan Ibu">
        </div>
      </div>

      <form action="kelola_data_diri.php" method="post">
  <!-- inputan biodata guru di sini -->
  
<div class="flex justify-end gap-2 mt-4">
  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 text-sm">
    <i class="fas fa-save mr-1"></i> Simpan
  </button>

  <!-- Tombol Batal -->
  <a href="kelola_data_diri.php" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">
    Batal
  </a>
</div>

      </div>
    </form>
  </main>
</body>
</html>
