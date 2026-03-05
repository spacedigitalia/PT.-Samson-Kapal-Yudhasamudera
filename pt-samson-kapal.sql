-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 05, 2026 at 04:06 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pt-samson-kapal`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int UNSIGNED NOT NULL,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `fullname`, `email`, `password`, `role_id`, `created_at`, `updated_at`) VALUES
(2, 'admin', 'admin@gmail.com', '$2y$10$zexAiWBCCIdbHclG0J7Un.Oi5a0cPW0iHUJoJToyfplaG7zQqFHc.', 2, '2026-02-15 00:00:02', '2026-02-15 15:44:15');

-- --------------------------------------------------------

--
-- Table structure for table `company_kpis`
--

CREATE TABLE `company_kpis` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `list` json NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_kpis`
--

INSERT INTO `company_kpis` (`id`, `title`, `description`, `list`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Komitmen & Kinerja Unggul', 'Kami memantau 33 indikator KPI kunci, mengacu pada BIMCO Shipping KPI, untuk memastikan kinerja terbaik.', '[{\"title\": \"Technical Availability\", \"value\": \"99.8%\", \"description\": \"Kapal selalu siap beroperasi.\"}, {\"title\": \"PSC Detention\", \"value\": \"0\", \"description\": \"Tidak ada penahanan dalam 3 tahun terakhir.\"}, {\"title\": \"Class & Statutory Compliance\", \"value\": \"100%\", \"description\": \"Kepatuhan penuh terhadap regulasi.\"}, {\"title\": \"Fuel Efficiency\", \"value\": \"12%\", \"description\": \"Peningkatan efisiensi berkat AI.\"}, {\"title\": \"Crew Retention\", \"value\": \"98.5%\", \"description\": \"Tingkat retensi awak kapal yang tinggi.\"}, {\"title\": \"Transport Loss\", \"value\": \"0.009%\", \"description\": \"Jauh di bawah standar industri (0,3%).\"}]', 2, '2026-02-15 11:19:10', '2026-02-15 19:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int UNSIGNED NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `building_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street_address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `company_name`, `building_name`, `floor`, `district`, `street_address`, `city`, `postal_code`, `country`, `phone`, `email`, `website`, `description`, `image_url`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'PT. SAMSON KAPAL YUDHA SAMUDERA', 'Prosperity Tower', '8th Floor', 'Kawasan District 8, SCBD LOT 13', 'Jl. Jend. Sudirman Kav 52-53, RT.5/RW.3', 'Jakarta Selatan', '12190', 'Indonesia', '021-50208100', 'operation@stmsenseman.com', 'https://www.stmsenseman.com', 'Kami siap menjadi mitra terpercaya Anda dalam manajemen kapal. Jangan ragu untuk menghubungi tim kami.', '/upload/contact/contact_20260215083306_869ec952.jpg', 2, '2026-02-15 15:33:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `homes`
--

CREATE TABLE `homes` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_link` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `homes`
--

INSERT INTO `homes` (`id`, `title`, `description`, `image`, `button_text`, `button_link`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'PT. S A M S O N K A P A L Y U D H A S A M U D E R A ( S K Y S )', 'Manajemen Kapal Terintegrasi dengan AI dan Standar Internasional\r\nDIREKTUR UTAMA: Capt. John Immanuel Pongduma, S.Si.T, S.H., M.Mar', '/upload/homes/homes_20260305160219_00f6d5c6.jpeg', 'Moto Perusahaan', 'http://localhost:8000#moto', 2, '2026-02-15 00:54:01', '2026-03-05 23:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` int UNSIGNED NOT NULL DEFAULT '0',
  `blocked_until` datetime DEFAULT NULL,
  `last_attempt_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(50, 2, 'login_success', 'Login admin berhasil untuk email/nama admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:02:06'),
(51, 2, 'home_update', 'Updated home id 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:02:19'),
(52, 2, 'ship_type_update', 'Updated ship type id 8', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:03:18'),
(53, 2, 'ship_type_update', 'Updated ship type id 7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:03:30'),
(54, 2, 'ship_type_update', 'Updated ship type id 6', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:03:44'),
(55, 2, 'ship_type_update', 'Updated ship type id 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:03:57'),
(56, 2, 'ship_type_update', 'Updated ship type id 4', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:04:13'),
(57, 2, 'ship_type_update', 'Updated ship type id 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:04:24'),
(58, 2, 'ship_type_update', 'Updated ship type id 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:04:36'),
(59, 2, 'ship_type_update', 'Updated ship type id 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:04:45'),
(60, 2, 'sky_sai_brain_update', 'Updated id 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `management_systems`
--

CREATE TABLE `management_systems` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `list` json NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `management_systems`
--

INSERT INTO `management_systems` (`id`, `title`, `description`, `list`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Fo n d a s i Kepatuhan Global', 'kebutuhan penuh terhadap regulasi IMO:', '[{\"title\": \"Safety of Life at Sea\"}, {\"title\": \"Standards of Training, Certification and Watchkeeping\"}, {\"title\": \"Marine Pollution\"}, {\"title\": \"Maritime Labour Convention\"}]', 2, '2026-02-15 10:34:17', NULL),
(2, 'Sertifikasi I S O Terkemuka', NULL, '[{\"title\": \"ISO 9001:2015 – Sistem Manajemen Mutu\"}, {\"title\": \"ISO 14001:2015 – Sistem Manajemen Lingkungan\"}, {\"title\": \"ISO 45001:2018 – Sistem Manajemen K3 (Keselamatan dan Kesehatan Kerja)\"}, {\"title\": \"ISO 50001:2018 – Sistem Manajemen Energi\"}]', 2, '2026-02-15 10:35:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `missions`
--

CREATE TABLE `missions` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `list` json NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `missions`
--

INSERT INTO `missions` (`id`, `title`, `image`, `list`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Misi Kami: Pilar Keunggulan S K Y S', '/upload/missions/missions_20260215030803_876cee02.jpg', '[{\"title\": \"Keu nggulan Operasional\", \"number\": \"1\", \"description\": \"Layanan kelas dunia dengan sistem terintegrasi ISO dan AI.\"}, {\"title\": \"Keselamatan & Kepatuhan\", \"number\": \"2\", \"description\": \"Budaya safety first, 100% kepatuhan IMO s pemerintah.\"}, {\"title\": \"Pen gembangan S D M\", \"number\": \"3\", \"description\": \"Pelaut terbaik melalui pelatihan berkelanjutan.\"}, {\"title\": \"Kemitraan Berkelanjutan\", \"number\": \"4\", \"description\": \"Hubungan transparan, komunikatif, dan jangka panjang.\"}]', 2, '2026-02-15 10:08:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partnership`
--

CREATE TABLE `partnership` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quete` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_list` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `list` json NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partnership`
--

INSERT INTO `partnership` (`id`, `title`, `quete`, `image`, `title_list`, `list`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Prinsip Kemitraan Kami', '\"Kami tidak hanya mengelola kapal, kami membangun kemitraan yang saling menguntungkan. Transparansi, integritas, dan komunikasi adalah kunci hubungan profesional kami.\"', '/upload/partnership/partnership_20260216020113_fbaef21f.jpg', 'Komitmen SKYS kepada Mitra', '[{\"title\": \"Kualitas Terbaik: Layanan superior yang melebihi ekspektasi.\"}, {\"title\": \"Harga Kompetitif: Nilai optimal dengan biaya yang efisien.\"}, {\"title\": \"Hubungan Profesional Jangka Panjang: Membangun kepercayaan untuk kemitraan berkelanjutan.\"}]', 2, '2026-02-15 14:27:06', '2026-02-16 09:01:13');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` tinyint UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(2, 'admin'),
(1, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `image`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'S M K & Keselamatan Kerja', 'Sesuai ISM Code s ISO 45001, didukung AI.', '/upload/services/services_20260215034412_d5835a78.png', 2, '2026-02-15 10:44:12', NULL),
(2, 'Manajemen Teknis & Perawatan', 'ISO 9001, ISO 50001, diperkuat AI.', '/upload/services/services_20260215034428_65f3e90b.png', 2, '2026-02-15 10:44:29', NULL),
(3, 'Perbaikan & D o c k i n g Strategis', 'Berdasarkan ISO 9001, dengan bantuan AI.', '/upload/services/services_20260215034506_7cd4b82a.png', 2, '2026-02-15 10:44:49', '2026-02-15 10:45:06'),
(4, 'Pengawakan (Crewing)', 'ISO 9001, STCW, MLC 2006, dioptimalkan AI.', '/upload/services/services_20260215034524_113c6b52.png', 2, '2026-02-15 10:45:24', NULL),
(5, 'Sertifikasi & Kepatuhan', 'ISO 14001, dipantau dan diprediksi AI.', '/upload/services/services_20260215034540_296863c4.png', 2, '2026-02-15 10:45:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ship_types`
--

CREATE TABLE `ship_types` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ship_types`
--

INSERT INTO `ship_types` (`id`, `title`, `description`, `image`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Tanker', 'Crude Oil, Chemical, Product', '/upload/ship_types/ship_types_20260215031640_9d871952.jpg', 2, '2026-02-15 10:16:40', '2026-03-05 23:04:45'),
(2, 'Container', 'Feeder hingga Ultra & Large', '/upload/ship_types/ship_types_20260215031722_a3d18f69.jpg', 2, '2026-02-15 10:17:22', '2026-03-05 23:04:36'),
(3, 'Tugboat', 'Harbor & Ocean Towing', '/upload/ship_types/ship_types_20260215031758_df293525.jpg', 2, '2026-02-15 10:17:58', '2026-03-05 23:04:24'),
(4, 'Bulk Carrier', 'Handy hingga Capesize', '/upload/ship_types/ship_types_20260215031911_96dcc304.jpg', 2, '2026-02-15 10:19:11', '2026-03-05 23:04:13'),
(5, 'Ro-Ro Passenger', 'Ferry & Vehicle Carrier', '/upload/ship_types/ship_types_20260215032003_8d8ddd56.jpg', 2, '2026-02-15 10:20:03', '2026-03-05 23:03:57'),
(6, 'Crew Boat', 'Crew Transfer', '/upload/ship_types/ship_types_20260215032041_e8a6a0c7.jpg', 2, '2026-02-15 10:20:41', '2026-03-05 23:03:44'),
(7, 'L N G / L P G Carrier', 'Pengelolaan khusus, kapal gas.', '/upload/ship_types/ship_types_20260215032122_e8cb5605.jpg', 2, '2026-02-15 10:21:22', '2026-03-05 23:03:30'),
(8, 'Kapal Lainnya', 'Offshore, heavy lift, dll.', '/upload/ship_types/ship_types_20260215032156_fb24f6f0.jpg', 2, '2026-02-15 10:21:56', '2026-03-05 23:03:18');

-- --------------------------------------------------------

--
-- Table structure for table `sky_sai_brain`
--

CREATE TABLE `sky_sai_brain` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` json NOT NULL,
  `title_list` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `list` json NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sky_sai_brain`
--

INSERT INTO `sky_sai_brain` (`id`, `title`, `description`, `image`, `features`, `title_list`, `list`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Inovasi dengan S K Y S A I Brain', 'SKYS AI Brain adalah jantung inovasi kami, mengubah data menjadi keputusan cerdas.', '/upload/sky_sai_brain/sky_sai_brain_20260215040410_fc50be1d.png', '[{\"title\": \"Predictive Maintenance\", \"description\": \"Mengurangi downtime hingga 35% melalui analisis data sensor realtime\"}, {\"title\": \"Route Optimization\", \"description\": \"Hemat bahan bakar 12% dengan algoritma rute paling efisien.\"}, {\"title\": \"Real-time Analytics\", \"description\": \"Memastikan keputusan berbasis data akurat untuk operasi optimal.\"}]', 'S K Y S I N S I G H T P O R T A L', '[{\"title\": \"Monitoring real-time konsumsi BBM & CII.\"}, {\"title\": \"Status perawatan dan rekomendasi prediktif.\"}, {\"title\": \"Manajemen sertifikat & kepatuhan otomatis.\"}]', 2, '2026-02-15 11:04:10', '2026-03-05 23:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `taglines`
--

CREATE TABLE `taglines` (
  `id` int UNSIGNED NOT NULL,
  `title_moto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quete_moto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_moto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_vission` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quete_vission` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_vission` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taglines`
--

INSERT INTO `taglines` (`id`, `title_moto`, `quete_moto`, `description_moto`, `title_vission`, `quete_vission`, `description_vission`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'MOTTO PERUSAHAAN', '\"Bukan Sekadar Pilihan Pertama, Tetapi Mitra Terpercaya untuk Selamanya.\"', '\"We are not the first, but we are determined to be the best for you.\"', 'VISI PERUSAHAAN', 'Menjadi perusahaan manajemen kapal pilihan utama di Asia.', 'Dikenal karena dedikasi tanpa kompromi terhadap keselamatan, keandalan teknis, dan kemitraan yang transparan.', 2, '2026-02-15 09:51:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_email` (`email`),
  ADD KEY `idx_fullname` (`fullname`),
  ADD KEY `fk_accounts_role` (`role_id`);

--
-- Indexes for table `company_kpis`
--
ALTER TABLE `company_kpis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_company_name` (`company_name`);

--
-- Indexes for table `homes`
--
ALTER TABLE `homes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`ip_address`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `fk_logs_account` (`user_id`);

--
-- Indexes for table `management_systems`
--
ALTER TABLE `management_systems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `missions`
--
ALTER TABLE `missions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `partnership`
--
ALTER TABLE `partnership`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_name` (`name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `ship_types`
--
ALTER TABLE `ship_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `sky_sai_brain`
--
ALTER TABLE `sky_sai_brain`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `taglines`
--
ALTER TABLE `taglines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_kpis`
--
ALTER TABLE `company_kpis`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `homes`
--
ALTER TABLE `homes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `management_systems`
--
ALTER TABLE `management_systems`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `missions`
--
ALTER TABLE `missions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `partnership`
--
ALTER TABLE `partnership`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ship_types`
--
ALTER TABLE `ship_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sky_sai_brain`
--
ALTER TABLE `sky_sai_brain`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `taglines`
--
ALTER TABLE `taglines`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `fk_accounts_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `company_kpis`
--
ALTER TABLE `company_kpis`
  ADD CONSTRAINT `fk_company_kpis_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `fk_contact_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `homes`
--
ALTER TABLE `homes`
  ADD CONSTRAINT `fk_homes_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_logs_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `management_systems`
--
ALTER TABLE `management_systems`
  ADD CONSTRAINT `fk_management_systems_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `missions`
--
ALTER TABLE `missions`
  ADD CONSTRAINT `fk_missions_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `partnership`
--
ALTER TABLE `partnership`
  ADD CONSTRAINT `fk_partnership_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `fk_services_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ship_types`
--
ALTER TABLE `ship_types`
  ADD CONSTRAINT `fk_ship_types_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sky_sai_brain`
--
ALTER TABLE `sky_sai_brain`
  ADD CONSTRAINT `fk_sky_sai_brain_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `taglines`
--
ALTER TABLE `taglines`
  ADD CONSTRAINT `fk_taglines_account` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
