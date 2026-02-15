<?php
session_start();

// Proteksi halaman: hanya admin yang boleh masuk
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../config/db.php';

$active = 'profile';
$breadcrumbs = [
    ['label' => 'Profile', 'href' => '#'],
];

// Get user data from database
$userId = $_SESSION['user']['id'] ?? null;
$userData = null;
$logs = [];

if ($userId) {
    // Get user data
    $stmt = $db->prepare("SELECT a.id, a.fullname, a.email, r.name AS role, a.created_at, a.updated_at FROM accounts a LEFT JOIN roles r ON a.role_id = r.id WHERE a.id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();

    // Get user logs (latest 50)
    $stmt = $db->prepare("SELECT id, action, description, ip_address, user_agent, created_at FROM logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
    $stmt->close();
}

if (!$userData) {
    $_SESSION['error'] = 'Data user tidak ditemukan.';
    header('Location: dashboard');
    exit;
}
?>
<div class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <main class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4 pb-4">
        <!-- Hamburger button fixed on mobile -->
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <!-- Profile Header -->
        <div
            class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 border border-blue-100/50 shadow-lg">
            <!-- Decorative background elements -->
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-200/20 to-purple-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
            </div>
            <div
                class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-indigo-200/20 to-blue-200/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
            </div>

            <div class="relative px-6 md:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-6">
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Avatar Container -->
                        <div
                            class="flex-shrink-0 w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg flex items-center justify-center transform transition-transform hover:scale-105">
                            <i class='bx bx-user text-3xl md:text-4xl text-white'></i>
                        </div>

                        <!-- Title and Description -->
                        <div class="flex flex-col flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-2xl md:text-4xl font-bold text-slate-900 tracking-tight">My Profile</h1>
                                <span
                                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                    <i class='bx bx-check-circle text-sm'></i>
                                    <?php echo htmlspecialchars(strtoupper($userData['role'])); ?>
                                </span>
                            </div>
                            <p class="text-sm md:text-base text-slate-600 leading-relaxed max-w-2xl">
                                Informasi akun dan detail profil Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Profile Card -->
            <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-slate-900">Profile Information</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Full
                                Name</label>
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <i class='bx bx-user text-xl text-slate-400'></i>
                                <div class="flex-1">
                                    <div class="text-base font-semibold text-slate-900">
                                        <?php echo htmlspecialchars($userData['fullname']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email
                                Address</label>
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <i class='bx bx-envelope text-xl text-slate-400'></i>
                                <div class="flex-1">
                                    <div class="text-base font-semibold text-slate-900">
                                        <?php echo htmlspecialchars($userData['email']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Role</label>
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <i class='bx bx-shield text-xl text-slate-400'></i>
                                <div class="flex-1">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                                        <?php echo htmlspecialchars(strtoupper($userData['role'])); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- User ID -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">User
                                ID</label>
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <i class='bx bx-id-card text-xl text-slate-400'></i>
                                <div class="flex-1">
                                    <div class="text-base font-semibold text-slate-900">
                                        #<?php echo htmlspecialchars($userData['id']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info Card -->
            <div class="space-y-6">
                <!-- Account Details -->
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Account Details</h3>
                        <div class="space-y-4">
                            <!-- Created At -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Account
                                    Created</label>
                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    <i class='bx bx-calendar text-slate-400'></i>
                                    <span><?php echo date('M d, Y', strtotime($userData['created_at'])); ?></span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500 mt-1 ml-6">
                                    <i class='bx bx-time text-slate-400'></i>
                                    <span><?php echo date('H:i:s', strtotime($userData['created_at'])); ?></span>
                                </div>
                            </div>

                            <!-- Updated At -->
                            <div>
                                <label
                                    class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Last
                                    Updated</label>
                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    <i class='bx bx-calendar-check text-slate-400'></i>
                                    <span><?php echo date('M d, Y', strtotime($userData['updated_at'])); ?></span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500 mt-1 ml-6">
                                    <i class='bx bx-time text-slate-400'></i>
                                    <span><?php echo date('H:i:s', strtotime($userData['updated_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="/dashboard"
                                class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors">
                                <i class='bx bx-home text-xl text-slate-600'></i>
                                <span class="text-sm font-semibold text-slate-700">Go to Dashboard</span>
                            </a>
                            <a href="/dashboard/profile/change-password.php"
                                class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors">
                                <i class='bx bx-lock-alt text-xl text-slate-600'></i>
                                <span class="text-sm font-semibold text-slate-700">Change Password</span>
                            </a>
                            <form action="/dashboard/process.php" method="POST" class="m-0">
                                <input type="hidden" name="action" value="logout">
                                <button type="submit"
                                    class="w-full flex items-center gap-3 p-3 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                    <i class='bx bx-log-out text-xl text-rose-600'></i>
                                    <span class="text-sm font-semibold text-rose-700">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Logs Section -->
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class='bx bx-history text-xl text-white'></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Activity Logs</h2>
                            <p class="text-sm text-slate-500">Riwayat aktivitas akun Anda</p>
                        </div>
                    </div>
                    <div class="text-sm text-slate-500">
                        Total: <?php echo count($logs); ?> logs
                    </div>
                </div>

                <?php if (!empty($logs)): ?>
                    <div class="space-y-3 max-h-[600px] overflow-y-auto">
                        <?php foreach ($logs as $log): ?>
                            <div
                                class="flex items-start gap-4 p-4 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                                <!-- Icon based on action type -->
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center
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
                                    echo $bgClass . ' ' . $iconClass;
                                    ?>">
                                    <i class="bx <?php echo $icon; ?> text-xl"></i>
                                </div>

                                <!-- Log Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4 mb-1">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-sm font-semibold text-slate-900">
                                                    <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $log['action']))); ?>
                                                </span>
                                                <?php if ($log['ip_address']): ?>
                                                    <span class="text-xs text-slate-500 px-2 py-0.5 rounded-full bg-slate-100">
                                                        <i class='bx bx-globe text-xs'></i>
                                                        <?php echo htmlspecialchars($log['ip_address']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($log['description']): ?>
                                                <p class="text-sm text-slate-600 mb-2">
                                                    <?php echo htmlspecialchars($log['description']); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($log['user_agent']): ?>
                                                <p class="text-xs text-slate-400 truncate"
                                                    title="<?php echo htmlspecialchars($log['user_agent']); ?>">
                                                    <i class='bx bx-devices text-xs'></i>
                                                    <?php echo htmlspecialchars(mb_substr($log['user_agent'], 0, 80)); ?>
                                                    <?php if (mb_strlen($log['user_agent']) > 80): ?>...<?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-shrink-0 text-right">
                                            <div class="text-xs font-medium text-slate-700">
                                                <?php echo date('M d, Y', strtotime($log['created_at'])); ?>
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                <?php echo date('H:i:s', strtotime($log['created_at'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <i class='bx bx-inbox text-6xl text-slate-300 mb-4 block'></i>
                        <h3 class="text-lg font-semibold text-slate-700 mb-2">No Activity Logs</h3>
                        <p class="text-sm text-slate-500">
                            Belum ada aktivitas yang tercatat untuk akun ini.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="../js/toast.js"></script>
</body>

</html>