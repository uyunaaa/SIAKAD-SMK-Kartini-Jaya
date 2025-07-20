<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1) Required siswa fields for step 1
$required = [
  'nama_lengkap','nis','nisn','username','password',
  'tanggal_lahir','jenis_kelamin','kelas','jurusan',
  'agama','no_hp','email','alamat'
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  // masuk via GET: cek session siswa
  $ok = ! empty($_SESSION['siswa']);
  if ($ok) {
    foreach ($required as $f) {
      if (empty($_SESSION['siswa'][$f])) {
        $ok = false;
        break;
      }
    }
  }
  if (! $ok) {
    $_SESSION['error'] = 'Silakan lengkapi data siswa terlebih dahulu.';
    header('Location: register.php');
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // ——— STEP 1: insert siswa & users ———
  if (empty($_SESSION['siswa_inserted'])) {
    // validasi
    foreach ($required as $f) {
      if (empty($_POST[$f])) {
        $_SESSION['error'] = 'Data siswa tidak lengkap. Silakan lengkapi data siswa.';
        header('Location: register.php');
        exit;
      }
    }
    $_SESSION['siswa'] = $_POST;
    $s = $_SESSION['siswa'];

    $db = new mysqli('localhost','root','','siakad_smk_kartini_jaya');
    if ($db->connect_error) die('Koneksi gagal: '.$db->connect_error);

    // cek username unik
    $chk = $db->prepare('SELECT COUNT(*) FROM users WHERE Username=?');
    $chk->bind_param('s', $s['username']);
    $chk->execute();
    $chk->bind_result($cnt);
    $chk->fetch();
    $chk->close();
    if ($cnt>0) {
      $_SESSION['error'] = 'Username sudah dipakai. Silakan pilih yang lain.';
      unset($_SESSION['siswa']);
      header('Location: register.php');
      exit;
    }

    // insert users
    $pw = password_hash($s['password'], PASSWORD_DEFAULT);
    $insU = $db->prepare(
      'INSERT INTO users
         (Nama_Lengkap, Username, Email, Password, Role)
       VALUES (?,?,?,?,?)'
    );
    $role = 'siswa';
    $insU->bind_param(
      'sssss',
      $s['nama_lengkap'],
      $s['username'],
      $s['email'],
      $pw,
      $role
    );
    $insU->execute();
    $user_id = $db->insert_id;

    // insert siswa
    $esc    = fn($v)=> $db->real_escape_string($v);
    $nullOr = fn($v)=> ($v===''||$v===null) ? 'NULL' : "'$v'";
    $tm   = $nullOr($s['tahun_masuk'] ?? '');
    $prov = $nullOr($esc($s['provinsi'] ?? ''));
    $kot  = $nullOr($esc($s['kota']    ?? ''));
    $foto = $nullOr($esc($s['foto']    ?? ''));

    $sql = "
      INSERT INTO siswa (
        UserID, nama_lengkap, NIS, NISN, tempat_lahir,
        tanggal_lahir, jenis_kelamin, Kelas, Jurusan,
        TahunMasuk, agama, alamat, no_hp, email,
        provinsi, kota, foto
      ) VALUES (
        {$user_id},
        '{$esc($s['nama_lengkap'])}',
        '{$esc($s['nis'])}',
        '{$esc($s['nisn'])}',
        '{$esc($s['tempat_lahir'])}',
        '{$esc($s['tanggal_lahir'])}',
        '{$esc($s['jenis_kelamin'])}',
        '{$esc($s['kelas'])}',
        '{$esc($s['jurusan'])}',
        {$tm},
        '{$esc($s['agama'])}',
        '{$esc($s['alamat'])}',
        '{$esc($s['no_hp'])}',
        '{$esc($s['email'])}',
        {$prov},
        {$kot},
        {$foto}
      )
    ";
    $db->query($sql);

    $_SESSION['user_id']        = $user_id;
    $_SESSION['siswa_inserted'] = true;
    header('Location: register_ortu.php');
    exit;
  }

  // ——— STEP 2: update data orang‑tua ———
  // 1) ekstrak POST ke variabel
  $nama_ayah        = $_POST['nama_ayah'];
  $nik_ayah         = $_POST['nik_ayah'];
  $tgl_lahir_ayah   = $_POST['tgl_lahir_ayah'];
  $telp_ayah        = $_POST['telp_ayah'];
  $email_ayah       = $_POST['email_ayah'];
  $pendidikan_ayah  = $_POST['pendidikan_ayah'];
  $penghasilan_ayah = $_POST['penghasilan_ayah'];
  $pekerjaan_ayah   = $_POST['pekerjaan_ayah'];

  $nama_ibu         = $_POST['nama_ibu'];
  $nik_ibu          = $_POST['nik_ibu'];
  $tgl_lahir_ibu    = $_POST['tgl_lahir_ibu'];
  $telp_ibu         = $_POST['telp_ibu'];
  $email_ibu        = $_POST['email_ibu'];
  $pendidikan_ibu   = $_POST['pendidikan_ibu'];
  $penghasilan_ibu  = $_POST['penghasilan_ibu'];
  $pekerjaan_ibu    = $_POST['pekerjaan_ibu'];

  $user_id          = (int) $_SESSION['user_id'];

  // 2) prepare & bind hanya variabel di atas
  $upd = $db->prepare(
    'UPDATE siswa SET
       nama_ayah=?, nik_ayah=?, tgl_lahir_ayah=?, telp_ayah=?, email_ayah=?,
       pendidikan_ayah=?, penghasilan_ayah=?, pekerjaan_ayah=?,
       nama_ibu=?, nik_ibu=?, tgl_lahir_ibu=?, telp_ibu=?, email_ibu=?,
       pendidikan_ibu=?, penghasilan_ibu=?, pekerjaan_ibu=?
     WHERE UserID=?'
  );
  if (! $upd) {
    die("Prepare gagal: ".$db->error);
  }

  $upd->bind_param(
    'sssssssssssssssssi',
    $nama_ayah,
    $nik_ayah,
    $tgl_lahir_ayah,
    $telp_ayah,
    $email_ayah,
    $pendidikan_ayah,
    $penghasilan_ayah,
    $pekerjaan_ayah,
    $nama_ibu,
    $nik_ibu,
    $tgl_lahir_ibu,
    $telp_ibu,
    $email_ibu,
    $pendidikan_ibu,
    $penghasilan_ibu,
    $pekerjaan_ibu,
    $user_id
  );
  if (! $upd->execute()) {
    die("Update gagal: ".$upd->error);
  }

  // sukses → redirect
  unset($_SESSION['siswa'], $_SESSION['user_id'], $_SESSION['siswa_inserted']);
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Form Data Orang Tua</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex min-h-screen">
  <!-- left panel (same as your register.php) -->
  <div class="md:w-2/5 w-full h-64 md:h-auto relative">
    <!-- … -->
  </div>

  <!-- ORTU FORM -->
  <div class="bg-white p-8 w-full">
    <h2 class="text-xl font-semibold mb-6">Form Data Orang Tua</h2>
    <form method="post">
      <div class="grid grid-cols-2 gap-6">
        <!-- Ayah -->
        <div><label>Nama Ayah:</label><input name="nama_ayah" required class="w-full border p-2 rounded" /></div>
        <div><label>NIK Ayah:</label><input name="nik_ayah" required class="w-full border p-2 rounded" /></div>
        <div><label>Tgl Lahir Ayah:</label><input type="date" name="tgl_lahir_ayah" required class="w-full border p-2 rounded" /></div>
        <div><label>Telp Ayah:</label><input name="telp_ayah" class="w-full border p-2 rounded" /></div>
        <div><label>Email Ayah:</label><input type="email" name="email_ayah" class="w-full border p-2 rounded" /></div>
        <div><label>Pendidikan Ayah:</label>
          <select name="pendidikan_ayah" required class="w-full border p-2 rounded">
            <?php foreach (["Tidak\u{00A0}Sekolah", "Putus\u{00A0}SD", "SD\u{00A0}/\u{00A0}Sederajat", "SMP\u{00A0}/\u{00A0}Sederajat", "SMA\u{00A0}/\u{00A0}Sederajat", 'D1', 'D2', 'D3', 'D4/S1', 'S2', 'S3'] as $ed): ?>
              <option><?= $ed ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div><label>Penghasilan Ayah:</label>
          <select name="penghasilan_ayah" required class="w-full border p-2 rounded">
            <option>
              < Rp. 500.000</option>
            <option>Rp. 500.000 – Rp. 1.000.000</option>
            <option>Rp. 1.000.001 – Rp. 2.000.000</option>
            <option>Rp. 2.000.001 – Rp. 3.000.000</option>
            <option>> Rp. 3.000.000</option>
          </select>
        </div>
        <div><label>Pekerjaan Ayah:</label>
          <select name="pekerjaan_ayah" required class="w-full border p-2 rounded">
            <?php foreach (["Tidak\u{00A0}Bekerja", 'Petani', 'Nelayan', 'Buruh', 'PNS/TNI/POLRI', "Karyawan\u{00A0}Swasta", 'Wiraswasta', 'Guru/Dosen', 'Dokter/Bidan/Perawat', 'Pedagang', 'Lainnya'] as $job): ?>
              <option><?= $job ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Ibu -->
        <div><label>Nama Ibu:</label><input name="nama_ibu" required class="w-full border p-2 rounded" /></div>
        <div><label>NIK Ibu:</label><input name="nik_ibu" required class="w-full border p-2 rounded" /></div>
        <div><label>Tgl Lahir Ibu:</label><input type="date" name="tgl_lahir_ibu" required class="w-full border p-2 rounded" /></div>
        <div><label>Telp Ibu:</label><input name="telp_ibu" class="w-full border p-2 rounded" /></div>
        <div><label>Email Ibu:</label><input type="email" name="email_ibu" class="w-full border p-2 rounded" /></div>
        <div><label>Pendidikan Ibu:</label>
          <select name="pendidikan_ibu" required class="w-full border p-2 rounded">
            <?php foreach (["Tidak\u{00A0}Sekolah", "Putus\u{00A0}SD", "SD\u{00A0}/\u{00A0}Sederajat", "SMP\u{00A0}/\u{00A0}Sederajat", "SMA\u{00A0}/\u{00A0}Sederajat", 'D1', 'D2', 'D3', 'D4/S1', 'S2', 'S3'] as $ed): ?>
              <option><?= $ed ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div><label>Penghasilan Ibu:</label>
          <select name="penghasilan_ibu" required class="w-full border p-2 rounded">
            <option>
              < Rp. 500.000</option>
            <option>Rp. 500.000 – Rp. 1.000.000</option>
            <option>Rp. 1.000.001 – Rp. 2.000.000</option>
            <option>Rp. 2.000.001 – Rp. 3.000.000</option>
            <option>> Rp. 3.000.000</option>
          </select>
        </div>
        <div><label>Pekerjaan Ibu:</label>
          <select name="pekerjaan_ibu" required class="w-full border p-2 rounded">
            <?php foreach (["Tidak\u{00A0}Bekerja", 'Petani', 'Nelayan', 'Buruh', 'PNS/TNI/POLRI', "Karyawan\u{00A0}Swasta", 'Wiraswasta', 'Guru/Dosen', 'Dokter/Bidan/Perawat', 'Pedagang', "Ibu\u{00A0}Rumah\u{00A0}Tangga", 'Lainnya'] as $job): ?>
              <option><?= $job ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-span-2 flex justify-between mt-6">
          <a href="register.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Sebelumnya</a>
          <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Daftar Akun</button>
        </div>
      </div>
    </form>
  </div>
</body>

</html>