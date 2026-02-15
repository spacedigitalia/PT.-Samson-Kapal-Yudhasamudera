<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /dashboard/company-kpis');
    exit;
}

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/CompanyKpisController.php';

$action = $_POST['action'] ?? '';
$auth = new AuthController($db);
$controller = new CompanyKpisController($db, $auth);

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

/** Build list array (value, title, description) from POST. Value can be e.g. "99.8%" */
function buildListFromPost(): array
{
    $values = $_POST['list_value'] ?? [];
    $titles = $_POST['list_title'] ?? [];
    $descriptions = $_POST['list_description'] ?? [];
    if (!is_array($values)) {
        $values = [];
    }
    if (!is_array($titles)) {
        $titles = [];
    }
    if (!is_array($descriptions)) {
        $descriptions = [];
    }
    $list = [];
    $max = max(count($values), count($titles), count($descriptions));
    for ($i = 0; $i < $max; $i++) {
        $val = isset($values[$i]) ? trim((string) $values[$i]) : '';
        $title = isset($titles[$i]) ? trim((string) $titles[$i]) : '';
        $desc = isset($descriptions[$i]) ? trim((string) $descriptions[$i]) : '';
        if ($val === '' && $title === '' && $desc === '') {
            continue;
        }
        $list[] = [
            'value'       => $val,
            'title'       => $title,
            'description' => $desc,
        ];
    }
    return $list;
}

switch ($action) {
    case 'create':
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($title === '' || $description === '') {
            $_SESSION['error'] = 'Judul dan deskripsi wajib diisi.';
            header('Location: /dashboard/company-kpis/create.php');
            exit;
        }
        $list = buildListFromPost();
        if ($controller->create($title, $description, $list, $userId)) {
            $_SESSION['success'] = 'Company KPIs berhasil ditambahkan.';
        } else {
            $_SESSION['error'] = 'Gagal menambah data.';
        }
        header('Location: /dashboard/company-kpis');
        exit;

    case 'update':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
            header('Location: /dashboard/company-kpis');
            exit;
        }
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($title === '' || $description === '') {
            $_SESSION['error'] = 'Judul dan deskripsi wajib diisi.';
            header('Location: /dashboard/company-kpis/edit.php?id=' . $id);
            exit;
        }
        $list = buildListFromPost();
        if ($controller->update($id, $title, $description, $list, $userId)) {
            $_SESSION['success'] = 'Company KPIs berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui data.';
        }
        header('Location: /dashboard/company-kpis');
        exit;

    case 'delete':
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid.';
        } else {
            if ($controller->delete($id, $userId)) {
                $_SESSION['success'] = 'Company KPIs berhasil dihapus.';
            } else {
                $_SESSION['error'] = 'Gagal menghapus data.';
            }
        }
        header('Location: /dashboard/company-kpis');
        exit;

    default:
        $_SESSION['error'] = 'Aksi tidak dikenal.';
        header('Location: /dashboard/company-kpis');
        exit;
}
