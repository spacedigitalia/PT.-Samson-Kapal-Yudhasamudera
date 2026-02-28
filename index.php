<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/TaglineController.php';
require_once __DIR__ . '/controllers/MissionController.php';
require_once __DIR__ . '/controllers/ShiptypesController.php';
require_once __DIR__ . '/controllers/SystemManagementController.php';
require_once __DIR__ . '/controllers/SKYSAIBrainController.php';
require_once __DIR__ . '/controllers/ServicesController.php';
require_once __DIR__ . '/controllers/CompanyKpisController.php';
require_once __DIR__ . '/controllers/PartnershipController.php';
require_once __DIR__ . '/controllers/ContactController.php';

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

$servicesController = new ServicesController($db);
$servicesList = $servicesController->getAll();
usort($servicesList, fn($a, $b) => ($a['id'] ?? 0) <=> ($b['id'] ?? 0));

$companyKpisController = new CompanyKpisController($db);
$companyKpisData = $companyKpisController->getFirst();
$companyKpisList = $companyKpisData['list'] ?? [];

$partnershipController = new PartnershipController($db);
$partnershipData = $partnershipController->getFirst();
$partnershipList = $partnershipData['list'] ?? [];

$contactController = new ContactController($db);
$contactData = $contactController->getFirst();

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
    <meta property="og:url" content="http://localhost:8000/">
    <meta property="og:title" content="Surenusantara - Business Solutions">
    <meta property="og:description" content="Your trusted partner for comprehensive business solutions and services.">
    <meta property="og:image" content="http://localhost:8000/favicon.ico">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="http://localhost:8000/">
    <meta property="twitter:title" content="Surenusantara - Business Solutions">
    <meta property="twitter:description"
        content="Your trusted partner for comprehensive business solutions and services.">
    <meta property="twitter:image" content="http://localhost:8000/favicon.ico">

    <!-- Canonical URL -->
    <link rel="canonical" href="http://localhost:8000/" />

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
            <div
                class="flex-1 lg:flex-[0.48] bg-[#1a1a1a] flex flex-col justify-center px-4 md:px-12 lg:px-16 xl:px-20 py-10 lg:py-16">
                <h1 class="text-[#f2e780] font-bold text-2xl sm:text-3xl md:text-4xl lg:text-[2.25rem] xl:text-5xl leading-tight tracking-tight mb-2"
                    style="line-height: 1.5;" data-aos="fade-up">
                    <?= $homeData ? htmlspecialchars($homeData['title']) : 'PT. SAMSON KAPAL YUDHA SAMUDERA' ?>
                </h1>
                <p class="text-gray-300 text-sm md:text-base lg:text-xl max-w-2xl mb-6" style="line-height: 2.5;"
                    data-aos="fade-up" data-aos-delay="100">
                    <?= $homeData ? htmlspecialchars($homeData['description']) : 'Manajemen Kapal Terintegrasi dengan AI dan Standar Internasional' ?>
                </p>
                <div class="flex items-center justify-center gap-2 bg-[#f2e780] rounded-full w-fit px-4"
                    data-aos="fade-up" data-aos-delay="200">
                    <a href="<?= $homeData && !empty($homeData['button_link']) ? htmlspecialchars($homeData['button_link']) : '#' ?>"
                        class="text-black text-md inline-flex items-center gap-2" style="line-height: 2.5;">
                        <?= $homeData && !empty($homeData['button_text']) ? htmlspecialchars($homeData['button_text']) : 'Selengkapnya' ?>
                    </a>
                </div>
            </div>
            <!-- Right: Image -->
            <div class="flex-1 lg:flex-[0.52] relative min-h-[320px] lg:min-h-0">
                <img src="<?= $homeData && !empty($homeData['image']) ? htmlspecialchars($homeData['image']) : 'assets/hero.jpg' ?>"
                    alt="PT. Samson Kapal Yudha Samudera - Kapal" class="w-full h-full object-cover object-center"
                    data-aos="fade-left" />
            </div>
        </section>

        <!-- Direktur Utama -->
        <section class="flex flex-col lg:flex-row">
            <!-- Left: Photo (sama seperti home) -->
            <div class="flex-1 lg:flex-[0.52] relative aspect-[4/5] lg:aspect-[4/5] order-2 lg:order-1 w-full">
                <img src="/assets/jhon-emanuel.jpeg" alt="Direktur Utama PT. Samson Kapal Yudha Samudera"
                    class="absolute inset-0 w-full h-full object-cover object-center"
                    onerror="this.src='https://via.placeholder.com/800x1000/2C2C2C/f2e780?text=Photo'"
                    data-aos="fade-right" />
            </div>

            <!-- Right: Text content -->
            <div
                class="flex-1 lg:flex-[0.48] flex flex-col justify-center px-4 md:px-12 lg:px-16 xl:px-20 py-6 lg:py-16 order-1 lg:order-2">
                <span class="inline-block bg-[#423d09] w-fit text-gray-300 px-4 py-2 text-sm font-semibold rounded mb-6"
                    data-aos="fade-up">
                    DIREKTUR UTAMA
                </span>

                <h2 class="text-[#f2e780] font-bold text-2xl sm:text-3xl md:text-4xl lg:text-[2.25rem] xl:text-7xl leading-tight tracking-tight mb-2"
                    style="line-height: 1.5;" data-aos="fade-up" data-aos-delay="100">
                    John Immanuel Pongduma
                </h2>

                <p class="text-gray-300 text-sm md:text-base lg:text-lg max-w-2xl leading-relaxed"
                    style="line-height: 2;" data-aos="fade-up" data-aos-delay="200">
                    Memimpin PT. Samson Eka Marina Nusantara (SEAMAN) dengan komitmen terhadap keselamatan, keandalan
                    operasional, dan kemitraan jangka panjang dengan seluruh stakeholder.
                </p>
            </div>
        </section>

        <!-- Moto & Visi -->
        <section id="moto" class="py-10 md:py-16 lg:py-20 px-4 md:px-10 lg:px-16 xl:px-24 relative">
            <div class="space-y-10">
                <!-- Motto -->
                <div class="space-y-10">
                    <span class="inline-block bg-[#423d09] text-gray-300 px-4 py-2 text-sm font-semibold rounded"
                        data-aos="fade-up">
                        <?= $taglineData ? htmlspecialchars($taglineData['title_moto']) : 'MOTTO PERUSAHAAN' ?>
                    </span>

                    <h2 class="text-[#f2e780] text-xl md:text-2xl lg:text-[2.25rem] xl:text-7xl max-w-6xl font-medium leading-relaxed"
                        style="line-height: 1.3;" data-aos="fade-up" data-aos-delay="100">
                        <?= $taglineData ? htmlspecialchars($taglineData['quete_moto']) : '"Bukan Sekadar Pilihan Pertama, Tetapi Mitra Terpercaya untuk Selamanya."' ?>
                    </h2>

                    <p class="text-gray-300 text-base md:text-lg" data-aos="fade-up" data-aos-delay="200">
                        <?= $taglineData ? htmlspecialchars($taglineData['description_moto']) : 'We are not the first, but we are determined to be the best for you.' ?>
                    </p>
                </div>

                <hr class="border-gray-600 mb-8" />

                <!-- Visi -->
                <div class="space-y-6">
                    <span class="inline-block bg-[#423d09] text-gray-300 px-4 py-2 text-sm font-semibold rounded"
                        data-aos="fade-up">
                        <?= $taglineData ? htmlspecialchars($taglineData['title_vission']) : 'VISI PERUSAHAAN' ?>
                    </span>

                    <h2 class="text-[#f2e780] text-xl md:text-2xl leading-relaxed" style="line-height: 1.3;"
                        data-aos="fade-up" data-aos-delay="100">
                        <?= $taglineData ? htmlspecialchars($taglineData['quete_vission']) : 'Menjadi perusahaan manajemen kapal pilihan utama di Asia.' ?>
                    </h2>

                    <p class="text-gray-400 text-base md:text-lg" data-aos="fade-up" data-aos-delay="200">
                        <?= $taglineData ? htmlspecialchars($taglineData['description_vission']) : 'Dikenal karena dedikasi tanpa kompromi terhadap keselamatan, keandalan teknis, dan kemitraan yang transparan.' ?>
                    </p>
                </div>
            </div>
        </section>

        <!-- Misi -->
        <section class="flex flex-col lg:flex-row">
            <!-- Left: Mission text (~60%) -->
            <div
                class="flex-1 lg:flex-[0.6] flex flex-col justify-center px-4 md:px-12 lg:px-16 xl:px-20 py-10 lg:py-16 space-y-20">
                <h2 class="text-[#f2e780] text-xl md:text-2xl xl:text-5xl max-w-6xl font-medium leading-relaxed"
                    style="line-height: 1.3;" data-aos="fade-up">
                    <?= $missionData ? htmlspecialchars($missionData['title']) : 'Misi Kami: Pilar Keunggulan SKYS' ?>
                </h2>

                <div class="space-y-10">
                    <?php foreach ($missionList as $i => $item): ?>
                        <div class="relative border-t-4 border-[#f2e780] pt-6 pb-4 px-5">
                            <div class="absolute -top-[28px] left-1/2 -translate-x-1/2 flex justify-center">
                                <span
                                    class="w-14 h-14 rounded-full bg-[#f2e780] text-[#1a1a1a] font-bold flex items-center justify-center text-base shadow-md ring-2 ring-[#212121]"><?= (int)($item['number'] ?? 0) ?></span>
                            </div>

                            <h3 class="text-gray-300 font-semibold text-base md:text-lg mb-4" data-aos="fade-up"
                                data-aos-delay="<?= $i * 80 ?>"><?= htmlspecialchars($item['title'] ?? '') ?></h3>
                            <p class="text-gray-400 text-sm md:text-base leading-relaxed" data-aos="fade-up"
                                data-aos-delay="<?= $i * 80 + 50 ?>"><?= htmlspecialchars($item['description'] ?? '') ?></p>

                            <div class="absolute left-0 bottom-0 border-r border-b border-gray-600 h-full"></div>
                            <div class="absolute right-0 bottom-0 border-r border-b border-gray-600 h-full"></div>
                            <div class="absolute right-0 left-0 bottom-0 border-b border-gray-600 w-full"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right: Image (~40%) -->
            <div class="flex-1 lg:flex-[0.4] relative min-h-[320px] lg:min-h-0">
                <img src="<?= $missionData && !empty($missionData['image']) ? htmlspecialchars($missionData['image']) : 'assets/mission.jpg' ?>"
                    alt="Misi SKYS - Operasional" class="w-full h-full object-cover object-center"
                    data-aos="fade-left" />
            </div>
        </section>

        <!-- Ship Types - Jenis Kapal -->
        <section id="jenis-kapal" class="py-10 md:py-16 lg:py-20 px-4 md:px-10 lg:px-16 space-y-10 md:space-y-20">
            <h2 class="text-[#f2e780] text-xl md:text-2xl xl:text-5xl max-w-6xl font-medium leading-relaxed"
                style="line-height: 1.3;" data-aos="fade-up">
                Jenis Kapal yang Kami Kelola
            </h2>

            <div class="mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <?php foreach ($shipTypes as $i => $item): ?>
                    <div class="flex gap-4 items-start p-4 rounded-lg">
                        <img src="<?= !empty($item['image']) ? htmlspecialchars((strpos($item['image'], '/') === 0 ? '' : '/') . $item['image']) : 'https://via.placeholder.com/56?text=Ship' ?>"
                            alt="<?= htmlspecialchars($item['title']) ?>"
                            class="w-20 h-20 flex-shrink-0 rounded-lg object-cover bg-[#2C2C2C]"
                            onerror="this.src='https://via.placeholder.com/56?text=Ship'" />
                        <div class="min-w-0 flex-1">
                            <h3 class="text-gray-300 font-semibold text-base md:text-lg mb-2" data-aos="fade-up"
                                data-aos-delay="<?= $i * 80 ?>"><?= htmlspecialchars($item['title']) ?></h3>
                            <p class="text-gray-400 text-sm md:text-base leading-relaxed" data-aos="fade-up"
                                data-aos-delay="<?= $i * 80 + 40 ?>"><?= htmlspecialchars($item['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- System Management - Sistem Manajemen Terintegrasi -->
        <section id="sistem-manajemen" class="relative flex flex-col lg:flex-row">
            <!-- Left: Text content -->
            <div
                class="flex-1 lg:flex-[0.55] flex flex-col justify-center pr-0 lg:pr-8 xl:pr-12 space-y-14 md:py-16 px-4 md:px-10 lg:px-16">
                <h2 class="text-[#f2e780] text-xl md:text-2xl xl:text-6xl max-w-3xl font-medium leading-relaxed"
                    style="line-height: 1.3;" data-aos="fade-up">
                    <?= htmlspecialchars($systemMgmtMainTitle) ?>
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                    <?php foreach ($systemMgmtSections as $i => $section): ?>
                        <div class="space-y-10">
                            <h3 class="text-[#f2e780] text-base md:text-xl" data-aos="fade-up"
                                data-aos-delay="<?= $i * 100 ?>"><?= htmlspecialchars($section['title'] ?? '') ?></h3>
                            <?php if (!empty($section['description']) && trim((string)$section['description']) !== ''): ?>
                                <p class="text-gray-400 text-base leading-relaxed capitalize" style="max-width: 250px;"
                                    data-aos="fade-up" data-aos-delay="<?= $i * 100 + 50 ?>">
                                    <?= htmlspecialchars($section['description']) ?></p>
                            <?php endif; ?>
                            <?php $sectionList = $section['list'] ?? [];
                            if (!empty($sectionList)): ?>
                                <ul class="space-y-4 text-gray-400 text-base list-disc list-inside leading-relaxed">
                                    <?php foreach ($sectionList as $j => $item): ?>
                                        <li data-aos="fade-up" data-aos-delay="<?= $i * 100 + 100 + $j * 60 ?>">
                                            <?= htmlspecialchars($item['title'] ?? '') ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="space-y-4">
                    <h3 class="text-[#f2e780] text-base md:text-xl" data-aos="fade-up">Teknologi A I Revolusioner</h3>
                    <p class="text-gray-400 text-base leading-relaxed capitalize" data-aos="fade-up"
                        data-aos-delay="100">SKYS AI Brain untuk <span class="text-[#f2e780]">predictive maintenance,
                            optimasi rute,</span> dan <span class="text-[#f2e780]">analitik in real-time</span>.</p>
                </div>
            </div>

            <!-- Right: Dashboard image -->
            <div
                class="flex-1 lg:flex-[0.45] relative min-h-[320px] lg:min-h-[500px] mt-8 lg:mt-0 rounded-lg overflow-hidden">
                <img src="assets/sm.jpg" alt="SKYS - Ship Data & AI Analytic Dashboard"
                    class="w-full h-full object-cover object-center"
                    onerror="this.src='https://via.placeholder.com/800x500/1C1C1C/FFD700?text=SKYS+Dashboard'"
                    data-aos="fade-left" />
            </div>
        </section>

        <!-- Services - Lima Pilar Layanan Utama -->
        <section id="layanan-kami" class="py-10 md:py-16 px-4 md:px-10 lg:px-16">
            <div class="space-y-14">
                <h2 class="text-[#f2e780] text-xl md:text-2xl xl:text-5xl max-w-6xl font-medium leading-relaxed"
                    style="line-height: 1.3;" data-aos="fade-up">
                    Lima Pilar Layanan Utama Kami
                </h2>

                <div class="space-y-4">
                    <?php
                    foreach ($servicesList as $i => $item):
                        $isLast = ($i === count($servicesList) - 1);
                    ?>
                        <div class="flex gap-4 md:gap-6 items-start group">
                            <!-- Icon container: chevron shape + icon -->
                            <div class="flex-shrink-0 flex flex-col items-center">
                                <div class="w-14 h-14 md:w-24 md:h-24 flex items-center justify-center transition-colors">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= htmlspecialchars((strpos($item['image'], '/') === 0 ? '' : '/') . $item['image']) ?>"
                                            alt="" class="w-full h-full object-cover">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0 pb-10 md:pb-12">
                                <h3 class="text-gray-300 font-semibold text-base md:text-lg mb-2" data-aos="fade-up"
                                    data-aos-delay="<?= $i * 80 ?>">
                                    <?= htmlspecialchars($item['title'] ?? '') ?>
                                </h3>
                                <?php if (!empty($item['description'])): ?>
                                    <p class="text-gray-400 text-sm md:text-base leading-relaxed" data-aos="fade-up"
                                        data-aos-delay="<?= $i * 80 + 40 ?>">
                                        <?= htmlspecialchars($item['description']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="text-gray-400 text-base leading-relaxed capitalize mt-4" data-aos="fade-up">
                    Setiap pilar kami dibangun di atas fondasi keunggulan dan inovasi.
                </p>
            </div>
        </section>

        <!-- SKYS AI Brain - Inovasi -->
        <section id="skys-ai-brain" class="py-10 md:py-16 px-4 md:px-10 lg:px-16">
            <div class="flex flex-col lg:flex-row gap-10 lg:gap-12">
                <!-- Left: Title, intro, feature cards -->
                <div class="flex-1 min-w-0 lg:min-w-[55%] lg:max-w-[60%] space-y-20">
                    <h2 class="text-[#f2e780] text-xl md:text-2xl lg:text-3xl xl:text-5xl font-bold leading-tight"
                        data-aos="fade-up">
                        <?= $skyAiBrainData ? htmlspecialchars($skyAiBrainData['title']) : 'Inovasi dengan SKYS AI Brain' ?>
                    </h2>

                    <div class="space-y-6">
                        <p class="text-gray-300 text-base md:text-lg leading-relaxed" data-aos="fade-up"
                            data-aos-delay="100">
                            <?= $skyAiBrainData && !empty($skyAiBrainData['description']) ? htmlspecialchars($skyAiBrainData['description']) : 'SKYS AI Brain adalah jantung inovasi kami, mengubah data menjadi keputusan cerdas.' ?>
                        </p>

                        <?php if (!empty($skyAiBrainFeatures)): ?>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <?php foreach ($skyAiBrainFeatures as $j => $f): ?>
                                    <div class="p-4 md:p-5 rounded-lg bg-[#2a2a2a] border border-gray-600/50 space-y-6">
                                        <h3 class="text-gray-300 font-semibold text-sm md:text-2xl max-w-[200px] mb-2"
                                            data-aos="fade-up" data-aos-delay="<?= 200 + $j * 80 ?>">
                                            <?= htmlspecialchars($f['title'] ?? '') ?></h3>
                                        <p class="text-gray-400 text-xs md:text-base leading-relaxed" data-aos="fade-up"
                                            data-aos-delay="<?= 200 + $j * 80 + 40 ?>">
                                            <?= htmlspecialchars($f['description'] ?? '') ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- SKYS Insight Portal / list section -->
                    <?php if (!empty($skyAiBrainList)): ?>
                        <div class="space-y-6">
                            <?php $titleList = $skyAiBrainData['title_list'] ?? 'SKYS INSIGHT PORTAL'; ?>
                            <?php if (trim($titleList) !== ''): ?>
                                <h3 class="text-[#f2e780] text-base md:text-lg" data-aos="fade-up">
                                    <?= htmlspecialchars($titleList) ?>
                                </h3>
                            <?php endif; ?>

                            <ul class="space-y-2 text-gray-400 text-sm md:text-base">
                                <?php foreach ($skyAiBrainList as $item): ?>
                                    <li class="flex items-start gap-2">
                                        <span class="text-gray-300">•</span>
                                        <span><?= htmlspecialchars($item['title'] ?? '') ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right: Brain / circuit image -->
                <?php if ($skyAiBrainData && !empty($skyAiBrainData['image'])): ?>
                    <div class="flex-shrink-0 w-full lg:w-[45%] xl:w-[40%]">
                        <img src="<?= htmlspecialchars((strpos($skyAiBrainData['image'], '/') === 0 ? '' : '/') . $skyAiBrainData['image']) ?>"
                            alt="<?= htmlspecialchars($skyAiBrainData['title'] ?? 'SKYS AI Brain') ?>"
                            class="w-full h-auto max-h-[480px] object-contain object-center rounded-lg"
                            onerror="this.style.display='none'" data-aos="fade-left">
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Komitmen & Kinerja Unggul -->
        <section id="komitmen-kinerja" class="py-10 md:py-16 px-4 md:px-10 lg:px-16">
            <div class="space-y-10">
                <h2 class="text-[#f2e780] text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold mb-10 md:mb-12"
                    data-aos="fade-up">
                    <?= $companyKpisData ? htmlspecialchars($companyKpisData['title']) : 'Komitmen & Kinerja Unggul' ?>
                </h2>

                <?php if (!empty($companyKpisList)): ?>
                    <?php
                    $mid = (int) ceil(count($companyKpisList) / 2);
                    $leftItems = array_slice($companyKpisList, 0, $mid);
                    $rightItems = array_slice($companyKpisList, $mid);
                    $parsePercent = function ($v) {
                        $v = trim((string) $v);
                        if ($v === '') return 0;
                        $v = str_replace(',', '.', $v);
                        if (preg_match('/^([\d.]+)\s*%?$/', $v, $m)) return (float) $m[1];
                        return 0;
                    };
                    ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
                        <div class="space-y-6">
                            <?php foreach ($leftItems as $item):
                                $rawValue = $item['value'] ?? '';
                                $percent = min(100, $parsePercent($rawValue));
                            ?>
                                <div class="kpi-card space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2.5 md:h-3 bg-gray-600 rounded-full overflow-hidden">
                                            <div class="kpi-progress-bar h-full bg-[#f2e780] rounded-full transition-all duration-1000 ease-out"
                                                style="width: 0;" data-value="<?= (float) $percent ?>"></div>
                                        </div>
                                        <span
                                            class="text-gray-400 font-semibold text-sm md:text-base whitespace-nowrap"><?= htmlspecialchars($rawValue) ?></span>
                                    </div>

                                    <h3 class="text-gray-400 text-base md:text-lg" data-aos="fade-up">
                                        <?= htmlspecialchars($item['title'] ?? '') ?></h3>
                                    <p class="text-gray-400 text-sm md:text-base" data-aos="fade-up" data-aos-delay="50">
                                        <?= htmlspecialchars($item['description'] ?? '') ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="space-y-6">
                            <?php foreach ($rightItems as $item):
                                $rawValue = $item['value'] ?? '';
                                $percent = min(100, $parsePercent($rawValue));
                            ?>
                                <div class="kpi-card space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2.5 md:h-3 bg-gray-600 rounded-full overflow-hidden">
                                            <div class="kpi-progress-bar h-full bg-[#f2e780] rounded-full transition-all duration-1000 ease-out"
                                                style="width: 0;" data-value="<?= (float) $percent ?>"></div>
                                        </div>
                                        <span
                                            class="text-gray-400 font-semibold text-sm md:text-base whitespace-nowrap"><?= htmlspecialchars($rawValue) ?></span>
                                    </div>

                                    <h3 class="text-gray-400 text-base md:text-lg" data-aos="fade-up">
                                        <?= htmlspecialchars($item['title'] ?? '') ?></h3>
                                    <p class="text-gray-400 text-sm md:text-base" data-aos="fade-up" data-aos-delay="50">
                                        <?= htmlspecialchars($item['description'] ?? '') ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($companyKpisData && !empty(trim($companyKpisData['description'] ?? ''))): ?>
                    <p class="text-gray-400 text-sm md:text-base mt-8 md:mt-10 max-w-4xl" data-aos="fade-up">
                        <?= htmlspecialchars($companyKpisData['description']) ?>
                    </p>
                <?php else: ?>
                    <p class="text-gray-400 text-sm md:text-base mt-8 md:mt-10 max-w-4xl" data-aos="fade-up">
                        Kami memantau 33 indikator KPI kunci, mengacu pada BIMCO Shipping KPI, untuk memastikan kinerja
                        terbaik.
                    </p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Partnership -->
        <section id="partnership" class="flex flex-col lg:flex-row">
            <!-- Left: Image (~40-45%) -->
            <?php if ($partnershipData && !empty($partnershipData['image'])): ?>
                <div class="w-full lg:w-[30%] lg:flex-shrink-0 relative min-h-[280px] lg:min-h-[300px]">
                    <img src="<?= htmlspecialchars((strpos($partnershipData['image'], '/') === 0 ? '' : '/') . $partnershipData['image']) ?>"
                        alt="<?= htmlspecialchars($partnershipData['title'] ?? 'Partnership') ?>"
                        class="w-full h-full object-cover object-center" onerror="this.style.display='none'"
                        data-aos="fade-right">
                </div>
            <?php endif; ?>

            <!-- Right: Text content (~55-60%), dark bg -->
            <div class="flex-1 flex flex-col justify-center px-4 md:px-10 lg:px-12 xl:px-20 py-12 lg:py-16 space-y-14">
                <h2 class="text-[#f2e780] text-xl md:text-2xl lg:text-3xl xl:text-7xl" data-aos="fade-up">
                    <?= $partnershipData ? htmlspecialchars($partnershipData['title']) : 'Prinsip Kemitraan Kami' ?>
                </h2>

                <?php if ($partnershipData && !empty(trim($partnershipData['quete'] ?? ''))): ?>
                    <div class="flex gap-4">
                        <div class="w-1 flex-shrink-0 bg-[#f2e780] rounded-full self-stretch min-h-[60px]"></div>
                        <p class="text-gray-300 text-base md:text-lg leading-relaxed flex-1" style="line-height: 2;"
                            data-aos="fade-up" data-aos-delay="100">
                            <?= nl2br(htmlspecialchars($partnershipData['quete'])) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($partnershipList)): ?>
                    <?php $partnershipListTitle = trim($partnershipData['title_list'] ?? '') !== '' ? $partnershipData['title_list'] : 'Komitmen SKYS kepada Mitra'; ?>
                    <h3 class="text-[#f2e780] text-lg md:text-2xl" data-aos="fade-up">
                        <?= htmlspecialchars($partnershipListTitle) ?>
                    </h3>

                    <ul class="space-y-2 text-gray-300 text-sm md:text-base">
                        <?php foreach ($partnershipList as $i => $item): ?>
                            <li class="flex items-start gap-2" data-aos="fade-up" data-aos-delay="<?= 150 + $i * 80 ?>">
                                <span class="text-[#f2e780]">•</span>
                                <span><?= htmlspecialchars($item['title'] ?? '') ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </section>

        <!-- Hubungi Kami / Contact -->
        <section id="hubungi-kami" class="flex flex-col lg:flex-row">
            <!-- Left: Contact info (~2/3) -->
            <div
                class="flex-1 lg:flex-[0.65] flex flex-col justify-center px-4 md:px-12 lg:px-16 xl:px-20 py-10 lg:py-20 space-y-10">
                <h2 class="text-[#f2e780] text-xl md:text-5xl" data-aos="fade-up">
                    Hubungi Kami
                </h2>

                <?php if ($contactData): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-10">
                            <h3 class="text-[#f2e780] text-lg md:text-2xl" data-aos="fade-up" data-aos-delay="100">
                                <?= htmlspecialchars($contactData['company_name']) ?>
                            </h3>

                            <div class="text-white text-sm md:text-base space-y-4">
                                <?php if (!empty($contactData['building_name'])): ?>
                                    <p class="text-lg" data-aos="fade-up" data-aos-delay="150">
                                        <?= htmlspecialchars($contactData['building_name']) ?><?= !empty($contactData['floor']) ? ', ' . htmlspecialchars($contactData['floor']) : '' ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($contactData['district'])): ?>
                                    <p class="text-lg" data-aos="fade-up" data-aos-delay="200">
                                        <?= htmlspecialchars($contactData['district']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($contactData['street_address'])): ?>
                                    <p class="text-lg" data-aos="fade-up" data-aos-delay="250">
                                        <?= htmlspecialchars($contactData['street_address']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($contactData['city'])): ?>
                                    <p class="text-lg" data-aos="fade-up" data-aos-delay="300">
                                        <?= htmlspecialchars($contactData['city']) ?><?= !empty($contactData['postal_code']) ? ', ' . htmlspecialchars($contactData['postal_code']) : '' ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($contactData['country'])): ?>
                                    <p class="text-lg" data-aos="fade-up" data-aos-delay="350">
                                        <?= htmlspecialchars($contactData['country']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="space-y-10">
                            <h3 class="text-[#f2e780] text-lg md:text-2xl" data-aos="fade-up" data-aos-delay="100">
                                Kontak Langsung
                            </h3>

                            <ul class="text-white text-sm md:text-base space-y-4">
                                <?php if (!empty($contactData['phone'])): ?>
                                    <li class="text-lg" data-aos="fade-up" data-aos-delay="150">Telepon: <a
                                            href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', $contactData['phone'])) ?>"
                                            class="hover:text-[#f2e780] transition-colors text-lg"><?= htmlspecialchars($contactData['phone']) ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (!empty($contactData['email'])): ?>
                                    <li class="text-lg" data-aos="fade-up" data-aos-delay="200">Email: <a
                                            href="mailto:<?= htmlspecialchars($contactData['email']) ?>"
                                            class="hover:text-[#f2e780] transition-colors text-lg"><?= htmlspecialchars($contactData['email']) ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (!empty($contactData['website'])): ?>
                                    <li class="text-lg" data-aos="fade-up" data-aos-delay="250">Website: <a
                                            href="<?= htmlspecialchars(strpos($contactData['website'], 'http') === 0 ? $contactData['website'] : 'https://' . $contactData['website']) ?>"
                                            target="_blank" rel="noopener noreferrer"
                                            class="hover:text-[#f2e780] transition-colors"><?= htmlspecialchars($contactData['website']) ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty(trim($contactData['description'] ?? ''))): ?>
                    <p class="text-white text-sm md:text-lg leading-relaxed max-w-2xl" data-aos="fade-up">
                        <?= nl2br(htmlspecialchars($contactData['description'])) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Right: Image (~1/3) -->
            <div class="flex-1 lg:flex-[0.35] relative min-h-[280px] lg:min-h-0">
                <?php if ($contactData && !empty($contactData['image_url'])): ?>
                    <img src="<?= htmlspecialchars((strpos($contactData['image_url'], '/') === 0 ? '' : '/') . $contactData['image_url']) ?>"
                        alt="<?= htmlspecialchars($contactData['company_name']) ?>"
                        class="w-full h-full min-h-[280px] object-cover object-center"
                        onerror="this.src='https://via.placeholder.com/800x600/282828/f2e780?text=PT.+Samson+Kapal'"
                        data-aos="fade-left">
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="js/main.js"></script>

    <?php require_once __DIR__ . '/layout/Footer.php'; ?>