<?php
session_start();
include '../koneksi.php';

// Cek login admin
if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jam = mysqli_real_escape_string($koneksi, $_POST['jam']);
    $kode_kelas = mysqli_real_escape_string($koneksi, $_POST['kode_kelas']);
    $mata_pelajaran = mysqli_real_escape_string($koneksi, $_POST['mata_pelajaran']);
    $jp = intval($_POST['jp']);
    $ruang_kelas = mysqli_real_escape_string($koneksi, $_POST['ruang_kelas']);
    $guru = mysqli_real_escape_string($koneksi, $_POST['guru']);

    $query = "INSERT INTO jadwal (hari, kelas, jam, kode_kelas, mata_pelajaran, jp, ruang_kelas, guru) VALUES ('$hari', '$kelas', '$jam', '$kode_kelas', '$mata_pelajaran', $jp, '$ruang_kelas', '$guru')";
    if (mysqli_query($koneksi, $query)) {
        header('Location: data_jadwal.php?status=success');
        exit();
    } else {
        $error = 'Gagal menambah jadwal: ' . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-lg mt-10">
        <h2 class="text-2xl font-bold mb-6 text-blue-800">Tambah Jadwal Pelajaran</h2>
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Hari</label>
                <select name="hari" class="w-full border rounded px-3 py-2" required>
                    <option value="">Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
            </div>
          <div class="mb-4">
    <label class="block mb-1 font-semibold">Kelas</label>
    <select name="kelas" class="w-full border rounded px-3 py-2" required>
        <option value="">-- Pilih Kelas --</option>
        <option value="X">X</option>
        <option value="XI">XI</option>
        <option value="XII">XII</option>
    </select>
</div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Jam</label>
                <input type="text" name="jam" class="w-full border rounded px-3 py-2" placeholder="Contoh: 07:30 - 09:00" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Kode Kelas</label>
                <input type="text" name="kode_kelas" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">JP (Jam Pelajaran)</label>
                <input type="number" name="jp" class="w-full border rounded px-3 py-2" min="1" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Ruang Kelas</label>
                <input type="text" name="ruang_kelas" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Guru</label>
                <input type="text" name="guru" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="flex justify-between">
                <a href="data_jadwal.php" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Kembali</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html> 