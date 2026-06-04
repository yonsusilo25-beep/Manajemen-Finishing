-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251215.aa153def95
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 02, 2026 at 08:56 PM
-- Server version: 8.0.30
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengendalian-produksi`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-foremanppc@example.com|127.0.0.1', 'i:1;', 1770028909),
('laravel-cache-foremanppc@example.com|127.0.0.1:timer', 'i:1770028909;', 1770028909),
('laravel-cache-spvwaxroom@example.com|127.0.0.1', 'i:1;', 1770037035),
('laravel-cache-spvwaxroom@example.com|127.0.0.1:timer', 'i:1770037035;', 1770037035);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `code`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'DEV', 'Dev Engineer', 'Development Engineering Department', 1, '2026-01-10 09:10:28', '2026-01-10 09:10:28'),
(2, 'WAX', 'Wax Room', 'Wax Pattern Production', 1, '2026-01-10 09:10:28', '2026-01-10 09:10:28'),
(3, 'MLD', 'Mould Room', 'Mould Making Department', 1, '2026-01-10 09:10:28', '2026-01-10 09:10:28'),
(4, 'MLT', 'Melting', 'Metal Melting Department', 1, '2026-01-10 09:10:28', '2026-01-10 09:10:28'),
(5, 'CUT', 'Cut Off', 'Cutting Operations', 1, '2026-01-10 09:10:28', '2026-01-10 09:10:28'),
(6, 'FIN', 'Finishing & Straightening', 'Finishing Operations', 1, '2026-01-10 09:10:28', '2026-01-10 09:10:28'),
(7, 'MCH', 'Machining', 'Machining Operations', 1, '2026-01-10 09:10:29', '2026-01-10 09:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `department_inventories`
--

CREATE TABLE `department_inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `current_stock` int DEFAULT NULL,
  `min_stock` int DEFAULT NULL,
  `max_stock` int DEFAULT NULL,
  `reorder_point` int DEFAULT NULL,
  `reorder_quantity` int DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_counted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department_inventories`
--

INSERT INTO `department_inventories` (`id`, `department_id`, `material_id`, `unit`, `current_stock`, `min_stock`, `max_stock`, `reorder_point`, `reorder_quantity`, `location`, `status`, `last_counted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'kg', 40, 10, 200, 10, 30, NULL, 'active', '2026-02-02 06:54:41', '2026-02-02 06:27:56', '2026-02-02 06:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `job_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `status` enum('planned','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_number`, `job_name`, `product_name`, `quantity`, `status`, `start_date`, `due_date`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'BATCH-001', 'Valve Body Production', 'Valve Body Type A', 100, 'in_progress', '2026-01-12', '2026-01-25', 'High priority order for customer XYZ', 1, '2026-01-10 15:30:58', '2026-01-10 19:50:42'),
(2, 'BATCH-002', 'Pump Housing Production', 'Centrifugal Pump Housing', 50, 'in_progress', '2026-01-05', '2026-01-20', 'Customer ABC regular order', 1, '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(3, 'BATCH-003', 'Flange Casting', 'Industrial Flange 150mm', 200, 'planned', '2026-01-17', '2026-02-09', 'Large batch order', 1, '2026-01-10 15:30:58', '2026-01-10 15:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `job_boms`
--

CREATE TABLE `job_boms` (
  `id` bigint UNSIGNED NOT NULL,
  `job_id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `quantity_required` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sequence` int NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_boms`
--

