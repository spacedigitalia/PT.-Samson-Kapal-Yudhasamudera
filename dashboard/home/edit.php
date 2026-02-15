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
    header('Location: /dashboard/home');
    exit;
}

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/HomeController.php';

$auth = new AuthController($db);
$controller = new HomeController($db, $auth);
$row = $controller->getById($id);

if (!$row) {
    $_SESSION['error'] = 'Data tidak ditemukan.';
    header('Location: /dashboard/home');
    exit;
}

$active = 'home';

$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Home', 'href' => '/dashboard/home'],
    ['label' => 'Edit Content', 'href' => '#'],
];
?>
<div class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <main class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4 pb-4">
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 left-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <!-- Top bar -->
        <div class="px-4 md:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="lg:hidden w-10"></div>
                    <?php echo renderBreadcrumb($breadcrumbs); ?>
                </div>

                <a href="/dashboard/home"
                    class="flex items-center gap-2 rounded-2xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                    <i class='bx bx-arrow-back text-lg'></i>
                    <span class="hidden sm:inline">Back to Home</span>
                    <span class="sm:hidden">Back</span>
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <form action="/dashboard/home/process.php" method="POST" enctype="multipart/form-data" class="space-y-6"
                onsubmit="return validateForm()">
                <script>
                function validateForm() {
                    const title = document.querySelector('[name="title"]').value.trim();
                    const description = document.querySelector('[name="description"]').value.trim();

                    if (!title || !description) {
                        toastr.error('Harap lengkapi judul dan deskripsi');
                        return false;
                    }
                    return true;
                }
                </script>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                <input type="hidden" name="image_current" value="<?php echo htmlspecialchars($row['image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                        <input type="text" name="title" required placeholder="Masukkan judul utama..."
                            value="<?php echo htmlspecialchars($row['title']); ?>"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Image (Upload)</label>
                        <?php if (!empty($row['image'])): ?>
                        <div class="mb-2 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 inline-block">
                            <img src="<?php echo htmlspecialchars((strpos($row['image'], '/') === 0 ? '' : '/') . $row['image']); ?>" alt="" class="h-24 w-auto object-cover">
                        </div>
                        <p class="text-[10px] text-slate-500 mb-2">Gambar saat ini. Upload baru untuk mengganti.</p>
                        <?php endif; ?>
                        <input type="file" name="image" accept="image/*"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 transition-all">
                        <p class="mt-1 text-[10px] text-slate-500 italic">Format: JPG, PNG, WEBP (Max 10MB). Kosongkan untuk tetap pakai gambar lama.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="description" rows="3" required
                        placeholder="Tuliskan deskripsi singkat atau ringkasan..."
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"><?php echo htmlspecialchars($row['description']); ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Button Text</label>
                        <input type="text" name="button_text" placeholder="Contoh: Learn More"
                            value="<?php echo htmlspecialchars($row['button_text'] ?? ''); ?>"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
                        <p class="mt-1 text-[10px] text-slate-500 italic">Opsional. Teks tombol CTA.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Button Link</label>
                        <input type="url" name="button_link" placeholder="https:// atau /about"
                            value="<?php echo htmlspecialchars($row['button_link'] ?? ''); ?>"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
                        <p class="mt-1 text-[10px] text-slate-500 italic">Opsional. URL saat tombol diklik.</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit"
                        class="flex-1 md:flex-none rounded-xl bg-slate-900 px-10 py-3.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all shadow-sm">
                        <i class='bx bx-save mr-2'></i> Save Content
                    </button>
                    <a href="/dashboard/home"
                        class="flex-1 md:flex-none text-center rounded-xl bg-slate-100 px-10 py-3.5 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
