<?php
session_start();

// Proteksi halaman: harus login
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../config/db.php';

// Recent Logs (dari semua controller: Auth, Home, Tagline, Services, Mission, Management System, Ship Types, Company KPIs, SKY SAI Brain, Partnership, Contact)
$recentLogs = [];
$stmt = $db->prepare("SELECT l.*, a.fullname FROM logs l LEFT JOIN accounts a ON l.user_id = a.id ORDER BY l.created_at DESC LIMIT 10");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recentLogs[] = $row;
    }
    $stmt->close();
}

// Map action ke modul (sesuai controller)
$actionToModule = [
    'management_system' => 'Management System',
    'mission' => 'Mission',
    'contact' => 'Contact',
    'partnership' => 'Partnership',
    'ship_type' => 'Ship Types',
    'company_kpis' => 'Company KPIs',
    'sky_sai_brain' => 'SKY SAI Brain',
    'service' => 'Services',
    'tagline' => 'Tagline',
    'home' => 'Home',
    'login' => 'Auth',
    'logout' => 'Auth',
    'register' => 'Auth',
    'change_password' => 'Auth',
];
function getLogModule(string $action, array $map): string {
    foreach ($map as $prefix => $label) {
        if (strpos($action, $prefix) === 0) return $label;
    }
    return 'Lainnya';
}
function getLogActionBadgeClass(string $action): string {
    if (strpos($action, 'create') !== false || $action === 'login_success' || $action === 'register_success') return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    if (strpos($action, 'update') !== false) return 'bg-blue-100 text-blue-700 border-blue-200';
    if (strpos($action, 'delete') !== false) return 'bg-red-100 text-red-700 border-red-200';
    if (in_array($action, ['login_failed', 'login_denied', 'login_blocked_ip', 'login_error', 'register_failed', 'register_error'], true)) return 'bg-amber-100 text-amber-700 border-amber-200';
    if ($action === 'logout' || $action === 'change_password') return 'bg-slate-100 text-slate-700 border-slate-200';
    return 'bg-slate-100 text-slate-600 border-slate-200';
}
function getLogActionLabel(string $action): string {
    $labels = [
        'management_system_create' => 'Buat', 'management_system_update' => 'Ubah', 'management_system_delete' => 'Hapus',
        'mission_create' => 'Buat', 'mission_update' => 'Ubah', 'mission_delete' => 'Hapus',
        'contact_create' => 'Buat', 'contact_update' => 'Ubah', 'contact_delete' => 'Hapus',
        'partnership_create' => 'Buat', 'partnership_update' => 'Ubah', 'partnership_delete' => 'Hapus',
        'ship_type_create' => 'Buat', 'ship_type_update' => 'Ubah', 'ship_type_delete' => 'Hapus',
        'company_kpis_create' => 'Buat', 'company_kpis_update' => 'Ubah', 'company_kpis_delete' => 'Hapus',
        'sky_sai_brain_create' => 'Buat', 'sky_sai_brain_update' => 'Ubah', 'sky_sai_brain_delete' => 'Hapus',
        'service_create' => 'Buat', 'service_update' => 'Ubah', 'service_delete' => 'Hapus',
        'tagline_create' => 'Buat', 'tagline_update' => 'Ubah', 'tagline_delete' => 'Hapus',
        'home_create' => 'Buat', 'home_update' => 'Ubah', 'home_delete' => 'Hapus',
        'login_success' => 'Login', 'login_failed' => 'Login gagal', 'login_denied' => 'Akses ditolak', 'login_blocked_ip' => 'IP diblokir', 'login_error' => 'Error login',
        'logout' => 'Logout', 'register_success' => 'Daftar', 'register_failed' => 'Daftar gagal', 'register_error' => 'Error daftar', 'change_password' => 'Ganti password',
    ];
    return $labels[$action] ?? preg_replace('/_/', ' ', $action);
}

$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Overview'],
];
?>
<div class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <main class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4 pb-4">
        <!-- Hamburger button fixed on mobile -->
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <!-- Top bar -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <?php echo renderBreadcrumb($breadcrumbs); ?>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="hidden md:flex items-center gap-2 rounded-2xl bg-white px-4 py-2 shadow-sm border border-slate-200">
                    <span class="text-sm text-slate-500">
                        <?php echo date('l, d F Y'); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Welcome Banner -->
        <div
            class="rounded-3xl bg-gradient-to-r from-slate-800 to-slate-900 p-6 md:p-10 text-white relative overflow-hidden shadow-lg">
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
            </div>
            <div class="relative z-10">
                <h1 class="text-2xl md:text-3xl font-bold">Welcome back,
                    <?php echo htmlspecialchars($_SESSION['user']['fullname'] ?? 'Admin'); ?>! 👋</h1>
                <p class="mt-2 text-slate-300 max-w-xl">
                    Here's what's happening with your website today.
                </p>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="rounded-3xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Recent Activity</h2>
                <a href="/dashboard/logs" class="text-sm font-medium text-slate-500 hover:text-slate-900">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-semibold">User</th>
                            <th class="px-6 py-3 font-semibold">Modul</th>
                            <th class="px-6 py-3 font-semibold">Aksi</th>
                            <th class="px-6 py-3 font-semibold">Keterangan</th>
                            <th class="px-6 py-3 font-semibold">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($recentLogs)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3 text-slate-500">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                                            <i class='bx bx-history text-3xl text-slate-400'></i>
                                        </div>
                                        <p class="font-medium text-slate-600">Belum ada aktivitas</p>
                                        <p class="text-sm max-w-sm">Aktivitas akan tercatat saat login, mengubah konten (Home, Mission, Services, dll.), atau aksi lain di dashboard.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentLogs as $log):
                                $module = getLogModule($log['action'], $actionToModule);
                                $badgeClass = getLogActionBadgeClass($log['action']);
                                $actionLabel = getLogActionLabel($log['action']);
                            ?>
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                                        <?php echo htmlspecialchars($log['fullname'] ?? 'System'); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-xs font-medium text-slate-600 border border-slate-200">
                                            <?php echo htmlspecialchars($module); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium border <?php echo $badgeClass; ?>">
                                            <?php echo htmlspecialchars($actionLabel); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="<?php echo htmlspecialchars($log['description'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($log['description'] ?? '-'); ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 whitespace-nowrap">
                                        <?php echo date('d M Y, H:i', strtotime($log['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</body>

</html>