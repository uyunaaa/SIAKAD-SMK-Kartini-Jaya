<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama_lengkap'];
    $nuptk = $_POST['nuptk'];
    $tmt = $_POST['tmt_mengajar'];
    $nik_ibu = $_POST['nik_ibu'];
    $tgl_lahir_ibu = $_POST['tgl_lahir_ibu'];
    $tgl_lahir_ayah = $_POST['tgl_lahir_ayah'];

    $query = "UPDATE guru SET 
        nama_lengkap = '$nama',
        nuptk = '$nuptk',
        tmt_mengajar = '$tmt',
        nik_ibu = '$nik_ibu',
        tgl_lahir_ibu = '$tgl_lahir_ibu',
        tgl_lahir_ayah = '$tgl_lahir_ayah'
        WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        // ✅ Redirect ke halaman kelola data diri
        header("Location: kelola_data_diri.php");
        exit();
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
        // atau bisa redirect ke halaman error
    }
}
?>
