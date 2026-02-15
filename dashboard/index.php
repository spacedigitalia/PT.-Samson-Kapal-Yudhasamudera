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

// Recent Logs
$recentLogs = [];
$result = $db->query("SELECT l.*, a.fullname FROM logs l LEFT JOIN accounts a ON l.user_id = a.id ORDER BY l.created_at DESC LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recentLogs[] = $row;
    }
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
                <a href="logs" class="text-sm font-medium text-slate-500 hover:text-slate-900">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-semibold">User</th>
                            <th class="px-6 py-3 font-semibold">Action</th>
                            <th class="px-6 py-3 font-semibold">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($recentLogs)): ?>
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-500">No recent activity found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentLogs as $log): ?>
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        <?php echo htmlspecialchars($log['fullname'] ?? 'System'); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-xs font-medium text-slate-600 border border-slate-200">
                                            <?php echo htmlspecialchars($log['action']); ?>
                                        </span>
                                        <span
                                            class="ml-2 text-slate-500"><?php echo htmlspecialchars($log['description']); ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        <?php echo date('M d, H:i', strtotime($log['created_at'])); ?>
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