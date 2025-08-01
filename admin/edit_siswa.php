<?php
session_start();
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../index.php");
    exit;
}

// Ambil ID siswa
if (!isset($_GET['id'])) {
    die("ID siswa tidak ditemukan!");
}
$id = $_GET['id'];

// Ambil data siswa
$siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id='$id'");
$data = mysqli_fetch_assoc($siswa);

// Proses update
if (isset($_POST['simpan'])) {
    $NIS = $_POST['NIS'];
    $NISN = $_POST['NISN'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $Kelas = $_POST['Kelas'];
    $Jurusan = $_POST['Jurusan'];
    $TahunMasuk = $_POST['TahunMasuk'];
    $TahunMasuk_sql = ($TahunMasuk == '' ? 'NULL' : $TahunMasuk);
    $provinsi = isset($_POST['provinsi']) ? $_POST['provinsi'] : '';
    $kota = isset($_POST['kota']) ? $_POST['kota'] : '';
    
    // Data Ayah
    $nama_ayah = $_POST['nama_ayah'];
    $nik_ayah = $_POST['nik_ayah'];
    $tgl_lahir_ayah = $_POST['tgl_lahir_ayah'];
    $telp_ayah = $_POST['telp_ayah'];
    $email_ayah = $_POST['email_ayah'];
    $pendidikan_ayah = $_POST['pendidikan_ayah'];
    $penghasilan_ayah = $_POST['penghasilan_ayah'];
    $pekerjaan_ayah = $_POST['pekerjaan_ayah'];

    // Data Ibu
    $nama_ibu = $_POST['nama_ibu'];
    $nik_ibu = $_POST['nik_ibu'];
    $tgl_lahir_ibu = $_POST['tgl_lahir_ibu'];
    $telp_ibu = $_POST['telp_ibu'];
    $email_ibu = $_POST['email_ibu'];
    $pendidikan_ibu = $_POST['pendidikan_ibu'];
    $penghasilan_ibu = $_POST['penghasilan_ibu'];
    $pekerjaan_ibu = $_POST['pekerjaan_ibu'];

    // Handle tanggal kosong
    $tanggal_lahir_sql = ($tanggal_lahir == '' ? 'NULL' : "'$tanggal_lahir'");
    $tgl_lahir_ayah_sql = ($tgl_lahir_ayah == '' ? 'NULL' : "'$tgl_lahir_ayah'");
    $tgl_lahir_ibu_sql = ($tgl_lahir_ibu == '' ? 'NULL' : "'$tgl_lahir_ibu'");

    $query = "UPDATE siswa SET 
        NIS='$NIS', NISN='$NISN', nama_lengkap='$nama_lengkap', tempat_lahir='$tempat_lahir',
        tanggal_lahir=$tanggal_lahir_sql, jenis_kelamin='$jenis_kelamin', agama='$agama',
        alamat='$alamat', no_hp='$no_hp', email='$email', Kelas='$Kelas',
        Jurusan='$Jurusan', TahunMasuk=$TahunMasuk_sql, provinsi='$provinsi', kota='$kota',
        nama_ayah='$nama_ayah', nik_ayah='$nik_ayah', tgl_lahir_ayah=$tgl_lahir_ayah_sql, 
        telp_ayah='$telp_ayah', email_ayah='$email_ayah', pendidikan_ayah='$pendidikan_ayah',
        penghasilan_ayah='$penghasilan_ayah', pekerjaan_ayah='$pekerjaan_ayah',
        nama_ibu='$nama_ibu', nik_ibu='$nik_ibu', tgl_lahir_ibu=$tgl_lahir_ibu_sql,
        telp_ibu='$telp_ibu', email_ibu='$email_ibu', pendidikan_ibu='$pendidikan_ibu',
        penghasilan_ibu='$penghasilan_ibu', pekerjaan_ibu='$pekerjaan_ibu'
        WHERE id='$id'";

    $update = mysqli_query($koneksi, $query);

    if ($update) {
        echo "<script>alert('Data siswa berhasil diperbarui.'); window.location='data_siswa.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Edit Data Siswa</h1>
        <form method="POST">
            <div class="grid grid-cols-2 gap-4">
              <input type="text" name="NIS" value="<?= $data['NIS'] ?>" placeholder="NIS" class="border p-2 w-full mb-2">
            <input type="text" name="NISN" value="<?= $data['NISN'] ?>" placeholder="NISN" class="border p-2 w-full mb-2">
            <input type="text" name="Kelas" value="<?= $data['Kelas'] ?>" placeholder="Kelas" class="border p-2 w-full mb-2">
            <input type="text" name="Jurusan" value="<?= $data['Jurusan'] ?>" placeholder="Jurusan" class="border p-2 w-full mb-2">
            <input type="number" name="TahunMasuk" value="<?= $data['TahunMasuk'] ?>" placeholder="Tahun Masuk" class="border p-2 w-full mb-2">
            <input type="text" name="nama_lengkap" value="<?= $data['nama_lengkap'] ?>" placeholder="Nama Lengkap" class="border p-2 w-full mb-2">
            <input type="text" name="tempat_lahir" value="<?= $data['tempat_lahir'] ?>" placeholder="Tempat Lahir" class="border p-2 w-full mb-2">
            <input type="date" name="tanggal_lahir" value="<?= $data['tanggal_lahir'] ?>" class="border p-2 w-full mb-2">
            <select name="jenis_kelamin" class="border p-2 w-full mb-2">
                <option <?= ($data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                <option <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
            </select>
            <input type="text" name="agama" value="<?= $data['agama'] ?>" placeholder="Agama" class="border p-2 w-full mb-2">
            <textarea name="alamat" placeholder="Alamat" class="border p-2 w-full mb-2"><?= $data['alamat'] ?></textarea>
            <input type="text" name="no_hp" value="<?= $data['no_hp'] ?>" placeholder="No HP" class="border p-2 w-full mb-2">
            <input type="email" name="email" value="<?= $data['email'] ?>" placeholder="Email" class="border p-2 w-full mb-4">
            <input type="text" name="provinsi" value="<?= isset($data['provinsi']) ? $data['provinsi'] : '' ?>" placeholder="Provinsi" class="border p-2 w-full mb-2">
            <input type="text" name="kota" value="<?= isset($data['kota']) ? $data['kota'] : '' ?>" placeholder="Kota" class="border p-2 w-full mb-2">
            </div>

            <h2 class="mt-6 font-semibold">Data Ayah</h2>
            <div class="grid grid-cols-2 gap-4 mt-2">
                <input type="text" name="nama_ayah" value="<?= $data['nama_ayah'] ?>" placeholder="Nama Ayah" class="border p-2">
                <input type="text" name="nik_ayah" value="<?= $data['nik_ayah'] ?>" placeholder="NIK Ayah" class="border p-2">
                <input type="date" name="tgl_lahir_ayah" value="<?= $data['tgl_lahir_ayah'] ?>" class="border p-2">
                <input type="text" name="telp_ayah" value="<?= $data['telp_ayah'] ?>" placeholder="Telepon Ayah" class="border p-2">
                <input type="text" name="email_ayah" value="<?= $data['email_ayah'] ?>" placeholder="Email Ayah" class="border p-2">
                <input type="text" name="pendidikan_ayah" value="<?= $data['pendidikan_ayah'] ?>" placeholder="Pendidikan Ayah" class="border p-2">
                <input type="text" name="penghasilan_ayah" value="<?= $data['penghasilan_ayah'] ?>" placeholder="Penghasilan Ayah" class="border p-2">
                <input type="text" name="pekerjaan_ayah" value="<?= $data['pekerjaan_ayah'] ?>" placeholder="Pekerjaan Ayah" class="border p-2">
            </div>

            <h2 class="mt-6 font-semibold">Data Ibu</h2>
            <div class="grid grid-cols-2 gap-4 mt-2">
                <input type="text" name="nama_ibu" value="<?= $data['nama_ibu'] ?>" placeholder="Nama Ibu" class="border p-2">
                <input type="text" name="nik_ibu" value="<?= $data['nik_ibu'] ?>" placeholder="NIK Ibu" class="border p-2">
                <input type="date" name="tgl_lahir_ibu" value="<?= $data['tgl_lahir_ibu'] ?>" class="border p-2">
                <input type="text" name="telp_ibu" value="<?= $data['telp_ibu'] ?>" placeholder="Telepon Ibu" class="border p-2">
                <input type="text" name="email_ibu" value="<?= $data['email_ibu'] ?>" placeholder="Email Ibu" class="border p-2">
                <input type="text" name="pendidikan_ibu" value="<?= $data['pendidikan_ibu'] ?>" placeholder="Pendidikan Ibu" class="border p-2">
                <input type="text" name="penghasilan_ibu" value="<?= $data['penghasilan_ibu'] ?>" placeholder="Penghasilan Ibu" class="border p-2">
                <input type="text" name="pekerjaan_ibu" value="<?= $data['pekerjaan_ibu'] ?>" placeholder="Pekerjaan Ibu" class="border p-2">
            </div>

            <div class="mt-6">
                <button type="submit" name="simpan" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                <a href="data_siswa.php" class="ml-2 text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
