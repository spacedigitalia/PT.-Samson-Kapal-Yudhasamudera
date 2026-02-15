<?php
session_start();

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/SystemManagementController.php';

$active = 'management-systems';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Management Systems', 'href' => '/dashboard/management-systems'],
];

$auth = new AuthController($db);
$controller = new SystemManagementController($db, $auth);
$mgmtList = array_slice($controller->getAll(), 0, 2);
?>
<section class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <div class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4">
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-50 border border-violet-100/50 shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-violet-200/20 to-purple-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative px-6 md:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4 md:gap-6">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 shadow-lg flex items-center justify-center hidden md:flex">
                            <i class='bx bx-cog text-2xl md:text-3xl text-white'></i>
                        </div>
                        <div class="flex flex-col flex-1 min-w-0">
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">
                                Management Systems
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mt-1">
                                Kelola sistem manajemen. Data ada 2. List berisi title saja.
                            </p>
                            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-violet-100/50 text-xs text-slate-600">
                                <span><i class='bx bx-list-ul text-violet-500'></i> <?php echo count($mgmtList); ?> / 2 data</span>
                            </div>
                        </div>
                    </div>
                    <?php if (count($mgmtList) < 2): ?>
                        <div class="flex-shrink-0">
                            <a href="/dashboard/management-systems/create.php"
                                class="group flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-violet-600 to-purple-600 px-5 py-3 text-sm font-semibold text-white hover:from-violet-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class='bx bx-plus text-lg group-hover:rotate-90 transition-transform duration-300'></i>
                                <span class="hidden sm:inline">Add Content</span>
                                <span class="sm:hidden">Add</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($mgmtList)): ?>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <?php foreach ($mgmtList as $mgmtData): ?>
                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-slate-900"><?php echo htmlspecialchars($mgmtData['title']); ?></h2>
                                <a href="/dashboard/management-systems/edit.php?id=<?php echo (int) $mgmtData['id']; ?>"
                                    class="inline-flex items-center gap-1 rounded-xl bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                                    <i class='bx bx-edit'></i> Edit
                                </a>
                            </div>
                            <?php if (!empty(trim($mgmtData['description'] ?? ''))): ?>
                                <p class="text-sm text-slate-600 mb-4"><?php echo htmlspecialchars($mgmtData['description']); ?></p>
                            <?php endif; ?>

                            <?php
                            $list = $mgmtData['list'] ?? [];
                            if (!empty($list)):
                            ?>
                                <ul class="space-y-2">
                                    <?php foreach ($list as $item): ?>
                                        <li class="flex items-center gap-2 p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                                            <i class='bx bx-check text-violet-500'></i>
                                            <span class="font-medium text-slate-900"><?php echo htmlspecialchars($item['title'] ?? ''); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <div class="flex flex-wrap items-center gap-4 pt-4 mt-4 border-t border-slate-200 text-xs text-slate-500">
                                <span><i class='bx bx-user text-slate-400'></i> <?php echo htmlspecialchars($mgmtData['fullname'] ?? 'Unknown'); ?></span>
                                <span><i class='bx bx-calendar text-slate-400'></i> <?php echo date('M d, Y', strtotime($mgmtData['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($mgmtList) < 2): ?>
                <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50/50 p-8 text-center">
                    <p class="text-sm text-slate-500 mb-3">Slot ke-2 masih kosong. Tambah data ke-2.</p>
                    <a href="/dashboard/management-systems/create.php"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-300 transition-all">
                        <i class='bx bx-plus text-lg'></i> Add Data ke-2
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-12 text-center">
                <i class='bx bx-cog text-6xl text-slate-300 mb-4 block'></i>
                <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum ada Management Systems</h3>
                <p class="text-sm text-slate-500 mb-4">Data ada 2. Buat konten pertama (judul, description opsional, list title).</p>
                <a href="/dashboard/management-systems/create.php"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all">
                    <i class='bx bx-plus text-lg'></i> Add Content
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../footer.php'; ?>