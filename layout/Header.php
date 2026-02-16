<?php

/**
 * Site Header - PT. Samson Kapal Yudhasamudera
 * Navbar dengan logo, menu, dan mobile toggle.
 */
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$isHome = ($currentPage === 'index' || $currentPage === '');
?>
<header id="main-nav" class="sticky top-0 z-50 w-full">
    <div class="bg-[#1a1a1a]/95 backdrop-blur-sm border-b border-[#2a2a2a] shadow-lg">
        <div class="px-4 md:px-12 lg:px-16 xl:px-20">
            <div class="flex items-center justify-between h-16 md:h-18 lg:h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group">
                    <img src="/public/logo.jpeg" alt="PT. Samson Kapal Yudhasamudera" class="h-9 w-9 md:h-10 md:w-10 rounded object-cover" onerror="this.style.display='none'; this.nextElementSibling?.classList.remove('hidden');" />
                    <span class="hidden h-9 w-9 md:h-10 md:w-10 rounded bg-[#f2e780] flex items-center justify-center text-[#1a1a1a] font-bold text-sm">SK</span>
                    <span class="text-[#f2e780] font-bold text-base md:text-lg lg:text-xl tracking-tight group-hover:text-[#FFD700] transition-colors">
                        PT. Samson Kapal Yudhasamudera
                    </span>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center gap-1" aria-label="Main navigation">
                    <a href="/" class="nav-link px-4 py-2 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] transition-colors font-medium" data-nav-section="beranda">Beranda</a>
                    <a href="/#moto" class="nav-link px-4 py-2 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] transition-colors font-medium" data-nav-section="moto">Tentang</a>
                    <a href="/#jenis-kapal" class="nav-link px-4 py-2 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] transition-colors font-medium" data-nav-section="jenis-kapal">Jenis Kapal</a>
                    <a href="/#sistem-manajemen" class="nav-link px-4 py-2 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] transition-colors font-medium" data-nav-section="sistem-manajemen">Sistem</a>
                    <a href="/#layanan-kami" class="nav-link px-4 py-2 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] transition-colors font-medium" data-nav-section="layanan-kami">Layanan Kami</a>
                    <a href="/#hubungi-kami" class="nav-link px-4 py-2 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] transition-colors font-medium" data-nav-section="hubungi-kami" data-nav-path="contact">Kontak</a>
                </nav>

                <!-- Mobile menu button -->
                <div class="flex items-center lg:hidden">
                    <button type="button" id="nav-toggle" class="p-2 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] transition-colors" aria-label="Buka menu" aria-expanded="false">
                        <i class="bx bx-menu text-2xl" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile dropdown -->
        <div id="nav-menu" class="hidden lg:hidden border-t border-[#2a2a2a] bg-[#1a1a1a]" aria-hidden="true">
            <nav class="container mx-auto  flex flex-col gap-1" aria-label="Mobile navigation">
                <a href="/" class="nav-link px-4 py-3 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] font-medium" data-nav-section="beranda">Beranda</a>
                <a href="/#moto-visi" class="nav-link px-4 py-3 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] font-medium" data-nav-section="moto">Tentang</a>
                <a href="/#jenis-kapal" class="nav-link px-4 py-3 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] font-medium" data-nav-section="jenis-kapal">Jenis Kapal</a>
                <a href="/#sistem-manajemen" class="nav-link px-4 py-3 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] font-medium" data-nav-section="sistem-manajemen">Sistem</a>
                <a href="/#layanan-kami" class="nav-link px-4 py-3 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] font-medium" data-nav-section="layanan-kami">Layanan Kami</a>
                <a href="/#hubungi-kami" class="nav-link px-4 py-3 rounded-lg text-gray-300 hover:text-[#f2e780] hover:bg-[#2a2a2a] font-medium" data-nav-section="hubungi-kami" data-nav-path="contact">Kontak</a>
            </nav>
        </div>
    </div>
</header>
<script>
    (function() {
        var toggle = document.getElementById('nav-toggle');
        var menu = document.getElementById('nav-menu');
        var icon = toggle && toggle.querySelector('i');
        if (!toggle || !menu) return;
        toggle.addEventListener('click', function() {
            var open = !menu.classList.contains('hidden');
            menu.classList.toggle('hidden', open);
            toggle.setAttribute('aria-expanded', open ? 'false' : 'true');
            if (icon) {
                icon.classList.remove(open ? 'bx-x' : 'bx-menu');
                icon.classList.add(open ? 'bx-menu' : 'bx-x');
            }
        });
    })();
</script>