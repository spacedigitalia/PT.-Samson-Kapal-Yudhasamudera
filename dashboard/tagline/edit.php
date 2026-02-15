<?php
session_start();

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: /login.php');
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    $_SESSION['error'] = 'ID tidak valid.';
    header('Location: /dashboard/tagline');
    exit;
}

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/TaglineController.php';

$auth = new AuthController($db);
$controller = new TaglineController($db, $auth);
$row = $controller->getById($id);

if (!$row) {
    $_SESSION['error'] = 'Data tidak ditemukan.';
    header('Location: /dashboard/tagline');
    exit;
}

$active = 'tagline';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Tagline', 'href' => '/dashboard/tagline'],
    ['label' => 'Edit Tagline', 'href' => '#'],
];
?>
<div class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <main class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4 pb-4">
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 left-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <div class="px-4 md:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="lg:hidden w-10"></div>
                    <?php echo renderBreadcrumb($breadcrumbs); ?>
                </div>
                <a href="/dashboard/tagline"
                    class="flex items-center gap-2 rounded-2xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                    <i class='bx bx-arrow-back text-lg'></i>
                    <span class="hidden sm:inline">Kembali</span>
                    <span class="sm:hidden">Back</span>
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <form action="/dashboard/tagline/process.php" method="POST" class="space-y-6" onsubmit="return validateTaglineForm()">
                <script>
                function validateTaglineForm() {
                    const required = ['title_moto', 'quete_moto', 'description_moto', 'quete_vission', 'description_vission'];
                    for (const name of required) {
                        const el = document.querySelector('[name="' + name + '"]');
                        if (el && !el.value.trim()) {
                            toastr.error('Harap lengkapi semua field wajib (Moto & Vision)');
                            return false;
                        }
                    }
                    return true;
                }
                </script>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">

                <div class="border-b border-slate-200 pb-4">
                    <h3 class="text-base font-semibold text-amber-800 mb-4">Moto</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Moto</label>
                            <input type="text" name="title_moto" required placeholder="Contoh: Moto Perusahaan"
                                value="<?php echo htmlspecialchars($row['title_moto']); ?>"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Quote Moto</label>
                            <input type="text" name="quete_moto" required placeholder="Kutipan singkat moto"
                                value="<?php echo htmlspecialchars($row['quete_moto']); ?>"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Moto</label>
                            <textarea name="description_moto" rows="3" required placeholder="Penjelasan moto perusahaan"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"><?php echo htmlspecialchars($row['description_moto']); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="border-b border-slate-200 pb-4">
                    <h3 class="text-base font-semibold text-orange-800 mb-4">Vision</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Vision (opsional)</label>
                            <input type="text" name="title_vission" placeholder="Contoh: Vision"
                                value="<?php echo htmlspecialchars($row['title_vission'] ?? ''); ?>"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Quote Vision</label>
                            <input type="text" name="quete_vission" required placeholder="Kutipan vision"
                                value="<?php echo htmlspecialchars($row['quete_vission']); ?>"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Vision</label>
                            <textarea name="description_vission" rows="3" required placeholder="Penjelasan vision perusahaan"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"><?php echo htmlspecialchars($row['description_vission']); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit"
                        class="flex-1 md:flex-none rounded-xl bg-slate-900 px-10 py-3.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all shadow-sm">
                        <i class='bx bx-save mr-2'></i> Simpan Perubahan
                    </button>
                    <a href="/dashboard/tagline"
                        class="flex-1 md:flex-none text-center rounded-xl bg-slate-100 px-10 py-3.5 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
