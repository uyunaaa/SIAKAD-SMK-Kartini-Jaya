<?php
// generate_guru.php
require 'koneksi.php';

for ($i = 1; $i <= 50; $i++) {
    // 1) create a users row
    $name      = 'Guru ' . $i;
    $username  = 'guru' . str_pad($i, 3, '0', STR_PAD_LEFT);
    $password  = password_hash('pass' . $i, PASSWORD_DEFAULT);
    $role      = 'guru';
    $createdAt = date('Y-m-d H:i:s');

    $uStmt = mysqli_prepare($koneksi,
      "INSERT INTO users
         (Nama_Lengkap, Username, Password, Role, CreatedAt)
       VALUES (?,?,?,?,?)"
    );
    mysqli_stmt_bind_param($uStmt, 'sssss',
      $name, $username, $password, $role, $createdAt
    );
    mysqli_stmt_execute($uStmt);
    $userId = mysqli_insert_id($koneksi);
    mysqli_stmt_close($uStmt);

    // 2) insert the guru row, pointing at that new user
    $nip   = 'NIP' . str_pad($i, 4, '0', STR_PAD_LEFT);
    $mapel = 'MataPelajaran ' . $i;

    $gStmt = mysqli_prepare($koneksi,
      "INSERT INTO guru
         (UserID, NIP, nama_lengkap, MataPelajaran)
       VALUES (?,?,?,?)"
    );
    mysqli_stmt_bind_param($gStmt, 'isss',
      $userId, $nip, $name, $mapel
    );
    mysqli_stmt_execute($gStmt);

    if (mysqli_stmt_error($gStmt)) {
        echo "Error inserting guru #{$i}: "
             . mysqli_stmt_error($gStmt) . "<br>\n";
    }
    mysqli_stmt_close($gStmt);
}

echo "✅ Done creating 50 new guru users.";