INSERT INTO `job_boms` (`id`, `job_id`, `material_id`, `department_id`, `quantity_required`, `unit`, `sequence`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 15.00, 'kg', 1, 'Primary wax for pattern making', '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(2, 1, 2, 2, 8.50, 'kg', 2, 'Blue pattern wax for detail work', '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(3, 1, 3, 3, 50.00, 'liter', 3, 'For shell building - 5 layers', '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(4, 1, 4, 3, 100.00, 'kg', 4, 'Stucco material for shell strength', '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(5, 1, 5, 4, 250.00, 'kg', 5, 'Main casting material - SS316L', '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(6, 1, 10, 4, 10.00, 'liter', 6, 'Cutting oil for machining operations', '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(7, 1, 9, 4, 20.00, 'pcs', 7, 'For grinding and finishing', '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(8, 1, 6, 4, 50.00, 'kg', 8, 'Backup material - Carbon Steel A36', '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(9, 2, 1, 2, 20.00, 'kg', 1, NULL, '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(10, 2, 3, 3, 70.00, 'liter', 2, NULL, '2026-01-10 15:30:58', '2026-01-10 15:30:58'),
(11, 2, 5, 4, 180.00, 'kg', 3, NULL, '2026-01-10 15:30:58', '2026-01-10 15:30:58');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternative_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conversion_factor` decimal(10,4) DEFAULT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `min_stock` int NOT NULL DEFAULT '0',
  `max_stock` int NOT NULL DEFAULT '0',
  `current_stock` int NOT NULL DEFAULT '0',
  `packing_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity_per_pack` int DEFAULT NULL,
  `lead_time_days` int NOT NULL DEFAULT '0',
  `storage_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `code`, `name`, `description`, `type`, `unit`, `alternative_unit`, `conversion_factor`, `unit_price`, `min_stock`, `max_stock`, `current_stock`, `packing_unit`, `capacity_per_pack`, `lead_time_days`, `storage_location`, `supplier`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'WAX-001', 'Injection Wax Grade A', 'High quality injection wax for precision casting', 'Material', 'kg', NULL, NULL, 150000.00, 50, 200, 60, NULL, NULL, 7, 'A-01', 'Wax Supplier Co.', NULL, 1, '2026-01-10 09:10:30', '2026-02-02 06:54:41'),
(2, 'WAX-002', 'Pattern Wax Blue', 'Blue colored pattern wax', 'Material', 'kg', NULL, NULL, 125000.00, 30, 150, 85, NULL, NULL, 7, 'A-02', 'Wax Supplier Co.', NULL, 1, '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(3, 'CER-001', 'Ceramic Slurry Prime', 'Primary ceramic coating slurry', 'Material', 'liter', NULL, NULL, 85000.00, 100, 500, 35, 'Drum', 190, 14, 'B-01', 'Ceramic Materials Inc.', NULL, 1, '2026-01-10 09:10:30', '2026-02-02 06:01:08'),
(4, 'CER-002', 'Zircon Sand Fine', 'Fine grade zircon sand for shell building', 'Material', 'kg', NULL, NULL, 45000.00, 200, 800, 550, NULL, NULL, 14, 'B-02', 'Refractory Supply Ltd.', NULL, 1, '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(5, 'MET-001', 'Stainless Steel 316L', 'Stainless steel grade 316L ingots', 'Material', 'kg', NULL, NULL, 95000.00, 500, 2000, 770, NULL, NULL, 21, 'C-01', 'Metal Trading Co.', NULL, 1, '2026-01-10 09:10:30', '2026-02-02 06:01:08'),
(6, 'MET-002', 'Carbon Steel A36', 'Carbon steel grade A36', 'Material', 'kg', NULL, NULL, 25000.00, 1000, 5000, 3450, NULL, NULL, 21, 'C-02', 'Metal Trading Co.', NULL, 1, '2026-01-10 09:10:30', '2026-01-10 19:50:42'),
(7, 'TL-001', 'Carbide End Mill 10mm', '10mm diameter carbide end mill', 'Tool', 'pcs', NULL, NULL, 250000.00, 10, 50, 8, NULL, NULL, 14, 'D-01', 'Cutting Tools Supplier', NULL, 1, '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(8, 'TL-002', 'HSS Drill Bit Set', 'High speed steel drill bit set 1-13mm', 'Tool', 'set', NULL, NULL, 450000.00, 5, 20, 12, NULL, NULL, 7, 'D-02', 'Cutting Tools Supplier', NULL, 1, '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(9, 'CON-001', 'Grinding Wheel 180mm', '180mm grinding wheel for angle grinder', 'Consumable', 'pcs', NULL, NULL, 35000.00, 20, 100, 100, NULL, NULL, 3, 'E-01', 'Abrasives Inc.', NULL, 1, '2026-01-10 09:10:30', '2026-01-10 19:50:42'),
(10, 'CON-002', 'Cutting Oil Premium', 'Premium grade cutting and cooling oil', 'Consumable', 'liter', NULL, NULL, 65000.00, 50, 200, 110, NULL, NULL, 7, 'E-02', 'Lubricants Co.', NULL, 1, '2026-01-10 09:10:30', '2026-01-10 19:50:42'),
(11, 'CON-003', 'Safety Gloves (Pair)', 'Heat resistant safety gloves', 'Consumable', 'pair', NULL, NULL, 45000.00, 50, 200, 25, NULL, NULL, 3, 'E-03', 'Safety Equipment Co.', NULL, 1, '2026-01-10 09:10:30', '2026-01-10 09:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `material_specifications`
--

CREATE TABLE `material_specifications` (
  `id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `spec_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spec_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_specifications`
--

INSERT INTO `material_specifications` (`id`, `material_id`, `spec_name`, `spec_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'Melting Point', '65-70°C', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(2, 1, 'Viscosity', '150-200 cPs', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(5, 5, 'Carbon (C)', 'Max 0.030%', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(6, 5, 'Chromium (Cr)', '16-18%', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(7, 5, 'Nickel (Ni)', '10-14%', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(8, 5, 'Molybdenum (Mo)', '2-3%', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(9, 7, 'Diameter', '10mm', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(10, 7, 'Flutes', '4', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(11, 7, 'Coating', 'TiAlN', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(12, 9, 'Diameter', '180mm', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(13, 9, 'Grit', '60', '2026-01-10 09:10:30', '2026-01-10 09:10:30'),
(14, 3, 'Mesh Size', '325 mesh', '2026-02-02 05:21:59', '2026-02-02 05:21:59'),
(15, 3, 'Binder Type', 'Colloidal Silica', '2026-02-02 05:21:59', '2026-02-02 05:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_27_222049_create_personal_access_tokens_table', 1),
(5, '2025_12_15_061955_create_categories_table', 1),
(6, '2025_12_30_073642_create_departments_table', 1),
(7, '2025_12_30_074246_create_materials_table', 1),
(8, '2025_12_30_074947_create_job_boms_table', 1),
(9, '2025_12_30_074949_create_material_spesifications_table', 1),
(10, '2025_12_30_074950_create_requests_table', 1),
(11, '2025_12_30_074953_create_request_items_table', 1),
(12, '2025_12_30_075505_create_request_status_histories_table', 1),
(13, '2025_12_30_075755_create_stock_movements_table', 1),
(14, '2025_12_30_080243_add_role_to_users', 1),
(15, '2026_01_10_062254_add_request_type_to_requests', 1),
(17, '2026_02_02_123333_create_department_inventories_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` bigint UNSIGNED NOT NULL,
  `request_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `request_type` enum('normal','additional') COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_date` date NOT NULL,
  `urgency` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `status` enum('draft','submitted','checking_stock','approved','partial_approved','ready_for_pickup','completed','rejected','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `job_id` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `completed_by` bigint UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `request_number`, `department_id`, `user_id`, `request_type`, `request_date`, `urgency`, `status`, `notes`, `job_id`, `approved_by`, `approved_at`, `rejection_reason`, `completed_by`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 'REQ-20260110-0001', 2, 1, 'normal', '2026-01-10', 'normal', 'partial_approved', NULL, 2, 1, '2026-02-02 06:01:08', NULL, NULL, NULL, '2026-01-10 15:43:20', '2026-02-02 06:01:08'),
(2, 'REQ-20260110-0002', 4, 1, 'normal', '2026-01-10', 'normal', 'approved', NULL, 1, 1, '2026-01-10 19:50:42', NULL, NULL, NULL, '2026-01-10 16:21:32', '2026-01-10 19:50:42'),
(3, 'REQ-20260202-0001', 2, 3, 'normal', '2026-02-06', 'normal', 'approved', NULL, 2, 2, '2026-02-02 06:27:56', NULL, NULL, NULL, '2026-02-02 06:27:02', '2026-02-02 06:27:56'),
(4, 'REQ-20260202-0002', 2, 3, 'normal', '2026-02-02', 'normal', 'approved', NULL, 2, 2, '2026-02-02 06:54:41', NULL, NULL, NULL, '2026-02-02 06:54:05', '2026-02-02 06:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `request_items`
--

CREATE TABLE `request_items` (
  `id` bigint UNSIGNED NOT NULL,
  `request_id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `quantity_requested` decimal(10,2) NOT NULL,
  `quantity_approved` decimal(10,2) DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specifications` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `job_bom_id` bigint UNSIGNED DEFAULT NULL,
  `is_additional` tinyint(1) NOT NULL DEFAULT '0',
  `additional_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_items`
--

INSERT INTO `request_items` (`id`, `request_id`, `material_id`, `quantity_requested`, `quantity_approved`, `unit`, `specifications`, `notes`, `job_bom_id`, `is_additional`, `additional_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 20.00, 20.00, 'kg', NULL, NULL, NULL, 0, NULL, '2026-01-10 15:43:20', '2026-02-02 06:01:08'),
(2, 1, 3, 70.00, 10.00, 'liter', NULL, NULL, NULL, 0, NULL, '2026-01-10 15:43:20', '2026-02-02 06:01:08'),
(3, 1, 5, 180.00, 180.00, 'kg', NULL, NULL, NULL, 0, NULL, '2026-01-10 15:43:20', '2026-02-02 06:01:08'),
(4, 2, 5, 250.00, 250.00, 'kg', NULL, 'Main casting material - SS316L', NULL, 0, NULL, '2026-01-10 16:21:32', '2026-01-10 19:50:42'),
(5, 2, 10, 10.00, 10.00, 'liter', NULL, 'Cutting oil for machining operations', NULL, 0, NULL, '2026-01-10 16:21:32', '2026-01-10 19:50:42'),
(6, 2, 9, 20.00, 20.00, 'pcs', NULL, 'For grinding and finishing', NULL, 0, NULL, '2026-01-10 16:21:32', '2026-01-10 19:50:42'),
(7, 2, 6, 50.00, 50.00, 'kg', NULL, 'Backup material - Carbon Steel A36', NULL, 0, NULL, '2026-01-10 16:21:32', '2026-01-10 19:50:42'),
(8, 3, 1, 20.00, 20.00, 'kg', NULL, NULL, NULL, 0, NULL, '2026-02-02 06:27:02', '2026-02-02 06:27:56'),
(9, 4, 1, 20.00, 20.00, 'kg', NULL, NULL, NULL, 0, NULL, '2026-02-02 06:54:05', '2026-02-02 06:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `request_status_history`
--

CREATE TABLE `request_status_history` (
  `id` bigint UNSIGNED NOT NULL,
  `request_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_status_history`
--

INSERT INTO `request_status_history` (`id`, `request_id`, `status`, `user_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'draft', 1, 'Request created', '2026-01-10 15:43:20', '2026-01-10 15:43:20'),
(2, 2, 'draft', 1, 'Request created', '2026-01-10 16:21:32', '2026-01-10 16:21:32'),
(3, 2, 'submitted', 1, 'Request submitted for approval', '2026-01-10 19:21:07', '2026-01-10 19:21:07'),
(4, 2, 'approved', 1, 'Request approved by warehouse', '2026-01-10 19:50:42', '2026-01-10 19:50:42'),
(5, 1, 'submitted', 3, 'Request submitted for approval', '2026-01-10 20:13:18', '2026-01-10 20:13:18'),
(6, 1, 'partial_approved', 1, 'Request approved by warehouse', '2026-02-02 06:01:08', '2026-02-02 06:01:08'),
(7, 3, 'draft', 3, 'Request created', '2026-02-02 06:27:02', '2026-02-02 06:27:02'),
(8, 3, 'submitted', 3, 'Request submitted for approval', '2026-02-02 06:27:15', '2026-02-02 06:27:15'),
(9, 3, 'approved', 2, 'Request approved by warehouse', '2026-02-02 06:27:56', '2026-02-02 06:27:56'),
(10, 4, 'draft', 3, 'Request created', '2026-02-02 06:54:05', '2026-02-02 06:54:05'),
(11, 4, 'submitted', 3, 'Request submitted for approval', '2026-02-02 06:54:12', '2026-02-02 06:54:12'),
(12, 4, 'approved', 2, 'Request approved by warehouse', '2026-02-02 06:54:41', '2026-02-02 06:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7cegwTfUVMJqzVMbUgWYlqWSoKoQPHc5ItjGoxzY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWxDd0t3M3R1MlpKcWlZTUFQbFBMeXlRdHFWaVVnOExkckxaRHNrViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wZW5nZW5kYWxpYW4tcHJvZHVrc2kudGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1770040520),
('7opwvNhKhQFUaUYhINC1DFjPtMjubqIsAytsZ0NR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDF4VUh0Tzc1SmtBajhHVlZna3hLdzhZNzk1QU1BcG80VlNHNlFLcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wZW5nZW5kYWxpYW4tcHJvZHVrc2kudGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1770040567),
('q9GhhMpr8tAdZCr4zzxwNSsynTb5jvoc4SokBpwX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicnVMTnlzWlJtRVRraUxTd242MVR0YXZEa2JoM1ZEV0JZQjdubDlobiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wZW5nZW5kYWxpYW4tcHJvZHVrc2kudGVzdC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1770040554);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `stock_before` int NOT NULL,
  `stock_after` int NOT NULL,
  `request_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `movement_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `material_id`, `type`, `quantity`, `stock_before`, `stock_after`, `request_id`, `user_id`, `notes`, `movement_date`, `created_at`, `updated_at`) VALUES
(1, 5, 'out', 250.00, 1200, 950, 2, 1, 'Material request approved - Job: BATCH-001', '2026-01-11', '2026-01-10 19:50:42', '2026-01-10 19:50:42'),
(2, 10, 'out', 10.00, 120, 110, 2, 1, 'Material request approved - Job: BATCH-001', '2026-01-11', '2026-01-10 19:50:42', '2026-01-10 19:50:42'),
(3, 9, 'out', 20.00, 15, -5, 2, 1, 'Material request approved - Job: BATCH-001', '2026-01-11', '2026-01-10 19:50:42', '2026-01-10 19:50:42'),
(4, 6, 'out', 50.00, 3500, 3450, 2, 1, 'Material request approved - Job: BATCH-001', '2026-01-11', '2026-01-10 19:50:42', '2026-01-10 19:50:42'),
(5, 1, 'out', 20.00, 120, 100, 1, 1, 'Material request approved - Job: BATCH-002', '2026-02-02', '2026-02-02 06:01:08', '2026-02-02 06:01:08'),
(6, 3, 'out', 10.00, 45, 35, 1, 1, 'Material request approved - Job: BATCH-002', '2026-02-02', '2026-02-02 06:01:08', '2026-02-02 06:01:08'),
(7, 5, 'out', 180.00, 950, 770, 1, 1, 'Material request approved - Job: BATCH-002', '2026-02-02', '2026-02-02 06:01:08', '2026-02-02 06:01:08'),
(8, 1, 'out', 20.00, 100, 80, 3, 2, 'Material request approved - Job: BATCH-002', '2026-02-02', '2026-02-02 06:27:56', '2026-02-02 06:27:56'),
(9, 1, 'out', 20.00, 80, 60, 4, 2, 'Material request approved - Job: BATCH-002', '2026-02-02', '2026-02-02 06:54:41', '2026-02-02 06:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `role` enum('admin','manager','foreman','supervisor') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'foreman',
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `department_id`, `role`, `is_active`) VALUES
(1, 'Administrator', 'admin@example.com', NULL, '$2y$12$FeAz4OIWiDhVI1YM5fOwaO5ed3Qko78YPk4DYSJ7CIKI1DdjtlQFm', NULL, '2026-01-10 09:10:29', '2026-01-10 09:10:29', NULL, 'admin', 1),
(2, 'Manager', 'manager@example.com', NULL, '$2y$12$6WXa0mg1BDX8pxi.X8Kuou0AuIWPUt6zmA/YGz7Xy7nvYbOhqU5xu', NULL, '2026-01-10 09:10:30', '2026-01-10 09:10:30', NULL, 'manager', 1),
(3, 'Spv. Wax Room', 'spvwax@example.com', NULL, '$2y$12$Z0fY.kfqx9Utqzy9N.knLeGozc8Gx88h/70F30QcMcAMythcfc5qa', NULL, '2026-01-10 19:30:56', '2026-02-02 04:05:21', 2, 'supervisor', 1),
(4, 'Spv. Melting', 'spvmelting@example.com', NULL, '$2y$12$TBNmVYqELI1wlR1rVdB59uzvoAuqY/FB37z4OdG8LfQfFQlzAEnky', NULL, '2026-01-10 19:34:47', '2026-02-02 04:05:43', 4, 'supervisor', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_code_unique` (`code`);

--
-- Indexes for table `department_inventories`
--
ALTER TABLE `department_inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_inventories_department_id_material_id_unique` (`department_id`,`material_id`),
  ADD KEY `department_inventories_material_id_foreign` (`material_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jobs_job_number_unique` (`job_number`),
  ADD KEY `jobs_created_by_foreign` (`created_by`);

--
-- Indexes for table `job_boms`
--
ALTER TABLE `job_boms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_boms_job_id_foreign` (`job_id`),
  ADD KEY `job_boms_material_id_foreign` (`material_id`),
  ADD KEY `job_boms_department_id_foreign` (`department_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `materials_code_unique` (`code`);

--
-- Indexes for table `material_specifications`
--
ALTER TABLE `material_specifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_specifications_material_id_foreign` (`material_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `requests_request_number_unique` (`request_number`),
  ADD KEY `requests_department_id_foreign` (`department_id`),
  ADD KEY `requests_user_id_foreign` (`user_id`),
  ADD KEY `requests_job_id_foreign` (`job_id`),
  ADD KEY `requests_approved_by_foreign` (`approved_by`),
  ADD KEY `requests_completed_by_foreign` (`completed_by`);

--
-- Indexes for table `request_items`
--
ALTER TABLE `request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_items_request_id_foreign` (`request_id`),
  ADD KEY `request_items_material_id_foreign` (`material_id`),
  ADD KEY `request_items_job_bom_id_foreign` (`job_bom_id`);

--
-- Indexes for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_status_history_request_id_foreign` (`request_id`),
  ADD KEY `request_status_history_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_material_id_foreign` (`material_id`),
  ADD KEY `stock_movements_request_id_foreign` (`request_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_department_id_foreign` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `department_inventories`
--
ALTER TABLE `department_inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_boms`
--
ALTER TABLE `job_boms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `material_specifications`
--
ALTER TABLE `material_specifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_items`
--
ALTER TABLE `request_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `request_status_history`
--
ALTER TABLE `request_status_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department_inventories`
--
ALTER TABLE `department_inventories`
  ADD CONSTRAINT `department_inventories_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `department_inventories_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `job_boms`
--
ALTER TABLE `job_boms`
  ADD CONSTRAINT `job_boms_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `job_boms_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_boms_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`);

--
-- Constraints for table `material_specifications`
--
ALTER TABLE `material_specifications`
  ADD CONSTRAINT `material_specifications_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `requests_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `requests_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `requests_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `request_items`
--
ALTER TABLE `request_items`
  ADD CONSTRAINT `request_items_job_bom_id_foreign` FOREIGN KEY (`job_bom_id`) REFERENCES `job_boms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  ADD CONSTRAINT `request_items_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD CONSTRAINT `request_status_history_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_status_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`),
  ADD CONSTRAINT `stock_movements_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `requests` (`id`),
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
