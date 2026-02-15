<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /dashboard/tagline');
    exit;
}

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/TaglineController.php';

$action = $_POST['action'] ?? '';
$auth = new AuthController($db);
$controller = new TaglineController($db, $auth);

$userId = null;
if (isset($_SESSION['user']['id'])) {
    $uid = (int) $_SESSION['user']['id'];
    if ($uid > 0) {
        $check = $db->prepare("SELECT id FROM accounts WHERE id = ? LIMIT 1");
        $check->bind_param('i', $uid);
        $check->execute();
        if ($check->get_result()->fetch_assoc()) {
            $userId = $uid;
        }
        $check->close();
    }
}

switch ($action) {
    case 'create':
        $titleMoto = trim($_POST['title_moto'] ?? '');
        $queteMoto = trim($_POST['quete_moto'] ?? '');
        $descriptionMoto = trim($_POST['description_moto'] ?? '');
        $titleVission = trim($_POST['title_vission'] ?? '') ?: null;
        $queteVission = trim($_POST['quete_vission'] ?? '');
        $descriptionVission = trim($_POST['description_vission'] ?? '');
        if ($titleMoto === '' || $queteMoto === '' || $descriptionMoto === '' || $queteVission === '' || $descriptionVission === '') {
            $_SESSION['error'] = 'Field Moto dan Vision wajib diisi.';
            header('Location: /dashboard/tagline/create.php');
            exit;
        }
        if ($controller->create($titleMoto, $queteMoto, $descriptionMoto, $titleVission, $queteVission, $descriptionVission, $userId)) {
            $_SESSION['success'] = 'Tagline berhasil ditambahkan.';
        } else {
            $_SESSION['error'] = 'Gagal menambah data.';
        }
        header('Location: /dashboard/tagline');
        exit;

    case 'update':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
            header('Location: /dashboard/tagline');
            exit;
        }
        $titleMoto = trim($_POST['title_moto'] ?? '');
        $queteMoto = trim($_POST['quete_moto'] ?? '');
        $descriptionMoto = trim($_POST['description_moto'] ?? '');
        $titleVission = trim($_POST['title_vission'] ?? '') ?: null;
        $queteVission = trim($_POST['quete_vission'] ?? '');
        $descriptionVission = trim($_POST['description_vission'] ?? '');
        if ($titleMoto === '' || $queteMoto === '' || $descriptionMoto === '' || $queteVission === '' || $descriptionVission === '') {
            $_SESSION['error'] = 'Field Moto dan Vision wajib diisi.';
            header('Location: /dashboard/tagline/edit.php?id=' . $id);
            exit;
        }
        if ($controller->update($id, $titleMoto, $queteMoto, $descriptionMoto, $titleVission, $queteVission, $descriptionVission, $userId)) {
            $_SESSION['success'] = 'Tagline berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui data.';
        }
        header('Location: /dashboard/tagline');
        exit;

    case 'delete':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
        } else {
            if ($controller->delete($id, $userId)) {
                $_SESSION['success'] = 'Tagline berhasil dihapus.';
            } else {
                $_SESSION['error'] = 'Gagal menghapus data.';
            }
        }
        header('Location: /dashboard/tagline');
        exit;

    default:
        $_SESSION['error'] = 'Aksi tidak dikenal.';
        header('Location: /dashboard/tagline');
        exit;
}
