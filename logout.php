<?php
session_start(); // Memulai session

// Hapus semua session
$_SESSION = array();

// Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"], $params["secure"], $params["httponly"]
    );
}

// Akhiri session
session_destroy();

// Arahkan kembali ke halaman login atau homepage
header("Location: users/login.php"); // Ganti dengan path halaman yang diinginkan
exit();
?>
