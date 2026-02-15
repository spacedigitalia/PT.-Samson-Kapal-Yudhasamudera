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
require_once __DIR__ . '/../../controllers/PartnershipController.php';

$active = 'partnership';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Partnership', 'href' => '/dashboard/partnership'],
];

$auth = new AuthController($db);
$controller = new PartnershipController($db, $auth);
$data = $controller->getFirst();
?>
<section class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <div class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4">
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 border border-emerald-100/50 shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-emerald-200/20 to-teal-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative px-6 md:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4 md:gap-6">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 shadow-lg flex items-center justify-center hidden md:flex">
                            <i class='bx bx-group text-2xl md:text-3xl text-white'></i>
                        </div>
                        <div class="flex flex-col flex-1 min-w-0">
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">
                                Partnership
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mt-1">
                                List berisi title saja.
                            </p>
                            <?php if ($data): ?>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 pt-3 border-t border-emerald-100/50 text-xs text-slate-600">
                                    <span><i class='bx bx-time text-emerald-500'></i> Last updated: <?php echo date('M d, Y', strtotime($data['updated_at'] ?? $data['created_at'])); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!$data): ?>
                        <div class="flex-shrink-0">
                            <a href="/dashboard/partnership/create.php"
                                class="group flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-3 text-sm font-semibold text-white hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class='bx bx-plus text-lg group-hover:rotate-90 transition-transform duration-300'></i>
                                <span class="hidden sm:inline">Add Content</span>
                                <span class="sm:hidden">Add</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($data): ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-slate-900">Partnership Content</h2>
                        <a href="/dashboard/partnership/edit.php?id=<?php echo (int) $data['id']; ?>"
                            class="inline-flex items-center gap-1 rounded-xl bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                            <i class='bx bx-edit'></i> Edit
                        </a>
                    </div>

                    <h3 class="text-xl font-semibold text-slate-900 mb-2"><?php echo htmlspecialchars($data['title']); ?></h3>
                    <?php if (!empty($data['image'])): ?>
                        <div class="mb-4">
                            <img src="<?php echo htmlspecialchars((strpos($data['image'], '/') === 0 ? '' : '/') . $data['image']); ?>" alt="<?php echo htmlspecialchars($data['title']); ?>" class="max-h-64 w-auto object-contain rounded-xl border border-slate-200">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['quete'])): ?>
                        <p class="text-slate-600 mb-4 whitespace-pre-wrap"><?php echo htmlspecialchars($data['quete']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($data['title_list'])): ?>
                        <h4 class="text-sm font-semibold text-slate-700 mb-2"><?php echo htmlspecialchars($data['title_list']); ?></h4>
                    <?php endif; ?>

                    <?php
                    $list = $data['list'] ?? [];
                    if (!empty($list)):
                    ?>
                        <ul class="space-y-2">
                            <?php foreach ($list as $item): ?>
                                <li class="flex items-center gap-2 p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                                    <i class='bx bx-check-circle text-emerald-500 text-lg flex-shrink-0'></i>
                                    <span class="font-medium text-slate-900"><?php echo htmlspecialchars($item['title'] ?? ''); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="flex flex-wrap items-center gap-4 pt-4 mt-4 border-t border-slate-200 text-xs text-slate-500">
                        <span><i class='bx bx-user text-slate-400'></i> <?php echo htmlspecialchars($data['fullname'] ?? 'Unknown'); ?></span>
                        <span><i class='bx bx-calendar text-slate-400'></i> <?php echo date('M d, Y', strtotime($data['created_at'])); ?></span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-12 text-center">
                <i class='bx bx-handshake text-6xl text-slate-300 mb-4 block'></i>
                <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum ada Partnership</h3>
                <p class="text-sm text-slate-500 mb-4">Buat konten partnership dengan list (title saja).</p>
                <a href="/dashboard/partnership/create.php"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all">
                    <i class='bx bx-plus text-lg'></i> Add Content
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../footer.php'; ?>