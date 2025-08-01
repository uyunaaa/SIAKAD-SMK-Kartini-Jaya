<?php
session_start();
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit;
}

// Ambil ID guru
if (!isset($_GET['id'])) {
    die("ID guru tidak ditemukan!");
}
$id = $_GET['id'];

// Ambil data guru
$guru = mysqli_query($koneksi, "SELECT * FROM guru WHERE id='$id'");
$data = mysqli_fetch_assoc($guru);

// Proses update
if (isset($_POST['simpan'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $NIP = $_POST['NIP'];
    $MataPelajaran = $_POST['MataPelajaran'];
    $no_hp = $_POST['no_hp'];
    $nuptk = $_POST['nuptk'];
    $tmt_mengajar = $_POST['tmt_mengajar'];
    $nik_ibu = $_POST['nik_ibu'];
    $tgl_lahir_ibu = $_POST['tgl_lahir_ibu'];
    $tgl_lahir_ayah = $_POST['tgl_lahir_ayah'];

    // Handle empty dates
    $tmt_mengajar_sql = ($tmt_mengajar == '' ? 'NULL' : "'$tmt_mengajar'");
    $tgl_lahir_ibu_sql = ($tgl_lahir_ibu == '' ? 'NULL' : "'$tgl_lahir_ibu'");
    $tgl_lahir_ayah_sql = ($tgl_lahir_ayah == '' ? 'NULL' : "'$tgl_lahir_ayah'");

    $query = "UPDATE guru SET 
        nama_lengkap='$nama_lengkap',
        NIP='$NIP',
        MataPelajaran='$MataPelajaran',
        no_hp='$no_hp',
        nuptk='$nuptk',
        tmt_mengajar=$tmt_mengajar_sql,
        nik_ibu='$nik_ibu',
        tgl_lahir_ibu=$tgl_lahir_ibu_sql,
        tgl_lahir_ayah=$tgl_lahir_ayah_sql
        WHERE id='$id'";

    $update = mysqli_query($koneksi, $query);

    if ($update) {
        echo "<script>alert('Data guru berhasil diperbarui.'); window.location='data_guru.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Guru</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Edit Data Guru</h1>
        <form method="POST">
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap'] ?? '') ?>" placeholder="Nama Lengkap" class="border p-2 w-full mb-2" required>
                <input type="text" name="NIP" value="<?= htmlspecialchars($data['NIP'] ?? '') ?>" placeholder="NIP" class="border p-2 w-full mb-2">
                <input type="text" name="MataPelajaran" value="<?= htmlspecialchars($data['MataPelajaran'] ?? '') ?>" placeholder="Mata Pelajaran" class="border p-2 w-full mb-2">
                <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp'] ?? '') ?>" placeholder="No HP" class="border p-2 w-full mb-2">
                <input type="text" name="nuptk" value="<?= htmlspecialchars($data['nuptk'] ?? '') ?>" placeholder="NUPTK" class="border p-2 w-full mb-2">
                <input type="date" name="tmt_mengajar" value="<?= htmlspecialchars($data['tmt_mengajar'] ?? '') ?>" placeholder="TMT Mengajar" class="border p-2 w-full mb-2">
            </div>
            <button type="submit" name="simpan" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html> 