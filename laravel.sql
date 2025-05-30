-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 15 Bulan Mei 2025 pada 23.15
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `class`
--

CREATE TABLE `class` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_code` varchar(15) NOT NULL,
  `class_name_long` varchar(100) NOT NULL,
  `class_name_short` varchar(10) NOT NULL,
  `class_availability` int(11) NOT NULL,
  `visibility` int(11) NOT NULL,
  `description` text NOT NULL,
  `class_thumbnail` text DEFAULT NULL,
  `tag` varchar(50) NOT NULL,
  `responsible_lecturer_id` bigint(20) UNSIGNED NOT NULL,
  `study_program_id` bigint(20) UNSIGNED NOT NULL,
  `period_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class`
--

INSERT INTO `class` (`id`, `class_code`, `class_name_long`, `class_name_short`, `class_availability`, `visibility`, `description`, `class_thumbnail`, `tag`, `responsible_lecturer_id`, `study_program_id`, `period_id`, `created_at`, `updated_at`) VALUES
(1, 'CLS123', 'Introduction to Programming', 'IntroProg', 30, 1, 'This is a basic programming class.', '-', 'programming', 1, 2, 2, '2025-05-12 01:24:01', '2025-05-12 01:27:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_student`
--

CREATE TABLE `class_student` (
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_student`
--

INSERT INTO `class_student` (`class_id`, `student_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-05-13 18:13:10', '2025-05-13 18:13:10'),
(1, 2, '2025-05-15 14:13:11', '2025-05-15 14:13:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_topic`
--

CREATE TABLE `class_topic` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_topic`
--

INSERT INTO `class_topic` (`id`, `title`, `class_id`, `created_at`, `updated_at`) VALUES
(1, 'Introduction to ML', 1, '2025-05-12 02:55:33', '2025-05-12 02:55:33'),
(2, 'Introduction to RL', 1, '2025-05-15 13:46:51', '2025-05-15 13:46:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_topic_menus`
--

CREATE TABLE `class_topic_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu` varchar(255) NOT NULL,
  `is_modul` tinyint(1) NOT NULL,
  `is_exam` tinyint(1) NOT NULL,
  `class_topic_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_topic_menus`
--

INSERT INTO `class_topic_menus` (`id`, `menu`, `is_modul`, `is_exam`, `class_topic_id`, `created_at`, `updated_at`) VALUES
(2, 'Modul', 1, 0, 1, '2025-05-12 07:01:11', '2025-05-12 07:01:11'),
(4, 'Kuis', 0, 1, 1, '2025-05-12 22:20:28', '2025-05-12 22:20:28'),
(5, 'Tugas', 0, 0, 1, '2025-05-13 17:30:17', '2025-05-13 17:30:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_topic_modul_question`
--

CREATE TABLE `class_topic_modul_question` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_topic_id` bigint(20) UNSIGNED NOT NULL,
  `topic_modul_id` bigint(20) UNSIGNED DEFAULT NULL,
  `topic_exam_question_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_topic_modul_question`
--

INSERT INTO `class_topic_modul_question` (`id`, `class_topic_id`, `topic_modul_id`, `topic_exam_question_id`, `created_at`, `updated_at`) VALUES
(3, 1, 4, NULL, '2025-05-12 07:01:11', '2025-05-12 07:01:11'),
(10, 1, NULL, 7, '2025-05-12 22:15:27', '2025-05-12 22:15:27'),
(11, 1, NULL, 8, '2025-05-12 22:15:27', '2025-05-12 22:15:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `faculty`
--

CREATE TABLE `faculty` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `established_year` int(11) DEFAULT NULL,
  `dean_name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(254) DEFAULT NULL,
  `contact_phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `faculty`
--

INSERT INTO `faculty` (`id`, `name`, `code`, `description`, `established_year`, `dean_name`, `contact_email`, `contact_phone`, `created_at`, `updated_at`) VALUES
(5, 'Teknologi dan Bisnis Energi', '-', 'test', 2023, 'Suci', 'ftbe@itpln.ac.id', '081234567', '2025-05-11 01:41:42', '2025-05-11 01:57:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lecture`
--

CREATE TABLE `lecture` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `nidn` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `study_program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `lecture`
--

INSERT INTO `lecture` (`id`, `first_name`, `last_name`, `profile_picture`, `birthdate`, `phone_number`, `nidn`, `user_id`, `study_program_id`, `created_at`, `updated_at`) VALUES
(1, 'manajemen', 'skuring', NULL, NULL, NULL, '123', 18, 2, '2025-05-12 01:21:28', '2025-05-12 01:21:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_07_181723_create_personal_access_tokens_table', 2),
(5, '2025_05_08_144414_create_faculties_table', 3),
(6, '2025_05_08_150154_create_study_programs_table', 3),
(7, '2025_05_08_232039_create_lectures_table', 3),
(8, '2025_05_08_234217_create_students_table', 3),
(9, '2025_05_08_234712_create_periods_table', 3),
(10, '2025_05_09_000840_create_class_apps_table', 3),
(11, '2025_05_09_013446_create_roles_table', 3),
(12, '2025_05_09_013750_update_users_table', 4),
(13, '2025_05_09_014649_create_score_types_table', 4),
(14, '2025_05_09_015729_create_class_topics_table', 5),
(15, '2025_05_10_010242_create_modul_types_table', 5),
(16, '2025_05_10_010723_create_topic_moduls_table', 5),
(17, '2025_05_10_011449_create_class_topic_menus_table', 5),
(18, '2025_05_10_012219_create_topic_assignments_table', 5),
(19, '2025_05_10_021951_create_presence_types_table', 5),
(20, '2025_05_10_023359_create_student_presences_table', 5),
(21, '2025_05_10_024236_create_score_settings_table', 5),
(22, '2025_05_10_024932_create_topic_exam_questions_table', 5),
(23, '2025_05_10_030234_create_student_exam_answers_table', 5),
(24, '2025_05_10_031101_create_student_assignments_table', 5),
(25, '2025_05_10_031259_create_student_scores_table', 5),
(26, '2025_05_11_112734_update_lecture', 6),
(27, '2025_05_11_112845_update_student', 6),
(28, '2025_05_11_114208_update_lecture_profile_picture', 7),
(29, '2025_05_11_114639_student_profile_picture', 7),
(30, '2025_05_12_121638_create_class_topic_modul_question_table', 8),
(31, '2025_05_13_010751_update_topic_exam_questions', 9),
(32, '2025_05_13_092044_update_topic_assignment', 10),
(33, '2025_05_13_103524_create_class_student_table', 11);

-- --------------------------------------------------------

--
-- Struktur dari tabel `modul_types`
--

CREATE TABLE `modul_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `modul_types`
--

INSERT INTO `modul_types` (`id`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Dokumen PDF ', NULL, NULL),
(2, 'Video URL', NULL, NULL),
(3, 'Link URL', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('irfangamer@gmail.com', '$2y$12$Gw430ve/wDNkINAfbI1SHOA8MQ6eiVxLteyrQYD/Wj/SDekB7jmLG', '2025-05-11 03:35:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `periods`
--

CREATE TABLE `periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `periods`
--

INSERT INTO `periods` (`id`, `period`, `created_at`, `updated_at`) VALUES
(2, 'Genap 2025', '2025-05-12 00:49:47', '2025-05-12 00:49:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'myAppToken', 'c93faf714c906f9f120bcd9a2c4d2d5980a85a1edc0b60283e159ea8a524c2e1', '[\"*\"]', NULL, NULL, '2025-05-07 11:17:55', '2025-05-07 11:17:55'),
(2, 'App\\Models\\User', 4, 'myAppToken', '5ccf865fc8d134362dc3ea2fc5ce945c3d33842a82725b4b84e85773b3ee8dd4', '[\"*\"]', '2025-05-07 21:47:44', NULL, '2025-05-07 11:19:50', '2025-05-07 21:47:44'),
(3, 'App\\Models\\User', 5, 'myAppToken', '45f04b842d93533994c787495bc1540432e7feaab1a43d3e9536d266f0aaa314', '[\"*\"]', NULL, NULL, '2025-05-10 22:42:21', '2025-05-10 22:42:21'),
(4, 'App\\Models\\User', 5, 'myAppToken', '2b6206c884d619e9f5c03eaa4865f6d9aa97ae636eaef69e1d4cd72b452b2f1a', '[\"*\"]', '2025-05-10 23:08:07', NULL, '2025-05-10 22:46:28', '2025-05-10 23:08:07'),
(5, 'App\\Models\\User', 5, 'myAppToken', '9e1d092caebab3ae6d2d6ace03ab31a9a07dc4bfd5f19bda6952e5107d56174c', '[\"*\"]', '2025-05-15 14:13:31', NULL, '2025-05-10 23:08:47', '2025-05-15 14:13:31'),
(6, 'App\\Models\\User', 7, 'myAppToken', 'b869c67e8a6c264270c803949b7c3092208095aebbc8a9c2a4766ba3451ba2a7', '[\"*\"]', NULL, NULL, '2025-05-11 03:06:36', '2025-05-11 03:06:36'),
(7, 'App\\Models\\User', 8, 'myAppToken', 'e7ebeaa4f6a68247ac2621a8191d69b4ad820439d665498f99cce5f8ae2bba14', '[\"*\"]', NULL, NULL, '2025-05-11 03:23:47', '2025-05-11 03:23:47'),
(8, 'App\\Models\\User', 9, 'myAppToken', '1871564cf22c8c7f77c59da8eeb3d6e38a62ac0c1b441cfc4c2618bfe5d7c60a', '[\"*\"]', NULL, NULL, '2025-05-11 03:25:10', '2025-05-11 03:25:10'),
(9, 'App\\Models\\User', 10, 'myAppToken', '4e5cbb9b8cf2d757cba7706da82a5934a270373970db4bcc16f43f45a3d71e0f', '[\"*\"]', NULL, NULL, '2025-05-11 03:29:26', '2025-05-11 03:29:26'),
(10, 'App\\Models\\User', 11, 'myAppToken', 'd86b6d104d35153e5ed56919e3cb644aa193a58b099b3490fe8b0cee077acaeb', '[\"*\"]', NULL, NULL, '2025-05-11 03:30:57', '2025-05-11 03:30:57'),
(11, 'App\\Models\\User', 12, 'myAppToken', '067dfd426ac08da47f31b01ef65e726fb0aef760fe9019ce42f6adbfe2b58a3a', '[\"*\"]', NULL, NULL, '2025-05-11 03:34:16', '2025-05-11 03:34:16'),
(12, 'App\\Models\\User', 13, 'myAppToken', '88ef2e6cd6499063ab018f59d76d93367b1e6f2f74ac8d9d41ca3d5cc5cdf6a3', '[\"*\"]', NULL, NULL, '2025-05-11 03:35:35', '2025-05-11 03:35:35'),
(13, 'App\\Models\\User', 14, 'myAppToken', 'abb04b60bb1d741d5a4e1d00423d04ba3063c583480bbc9a2145d6809ea03c2a', '[\"*\"]', NULL, NULL, '2025-05-11 03:43:06', '2025-05-11 03:43:06'),
(14, 'App\\Models\\User', 15, 'myAppToken', 'af2868f2465b506e502033dc5fd393c05759423086822f2d3bc2be21d358eac2', '[\"*\"]', NULL, NULL, '2025-05-11 03:44:55', '2025-05-11 03:44:55'),
(15, 'App\\Models\\User', 15, 'myAppToken', '412b00bdedd3c08f73bdec9a2124caeffc044c83a98101388c41d5673c2866f0', '[\"*\"]', NULL, NULL, '2025-05-11 03:56:02', '2025-05-11 03:56:02'),
(16, 'App\\Models\\User', 15, 'myAppToken', '39d038e86cc77f2b3fe26da70014dcd7c136975452fa63a1917af90af003f9d5', '[\"*\"]', NULL, NULL, '2025-05-11 03:58:24', '2025-05-11 03:58:24'),
(17, 'App\\Models\\User', 15, 'myAppToken', '165783ae8c26991754bfd1448c80b4fa50998bcf9d5c7e184d62a5ec662d5dfd', '[\"*\"]', NULL, NULL, '2025-05-11 04:15:50', '2025-05-11 04:15:50'),
(18, 'App\\Models\\User', 16, 'myAppToken', 'b0322bbafc0d4c4f04b519abe012950e179cbfd64fddc9ce27395c1e71ebd508', '[\"*\"]', NULL, NULL, '2025-05-11 04:40:01', '2025-05-11 04:40:01'),
(19, 'App\\Models\\User', 17, 'myAppToken', 'be4361a6c1c3ea511e225d8fecbb651358b942930926ffe4397455482b549821', '[\"*\"]', NULL, NULL, '2025-05-11 04:48:48', '2025-05-11 04:48:48'),
(20, 'App\\Models\\User', 18, 'myAppToken', 'd0d2e3b5cfe7cb728d2d6a78506c5c4d8dd58036fd83bbd5c850b8ee774043bb', '[\"*\"]', NULL, NULL, '2025-05-12 01:21:28', '2025-05-12 01:21:28'),
(21, 'App\\Models\\User', 19, 'myAppToken', '18d30f66be44c7b048b02ccc610bfe70d130feb608c5c29ac71f914d1aed0b12', '[\"*\"]', NULL, NULL, '2025-05-13 17:58:45', '2025-05-13 17:58:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `presence_types`
--

CREATE TABLE `presence_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `presence_types`
--

INSERT INTO `presence_types` (`id`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Hadir', NULL, NULL),
(2, 'Sakit', NULL, NULL),
(3, 'Izin', NULL, NULL),
(4, 'Alfa', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'management', NULL, NULL),
(4, 'student', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `score_setting`
--

CREATE TABLE `score_setting` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `percent_value` decimal(5,2) NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `score_type_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `score_setting`
--

INSERT INTO `score_setting` (`id`, `percent_value`, `class_id`, `score_type_id`, `created_at`, `updated_at`) VALUES
(1, 15.00, 1, 1, '2025-05-13 20:20:30', '2025-05-13 20:20:46'),
(2, 20.00, 1, 2, '2025-05-13 20:20:30', '2025-05-13 20:20:46'),
(3, 25.00, 1, 3, '2025-05-13 20:20:30', '2025-05-13 20:20:46'),
(4, 25.00, 1, 4, '2025-05-13 20:20:30', '2025-05-13 20:20:46'),
(5, 15.00, 1, 5, '2025-05-13 20:20:30', '2025-05-13 20:20:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `score_types`
--

CREATE TABLE `score_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_exam` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `score_types`
--

INSERT INTO `score_types` (`id`, `type`, `is_exam`, `created_at`, `updated_at`) VALUES
(1, 'Absensi', 0, NULL, NULL),
(2, 'Tugas', 0, NULL, NULL),
(3, 'Kuis', 1, NULL, NULL),
(4, 'UTS', 1, NULL, NULL),
(5, 'UAS', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('D57BsUVv9jTsx9WQ5ul9wLBpjumz6WnVPFzFSJrU', NULL, '127.0.0.1', 'PostmanRuntime/7.43.4', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkxzampaMnJmeUJ3M3NiaUE5YnVEbmN1anFpVHVKZ1ZKcEJsY3VaMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1747276709),
('lB0lMtugCxE1iQitRuLMGLoNxKsY2UwgOexOkmXz', NULL, '127.0.0.1', 'PostmanRuntime/7.43.4', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSU5ucERJZmpQSFVqdjNKYVBENUxiN1RLVEJ6OUxMbTVBbWdKbUJ0NiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1746961688),
('Meqk5jtQogjzOfp2xzvFt58hyMPRqu4d2UXVkIsA', NULL, '127.0.0.1', 'PostmanRuntime/7.43.4', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNE0zeVllWFpzNlFYcks1c1U5aHdLOTgwT2N2WmdUbXRsTWhNUFU1NCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1747225480),
('SmIamDGtYZ0cCJIKRgA486mpaDLAWxtPbdQQEGvI', NULL, '127.0.0.1', 'PostmanRuntime/7.43.4', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlRib2Y1b3I3VTk0OWpIQWMwcnZZNHZ5WG1pWEhPdTNRSmhvVHYzUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1747192593);

-- --------------------------------------------------------

--
-- Struktur dari tabel `student`
--

CREATE TABLE `student` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `nim` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `study_program_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `student`
--

INSERT INTO `student` (`id`, `first_name`, `last_name`, `profile_picture`, `birthdate`, `phone_number`, `nim`, `user_id`, `study_program_id`, `created_at`, `updated_at`) VALUES
(1, 'Aflah', 'Wahyu', '-', '1998-02-02', '08123456789', '4245332', 17, 2, '2025-05-11 04:48:48', '2025-05-12 00:19:21'),
(2, 'irfan', 'fahmi', NULL, NULL, NULL, '123', 19, 2, '2025-05-13 17:58:45', '2025-05-13 17:58:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_assignment`
--

CREATE TABLE `student_assignment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assignment_file` varchar(255) NOT NULL,
  `lecture_feedback` varchar(255) DEFAULT NULL,
  `revision_date` date DEFAULT NULL,
  `topic_assignment_id` bigint(20) UNSIGNED NOT NULL,
  `class_topic_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `student_assignment`
--

INSERT INTO `student_assignment` (`id`, `assignment_file`, `lecture_feedback`, `revision_date`, `topic_assignment_id`, `class_topic_id`, `student_id`, `created_at`, `updated_at`) VALUES
(4, 'path/to_file.pdf', NULL, NULL, 3, 1, 1, '2025-05-14 18:45:47', '2025-05-14 18:45:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_exam_answer`
--

CREATE TABLE `student_exam_answer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_answer` varchar(255) NOT NULL,
  `topic_exam_question_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_presence`
--

CREATE TABLE `student_presence` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_topic_id` bigint(20) UNSIGNED NOT NULL,
  `presence_type_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `student_presence`
--

INSERT INTO `student_presence` (`id`, `class_topic_id`, `presence_type_id`, `student_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 1, '2025-05-14 05:25:43', '2025-05-14 06:02:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_scores`
--

CREATE TABLE `student_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `score` decimal(5,2) NOT NULL,
  `score_setting_id` bigint(20) NOT NULL,
  `class_topic_id` bigint(20) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `student_scores`
--

INSERT INTO `student_scores` (`id`, `score`, `score_setting_id`, `class_topic_id`, `student_id`, `created_at`, `updated_at`) VALUES
(12, 100.00, 2, 1, 1, '2025-05-14 19:21:24', '2025-05-14 19:21:35'),
(14, 100.00, 1, 2, 1, NULL, NULL),
(15, 0.00, 1, 1, 2, NULL, NULL),
(16, 100.00, 1, 2, 2, NULL, NULL),
(17, 100.00, 1, 1, 1, NULL, NULL),
(18, 90.00, 1, 1, 2, NULL, NULL),
(19, 75.00, 3, 2, 1, NULL, NULL),
(20, 90.00, 3, 2, 2, NULL, NULL),
(21, 88.00, 3, 1, 1, NULL, NULL),
(22, 77.00, 3, 1, 2, NULL, NULL),
(23, 100.00, 2, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `study_program`
--

CREATE TABLE `study_program` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `head_of_program` varchar(100) DEFAULT NULL,
  `established_year` int(11) DEFAULT NULL,
  `contact_email` varchar(254) DEFAULT NULL,
  `contact_phone` varchar(15) DEFAULT NULL,
  `faculty_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `study_program`
--

INSERT INTO `study_program` (`id`, `name`, `code`, `description`, `head_of_program`, `established_year`, `contact_email`, `contact_phone`, `faculty_id`, `created_at`, `updated_at`) VALUES
(2, 'Sistem Informasi', '51', '-', 'Budi', 2025, 'si@itpln.ac.id', '081234567', 5, '2025-05-11 04:36:06', '2025-05-11 04:36:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `topic_assignment`
--

CREATE TABLE `topic_assignment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `class_topic_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `due_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `topic_assignment`
--

INSERT INTO `topic_assignment` (`id`, `title`, `description`, `class_topic_id`, `created_at`, `updated_at`, `due_date`) VALUES
(3, 'Tugas A', 'Deskripsi…', 1, '2025-05-13 17:30:17', '2025-05-13 17:30:17', '2025-05-20 14:30:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `topic_exam_questions`
--

CREATE TABLE `topic_exam_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `is_essay` tinyint(1) NOT NULL,
  `is_multiple_choice` tinyint(1) NOT NULL,
  `multiple_choice_options` varchar(255) DEFAULT NULL,
  `true_answer` varchar(255) NOT NULL,
  `class_topic_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `score_type_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `topic_exam_questions`
--

INSERT INTO `topic_exam_questions` (`id`, `question`, `is_essay`, `is_multiple_choice`, `multiple_choice_options`, `true_answer`, `class_topic_id`, `created_at`, `updated_at`, `score_type_id`) VALUES
(7, 'Pertanyaan ...', 1, 0, NULL, 'Jawaban benar', 1, '2025-05-12 22:15:27', '2025-05-12 22:20:28', 3),
(8, 'Pilih Eloquent atau Query Builder', 0, 1, 'Eloquent,Query Builder', 'Eloquent', 1, '2025-05-12 22:15:27', '2025-05-12 22:20:28', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `topic_modules`
--

CREATE TABLE `topic_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `modul` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `modul_type_id` bigint(20) UNSIGNED NOT NULL,
  `class_topic_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `topic_modules`
--

INSERT INTO `topic_modules` (`id`, `modul`, `content`, `description`, `modul_type_id`, `class_topic_id`, `created_at`, `updated_at`) VALUES
(4, 'Pendahuluan', 'skuring/modul/123/test.pdf', '-', 1, 1, '2025-05-12 07:01:11', '2025-05-12 07:01:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(5, 1, 'john', 'johndoe@example.com', NULL, '$2y$12$WHx20.LpAHgO8wlvW20NUOFfaXMK9zH5Fm7n3vF6bVH6Ky7/oVU7G', NULL, '2025-05-10 22:42:21', '2025-05-10 22:42:21'),
(17, 4, 'Aflah Wahyu', 'irfangamer@gmail.com', NULL, '$2y$12$1PdWKlJoGk1Ug3zLwoJ7AOt4lBxeJwMJ35taVhnejtnkqiP4jFuSi', NULL, '2025-05-11 04:48:48', '2025-05-12 00:19:21'),
(18, 2, 'manajemen', 'skuring1901@gmail.com', NULL, '$2y$12$4NaXMXPewCZid5j/13GgYO0hE5oMhzZLG0RCrJJEbrsN.KxMTu99S', NULL, '2025-05-12 01:21:28', '2025-05-12 01:21:28'),
(19, 4, 'irfan fahmi', 'fahmiagaz@gmail.com', NULL, '$2y$12$5TasZCuRSaD1IXE9jtoo/.iykakrWBBcoGEyJS.60TlCdx/nv2fAm', NULL, '2025-05-13 17:58:45', '2025-05-13 17:58:45');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_responsible_lecturer_id_foreign` (`responsible_lecturer_id`),
  ADD KEY `class_study_program_id_foreign` (`study_program_id`),
  ADD KEY `class_period_id_foreign` (`period_id`);

--
-- Indeks untuk tabel `class_student`
--
ALTER TABLE `class_student`
  ADD PRIMARY KEY (`class_id`,`student_id`),
  ADD KEY `class_student_student_id_foreign` (`student_id`);

--
-- Indeks untuk tabel `class_topic`
--
ALTER TABLE `class_topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_topic_class_id_foreign` (`class_id`);

--
-- Indeks untuk tabel `class_topic_menus`
--
ALTER TABLE `class_topic_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_topic_menus_class_topic_id_foreign` (`class_topic_id`);

--
-- Indeks untuk tabel `class_topic_modul_question`
--
ALTER TABLE `class_topic_modul_question`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ctmq_unique` (`class_topic_id`,`topic_modul_id`,`topic_exam_question_id`),
  ADD KEY `class_topic_modul_question_topic_modul_id_foreign` (`topic_modul_id`),
  ADD KEY `class_topic_modul_question_topic_exam_question_id_foreign` (`topic_exam_question_id`);

--
-- Indeks untuk tabel `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lecture`
--
ALTER TABLE `lecture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecture_user_id_foreign` (`user_id`),
  ADD KEY `lecture_study_program_id_foreign` (`study_program_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `modul_types`
--
ALTER TABLE `modul_types`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `periods`
--
ALTER TABLE `periods`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `presence_types`
--
ALTER TABLE `presence_types`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `score_setting`
--
ALTER TABLE `score_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `score_setting_class_id_foreign` (`class_id`),
  ADD KEY `score_setting_score_type_id_foreign` (`score_type_id`);

--
-- Indeks untuk tabel `score_types`
--
ALTER TABLE `score_types`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_user_id_foreign` (`user_id`),
  ADD KEY `student_study_program_id_foreign` (`study_program_id`);

--
-- Indeks untuk tabel `student_assignment`
--
ALTER TABLE `student_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_assignment_topic_assignment_id_foreign` (`topic_assignment_id`),
  ADD KEY `student_assignment_class_topic_id_foreign` (`class_topic_id`),
  ADD KEY `student_assignment_student_id_foreign` (`student_id`);

--
-- Indeks untuk tabel `student_exam_answer`
--
ALTER TABLE `student_exam_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_exam_answer_topic_exam_question_id_foreign` (`topic_exam_question_id`),
  ADD KEY `student_exam_answer_student_id_foreign` (`student_id`);

--
-- Indeks untuk tabel `student_presence`
--
ALTER TABLE `student_presence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_presence_class_topic_id_foreign` (`class_topic_id`),
  ADD KEY `student_presence_presence_type_id_foreign` (`presence_type_id`),
  ADD KEY `student_presence_student_id_foreign` (`student_id`);

--
-- Indeks untuk tabel `student_scores`
--
ALTER TABLE `student_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student_scores_student_id` (`student_id`);

--
-- Indeks untuk tabel `study_program`
--
ALTER TABLE `study_program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `study_program_faculty_id_foreign` (`faculty_id`);

--
-- Indeks untuk tabel `topic_assignment`
--
ALTER TABLE `topic_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_assignment_class_topic_id_foreign` (`class_topic_id`);

--
-- Indeks untuk tabel `topic_exam_questions`
--
ALTER TABLE `topic_exam_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_exam_questions_class_topic_id_foreign` (`class_topic_id`),
  ADD KEY `topic_exam_questions_score_type_id_foreign` (`score_type_id`);

--
-- Indeks untuk tabel `topic_modules`
--
ALTER TABLE `topic_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_modules_modul_type_id_foreign` (`modul_type_id`),
  ADD KEY `topic_modules_class_topic_id_foreign` (`class_topic_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `class`
--
ALTER TABLE `class`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `class_topic`
--
ALTER TABLE `class_topic`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `class_topic_menus`
--
ALTER TABLE `class_topic_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `class_topic_modul_question`
--
ALTER TABLE `class_topic_modul_question`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `lecture`
--
ALTER TABLE `lecture`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `modul_types`
--
ALTER TABLE `modul_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `periods`
--
ALTER TABLE `periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `presence_types`
--
ALTER TABLE `presence_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `score_setting`
--
ALTER TABLE `score_setting`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `score_types`
--
ALTER TABLE `score_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `student`
--
ALTER TABLE `student`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `student_assignment`
--
ALTER TABLE `student_assignment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `student_exam_answer`
--
ALTER TABLE `student_exam_answer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `student_presence`
--
ALTER TABLE `student_presence`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `student_scores`
--
ALTER TABLE `student_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `study_program`
--
ALTER TABLE `study_program`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `topic_assignment`
--
ALTER TABLE `topic_assignment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `topic_exam_questions`
--
ALTER TABLE `topic_exam_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `topic_modules`
--
ALTER TABLE `topic_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_responsible_lecturer_id_foreign` FOREIGN KEY (`responsible_lecturer_id`) REFERENCES `lecture` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_study_program_id_foreign` FOREIGN KEY (`study_program_id`) REFERENCES `study_program` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `class_student`
--
ALTER TABLE `class_student`
  ADD CONSTRAINT `class_student_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_student_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `class_topic`
--
ALTER TABLE `class_topic`
  ADD CONSTRAINT `class_topic_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `class_topic_menus`
--
ALTER TABLE `class_topic_menus`
  ADD CONSTRAINT `class_topic_menus_class_topic_id_foreign` FOREIGN KEY (`class_topic_id`) REFERENCES `class_topic` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `class_topic_modul_question`
--
ALTER TABLE `class_topic_modul_question`
  ADD CONSTRAINT `class_topic_modul_question_class_topic_id_foreign` FOREIGN KEY (`class_topic_id`) REFERENCES `class_topic` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_topic_modul_question_topic_exam_question_id_foreign` FOREIGN KEY (`topic_exam_question_id`) REFERENCES `topic_exam_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_topic_modul_question_topic_modul_id_foreign` FOREIGN KEY (`topic_modul_id`) REFERENCES `topic_modules` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `lecture`
--
ALTER TABLE `lecture`
  ADD CONSTRAINT `lecture_study_program_id_foreign` FOREIGN KEY (`study_program_id`) REFERENCES `study_program` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lecture_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `score_setting`
--
ALTER TABLE `score_setting`
  ADD CONSTRAINT `score_setting_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `score_setting_score_type_id_foreign` FOREIGN KEY (`score_type_id`) REFERENCES `score_types` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_study_program_id_foreign` FOREIGN KEY (`study_program_id`) REFERENCES `study_program` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `student_assignment`
--
ALTER TABLE `student_assignment`
  ADD CONSTRAINT `student_assignment_class_topic_id_foreign` FOREIGN KEY (`class_topic_id`) REFERENCES `class_topic` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_assignment_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_assignment_topic_assignment_id_foreign` FOREIGN KEY (`topic_assignment_id`) REFERENCES `topic_assignment` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `student_exam_answer`
--
ALTER TABLE `student_exam_answer`
  ADD CONSTRAINT `student_exam_answer_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_exam_answer_topic_exam_question_id_foreign` FOREIGN KEY (`topic_exam_question_id`) REFERENCES `topic_exam_questions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `student_presence`
--
ALTER TABLE `student_presence`
  ADD CONSTRAINT `student_presence_class_topic_id_foreign` FOREIGN KEY (`class_topic_id`) REFERENCES `class_topic` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_presence_presence_type_id_foreign` FOREIGN KEY (`presence_type_id`) REFERENCES `presence_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_presence_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `student_scores`
--
ALTER TABLE `student_scores`
  ADD CONSTRAINT `student_scores_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `study_program`
--
ALTER TABLE `study_program`
  ADD CONSTRAINT `study_program_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `topic_assignment`
--
ALTER TABLE `topic_assignment`
  ADD CONSTRAINT `topic_assignment_class_topic_id_foreign` FOREIGN KEY (`class_topic_id`) REFERENCES `class_topic` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `topic_exam_questions`
--
ALTER TABLE `topic_exam_questions`
  ADD CONSTRAINT `topic_exam_questions_class_topic_id_foreign` FOREIGN KEY (`class_topic_id`) REFERENCES `class_topic` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topic_exam_questions_score_type_id_foreign` FOREIGN KEY (`score_type_id`) REFERENCES `score_types` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `topic_modules`
--
ALTER TABLE `topic_modules`
  ADD CONSTRAINT `topic_modules_class_topic_id_foreign` FOREIGN KEY (`class_topic_id`) REFERENCES `class_topic` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topic_modules_modul_type_id_foreign` FOREIGN KEY (`modul_type_id`) REFERENCES `modul_types` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
