<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /dashboard/contact');
    exit;
}

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/ContactController.php';

$action = $_POST['action'] ?? '';
$auth = new AuthController($db);
$controller = new ContactController($db, $auth);

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

const TABLE_NAME = 'contact';
const UPLOAD_DIR_CONTACT = __DIR__ . '/../../upload/contact/';
const UPLOAD_URL_CONTACT = '/upload/contact/';
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
    if (!is_dir(UPLOAD_DIR_CONTACT)) {
        mkdir(UPLOAD_DIR_CONTACT, 0755, true);
    }
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION) ?: 'jpg';
    $name = TABLE_NAME . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
    $path = UPLOAD_DIR_CONTACT . $name;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
        if ($isUpdate) {
            $_SESSION['error'] = 'Gagal menyimpan file gambar.';
        }
        return '';
    }
    return UPLOAD_URL_CONTACT . $name;
}

switch ($action) {
    case 'create':
        $companyName = trim($_POST['company_name'] ?? '');
        if ($companyName === '') {
            $_SESSION['error'] = 'Nama perusahaan wajib diisi.';
            header('Location: /dashboard/contact/create.php');
            exit;
        }
        $imageUrl = handleImageUpload();
        $imageUrl = $imageUrl !== '' ? $imageUrl : null;
        if ($controller->create(
            $companyName,
            trim($_POST['building_name'] ?? '') ?: null,
            trim($_POST['floor'] ?? '') ?: null,
            trim($_POST['district'] ?? '') ?: null,
            trim($_POST['street_address'] ?? '') ?: null,
            trim($_POST['city'] ?? '') ?: null,
            trim($_POST['postal_code'] ?? '') ?: null,
            trim($_POST['country'] ?? '') ?: null,
            trim($_POST['phone'] ?? '') ?: null,
            trim($_POST['email'] ?? '') ?: null,
            trim($_POST['website'] ?? '') ?: null,
            trim($_POST['description'] ?? '') ?: null,
            $imageUrl,
            $userId
        )) {
            $_SESSION['success'] = 'Konten contact berhasil ditambahkan.';
        } else {
            $_SESSION['error'] = 'Gagal menambah data.';
        }
        header('Location: /dashboard/contact');
        exit;

    case 'update':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
            header('Location: /dashboard/contact');
            exit;
        }
        $companyName = trim($_POST['company_name'] ?? '');
        if ($companyName === '') {
            $_SESSION['error'] = 'Nama perusahaan wajib diisi.';
            header('Location: /dashboard/contact/edit.php?id=' . $id);
            exit;
        }
        $imageUrl = handleImageUpload(true);
        $imageCurrent = trim((string) ($_POST['image_current'] ?? ''));
        if ($imageUrl === '') {
            $imageUrl = $imageCurrent !== '' ? $imageCurrent : null;
        }
        if ($controller->update(
            $id,
            $companyName,
            trim($_POST['building_name'] ?? '') ?: null,
            trim($_POST['floor'] ?? '') ?: null,
            trim($_POST['district'] ?? '') ?: null,
            trim($_POST['street_address'] ?? '') ?: null,
            trim($_POST['city'] ?? '') ?: null,
            trim($_POST['postal_code'] ?? '') ?: null,
            trim($_POST['country'] ?? '') ?: null,
            trim($_POST['phone'] ?? '') ?: null,
            trim($_POST['email'] ?? '') ?: null,
            trim($_POST['website'] ?? '') ?: null,
            trim($_POST['description'] ?? '') ?: null,
            $imageUrl,
            $userId
        )) {
            $_SESSION['success'] = 'Konten contact berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui data.';
        }
        header('Location: /dashboard/contact');
        exit;

    case 'delete':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
        } else {
            if ($controller->delete($id, $userId)) {
                $_SESSION['success'] = 'Konten contact berhasil dihapus.';
            } else {
                $_SESSION['error'] = 'Gagal menghapus data.';
            }
        }
        header('Location: /dashboard/contact');
        exit;

    default:
        $_SESSION['error'] = 'Aksi tidak dikenal.';
        header('Location: /dashboard/contact');
        exit;
}
