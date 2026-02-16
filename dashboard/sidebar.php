<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;
$active = $active ?? 'dashboard';

function navClass(string $key, string $active): string
{
    $base = 'flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold ';
    if ($key === $active) {
        return $base . 'bg-slate-900 text-white';
    }

    return $base . 'text-slate-700 hover:bg-slate-100';
}
?>
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white text-slate-800 h-screen min-h-screen overflow-y-auto flex flex-col border-r border-slate-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0">
    <div class="px-6 py-6 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="h-14 w-14 rounded-xl overflow-hidden">
                <img src="/public/logo.jpeg" alt="PT Samson Sure" class="h-full w-full object-cover">
            </div>
            <div>
                <div class="text-base font-semibold leading-tight">PT. Samson Kapal Yudhasamudera</div>
                <div class="text-xs text-slate-500">Manajemen Kapal</div>
            </div>
        </div>
        <!-- Close button for mobile -->
        <button id="close-sidebar" class="lg:hidden text-slate-500 hover:text-slate-900">
            <i class="bx bx-x text-2xl"></i>
        </button>
    </div>

    <nav class="px-4 py-4 flex-1 space-y-1">
        <a href="/dashboard" class="<?php echo navClass('dashboard', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'dashboard' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-grid-alt"></i>
            </span>
            Dashboard
        </a>

        <div class="pt-4 pb-2 px-3 text-[11px] uppercase tracking-wider text-slate-400">Workspace</div>

        <a href="/dashboard/home" class="<?php echo navClass('home', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'home' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-home-alt-2"></i>
            </span>
            Home
        </a>

        <a href="/dashboard/tagline" class="<?php echo navClass('tagline', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'tagline' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-purchase-tag-alt"></i>
            </span>
            Tagline
        </a>

        <a href="/dashboard/mission" class="<?php echo navClass('mission', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'mission' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-list-check"></i>
            </span>
            Mission
        </a>

        <a href="/dashboard/ship-types" class="<?php echo navClass('ship-types', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'ship-types' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bxs-ship"></i>
            </span>
            Ship Types
        </a>

        <a href="/dashboard/management-systems" class="<?php echo navClass('management-systems', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'management-systems' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-shield"></i>
            </span>
            Management Systems
        </a>

        <a href="/dashboard/services" class="<?php echo navClass('services', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'services' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-cog"></i>
            </span>
            Services
        </a>

        <a href="/dashboard/skys-ai-brain" class="<?php echo navClass('skys-ai-brain', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'skys-ai-brain' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-brain"></i>
            </span>
            Skys AI Brain
        </a>

        <a href="/dashboard/company-kpis" class="<?php echo navClass('company-kpis', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'company-kpis' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-trending-up"></i>
            </span>
            Company KPIs
        </a>

        <a href="/dashboard/partnership" class="<?php echo navClass('partnership', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'partnership' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-group"></i>
            </span>
            Partnership
        </a>

        <a href="/dashboard/contact" class="<?php echo navClass('contact', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'contact' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-phone"></i>
            </span>
            Contact
        </a>

        <div class="pt-4 pb-2 px-3 text-[11px] uppercase tracking-wider text-slate-400">Account</div>

        <a href="/dashboard/profile" class="<?php echo navClass('profile', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'profile' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-user"></i>
            </span>
            Profile
        </a>

        <a href="/dashboard/logs" class="<?php echo navClass('logs', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'logs' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-history"></i>
            </span>
            Logs
        </a>

        <a href="/" class="<?php echo navClass('back-home', $active); ?>">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg <?php echo $active === 'back-home' ? 'bg-white/15' : 'bg-slate-100 text-slate-700'; ?>">
                <i class="bx bx-home-circle"></i>
            </span>
            Back Home
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-slate-200">
        <div class="flex items-center gap-3 px-3 py-3 rounded-2xl bg-slate-50 border border-slate-200">
            <div class="h-10 w-10 rounded-md overflow-hidden">
                <img src="/public/logo.jpeg" alt="profile" />
            </div>
            <div class="min-w-0">
                <div class="text-sm font-semibold truncate">
                    <?php echo htmlspecialchars($user['fullname'] ?? 'Admin'); ?></div>
                <div class="text-xs text-slate-500 truncate"><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
            </div>
        </div>

        <form action="/dashboard/process.php" method="POST" class="mt-3">
            <input type="hidden" name="action" value="logout">
            <button type="submit"
                class="w-full rounded-xl bg-rose-600 px-3 py-2.5 text-sm font-semibold text-white hover:bg-rose-700">
                Logout
            </button>
        </form>
    </div>
</aside>