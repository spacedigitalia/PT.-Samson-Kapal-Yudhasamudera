<?php
session_start();

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../config/db.php';

// Handle Clear Logs action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_logs'])) {
    try {
        $stmt = $db->prepare("DELETE FROM logs");
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Semua logs berhasil dihapus.';
        } else {
            throw new Exception("Gagal menghapus logs.");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
    }

    // Redirect to refresh page
    header('Location: index.php');
    exit;
}

$active = 'logs';
$breadcrumbs = [
    ['label' => 'Logs', 'href' => 'logs'],
];

// Get all logs with user information
$logs = [];
$stmt = $db->prepare("SELECT l.*, ac.fullname, ac.email FROM logs l LEFT JOIN accounts ac ON l.user_id = ac.id ORDER BY l.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}
$stmt->close();

// Get statistics
$totalLogs = count($logs);
$loginLogs = count(array_filter($logs, function ($log) {
    return strpos(strtolower($log['action']), 'login') !== false;
}));
$errorLogs = count(array_filter($logs, function ($log) {
    return strpos(strtolower($log['action']), 'error') !== false || strpos(strtolower($log['action']), 'denied') !== false;
}));
?>
<div class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <main class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4 pb-4">
        <!-- Hamburger button fixed on mobile -->
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <!-- Logs Header -->
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-50 via-orange-50 to-red-50 border border-amber-100/50 shadow-lg">
            <!-- Decorative background elements -->
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-200/20 to-red-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
            </div>
            <div
                class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-orange-200/20 to-amber-200/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
            </div>

            <div class="relative px-6 md:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4 md:gap-6">
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Icon Container -->
                        <div
                            class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg flex items-center justify-center transform transition-transform hover:scale-105 hidden md:flex">
                            <i class='bx bx-history text-2xl md:text-3xl text-white'></i>
                        </div>

                        <!-- Title and Description -->
                        <div class="flex flex-col flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-2xl md:text-4xl font-bold text-slate-900 tracking-tight">Activity Logs
                                </h1>
                                <span
                                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                    <i class='bx bx-check-circle text-sm'></i>
                                    All Logs
                                </span>
                            </div>
                            <p class="text-sm md:text-base text-slate-600 leading-relaxed max-w-2xl">
                                Riwayat aktivitas sistem dan pengguna. Monitor semua aktivitas yang terjadi di platform.
                            </p>

                            <!-- Stats -->
                            <?php if ($totalLogs > 0): ?>
                                <div class="flex items-center gap-4 mt-3 pt-3 border-t border-amber-100/50">
                                    <div class="flex items-center gap-2 text-xs text-slate-600">
                                        <i class='bx bx-list-ul text-amber-500'></i>
                                        <span class="font-medium"><?php echo number_format($totalLogs); ?> Total Logs</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-slate-600">
                                        <i class='bx bx-log-in text-green-500'></i>
                                        <span class="font-medium"><?php echo number_format($loginLogs); ?> Login</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-slate-600">
                                        <i class='bx bx-error text-red-500'></i>
                                        <span class="font-medium"><?php echo number_format($errorLogs); ?> Errors</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($totalLogs > 0): ?>
                        <div class="flex-shrink-0">
                            <form id="clearLogsForm" method="POST" class="hidden">
                                <input type="hidden" name="clear_logs" value="1">
                            </form>
                            <button onclick="confirmClearLogs()"
                                class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm text-red-600 rounded-xl border border-red-200 hover:bg-red-50 hover:border-red-300 transition-all text-sm font-semibold shadow-sm group">
                                <i class='bx bx-trash group-hover:scale-110 transition-transform'></i>
                                <span>Clear Logs</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <?php if (!empty($logs)): ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Action</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Description</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    User</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    IP Address</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    User Agent</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Created At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php foreach ($logs as $log): ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <?php
                                            $action = strtolower($log['action']);
                                            $iconClass = '';
                                            $bgClass = '';
                                            $icon = 'bx-info-circle';

                                            // Success actions (green) - check first for specific success actions
                                            if (strpos($action, 'login_success') !== false || strpos($action, 'register_success') !== false || ($action === 'success')) {
                                                $iconClass = 'text-green-600';
                                                $bgClass = 'bg-green-100';
                                                $icon = 'bx-check-circle';
                                            }
                                            // Blocked/Denied (orange/amber) - check before login to catch login_blocked_ip
                                            elseif (strpos($action, 'login_blocked_ip') !== false || strpos($action, 'blocked') !== false || strpos($action, 'denied') !== false || strpos($action, 'block') !== false) {
                                                $iconClass = 'text-amber-600';
                                                $bgClass = 'bg-amber-100';
                                                $icon = 'bx-block';
                                            }
                                            // Failed/Error (red) - check before login to catch login_failed
                                            elseif (strpos($action, 'login_failed') !== false || strpos($action, 'failed') !== false || strpos($action, 'fail') !== false || strpos($action, 'error') !== false) {
                                                $iconClass = 'text-red-600';
                                                $bgClass = 'bg-red-100';
                                                $icon = 'bx-error';
                                            }
                                            // Login (blue/cyan) - general login actions
                                            elseif (strpos($action, 'login') !== false) {
                                                $iconClass = 'text-blue-600';
                                                $bgClass = 'bg-blue-100';
                                                $icon = 'bx-log-in';
                                            }
                                            // Create (emerald)
                                            elseif (strpos($action, 'create') !== false) {
                                                $iconClass = 'text-emerald-600';
                                                $bgClass = 'bg-emerald-100';
                                                $icon = 'bx-plus';
                                            }
                                            // Update (indigo)
                                            elseif (strpos($action, 'update') !== false) {
                                                $iconClass = 'text-indigo-600';
                                                $bgClass = 'bg-indigo-100';
                                                $icon = 'bx-refresh';
                                            }
                                            // Delete (red)
                                            elseif (strpos($action, 'delete') !== false) {
                                                $iconClass = 'text-red-600';
                                                $bgClass = 'bg-red-100';
                                                $icon = 'bx-trash';
                                            }
                                            // Password change (purple)
                                            elseif (strpos($action, 'password_change') !== false || strpos($action, 'change_password') !== false) {
                                                $iconClass = 'text-purple-600';
                                                $bgClass = 'bg-purple-100';
                                                $icon = 'bx-lock';
                                            }
                                            // Default (gray)
                                            else {
                                                $iconClass = 'text-slate-600';
                                                $bgClass = 'bg-slate-100';
                                                $icon = 'bx-info-circle';
                                            }
                                            ?>
                                            <div
                                                class="w-8 h-8 rounded-lg <?php echo $bgClass; ?> flex items-center justify-center">
                                                <i class='bx <?php echo $icon; ?> text-sm <?php echo $iconClass; ?>'></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900">
                                                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $log['action']))); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600 max-w-md">
                                            <?php if ($log['description']): ?>
                                                <?php echo htmlspecialchars(mb_substr($log['description'], 0, 150)); ?>
                                                <?php if (mb_strlen($log['description']) > 150): ?>...<?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-slate-400 italic">No description</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($log['fullname']): ?>
                                            <div class="text-sm font-semibold text-slate-900">
                                                <?php echo htmlspecialchars($log['fullname']); ?>
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                <?php echo htmlspecialchars($log['email']); ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-sm text-slate-400 italic">System / Unknown</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($log['ip_address']): ?>
                                            <div class="flex items-center gap-1 text-sm text-slate-600">
                                                <i class='bx bx-globe text-slate-400'></i>
                                                <span><?php echo htmlspecialchars($log['ip_address']); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-sm text-slate-400 italic">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($log['user_agent']): ?>
                                            <div class="text-xs text-slate-500 max-w-xs truncate"
                                                title="<?php echo htmlspecialchars($log['user_agent']); ?>">
                                                <?php echo htmlspecialchars(mb_substr($log['user_agent'], 0, 60)); ?>
                                                <?php if (mb_strlen($log['user_agent']) > 60): ?>...<?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-400 italic">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-slate-600">
                                            <?php echo date('M d, Y', strtotime($log['created_at'])); ?>
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            <?php echo date('H:i:s', strtotime($log['created_at'])); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-12 text-center">
                <i class='bx bx-inbox text-6xl text-slate-300 mb-4 block'></i>
                <h3 class="text-lg font-semibold text-slate-700 mb-2">No Logs Available</h3>
                <p class="text-sm text-slate-500 mb-4">
                    Belum ada aktivitas yang tercatat di sistem.
                </p>
            </div>
        <?php endif; ?>
    </main>
</div>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="../js/toast.js"></script>
<script>
    function confirmClearLogs() {
        if (confirm('Apakah Anda yakin ingin menghapus semua logs? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('clearLogsForm').submit();
        }
    }
</script>
</body>

</html>