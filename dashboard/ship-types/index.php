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
require_once __DIR__ . '/../../controllers/ShiptypesController.php';

$active = 'ship-types';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Ship Types', 'href' => '/dashboard/ship-types'],
];

$auth = new AuthController($db);
$controller = new ShiptypesController($db, $auth);
$list = $controller->getAll();
?>
<section class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <div class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4">
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-50 via-blue-50 to-indigo-50 border border-sky-100/50 shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-sky-200/20 to-blue-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative px-6 md:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4 md:gap-6">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 shadow-lg flex items-center justify-center hidden md:flex">
                            <i class='bx bxs-ship text-2xl md:text-3xl text-white'></i>
                        </div>
                        <div class="flex flex-col flex-1 min-w-0">
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">
                                Ship Types
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl mt-1">
                                Kelola tipe kapal: judul, deskripsi, dan gambar.
                            </p>
                            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-sky-100/50 text-xs text-slate-600">
                                <span><i class='bx bx-list-ul text-sky-500'></i> <?php echo count($list); ?> item</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="/dashboard/ship-types/create.php"
                            class="group flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-sky-600 to-blue-600 px-5 py-3 text-sm font-semibold text-white hover:from-sky-700 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class='bx bx-plus text-lg group-hover:rotate-90 transition-transform duration-300'></i>
                            <span class="hidden sm:inline">Add Ship Type</span>
                            <span class="sm:hidden">Add</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($list)): ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                            <tr>
                                <th class="px-4 py-3 rounded-tl-3xl">#</th>
                                <th class="px-4 py-3">Image</th>
                                <th class="px-4 py-3">Title</th>
                                <th class="px-4 py-3 max-w-xs">Description</th>
                                <th class="px-4 py-3 rounded-tr-3xl text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($list as $i => $item): ?>
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                                    <td class="px-4 py-3 text-slate-500"><?php echo $i + 1; ?></td>
                                    <td class="px-4 py-3">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?php echo htmlspecialchars((strpos($item['image'], '/') === 0 ? '' : '/') . $item['image']); ?>"
                                                alt="" class="h-12 w-12 rounded-lg object-cover border border-slate-200"
                                                onerror="this.src='https://via.placeholder.com/48?text=?'">
                                        <?php else: ?>
                                            <span class="text-slate-400">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-slate-900"><?php echo htmlspecialchars($item['title']); ?></td>
                                    <td class="px-4 py-3 text-slate-600 max-w-xs truncate"><?php echo htmlspecialchars($item['description']); ?></td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="/dashboard/ship-types/edit.php?id=<?php echo (int) $item['id']; ?>"
                                            class="inline-flex items-center gap-1 rounded-lg bg-blue-100 px-2.5 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200 mr-1">
                                            <i class='bx bx-edit'></i> Edit
                                        </a>
                                        <form action="/dashboard/ship-types/process.php" method="POST" class="inline" onsubmit="return confirm('Hapus ship type ini?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-red-100 px-2.5 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-200">
                                                <i class='bx bx-trash'></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-12 text-center">
                <i class='bx bx-ship text-6xl text-slate-300 mb-4 block'></i>
                <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum ada Ship Type</h3>
                <p class="text-sm text-slate-500 mb-4">Tambah tipe kapal dengan judul, deskripsi, dan gambar.</p>
                <a href="/dashboard/ship-types/create.php"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all">
                    <i class='bx bx-plus text-lg'></i> Add Ship Type
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../footer.php'; ?>