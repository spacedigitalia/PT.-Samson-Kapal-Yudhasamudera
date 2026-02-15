<?php
session_start();

session_unset();
session_destroy();

session_start();
$_SESSION['success'] = 'Berhasil logout.';
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
$base = dirname($scriptDir);
if ($base === '/' || $base === '\\' || $base === '.') {
    $base = '';
}
header('Location: ' . ($base !== '' ? $base . '/' : '/') . 'login.php');
exit;
