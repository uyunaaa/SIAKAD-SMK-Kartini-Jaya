<?php
session_start();
include '../koneksi.php';

// Cek login admin
if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit();
}

// Cek ID jadwal
if (!isset($_GET['id'])) {
    die("ID jadwal tidak ditemukan!");
}
$id = intval($_GET['id']);

// Ambil data jadwal
$query = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    die("Data jadwal tidak ditemukan!");
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hari = mysqli_real_escape_string($koneksi, $_POST['hari']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jam = mysqli_real_escape_string($koneksi, $_POST['jam']);
    $kode_kelas = mysqli_real_escape_string($koneksi, $_POST['kode_kelas']);
    $mata_pelajaran = mysqli_real_escape_string($koneksi, $_POST['mata_pelajaran']);
    $jp = intval($_POST['jp']);
    $ruang_kelas = mysqli_real_escape_string($koneksi, $_POST['ruang_kelas']);
    $guru = mysqli_real_escape_string($koneksi, $_POST['guru']);

    $update = mysqli_query($koneksi, "UPDATE jadwal SET hari='$hari', kelas='$kelas', jam='$jam', kode_kelas='$kode_kelas', mata_pelajaran='$mata_pelajaran', jp=$jp, ruang_kelas='$ruang_kelas', guru='$guru' WHERE id='$id'");
    if ($update) {
        header('Location: data_jadwal.php?status=updated');
        exit();
    } else {
        $error = 'Gagal mengupdate jadwal: ' . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-lg mt-10">
        <h2 class="text-2xl font-bold mb-6 text-blue-800">Edit Jadwal Pelajaran</h2>
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Hari</label>
                <select name="hari" class="w-full border rounded px-3 py-2" required>
                    <option value="">Pilih Hari</option>
                    <?php
                    $hariList = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
                    foreach ($hariList as $h) {
                        $selected = ($data['hari'] == $h) ? 'selected' : '';
                        echo "<option value='$h' $selected>$h</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Kelas</label>
                <input type="text" name="kelas" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($data['kelas']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Jam</label>
                <input type="text" name="jam" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($data['jam']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Kode Kelas</label>
                <input type="text" name="kode_kelas" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($data['kode_kelas']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($data['mata_pelajaran']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">JP (Jam Pelajaran)</label>
                <input type="number" name="jp" class="w-full border rounded px-3 py-2" min="1" value="<?php echo htmlspecialchars($data['jp']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Ruang Kelas</label>
                <input type="text" name="ruang_kelas" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($data['ruang_kelas']); ?>" required>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Guru</label>
                <input type="text" name="guru" class="w-full border rounded px-3 py-2" value="<?php echo htmlspecialchars($data['guru']); ?>" required>
            </div>
            <div class="flex justify-between">
                <a href="data_jadwal.php" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Kembali</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html> 

