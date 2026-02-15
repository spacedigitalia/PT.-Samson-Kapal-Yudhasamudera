<?php
session_start();

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: /login.php');
    exit;
}

require_once __DIR__ . '/../header.php';

$active = 'company-kpis';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Company KPIs', 'href' => '/dashboard/company-kpis'],
    ['label' => 'Create', 'href' => '#'],
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
                <a href="/dashboard/company-kpis"
                    class="flex items-center gap-2 rounded-2xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                    <i class='bx bx-arrow-back text-lg'></i>
                    <span class="hidden sm:inline">Back to Company KPIs</span>
                    <span class="sm:hidden">Back</span>
                </a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <form action="/dashboard/company-kpis/process.php" method="POST" class="space-y-6" id="kpiForm">
                <script>
                function validateForm() {
                    const title = document.querySelector('[name="title"]').value.trim();
                    if (!title) {
                        toastr.error('Judul wajib diisi.');
                        return false;
                    }
                    return true;
                }
                function addListItem() {
                    const tpl = document.getElementById('listItemTpl');
                    const wrap = document.getElementById('listItemsWrap');
                    const div = document.createElement('div');
                    div.className = 'list-item-row flex flex-wrap gap-4 items-start p-4 rounded-xl border border-slate-200 bg-slate-50/50';
                    div.innerHTML = tpl.innerHTML;
                    wrap.appendChild(div);
                }
                function removeListItem(btn) { btn.closest('.list-item-row').remove(); }
                </script>
                <input type="hidden" name="action" value="create">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                    <input type="text" name="title" required placeholder="Judul Company KPIs..."
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="description" rows="4" required placeholder="Deskripsi..."
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition-all"></textarea>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-semibold text-slate-700">List (value, title, description) — value bisa contoh 99.8%</label>
                        <button type="button" onclick="addListItem()" class="rounded-lg bg-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-300">
                            <i class='bx bx-plus'></i> Add Item
                        </button>
                    </div>
                    <div id="listItemsWrap" class="space-y-3">
                        <template id="listItemTpl">
                            <input type="text" name="list_value[]" placeholder="99.8%" class="w-24 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_title[]" placeholder="Title" class="flex-1 min-w-[140px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_description[]" placeholder="Description" class="flex-1 min-w-[180px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <button type="button" onclick="removeListItem(this)" class="rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200"><i class='bx bx-trash'></i></button>
                        </template>
                        <div class="list-item-row flex flex-wrap gap-4 items-start p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                            <input type="text" name="list_value[]" placeholder="99.8%" class="w-24 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_title[]" placeholder="Title" class="flex-1 min-w-[140px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <input type="text" name="list_description[]" placeholder="Description" class="flex-1 min-w-[180px] rounded-lg border border-slate-200 px-3 py-2 text-sm">
                            <button type="button" onclick="removeListItem(this)" class="rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200"><i class='bx bx-trash'></i></button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                    <button type="submit" form="kpiForm" onclick="return validateForm()"
                        class="flex-1 md:flex-none rounded-xl bg-slate-900 px-10 py-3.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all shadow-sm">
                        <i class='bx bx-save mr-2'></i> Save
                    </button>
                    <a href="/dashboard/company-kpis"
                        class="flex-1 md:flex-none text-center rounded-xl bg-slate-100 px-10 py-3.5 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../footer.php'; ?>
