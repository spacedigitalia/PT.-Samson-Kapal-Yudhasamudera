<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /dashboard/ship-types');
    exit;
}

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/ShiptypesController.php';

$action = $_POST['action'] ?? '';
$auth = new AuthController($db);
$controller = new ShiptypesController($db, $auth);

// Pastikan user_id ada di tabel accounts (hindari FK constraint error)
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

const TABLE_NAME = 'ship_types';
const UPLOAD_DIR = __DIR__ . '/../../upload/' . TABLE_NAME . '/';
const UPLOAD_URL = '/upload/' . TABLE_NAME . '/';
const MAX_SIZE = 10 * 1024 * 1024; // 10MB
const ALLOWED = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

function handleImageUpload(bool $isUpdate = false): string
{
    if (!isset($_FILES['image']) || empty($_FILES['image']['tmp_name'])) {
        return '';
    }
    $err = (int) ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err !== UPLOAD_ERR_OK) {
        if ($isUpdate && $err !== UPLOAD_ERR_NO_FILE) {
            $msg = [
                UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi batas server.',
                UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi 10MB.',
                UPLOAD_ERR_PARTIAL => 'File hanya ter-upload sebagian.',
                UPLOAD_ERR_NO_TMP_DIR => 'Folder temp tidak ada.',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file.',
                UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi.',
            ];
            $_SESSION['error'] = $msg[$err] ?? 'Gagal upload gambar (error ' . $err . ').';
        }
        return '';
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES['image']['tmp_name']);
    if (!in_array($mime, ALLOWED, true)) {
        if ($isUpdate) {
            $_SESSION['error'] = 'Format gambar tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.';
        }
        return '';
    }
    if ($_FILES['image']['size'] > MAX_SIZE) {
        if ($isUpdate) {
            $_SESSION['error'] = 'Ukuran gambar maksimal 10MB.';
        }
        return '';
    }
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
    $name = TABLE_NAME . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
    $path = UPLOAD_DIR . $name;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
        if ($isUpdate) {
            $_SESSION['error'] = 'Gagal menyimpan file gambar.';
        }
        return '';
    }
    return UPLOAD_URL . $name;
}

switch ($action) {
    case 'create':
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($title === '' || $description === '') {
            $_SESSION['error'] = 'Judul dan deskripsi wajib diisi.';
            header('Location: /dashboard/ship-types/create.php');
            exit;
        }
        $image = handleImageUpload();
        if ($image === '') {
            $image = '/assets/logo.jpg';
        }
        if ($controller->create($title, $description, $image, $userId)) {
            $_SESSION['success'] = 'Ship type berhasil ditambahkan.';
        } else {
            $_SESSION['error'] = 'Gagal menambah data.';
        }
        header('Location: /dashboard/ship-types');
        exit;

    case 'update':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
            header('Location: /dashboard/ship-types');
            exit;
        }
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($title === '' || $description === '') {
            $_SESSION['error'] = 'Judul dan deskripsi wajib diisi.';
            header('Location: /dashboard/ship-types/edit.php?id=' . $id);
            exit;
        }
        $image = handleImageUpload(true);
        $imageCurrent = trim((string) ($_POST['image_current'] ?? ''));
        if ($image === '') {
            $image = $imageCurrent !== '' ? $imageCurrent : '/assets/logo.jpg';
        }
        if ($controller->update($id, $title, $description, $image, $userId)) {
            $_SESSION['success'] = 'Ship type berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui data.';
        }
        header('Location: /dashboard/ship-types');
        exit;

    case 'delete':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
        } else {
            if ($controller->delete($id, $userId)) {
                $_SESSION['success'] = 'Ship type berhasil dihapus.';
            } else {
                $_SESSION['error'] = 'Gagal menghapus data.';
            }
        }
        header('Location: /dashboard/ship-types');
        exit;

    default:
        $_SESSION['error'] = 'Aksi tidak dikenal.';
        header('Location: /dashboard/ship-types');
        exit;
}
