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
require_once __DIR__ . '/../../controllers/TaglineController.php';

$active = 'tagline';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Tagline', 'href' => '/dashboard/tagline'],
];

$auth = new AuthController($db);
$controller = new TaglineController($db, $auth);
$taglineData = $controller->getFirst();
?>
<section class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <div class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4">
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class="bx bx-menu text-2xl"></i>
        </button>

        <!-- Tagline Header -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 border border-amber-100/50 shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-200/20 to-orange-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-yellow-200/20 to-amber-200/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

            <div class="relative px-6 md:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4 md:gap-6">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg flex items-center justify-center transform transition-transform hover:scale-105 hidden md:flex">
                            <i class="bx bx-purchase-tag-alt text-2xl md:text-3xl text-white"></i>
                        </div>
                        <div class="flex flex-col flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-2">
                                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Tagline Management</h1>
                                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                    <i class="bx bx-check-circle text-sm"></i> Active
                                </span>
                            </div>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl">
                                Kelola konten tagline: Moto dan Vision perusahaan.
                            </p>
                            <?php if ($taglineData): ?>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 pt-3 border-t border-amber-100/50">
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <i class="bx bx-check text-amber-500"></i>
                                    <span class="font-medium">Content Available</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <i class="bx bx-time text-orange-500"></i>
                                    <span>Last updated: <?php echo date('M d, Y', strtotime($taglineData['updated_at'] ?? $taglineData['created_at'])); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!$taglineData): ?>
                    <div class="flex-shrink-0">
                        <a href="/dashboard/tagline/create.php"
                            class="group flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-amber-600 to-orange-600 px-5 py-3 text-sm font-semibold text-white hover:from-amber-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="bx bx-plus text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                            <span class="hidden sm:inline">Add Tagline</span>
                            <span class="sm:hidden">Add</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($taglineData): ?>
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Tagline Preview</h2>
                    <a href="/dashboard/tagline/edit.php?id=<?php echo $taglineData['id']; ?>"
                        class="inline-flex items-center gap-1 rounded-xl bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                </div>

                <div class="space-y-6">
                    <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-100">
                        <h3 class="text-sm font-semibold text-amber-800 mb-1">Moto</h3>
                        <p class="text-lg font-medium text-slate-900 mb-1"><?php echo htmlspecialchars($taglineData['title_moto']); ?></p>
                        <p class="text-sm text-slate-600 italic mb-2">"<?php echo htmlspecialchars($taglineData['quete_moto']); ?>"</p>
                        <p class="text-sm text-slate-600"><?php echo nl2br(htmlspecialchars($taglineData['description_moto'])); ?></p>
                    </div>

                    <div class="p-4 rounded-2xl bg-orange-50/50 border border-orange-100">
                        <h3 class="text-sm font-semibold text-orange-800 mb-1">Vision</h3>
                        <?php if (!empty($taglineData['title_vission'])): ?>
                        <p class="text-lg font-medium text-slate-900 mb-1"><?php echo htmlspecialchars($taglineData['title_vission']); ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-slate-600 italic mb-2">"<?php echo htmlspecialchars($taglineData['quete_vission']); ?>"</p>
                        <p class="text-sm text-slate-600"><?php echo nl2br(htmlspecialchars($taglineData['description_vission'])); ?></p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-slate-200 text-xs text-slate-500">
                        <div class="flex items-center gap-2">
                            <i class="bx bx-user text-slate-400"></i>
                            <span>Created by: <strong><?php echo htmlspecialchars($taglineData['fullname'] ?? 'Unknown'); ?></strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bx bx-calendar text-slate-400"></i>
                            <span><?php echo date('M d, Y', strtotime($taglineData['created_at'])); ?></span>
                        </div>
                        <?php if (isset($taglineData['updated_at']) && $taglineData['updated_at'] !== $taglineData['created_at']): ?>
                        <div class="flex items-center gap-2">
                            <i class="bx bx-time text-slate-400"></i>
                            <span>Updated: <?php echo date('M d, Y', strtotime($taglineData['updated_at'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-12 text-center">
            <i class="bx bx-purchase-tag-alt text-6xl text-slate-300 mb-4 block"></i>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum Ada Tagline</h3>
            <p class="text-sm text-slate-500 mb-4">Tambahkan konten Moto dan Vision perusahaan.</p>
            <a href="/dashboard/tagline/create.php"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all">
                <i class="bx bx-plus text-lg"></i> Tambah Tagline
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../footer.php'; ?>
