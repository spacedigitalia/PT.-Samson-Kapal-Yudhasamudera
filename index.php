<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/TaglineController.php';
require_once __DIR__ . '/controllers/MissionController.php';
require_once __DIR__ . '/controllers/ShiptypesController.php';
require_once __DIR__ . '/controllers/SystemManagementController.php';
require_once __DIR__ . '/controllers/SKYSAIBrainController.php';

$homeController = new HomeController($db);
$homeData = $homeController->getFirst();

$taglineController = new TaglineController($db);
$taglineData = $taglineController->getFirst();

$missionController = new MissionController($db);
$missionData = $missionController->getFirst();
$missionList = $missionData['list'] ?? [];

$shiptypesController = new ShiptypesController($db);
$shipTypes = $shiptypesController->getAll();

$systemMgmtController = new SystemManagementController($db);
$systemMgmtAll = $systemMgmtController->getAll();
usort($systemMgmtAll, fn($a, $b) => ($a['id'] ?? 0) <=> ($b['id'] ?? 0));
$systemMgmtSections = array_slice($systemMgmtAll, 0, 3);
$systemMgmtMainTitle = 'Sistem Manajemen Terintegrasi S K Y S';

$skyAiBrainController = new SKYSAIBrainController($db);
$skyAiBrainData = $skyAiBrainController->getFirst();
$skyAiBrainFeatures = $skyAiBrainData['features'] ?? [];
$skyAiBrainList = $skyAiBrainData['list'] ?? [];

?>

<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Surenusantara - Your trusted partner for comprehensive business solutions and services.">
    <meta name="keywords" content="Surenusantara, business solutions, consulting, professional services">
    <meta name="author" content="Surenusantara">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.samsonsure.co.id/">
    <meta property="og:title" content="Surenusantara - Business Solutions">
    <meta property="og:description" content="Your trusted partner for comprehensive business solutions and services.">
    <meta property="og:image" content="https://www.samsonsure.co.id/assets/logo.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.samsonsure.co.id/">
    <meta property="twitter:title" content="Surenusantara - Business Solutions">
    <meta property="twitter:description"
        content="Your trusted partner for comprehensive business solutions and services.">
    <meta property="twitter:image" content="https://www.samsonsure.co.id/assets/logo.jpg">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://www.samsonsure.co.id/" />

    <title>PT. Samson Kapal Yudhasamudera</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style/globals.css">

    <!-- AOS Animation CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <!-- Breadcrumb Structured Data -->
    <script src="/js/breadchumb.js"></script>
</head>

