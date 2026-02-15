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
    header('Location: /dashboard/mission');
    exit;
}

require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/MissionController.php';

$auth = new AuthController($db);
$controller = new MissionController($db, $auth);
$row = $controller->getById($id);

if (!$row) {
    $_SESSION['error'] = 'Data tidak ditemukan.';
    header('Location: /dashboard/mission');
    exit;
}

$active = 'mission';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Mission', 'href' => '/dashboard/mission'],
    ['label' => 'Edit Content', 'href' => '#'],
];
$listItems = $row['list'] ?? [];
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
                <a href="/dashboard/mission"
                    class="flex items-center gap-2 rounded-2xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                    <i class='bx bx-arrow-back text-lg'></i>
                    <span class="hidden sm:inline">Back to Mission</span>
                    <span class="sm:hidden">Back</span>
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <form action="/dashboard/mission/process.php" method="POST" enctype="multipart/form-data" class="space-y-6" id="missionForm">
                <script>
                function validateForm() {
                    const title = document.querySelector('[name="title"]').value.trim();
                    if (!title) {
                        toastr.error('Judul mission wajib diisi.');
                        return false;
                    }
                    const numbers = document.querySelectorAll('[name="list_number[]"]');
                    for (let i = 0; i < numbers.length; i++) {
                        const n = numbers[i].value.trim();
                        const t = document.querySelectorAll('[name="list_title[]"]')[i].value.trim();
                        const d = document.querySelectorAll('[name="list_description[]"]')[i].value.trim();
                        if (n !== '' || t !== '' || d !== '') {
                            if (!t || !d) {
                                toastr.error('Untuk setiap item list, isi Number, Title, dan Description.');
                                return false;
                            }
                        }
                    }
                    return true;
                }
                function addListItem() {
                    const tpl = document.getElementById('listItemTpl');
                    const wrap = document.getElementById('listItemsWrap');
                    const div = document.createElement('div');
                    div.className = 'list-item-row flex flex-wrap gap-4 items-start p-4 rounded-xl border border-slate-200 bg-slate-50/50';
                    div.innerHTML = tpl.innerHTML.replace(/_INDEX_/g, Date.now());
                    wrap.appendChild(div);
                }
                function removeListItem(btn) {
                    btn.closest('.list-item-row').remove();
                }
                </script>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                <input type="hidden" name="image_current" value="<?php echo htmlspecialchars($row['image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                        <input type="text" name="title" required placeholder="Judul mission..."
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
                        <p class="mt-1 text-[10px] text-slate-500 italic">Kosongkan untuk tetap pakai gambar lama.</p>
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-semibold text-slate-700">List (number, title, description)</label>
                        <button type="button" onclick="addListItem()"
                            class="rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-300">
                            <i class='bx bx-plus'></i> Add Item
                        </button>
                    </div>
                    <div id="listItemsWrap" class="space-y-3">
                        <template id="listItemTpl">
                            <input type="number" name="list_number[]" placeholder="No" min="1" class="w-20 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_title[]" placeholder="Title" class="flex-1 min-w-[120px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_description[]" placeholder="Description" class="flex-1 min-w-[180px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <button type="button" onclick="removeListItem(this)" class="rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200">
                                <i class='bx bx-trash'></i>
                            </button>
                        </template>
                        <?php foreach ($listItems as $item): ?>
                        <div class="list-item-row flex flex-wrap gap-4 items-start p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                            <input type="number" name="list_number[]" placeholder="No" min="1" value="<?php echo htmlspecialchars((string)($item['number'] ?? '')); ?>" class="w-20 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_title[]" placeholder="Title" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" class="flex-1 min-w-[120px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_description[]" placeholder="Description" value="<?php echo htmlspecialchars($item['description'] ?? ''); ?>" class="flex-1 min-w-[180px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <button type="button" onclick="removeListItem(this)" class="rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($listItems)): ?>
                        <div class="list-item-row flex flex-wrap gap-4 items-start p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                            <input type="number" name="list_number[]" placeholder="No" min="1" class="w-20 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_title[]" placeholder="Title" class="flex-1 min-w-[120px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_description[]" placeholder="Description" class="flex-1 min-w-[180px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <button type="button" onclick="removeListItem(this)" class="rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" form="missionForm" onclick="return validateForm()"
                        class="flex-1 md:flex-none rounded-xl bg-slate-900 px-10 py-3.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all shadow-sm">
                        <i class='bx bx-save mr-2'></i> Save
                    </button>
                    <a href="/dashboard/mission"
                        class="flex-1 md:flex-none text-center rounded-xl bg-slate-100 px-10 py-3.5 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
