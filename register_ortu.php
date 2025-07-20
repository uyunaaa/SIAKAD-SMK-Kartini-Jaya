<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 1) first step: save siswa data & redirect to ortu form
  if (!isset($_SESSION['siswa'])) {
    $_SESSION['siswa'] = $_POST;
    header("Location: register_ortu.php");
    exit;
  }

  // 2) both siswa + ortu are now available
  $siswa = $_SESSION['siswa'];
  $ortu   = $_POST;

  // 3) connect
  $koneksi = new mysqli("localhost", "root", "", "siakad_smk_kartini_jaya");
  if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
  }

  // 4) check username – on duplicate, clear siswa and bounce back
  if (! empty($siswa['username'])) {
    $check = $koneksi->prepare("
      SELECT COUNT(*) FROM users WHERE username = ?
    ");
    $check->bind_param("s", $siswa['username']);
    $check->execute();
    $check->bind_result($count);
    $check->fetch();
    $check->close();

    if ($count > 0) {
      // duplicate: store error, clear the old siswa, redirect
      $_SESSION['error'] = 'Username sudah dipakai. Silakan pilih yang lain.';
      unset($_SESSION['siswa']);
      header("Location: register.php");
      exit;
    }
  }

  // 5) insert into users
  $password = password_hash($siswa['password'], PASSWORD_DEFAULT);
  $role     = "siswa";
  $insUser  = $koneksi->prepare("
    INSERT INTO users (username, password, role)
    VALUES (?, ?, ?)
  ");
  $insUser->bind_param("sss", $siswa['username'], $password, $role);
  $insUser->execute();
  $user_id = $koneksi->insert_id;

  // 6) insert into siswa
  $stmt = $koneksi->prepare("
    INSERT INTO siswa
      (UserID, nama_lengkap, NIS, NISN, tanggal_lahir,
       jenis_kelamin, Kelas, Jurusan, agama, no_hp, email, alamat)
    VALUES
      (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $stmt->bind_param(
    "isssssssssss",
    $user_id,
    $siswa['nama_lengkap'],
    $siswa['nis'],
    $siswa['nisn'],
    $siswa['tanggal_lahir'],
    $siswa['jenis_kelamin'],
    $siswa['kelas'],
    $siswa['jurusan'],
    $siswa['agama'],
    $siswa['no_hp'],
    $siswa['email'],
    $siswa['alamat']
  );
  $stmt->execute();

  // 7) update siswa with orang-tua data
  $upd = $koneksi->prepare("
    UPDATE siswa SET
      nama_ayah        = ?,
      nik_ayah         = ?,
      tgl_lahir_ayah   = ?,
      telp_ayah        = ?,
      email_ayah       = ?,
      pendidikan_ayah  = ?,
      penghasilan_ayah = ?,
      pekerjaan_ayah   = ?,
      nama_ibu         = ?,
      nik_ibu          = ?,
      tgl_lahir_ibu    = ?,
      telp_ibu         = ?,
      email_ibu        = ?,
      pendidikan_ibu   = ?,
      penghasilan_ibu  = ?,
      pekerjaan_ibu    = ?
    WHERE UserID = ?
  ");
  $upd->bind_param(
    "sssssssssssssssssi",
    $ortu['nama_ayah'],
    $ortu['nik_ayah'],
    $ortu['tgl_lahir_ayah'],
    $ortu['telp_ayah'],
    $ortu['email_ayah'],
    $ortu['pendidikan_ayah'],
    $ortu['penghasilan_ayah'],
    $ortu['pekerjaan_ayah'],
    $ortu['nama_ibu'],
    $ortu['nik_ibu'],
    $ortu['tgl_lahir_ibu'],
    $ortu['telp_ibu'],
    $ortu['email_ibu'],
    $ortu['pendidikan_ibu'],
    $ortu['penghasilan_ibu'],
    $ortu['pekerjaan_ibu'],
    $user_id
  );
  $upd->execute();

  // 8) done! clear session + redirect to login
  unset($_SESSION['siswa']);
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Data Orang Tua</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex min-h-screen">
  <!-- Panel Kiri (unchanged) -->
  <div class="md:w-2/5 w-full h-64 md:h-auto relative">
    <!-- … -->
  </div>

  <!-- Form Orang Tua -->
  <div class="bg-white p-8 rounded shadow-md w-full">
    <h2 class="text-xl font-semibold mb-6">Form Data Orang Tua</h2>
    <form method="post">
      <div class="grid grid-cols-2 gap-6">
        <!-- Nama Ayah -->
        <div>
          <label class="block font-semibold">Nama Ayah:</label>
          <input type="text" name="nama_ayah" required class="w-full border px-3 py-2 rounded" />
        </div>
        <!-- NIK Ayah -->
        <div>
          <label class="block font-semibold">NIK Ayah:</label>
          <input type="text" name="nik_ayah" required class="w-full border px-3 py-2 rounded" />
        </div>
        <!-- Tgl Lahir Ayah -->
        <div>
          <label class="block font-semibold">Tanggal Lahir Ayah:</label>
          <input type="date" name="tgl_lahir_ayah" required class="w-full border px-3 py-2 rounded" />
        </div>
        <!-- Telp Ayah -->
        <div>
          <label class="block font-semibold">No. Telp. Ayah:</label>
          <input type="text" name="telp_ayah" class="w-full border px-3 py-2 rounded" />
        </div>
        <!-- Email Ayah -->
        <div>
          <label class="block font-semibold">Email Ayah:</label>
          <input type="email" name="email_ayah" class="w-full border px-3 py-2 rounded" />
        </div>
        <!-- Pendidikan Ayah -->
        <div>
          <label class="block font-semibold">Pendidikan Terakhir Ayah:</label>
          <select name="pendidikan_ayah" required class="w-full border px-3 py-2 rounded">
            <option value="">-- Pilih Pendidikan --</option>
            <?php foreach (
              [
                'Tidak Sekolah',
                'Putus SD',
                'SD/Sederajat',
                'SMP/Sederajat',
                'SMA/Sederajat',
                'D1',
                'D2',
                'D3',
                'D4/S1',
                'S2',
                'S3'
              ] as $ed
            ): ?>
              <option value="<?= $ed ?>"><?= $ed ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <!-- Penghasilan Ayah -->
        <div>
          <label class="block font-semibold">Penghasilan Ayah:</label>
          <select name="penghasilan_ayah" required class="w-full border px-3 py-2 rounded">
            <option value="">-- Pilih Penghasilan --</option>
            <option value="< Rp. 500.000">
              < Rp. 500.000</option>
            <option value="Rp. 500.000 - Rp. 1.000.000">Rp. 500.000 - Rp. 1.000.000</option>
            <option value="Rp. 1.000.001 - Rp. 2.000.000">Rp. 1.000.001 - Rp. 2.000.000</option>
            <option value="Rp. 2.000.001 - Rp. 3.000.000">Rp. 2.000.001 - Rp. 3.000.000</option>
            <option value="> Rp. 3.000.000">> Rp. 3.000.000</option>
          </select>
        </div>
        <!-- Pekerjaan Ayah -->
        <div>
          <label class="block font-semibold">Pekerjaan Ayah:</label>
          <select name="pekerjaan_ayah" required class="w-full border px-3 py-2 rounded">
            <option value="">-- Pilih Pekerjaan --</option>
            <?php foreach (
              [
                'Tidak Bekerja',
                'Petani',
                'Nelayan',
                'Buruh',
                'PNS/TNI/POLRI',
                'Karyawan Swasta',
                'Wiraswasta',
                'Guru/Dosen',
                'Dokter/Bidan/Perawat',
                'Pedagang',
                'Lainnya'
              ] as $job
            ): ?>
              <option value="<?= $job ?>"><?= $job ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Data Ibu… repeat the same pattern … -->

        <div class="flex justify-between mt-8">
          <a href="register.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Sebelumnya</a>
          <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Daftar Akun</button>
        </div>
      </div>
    </form>
  </div>
</body>

</html>