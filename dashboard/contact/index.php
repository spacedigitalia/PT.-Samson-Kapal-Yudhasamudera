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
require_once __DIR__ . '/../../controllers/ContactController.php';

$active = 'contact';
$breadcrumbs = [
    ['label' => 'Dashboard', 'href' => '/dashboard'],
    ['label' => 'Contact', 'href' => '/dashboard/contact'],
];

$auth = new AuthController($db);
$controller = new ContactController($db, $auth);
$contactData = $controller->getFirst();
?>
<section class="flex min-h-screen overflow-hidden">
    <?php require_once __DIR__ . '/../sidebar.php'; ?>

    <div class="flex-1 min-w-0 h-screen overflow-y-auto px-4 md:px-8 space-y-4">
        <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 right-4 z-40 h-10 w-10 rounded-xl bg-white/80 backdrop-blur-md border border-slate-200 shadow-sm grid place-items-center text-slate-600 hover:bg-slate-50 transition-all">
            <i class='bx bx-menu text-2xl'></i>
        </button>

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-50 via-blue-50 to-indigo-50 border border-sky-100/50 shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-sky-200/20 to-indigo-200/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-blue-200/20 to-sky-200/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

            <div class="relative px-6 md:px-8 py-6 md:py-8">
                <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-4 md:gap-6">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 shadow-lg flex items-center justify-center transform transition-transform hover:scale-105 hidden md:flex">
                            <i class='bx bx-contact text-2xl md:text-3xl text-white'></i>
                        </div>
                        <div class="flex flex-col flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-2">
                                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">
                                    Contact Management
                                </h1>
                                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-100 text-sky-700 text-xs font-semibold">
                                    <i class='bx bx-check-circle text-sm'></i>
                                    Active
                                </span>
                            </div>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-2xl">
                                Kelola informasi kontak perusahaan: alamat, telepon, email, dan website.
                            </p>
                            <?php if ($contactData): ?>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 pt-3 border-t border-sky-100/50">
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <i class='bx bx-check text-sky-500'></i>
                                    <span class="font-medium">Content Available</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <i class='bx bx-time text-blue-500'></i>
                                    <span>Last updated: <?php echo date('M d, Y', strtotime($contactData['updated_at'] ?? $contactData['created_at'])); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!$contactData): ?>
                    <div class="flex-shrink-0">
                        <a href="/dashboard/contact/create.php"
                            class="group flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-sky-600 to-blue-600 px-5 py-3 text-sm font-semibold text-white hover:from-sky-700 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class='bx bx-plus text-lg group-hover:rotate-90 transition-transform duration-300'></i>
                            <span class="hidden sm:inline">Add New Content</span>
                            <span class="sm:hidden">Add</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($contactData): ?>
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Contact Content Preview</h2>
                    <a href="/dashboard/contact/edit.php?id=<?php echo $contactData['id']; ?>"
                        class="inline-flex items-center gap-1 rounded-xl bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                        <i class='bx bx-edit'></i>
                        Edit Content
                    </a>
                </div>

                <?php if (!empty($contactData['image_url'])): ?>
                <div class="mb-4 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                    <img src="<?php echo htmlspecialchars((strpos($contactData['image_url'], '/') === 0 ? '' : '/') . $contactData['image_url']); ?>"
                        alt="<?php echo htmlspecialchars($contactData['company_name']); ?>"
                        class="w-full h-48 md:h-64 object-cover"
                        onerror="this.src='https://via.placeholder.com/800x300?text=Image+Not+Found'">
                </div>
                <?php endif; ?>

                <div class="space-y-4">
                    <h3 class="text-xl md:text-2xl font-semibold text-slate-900">
                        <?php echo htmlspecialchars($contactData['company_name']); ?>
                    </h3>
                    <?php if (!empty($contactData['description'])): ?>
                    <p class="text-sm text-slate-600 leading-relaxed"><?php echo htmlspecialchars($contactData['description']); ?></p>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <?php if (!empty($contactData['building_name'])): ?>
                        <div class="flex gap-2">
                            <i class='bx bx-building text-slate-400 mt-0.5'></i>
                            <span><?php echo htmlspecialchars($contactData['building_name']); ?><?php echo !empty($contactData['floor']) ? ' - Lantai ' . htmlspecialchars($contactData['floor']) : ''; ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($contactData['street_address']) || !empty($contactData['district'])): ?>
                        <div class="flex gap-2">
                            <i class='bx bx-map text-slate-400 mt-0.5'></i>
                            <span><?php echo htmlspecialchars(trim(($contactData['street_address'] ?? '') . ' ' . ($contactData['district'] ?? ''))); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($contactData['city']) || !empty($contactData['postal_code']) || !empty($contactData['country'])): ?>
                        <div class="flex gap-2">
                            <i class='bx bx-current-location text-slate-400 mt-0.5'></i>
                            <span><?php echo htmlspecialchars(implode(', ', array_filter([$contactData['city'] ?? '', $contactData['postal_code'] ?? '', $contactData['country'] ?? '']))); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($contactData['phone'])): ?>
                        <div class="flex gap-2">
                            <i class='bx bx-phone text-slate-400 mt-0.5'></i>
                            <a href="tel:<?php echo htmlspecialchars($contactData['phone']); ?>" class="text-sky-600 hover:underline"><?php echo htmlspecialchars($contactData['phone']); ?></a>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($contactData['email'])): ?>
                        <div class="flex gap-2">
                            <i class='bx bx-envelope text-slate-400 mt-0.5'></i>
                            <a href="mailto:<?php echo htmlspecialchars($contactData['email']); ?>" class="text-sky-600 hover:underline"><?php echo htmlspecialchars($contactData['email']); ?></a>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($contactData['website'])): ?>
                        <div class="flex gap-2">
                            <i class='bx bx-link text-slate-400 mt-0.5'></i>
                            <a href="<?php echo htmlspecialchars($contactData['website']); ?>" target="_blank" rel="noopener noreferrer" class="text-sky-600 hover:underline"><?php echo htmlspecialchars($contactData['website']); ?></a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-slate-200 text-xs text-slate-500">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-user text-slate-400'></i>
                            <span>Created by: <strong><?php echo htmlspecialchars($contactData['fullname'] ?? 'Unknown'); ?></strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class='bx bx-calendar text-slate-400'></i>
                            <span><?php echo date('M d, Y', strtotime($contactData['created_at'])); ?></span>
                        </div>
                        <?php if (isset($contactData['updated_at']) && $contactData['updated_at'] !== $contactData['created_at']): ?>
                        <div class="flex items-center gap-2">
                            <i class='bx bx-time text-slate-400'></i>
                            <span>Updated: <?php echo date('M d, Y', strtotime($contactData['updated_at'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-12 text-center">
            <i class='bx bx-inbox text-6xl text-slate-300 mb-4 block'></i>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">No Content Available</h3>
            <p class="text-sm text-slate-500 mb-4">
                Belum ada konten contact. Klik "Add New" untuk membuat konten pertama.
            </p>
            <a href="/dashboard/contact/create.php"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition-all">
                <i class='bx bx-plus text-lg'></i>
                Create First Content
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../footer.php'; ?>
