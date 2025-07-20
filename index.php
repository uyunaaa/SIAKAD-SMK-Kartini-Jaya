<?php
// ─────────────────────────────────────────────────────────────────────
// index.php
// ─────────────────────────────────────────────────────────────────────
session_start();
require_once 'koneksi.php';

// 1) Jika sudah login, langsung kirim ke dashboard masing-masing
if (!empty($_SESSION['login']) && !empty($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: admin/dashboard.php');
            break;
        case 'guru':
            header('Location: guru/dashboard.php');
            break;
        case 'siswa':
            header('Location: siswa/dashboard.php');
            break;
    }
    exit;
}

// 2) Proses login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $koneksi->real_escape_string(trim($_POST['username']));
    $password = $_POST['password'];

    $sql = "SELECT Id AS id, Username, Password, Role, Nama_Lengkap 
            FROM users 
            WHERE Username = '$username' 
            LIMIT 1";
    $result = $koneksi->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        if (password_verify($password, $row['Password'])) {
            session_regenerate_id(true);

            $_SESSION['login']    = true;
            $_SESSION['UserID']   = (int)$row['id'];
            $_SESSION['username'] = $row['Username'];
            $_SESSION['role']     = $row['Role'];
            $_SESSION['nama']     = $row['Nama_Lengkap'];

            header("Location: {$_SESSION['role']}/dashboard.php");
            exit;
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Username tidak ditemukan!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login SIAKAD SMK Kartini Jaya</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row">

  <!-- Notifikasi error -->
  <?php if ($error): ?>
    <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded shadow-lg z-50">
      <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>

  <!-- Sidebar (background) -->
  <div class="md:w-2/5 w-full h-64 md:h-auto relative">
    <img src="https://storage.googleapis.com/a1aa/image/c973f1b2-64f7-44fe-10d0-3e54091e6a3e.jpg"
         alt="Gedung SMK"
         class="absolute inset-0 w-full h-full object-cover opacity-60 -z-10"/>
    <div class="absolute inset-0 bg-blue-800 bg-opacity-70 z-0"></div>
    <div class="relative z-10 flex flex-col justify-center items-start px-6 py-8 h-full text-white">
      <h1 class="text-2xl font-bold mb-2">SIAKAD SMK Kartini Jaya</h1>
      <p class="mb-4 text-sm">Sistem Informasi Akademik</p>
      <ul class="space-y-2 text-xs">
        <li>📅 Kalender Akademik 2024/2025</li>
        <li>📢 Pengumuman Ujian Semester</li>
        <li>📘 Daftar Ulang Tahun Ajaran Baru</li>
      </ul>
    </div>
  </div>

  <!-- Form Login -->
  <div class="md:w-3/5 w-full flex items-center justify-center px-6 py-12 bg-white">
    <form method="post" action="index.php"
          class="w-full max-w-sm bg-white rounded-lg shadow-md p-6">
      <h1 class="text-xl font-bold mb-6 text-black">Login</h1>

      <div class="mb-4">
        <input type="text" name="username" placeholder="Username" required
               class="w-full border border-gray-300 rounded px-4 py-2 
                      focus:outline-none focus:ring-2 focus:ring-blue-500"/>
      </div>

      <div class="mb-4">
        <input type="password" name="password" placeholder="Password" required
               class="w-full border border-gray-300 rounded px-4 py-2 
                      focus:outline-none focus:ring-2 focus:ring-blue-500"/>
      </div>

      <div class="mb-4 flex items-center space-x-2">
        <input type="checkbox" id="remember" name="remember" class="w-4 h-4"/>
        <label for="remember" class="text-sm text-black">Ingat Saya</label>
      </div>

      <button type="submit" name="login"
              class="w-full bg-blue-500 hover:bg-blue-600 text-white 
                     font-semibold py-2 rounded">
        Masuk
      </button>

      <div class="mt-4 text-sm text-center">
        <a href="lupa_password.php" class="text-blue-600 font-semibold hover:underline">
          Lupa kata sandi
        </a>
        <p class="mt-1 text-gray-800">
          atau <a href="register.php" class="text-blue-600 font-semibold hover:underline">
            Daftar jadi siswa baru
          </a>
        </p>
      </div>
    </form>
  </div>

</body>
</html>
