<?php

/**
 * Site Footer - PT. Samson Kapal Yudhasamudera
 */
$year = date('Y');
?>
<footer class="bg-[#1a1a1a] border-t border-[#2a2a2a] text-gray-400">
    <div class="px-8 md:px-12 lg:px-16 xl:px-20 py-12 md:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="/" class="inline-flex items-center gap-2 mb-4">
                    <img src="/public/logo.jpeg" alt="SKYS" class="h-10 w-10 rounded object-cover" onerror="this.style.display='none'; this.nextElementSibling?.classList.remove('hidden');" />
                    <span class="hidden h-10 w-10 rounded bg-[#f2e780] flex items-center justify-center text-[#1a1a1a] font-bold">SK</span>
                    <span class="text-[#f2e780] font-bold text-lg">PT. Samson Kapal Yudhasamudera</span>
                </a>
                <p class="text-sm leading-relaxed max-w-xs">
                    Mitra terpercaya manajemen kapal terintegrasi dengan AI dan standar internasional.
                </p>
            </div>

            <!-- Quick links (sama dengan Header) -->
            <div>
                <h3 class="text-[#f2e780] font-semibold text-sm uppercase tracking-wider mb-4">Tautan</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="hover:text-[#f2e780] transition-colors">Beranda</a></li>
                    <li><a href="/#moto" class="hover:text-[#f2e780] transition-colors">Tentang</a></li>
                    <li><a href="/#jenis-kapal" class="hover:text-[#f2e780] transition-colors">Jenis Kapal</a></li>
                    <li><a href="/#sistem-manajemen" class="hover:text-[#f2e780] transition-colors">Sistem</a></li>
                    <li><a href="/#layanan-kami" class="hover:text-[#f2e780] transition-colors">Layanan Kami</a></li>
                    <li><a href="/#hubungi-kami" class="hover:text-[#f2e780] transition-colors">Kontak</a></li>
                </ul>
            </div>

            <!-- Contact placeholder (bisa diisi dari DB nanti) -->
            <div>
                <h3 class="text-[#f2e780] font-semibold text-sm uppercase tracking-wider mb-4">Kontak</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2">
                        <i class="bx bx-phone text-[#f2e780] text-lg flex-shrink-0"></i>
                        <a href="tel:+622150208100" class="hover:text-[#f2e780] transition-colors">021-50208100</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bx bx-envelope text-[#f2e780] text-lg flex-shrink-0"></i>
                        <a href="mailto:operation@stmsenseman.com" class="hover:text-[#f2e780] transition-colors">operation@stmsenseman.com</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bx bx-globe text-[#f2e780] text-lg flex-shrink-0"></i>
                        <a href="https://www.stmsenseman.com" target="_blank" rel="noopener noreferrer" class="hover:text-[#f2e780] transition-colors">stmsenseman.com</a>
                    </li>
                </ul>
            </div>

            <!-- Legal / info -->
            <div>
                <h3 class="text-[#f2e780] font-semibold text-sm uppercase tracking-wider mb-4">Perusahaan</h3>
                <p class="text-sm leading-relaxed">
                    Manajemen kapal pilihan utama dengan dedikasi terhadap keselamatan, keandalan teknis, dan kemitraan yang transparan.
                </p>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-[#2a2a2a] flex items-center justify-center gap-4">
            <p class="text-sm text-gray-500">
                &copy; <?= (int) $year ?> PT. Samson Kapal Yudhasamudera. All rights reserved.
            </p>
        </div>
    </div>
</footer>