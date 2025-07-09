-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 09, 2025 at 10:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipeta`
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
('sipeta_cache_admin|127.0.0.1', 'i:1;', 1750563596),
('sipeta_cache_admin|127.0.0.1:timer', 'i:1750563596;', 1750563596);

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
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_topse`
--

CREATE TABLE `hasil_topse` (
  `id_hasil` bigint UNSIGNED NOT NULL,
  `lokasi_wisata_id` bigint UNSIGNED NOT NULL,
  `jarak_positif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jarak_negative` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_peferensi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rangking` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_wisata`
--

CREATE TABLE `jenis_wisata` (
  `id_jenis_wisata` bigint UNSIGNED NOT NULL,
  `nama_jenis_wisata` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_wisata`
--

INSERT INTO `jenis_wisata` (`id_jenis_wisata`, `nama_jenis_wisata`, `keterangan`) VALUES
(1, 'Wisata Alam', 'ini jenis wisata alam'),
(2, 'Wisata Sejarah', 'ini wisata sejarah'),
(3, 'Wisata Pantai', 'ini wisata pantai');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` bigint UNSIGNED NOT NULL,
  `nama_kriteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_kriteria` double NOT NULL,
  `tipe_kriteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `bobot_kriteria`, `tipe_kriteria`) VALUES
(1, 'Jenis wisata', 5, 'Benefit'),
(2, 'Fasilitas', 4, 'Benefit'),
(3, 'Keamanan', 3, 'Cost'),
(4, 'Transportasi', 2, 'Cost'),
(5, 'Akses lokasi', 1, 'Benefit');

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_wisata`
--

CREATE TABLE `lokasi_wisata` (
  `id_lokasi_wisata` bigint UNSIGNED NOT NULL,
  `jenis_wisata_id` bigint UNSIGNED NOT NULL,
  `nama_lokasi_wisata` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fasilitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keamanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transportasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `akses_lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitute` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_wisata`
--

INSERT INTO `lokasi_wisata` (`id_lokasi_wisata`, `jenis_wisata_id`, `nama_lokasi_wisata`, `fasilitas`, `keamanan`, `transportasi`, `akses_lokasi`, `longitude`, `latitute`) VALUES
(1, 1, 'Air Terjun Drakisi', 'Air terjun, jalur hiking', 'Perlu pendamping', 'Ojek, Jalan kaki', 'Jalur hutan alami', '100', '-200'),
(2, 1, 'Air Terjun Demta', 'Air terjun alami', 'Cukup Aman', 'Mobil, Jalan kaki', 'Akses terbatas, pedalaman', '140.511017', '-2.564108'),
(3, 1, 'Gua Mamda', 'Goa alami, Spot eksplorasi', 'Aman', 'Mobil, Jalan kaki', 'Akses hutan', NULL, NULL),
(4, 1, 'Habitat Burung Cendrawasih', 'Observasi burung, Alam liar', 'Perlu pemandu', 'Mobil, Jalan kaki', 'Hutan tropis', NULL, NULL),
(5, 1, 'Telaga Aloska', 'Danau kecil, Spot tenang', 'Aman', 'Motor', 'Dekat kampung', NULL, NULL),
(6, 1, 'Mata Air Garam', 'Mata air unik, Alami', 'Aman', 'Mobil', 'Area pegunungan', NULL, NULL),
(7, 1, 'Gunung Kandega', 'Pendakian, Panorama', 'Perlu hati-hati', 'Mobil, Jalan kaki', 'Jalur menanjak', NULL, NULL),
(8, 1, 'Spot Sabubu', 'Pemandangan bukit dan danau', 'Aman', 'Mobil, Ojek', 'Dekat pemukiman', NULL, NULL),
(9, 2, 'Fosil Sagu', 'Situs arkeologi, edukatif', 'Aman', 'Mobil', 'Jalur datar', NULL, NULL),
(10, 2, 'Fosil Manusia Raksasa', 'Situs prasejarah, Penelitian', 'Aman', 'Mobil', 'Pedalaman', NULL, NULL),
(11, 2, 'Tugu Monumen PD II', 'Monumen sejarah, Peringatan', 'Aman', 'Mobil, Ojek', 'Dekat jalan utama', NULL, NULL),
(12, 2, 'Tugu Yawa Datum', 'Tugu budaya dan sejarah', 'Aman', 'Mobil', 'Dekat pemukiman', NULL, NULL),
(13, 2, 'Tugu Perubahan Peradaban', 'Monumen budaya', 'Aman', 'Mobil', 'Pusat kota', NULL, NULL),
(14, 2, 'Ukiran Kayu dan Kerang', 'Galeri seni tradisional', 'Aman', 'Mobil, Jalan kaki', 'Dekat kampung wisata', NULL, NULL),
(15, 2, 'Pusat Penyebaran Harta Budaya', 'Museum lokal', 'Aman', 'Mobil, Ojek', 'Dekat pusat desa', NULL, NULL),
(16, 2, 'Yono Waw', 'Situs budaya atau ritual', 'Perlu pendamping', 'Motor, Jalan kaki', 'Area terpencil', NULL, NULL),
(17, 3, 'Pantai Wesapan', 'Pasir putih, Spot santai', 'Aman', 'Mobil, Motor', 'Akses jalan kampung', NULL, NULL),
(18, 3, 'Pantai Yaugapsa', 'Bermain air, Spot foto', 'Aman', 'Motor, Ojek', 'Dekat permukiman', NULL, NULL),
(19, 3, 'Pantai Tarfia', 'Snorkeling, Bersih', 'Aman', 'Mobil', 'Pinggir kampung', NULL, NULL),
(20, 3, 'Pantai Muaif', 'Spot renang, Warung', 'Aman', 'Mobil, Ojek', 'Dekat akses utama', NULL, NULL),
(21, 3, 'Pantai Snamai', 'Pantai alami', 'Aman', 'Mobil', 'Jalur tanah', NULL, NULL),
(22, 3, 'Pantai Meukisi', 'Pasir halus, Sunyi', 'Aman', 'Ojek', 'Tepi hutan', NULL, NULL),
(23, 3, 'Pantai Buseryo', 'Bermain pasir, Sunset', 'Aman', 'Mobil, Motor', 'Akses baik', NULL, NULL),
(24, 3, 'Pantai Endokisi', 'Spot santai, Warung', 'Aman', 'Motor, Ojek', 'Dekat desa', NULL, NULL),
(25, 3, 'Pantai Bukisi', 'Pasir putih, Ombak kecil', 'Aman', 'Mobil', 'Jalur pinggir danau', NULL, NULL),
(26, 3, 'Pantai Sinokisi', 'Spot foto, Santai', 'Aman', 'Ojek, Jalan kaki', 'Pinggir pantai tersembunyi', NULL, NULL),
(27, 3, 'Pantai Drakisi', 'Pantai alami, Sepi', 'Aman', 'Motor', 'Dekat air terjun', NULL, NULL);

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
(27, '0001_01_01_000000_create_users_table', 1),
(28, '0001_01_01_000001_create_cache_table', 1),
(29, '0001_01_01_000002_create_jobs_table', 1),
(30, '2025_06_01_161305_create_kriterias_table', 1),
(31, '2025_06_01_161345_create_subkriterias_table', 1),
(32, '2025_06_01_161545_create_jenis_wisatas_table', 1),
(33, '2025_06_01_161640_create_lokasi_wisatas_table', 1),
(34, '2025_06_01_161805_create_hasil_topses_table', 1),
(35, '2025_06_01_161831_create_nilai_alternatifs_table', 1),
(36, '2025_06_04_024041_create_notifications_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_alternatif`
--

CREATE TABLE `nilai_alternatif` (
  `id_alternatif` bigint UNSIGNED NOT NULL,
  `lokasi_wisata_id` bigint UNSIGNED NOT NULL,
  `subkriteria_id` bigint UNSIGNED NOT NULL,
  `nilai` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('jRDua4zQr35oHBjdnqBjzM231bZNpLcNZawgvIXy', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZXJHQzduYldwV2g3NG9XSHJPQ1lONHBzSnJINndIbWJuZDNJb01wdCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3RvcHNpcyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2FsdGVybmF0aWYiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1752057064);

-- --------------------------------------------------------

--
-- Table structure for table `subkriteria`
--

CREATE TABLE `subkriteria` (
  `id_subkriteria` bigint UNSIGNED NOT NULL,
  `kriteria_id` bigint UNSIGNED NOT NULL,
  `nama_subkriteria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_subkriteria` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subkriteria`
--

INSERT INTO `subkriteria` (`id_subkriteria`, `kriteria_id`, `nama_subkriteria`, `bobot_subkriteria`) VALUES
(1, 1, 'Wisata Alam', 5),
(2, 1, 'Wisata Sejarah', 3),
(3, 1, 'Wisata Pantai', 1),
(4, 2, 'Spot Foto', 5),
(5, 2, 'Tempat Makan', 4),
(6, 2, 'Toilet', 3),
(7, 2, 'Tempat Parkir', 2),
(8, 2, 'Tempat Sampah', 1),
(9, 3, 'Aman', 5),
(10, 3, 'Tidak Aman', 1),
(11, 4, 'Mobil', 5),
(12, 4, 'Motor', 3),
(13, 4, 'Perahu', 1),
(14, 5, 'Akses jalan mulus dan lebar', 5),
(15, 5, 'Akses jalan luas', 4),
(16, 5, 'Akses jalan sempit', 3),
(17, 5, 'Akses jalan berlubang', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Nama Baru', 'admin123', 'rizkiproject0912@gmail.com', NULL, '$2y$12$swnYRp8bQdh2zufYB1V1S.sCczlldEDdVKpfAgyrwT2E7CniaIQVa', 1, 'b9BFnsiOl9vgh3QrxHJcKqcgJ3MhZUVhXzmUPASz9OT42JtkQLKSvJ9Xfl3C', '2025-06-03 16:27:04', '2025-06-05 07:24:13');

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hasil_topse`
--
ALTER TABLE `hasil_topse`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `hasil_topse_lokasi_wisata_id_foreign` (`lokasi_wisata_id`);

--
-- Indexes for table `jenis_wisata`
--
ALTER TABLE `jenis_wisata`
  ADD PRIMARY KEY (`id_jenis_wisata`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `lokasi_wisata`
--
ALTER TABLE `lokasi_wisata`
  ADD PRIMARY KEY (`id_lokasi_wisata`),
  ADD KEY `lokasi_wisata_jenis_wisata_id_foreign` (`jenis_wisata_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  ADD PRIMARY KEY (`id_alternatif`),
  ADD KEY `nilai_alternatif_lokasi_wisata_id_foreign` (`lokasi_wisata_id`),
  ADD KEY `nilai_alternatif_subkriteria_id_foreign` (`subkriteria_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subkriteria`
--
ALTER TABLE `subkriteria`
  ADD PRIMARY KEY (`id_subkriteria`),
  ADD KEY `subkriteria_kriteria_id_foreign` (`kriteria_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_topse`
--
ALTER TABLE `hasil_topse`
  MODIFY `id_hasil` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_wisata`
--
ALTER TABLE `jenis_wisata`
  MODIFY `id_jenis_wisata` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lokasi_wisata`
--
ALTER TABLE `lokasi_wisata`
  MODIFY `id_lokasi_wisata` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  MODIFY `id_alternatif` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `subkriteria`
--
ALTER TABLE `subkriteria`
  MODIFY `id_subkriteria` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_topse`
--
ALTER TABLE `hasil_topse`
  ADD CONSTRAINT `hasil_topse_lokasi_wisata_id_foreign` FOREIGN KEY (`lokasi_wisata_id`) REFERENCES `lokasi_wisata` (`id_lokasi_wisata`) ON DELETE CASCADE;

--
-- Constraints for table `lokasi_wisata`
--
ALTER TABLE `lokasi_wisata`
  ADD CONSTRAINT `lokasi_wisata_jenis_wisata_id_foreign` FOREIGN KEY (`jenis_wisata_id`) REFERENCES `jenis_wisata` (`id_jenis_wisata`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  ADD CONSTRAINT `nilai_alternatif_lokasi_wisata_id_foreign` FOREIGN KEY (`lokasi_wisata_id`) REFERENCES `lokasi_wisata` (`id_lokasi_wisata`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_alternatif_subkriteria_id_foreign` FOREIGN KEY (`subkriteria_id`) REFERENCES `subkriteria` (`id_subkriteria`) ON DELETE CASCADE;

--
-- Constraints for table `subkriteria`
--
ALTER TABLE `subkriteria`
  ADD CONSTRAINT `subkriteria_kriteria_id_foreign` FOREIGN KEY (`kriteria_id`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
