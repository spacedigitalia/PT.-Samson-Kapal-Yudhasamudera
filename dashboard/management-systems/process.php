<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /dashboard/management-systems');
    exit;
}

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/SystemManagementController.php';

$action = $_POST['action'] ?? '';
$auth = new AuthController($db);
$controller = new SystemManagementController($db, $auth);

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

/** Build list array (title only) from POST */
function buildListFromPost(): array
{
    $titles = $_POST['list_title'] ?? [];
    if (!is_array($titles)) {
        $titles = [];
    }
    $list = [];
    foreach ($titles as $t) {
        $title = trim((string) $t);
        if ($title !== '') {
            $list[] = ['title' => $title];
        }
    }
    return $list;
}

switch ($action) {
    case 'create':
        $title = trim($_POST['title'] ?? '');
        if ($title === '') {
            $_SESSION['error'] = 'Judul wajib diisi.';
            header('Location: /dashboard/management-systems/create.php');
            exit;
        }
        $description = trim($_POST['description'] ?? '');
        $description = $description === '' ? null : $description;
        $list = buildListFromPost();
        if ($controller->create($title, $description, $list, $userId)) {
            $_SESSION['success'] = 'Management system berhasil ditambahkan.';
        } else {
            $_SESSION['error'] = 'Gagal menambah data.';
        }
        header('Location: /dashboard/management-systems');
        exit;

    case 'update':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
            header('Location: /dashboard/management-systems');
            exit;
        }
        $title = trim($_POST['title'] ?? '');
        if ($title === '') {
            $_SESSION['error'] = 'Judul wajib diisi.';
            header('Location: /dashboard/management-systems/edit.php?id=' . $id);
            exit;
        }
        $description = trim($_POST['description'] ?? '');
        $description = $description === '' ? null : $description;
        $list = buildListFromPost();
        if ($controller->update($id, $title, $description, $list, $userId)) {
            $_SESSION['success'] = 'Management system berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui data.';
        }
        header('Location: /dashboard/management-systems');
        exit;

    case 'delete':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
        } else {
            if ($controller->delete($id, $userId)) {
                $_SESSION['success'] = 'Management system berhasil dihapus.';
            } else {
                $_SESSION['error'] = 'Gagal menghapus data.';
            }
        }
        header('Location: /dashboard/management-systems');
        exit;

    default:
        $_SESSION['error'] = 'Aksi tidak dikenal.';
        header('Location: /dashboard/management-systems');
        exit;
}
