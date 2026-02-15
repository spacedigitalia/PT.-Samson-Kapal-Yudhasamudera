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
require_once __DIR__ . '/../../controllers/MissionController.php';

$active = 'mission';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Mission', 'href' => '/dashboard/mission'],
];

$auth = new AuthController($db);
$controller = new MissionController($db, $auth);

// Data mission dari SQL; list berisi array (number, title, description)
$missionData = $controller->getFirst();
?>
<section class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <div class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4">
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 border border-amber-100/50 shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-200/20 to-orange-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative px-6 md:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4 md:gap-6">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg flex items-center justify-center hidden md:flex">
                            <i class='bx bx-bullseye text-2xl md:text-3xl text-white'></i>
                        </div>
                        <div class="flex flex-col flex-1 min-w-0">
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">
                                Mission Management
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mt-1">
                                Kelola konten mission. List berisi number, title, dan description.
                            </p>
                            <?php if ($missionData): ?>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 pt-3 border-t border-amber-100/50 text-xs text-slate-600">
                                <span><i class='bx bx-time text-amber-500'></i> Last updated: <?php echo date('M d, Y', strtotime($missionData['updated_at'] ?? $missionData['created_at'])); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!$missionData): ?>
                    <div class="flex-shrink-0">
                        <a href="/dashboard/mission/create.php"
                            class="group flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-amber-600 to-orange-600 px-5 py-3 text-sm font-semibold text-white hover:from-amber-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class='bx bx-plus text-lg group-hover:rotate-90 transition-transform duration-300'></i>
                            <span class="hidden sm:inline">Add Mission</span>
                            <span class="sm:hidden">Add</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($missionData): ?>
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Mission Content</h2>
                    <a href="/dashboard/mission/edit.php?id=<?php echo (int) $missionData['id']; ?>"
                        class="inline-flex items-center gap-1 rounded-xl bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                        <i class='bx bx-edit'></i> Edit
                    </a>
                </div>

                <?php if (!empty($missionData['image'])): ?>
                <div class="mb-4 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                    <img src="<?php echo htmlspecialchars((strpos($missionData['image'], '/') === 0 ? '' : '/') . $missionData['image']); ?>"
                        alt="<?php echo htmlspecialchars($missionData['title']); ?>"
                        class="w-full h-48 md:h-64 object-cover"
                        onerror="this.src='https://via.placeholder.com/800x400?text=Image+Not+Found'">
                </div>
                <?php endif; ?>

                <h3 class="text-xl font-semibold text-slate-900 mb-4"><?php echo htmlspecialchars($missionData['title']); ?></h3>

                <!-- List: number, title, description -->
                <?php
                $list = $missionData['list'] ?? [];
                if (!empty($list)):
                ?>
                <div class="space-y-4">
                    <?php foreach ($list as $item): ?>
                    <div class="flex gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                        <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-sm">
                            <?php echo htmlspecialchars((string)($item['number'] ?? '')); ?>
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="font-semibold text-slate-900"><?php echo htmlspecialchars($item['title'] ?? ''); ?></div>
                            <div class="text-sm text-slate-600 mt-1"><?php echo htmlspecialchars($item['description'] ?? ''); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="flex flex-wrap items-center gap-4 pt-4 mt-4 border-t border-slate-200 text-xs text-slate-500">
                    <span><i class='bx bx-user text-slate-400'></i> <?php echo htmlspecialchars($missionData['fullname'] ?? 'Unknown'); ?></span>
                    <span><i class='bx bx-calendar text-slate-400'></i> <?php echo date('M d, Y', strtotime($missionData['created_at'])); ?></span>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-12 text-center">
            <i class='bx bx-inbox text-6xl text-slate-300 mb-4 block'></i>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum ada Mission</h3>
            <p class="text-sm text-slate-500 mb-4">Buat konten mission dengan list (number, title, description).</p>
            <a href="/dashboard/mission/create.php"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all">
                <i class='bx bx-plus text-lg'></i> Create Mission
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../footer.php'; ?>
