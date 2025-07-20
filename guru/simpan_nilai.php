<?php
session_start();
include '../koneksi.php';

// Cek apakah guru sudah login
if (!isset($_SESSION['UserID'])) {
  header("Location: ../index.php");
  exit;
}

$guru_id = $_SESSION['UserID'];

// Ambil daftar siswa untuk dropdown
$siswa = mysqli_query($koneksi, "SELECT id, nama_lengkap FROM siswa ORDER BY nama_lengkap");

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $siswa_id = intval($_POST['siswa_id']);
  $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);
  $semester = mysqli_real_escape_string($koneksi, $_POST['semester']);
  $tugas = intval($_POST['tugas']);
  $uts = intval($_POST['uts']);
  $uas = intval($_POST['uas']);

  // Masukkan data ke tabel nilai
  $insert = mysqli_query($koneksi, "INSERT INTO nilai (siswa_id, guru_id, mapel, semester, tugas, uts, uas) VALUES ($siswa_id, $guru_id, '$mapel', '$semester', $tugas, $uts, $uas)");

  if ($insert) {
    $success = "Nilai berhasil ditambahkan.";
  } else {
    $error = "Gagal menyimpan nilai: " . mysqli_error($koneksi);
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Input Nilai</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Form Input Nilai</h2>

    <?php if (isset($success)) : ?>
      <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)) : ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-4">
        <label class="block mb-1">Nama Siswa</label>
        <select name="siswa_id" required class="w-full border rounded px-3 py-2">
          <option value="">-- Pilih Siswa --</option>
          <?php while ($row = mysqli_fetch_assoc($siswa)) : ?>
            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nama_lengkap']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-4">
        <label class="block mb-1">Mata Pelajaran</label>
        <input type="text" name="mapel" required class="w-full border rounded px-3 py-2">
      </div>

      <div class="mb-4">
        <label class="block mb-1">Semester</label>
        <input type="text" name="semester" required class="w-full border rounded px-3 py-2">
      </div>

      <div class="mb-4">
        <label class="block mb-1">Nilai Tugas</label>
        <input type="number" name="tugas" required class="w-full border rounded px-3 py-2">
      </div>

      <div class="mb-4">
        <label class="block mb-1">Nilai UTS</label>
        <input type="number" name="uts" required class="w-full border rounded px-3 py-2">
      </div>

      <div class="mb-4">
        <label class="block mb-1">Nilai UAS</label>
        <input type="number" name="uas" required class="w-full border rounded px-3 py-2">
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Nilai</button>
    </form>
  </div>
</body>
</html>
