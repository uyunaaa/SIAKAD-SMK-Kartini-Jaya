<?php
// Fungsi untuk menampilkan hasil hash dengan label
function hash_password($label, $plainPassword) {
    echo $label . ": " . password_hash($plainPassword, PASSWORD_DEFAULT) . "<br>";
}

// Daftar password yang ingin di-hash
hash_password("admin123", "admin123");
hash_password("guru123", "guru123");
hash_password("guru456", "guru456");
hash_password("siswa1",  "siswa1");
hash_password("siswa2",  "siswa2");
?>