<body>
    <?php require_once __DIR__ . '/layout/Header.php'; ?>
    <main class="overflow-hidden">
        <!-- Home -->
        <section class="min-h-screen flex flex-col lg:flex-row">
            <!-- Left: Text content -->
            <div class="flex-1 lg:flex-[0.48] bg-[#1a1a1a] flex flex-col justify-center px-8 md:px-12 lg:px-16 xl:px-20 py-12 lg:py-16">
                <h1 class="text-[#f2e780] font-bold text-2xl sm:text-3xl md:text-4xl lg:text-[2.25rem] xl:text-5xl leading-tight tracking-tight mb-2" style="line-height: 1.5;">
                    <?= $homeData ? htmlspecialchars($homeData['title']) : 'PT. SAMSON KAPAL YUDHA SAMUDERA' ?>
                </h1>
                <p class="text-gray-300 text-sm md:text-base lg:text-xl max-w-2xl mb-6" style="line-height: 2.5;">
                    <?= $homeData ? htmlspecialchars($homeData['description']) : 'Manajemen Kapal Terintegrasi dengan AI dan Standar Internasional' ?>
                </p>
                <div class="flex items-center justify-center gap-2 bg-[#f2e780] rounded-full w-fit px-4">
                    <a href="<?= $homeData && !empty($homeData['button_link']) ? htmlspecialchars($homeData['button_link']) : '#' ?>" class="text-black text-xl inline-flex items-center gap-2" style="line-height: 2.5;">
                        <?= $homeData && !empty($homeData['button_text']) ? htmlspecialchars($homeData['button_text']) : 'Selengkapnya' ?>
                    </a>
                </div>
            </div>
            <!-- Right: Image -->
            <div class="flex-1 lg:flex-[0.52] relative min-h-[320px] lg:min-h-0">
                <img
                    src="<?= $homeData && !empty($homeData['image']) ? htmlspecialchars($homeData['image']) : 'assets/hero.jpg' ?>"
                    alt="PT. Samson Kapal Yudha Samudera - Kapal"
                    class="w-full h-full object-cover object-center" />
            </div>
        </section>

        <!-- Moto & Visi -->
        <section class="bg-[#1a1a1a] py-12 md:py-16 lg:py-20 px-6 md:px-10 lg:px-16 xl:px-24 relative">
            <div class="max-w-4xl">
                <!-- Motto -->
                <div class="mb-8">
                    <span class="inline-block bg-amber-700/80 text-amber-100 px-4 py-2 text-sm font-semibold rounded mb-4">
                        <?= $taglineData ? htmlspecialchars($taglineData['title_moto']) : 'MOTTO PERUSAHAAN' ?>
                    </span>
                    <p class="text-amber-100/95 text-xl md:text-2xl lg:text-3xl font-medium leading-relaxed mb-3">
                        <?= $taglineData ? htmlspecialchars($taglineData['quete_moto']) : '"Bukan Sekadar Pilihan Pertama, Tetapi Mitra Terpercaya untuk Selamanya."' ?>
                    </p>
                    <p class="text-gray-400 text-base md:text-lg">
                        <?= $taglineData ? htmlspecialchars($taglineData['description_moto']) : 'We are not the first, but we are determined to be the best for you.' ?>
                    </p>
                </div>

                <hr class="border-gray-600 mb-8" />

                <!-- Visi -->
                <div class="mb-6">
                    <span class="inline-block bg-amber-700/80 text-amber-100 px-4 py-2 text-sm font-semibold rounded mb-4">
                        <?= $taglineData ? htmlspecialchars($taglineData['title_vission']) : 'VISI PERUSAHAAN' ?>
                    </span>
                    <p class="text-amber-100/95 text-xl md:text-2xl lg:text-3xl font-medium leading-relaxed mb-3">
                        <?= $taglineData ? htmlspecialchars($taglineData['quete_vission']) : 'Menjadi perusahaan manajemen kapal pilihan utama di Asia.' ?>
                    </p>
                    <p class="text-gray-400 text-base md:text-lg">
                        <?= $taglineData ? htmlspecialchars($taglineData['description_vission']) : 'Dikenal karena dedikasi tanpa kompromi terhadap keselamatan, keandalan teknis, dan kemitraan yang transparan.' ?>
                    </p>
                </div>
            </div>
        </section>

        <!-- Misi -->
        <section class="min-h-[70vh] flex flex-col lg:flex-row">
            <!-- Left: Mission text (~60%) -->
            <div class="flex-1 lg:flex-[0.6] bg-[#1a1a1a] flex flex-col justify-center px-8 md:px-12 lg:px-16 xl:px-20 py-12 lg:py-16">
                <h2 class="text-[#D4AF37] font-bold text-2xl md:text-3xl lg:text-4xl mb-8 tracking-tight">
                    <?= $missionData ? htmlspecialchars($missionData['title']) : 'Misi Kami: Pilar Keunggulan SKYS' ?>
                </h2>
                <div class="space-y-6">
                    <?php foreach ($missionList as $item): ?>
                        <div class="relative bg-[#1a1a1a] border-t border-b border-[#D4AF37] pt-6 pb-4 px-5">
                            <div class="absolute -top-[14px] left-1/2 -translate-x-1/2 flex justify-center">
                                <span class="w-9 h-9 rounded-full bg-gradient-to-br from-[#D4AF37] to-[#B8860B] text-white font-bold flex items-center justify-center text-base shadow-md ring-2 ring-[#212121]"><?= (int)($item['number'] ?? 0) ?></span>
                            </div>
                            <h3 class="text-[#F0F0F0] font-semibold text-base md:text-lg mb-2"><?= htmlspecialchars($item['title'] ?? '') ?></h3>
                            <p class="text-[#B0B0B0] text-sm md:text-base leading-relaxed"><?= htmlspecialchars($item['description'] ?? '') ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Right: Image (~40%) -->
            <div class="flex-1 lg:flex-[0.4] relative min-h-[320px] lg:min-h-0">
                <img
                    src="<?= $missionData && !empty($missionData['image']) ? htmlspecialchars($missionData['image']) : 'assets/mission.jpg' ?>"
                    alt="Misi SKYS - Operasional"
                    class="w-full h-full object-cover object-center" />
                <a href="#" class="absolute bottom-4 right-4 bg-[#4CAF50] hover:bg-[#45a049] text-white font-medium text-sm px-4 py-2 rounded-lg shadow-lg transition-colors" title="Made by JIP">Made by JIP</a>
            </div>
        </section>

        <!-- Ship Types - Jenis Kapal -->
        <section class="bg-[#1C1C1C] py-12 md:py-16 lg:py-20 px-6 md:px-10 lg:px-16">
            <h2 class="text-[#FFD700] font-bold text-2xl md:text-3xl lg:text-4xl mb-10 md:mb-12">
                Jenis Kapal yang Kami Kelola
            </h2>

            <div class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <?php foreach ($shipTypes as $item): ?>
                    <div class="flex gap-4 items-start p-4 rounded-lg">
                        <img
                            src="<?= !empty($item['image']) ? htmlspecialchars((strpos($item['image'], '/') === 0 ? '' : '/') . $item['image']) : 'https://via.placeholder.com/56?text=Ship' ?>"
                            alt="<?= htmlspecialchars($item['title']) ?>"
                            class="w-14 h-14 flex-shrink-0 rounded-lg object-cover bg-[#2C2C2C]"
                            onerror="this.src='https://via.placeholder.com/56?text=Ship'" />
                        <div class="min-w-0 flex-1">
                            <h3 class="text-white font-bold text-base md:text-lg mb-1"><?= htmlspecialchars($item['title']) ?></h3>
                            <p class="text-[#CCCCCC] text-sm leading-relaxed"><?= htmlspecialchars($item['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- System Management - Sistem Manajemen Terintegrasi -->
        <section class="bg-[#1C1C1C] py-12 md:py-16 lg:py-20 px-6 md:px-10 lg:px-16 relative min-h-[70vh] flex flex-col lg:flex-row">
            <!-- Left: Text content -->
            <div class="flex-1 lg:flex-[0.55] flex flex-col justify-center pr-0 lg:pr-8 xl:pr-12">
                <h2 class="text-[#FFD700] font-bold text-2xl md:text-3xl lg:text-4xl mb-8 md:mb-10 tracking-tight">
                    <?= htmlspecialchars($systemMgmtMainTitle) ?>
                </h2>

                <?php foreach ($systemMgmtSections as $section): ?>
                <div class="mb-6 md:mb-8">
                    <h3 class="text-[#FFD700] font-bold text-base md:text-lg mb-2"><?= htmlspecialchars($section['title'] ?? '') ?></h3>
                    <?php if (!empty($section['description']) && trim((string)$section['description']) !== ''): ?>
                        <p class="text-gray-400 text-sm md:text-base mb-3"><?= htmlspecialchars($section['description']) ?></p>
                    <?php endif; ?>
                    <?php $sectionList = $section['list'] ?? []; if (!empty($sectionList)): ?>
                        <ul class="space-y-1.5 text-gray-400 text-sm md:text-base list-disc list-inside">
                            <?php foreach ($sectionList as $item): ?>
                                <li><?= htmlspecialchars($item['title'] ?? '') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Right: Dashboard image -->
            <div class="flex-1 lg:flex-[0.45] relative min-h-[320px] lg:min-h-[500px] mt-8 lg:mt-0 rounded-lg overflow-hidden">
                <img
                    src="assets/system-management-dashboard.png"
                    alt="SKYS - Ship Data & AI Analytic Dashboard"
                    class="w-full h-full object-cover object-center"
                    onerror="this.src='https://via.placeholder.com/800x500/1C1C1C/FFD700?text=SKYS+Dashboard'"
                />
                <a href="#" class="absolute bottom-4 right-4 bg-[#4CAF50] hover:bg-[#45a049] text-white font-medium text-sm px-4 py-2 rounded-lg shadow-lg transition-colors" title="Made by JIP">Made by JIP</a>
            </div>
        </section>

        <!-- Job-Description Key Responsibilities -->
        <section class="container min-h-full py-12 px-4">

        </section>

        <!-- Company management -->
        <section class="container min-h-full py-12 px-4">

        </section>

        <!-- Consultasi Banner -->
        <section class="container min-h-full py-12 px-4">

        </section>
    </main>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="js/main.js"></script>

    <?php require_once __DIR__ . '/layout/Footer.php'; ?>