-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 11, 2025 at 04:53 PM
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
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sipeta_cache_invertor2|127.0.0.1', 'i:1;', 1752125463),
('sipeta_cache_invertor2|127.0.0.1:timer', 'i:1752125463;', 1752125463);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_topsis`
--

CREATE TABLE `hasil_topsis` (
  `id_hasil` bigint UNSIGNED NOT NULL,
  `lokasi_wisata_id` bigint UNSIGNED NOT NULL,
  `jarak_positif` float NOT NULL,
  `jarak_negative` float NOT NULL,
  `tipe_preferensi` float NOT NULL,
  `rangking` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hasil_topsis`
--

INSERT INTO `hasil_topsis` (`id_hasil`, `lokasi_wisata_id`, `jarak_positif`, `jarak_negative`, `tipe_preferensi`, `rangking`) VALUES
(1, 3, 0.299, 1.56, 0.839, 1),
(2, 8, 0.446, 1.548, 0.776, 2),
(3, 7, 0.622, 1.461, 0.701, 3),
(4, 4, 0.636, 1.343, 0.679, 4),
(5, 6, 0.668, 1.37, 0.672, 5),
(6, 15, 0.61, 1.225, 0.668, 6),
(7, 9, 0.797, 1.299, 0.62, 7),
(8, 1, 0.863, 1.256, 0.593, 8),
(9, 11, 0.82, 1.088, 0.57, 9),
(10, 13, 0.825, 1.082, 0.567, 10),
(11, 2, 0.971, 1.166, 0.546, 11),
(12, 12, 0.902, 1.066, 0.542, 12),
(13, 5, 0.987, 1.161, 0.541, 13),
(14, 16, 0.937, 0.761, 0.448, 14),
(15, 17, 1.264, 1.009, 0.444, 15),
(16, 10, 1.082, 0.825, 0.433, 16),
(17, 28, 1.082, 0.825, 0.433, 17),
(18, 24, 1.302, 0.719, 0.356, 18),
(19, 25, 1.305, 0.709, 0.352, 19),
(20, 18, 1.354, 0.698, 0.34, 20),
(21, 14, 1.212, 0.618, 0.338, 21),
(22, 22, 1.476, 0.587, 0.285, 22),
(23, 23, 1.51, 0.567, 0.273, 23),
(24, 21, 1.361, 0.504, 0.27, 24),
(25, 26, 1.43, 0.528, 0.27, 25),
(26, 27, 1.43, 0.528, 0.27, 26),
(27, 20, 1.446, 0.37, 0.204, 27),
(28, 19, 1.508, 0.22, 0.127, 28);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_wisata`
--

CREATE TABLE `jenis_wisata` (
  `id_jenis_wisata` bigint UNSIGNED NOT NULL,
  `nama_jenis_wisata` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
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
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  `nama_kriteria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot_kriteria` double NOT NULL,
  `tipe_kriteria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
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
  `nama_lokasi_wisata` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fasilitas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keamanan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transportasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `akses_lokasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lokasi_wisata`
--

INSERT INTO `lokasi_wisata` (`id_lokasi_wisata`, `jenis_wisata_id`, `nama_lokasi_wisata`, `fasilitas`, `keamanan`, `transportasi`, `akses_lokasi`, `longitude`, `latitude`) VALUES
(1, 1, 'Air Terjun Drakisi', 'Air terjun, jalur hiking', 'Perlu pendamping', 'Ojek, Jalan kaki', 'Jalur hutan alami', 140.197390, -2.394968),
(2, 1, 'Air Terjun Demta', 'Air terjun alami', 'Cukup Aman', 'Mobil, Jalan kaki', 'Akses terbatas, pedalaman', 140.145740, -2.365130),
(3, 1, 'Gua Mamda', 'Goa alami, Spot eksplorasi', 'Aman', 'Mobil, Jalan kaki', 'Akses hutan', 140.334150, -2.581630),
(4, 1, 'Habitat Burung Cendrawasih', 'Observasi burung, Alam liar', 'Perlu pemandu', 'Mobil, Jalan kaki', 'Hutan tropis', 140.098698, -2.559438),
(5, 1, 'Telaga Aloska', 'Danau kecil, Spot tenang', 'Aman', 'Motor', 'Dekat kampung', 140.407681, -3.374742),
(6, 1, 'Mata Air Garam', 'Mata air unik, Alami', 'Aman', 'Mobil', 'Area pegunungan', 140.406108, -3.378945),
(7, 1, 'Gunung Kandega', 'Pendakian, Panorama', 'Perlu hati-hati', 'Mobil, Jalan kaki', 'Jalur menanjak', 140.034399, -3.609303),
(8, 1, 'Spot Sabubu', 'Pemandangan bukit dan danau', 'Aman', 'Mobil, Ojek', 'Dekat pemukiman', 140.371107, -2.397835),
(9, 2, 'Fosil Sagu', 'Situs arkeologi, edukatif', 'Aman', 'Mobil', 'Jalur datar', 140.215218, -2.636799),
(10, 2, 'Fosil Manusia Raksasa', 'Situs prasejarah, Penelitian', 'Aman', 'Mobil', 'Pedalaman', 140.145131, -2.360878),
(11, 2, 'Tugu Monumen PD II', 'Monumen sejarah, Peringatan', 'Aman', 'Mobil, Ojek', 'Dekat jalan utama', 140.167012, -2.596158),
(12, 2, 'Tugu Yawa Datum', 'Tugu budaya dan sejarah', 'Aman', 'Mobil', 'Dekat pemukiman', 140.166562, -2.596219),
(13, 2, 'Tugu Perubahan Peradaban', 'Monumen budaya', 'Aman', 'Mobil', 'Pusat kota', 140.195024, -2.608886),
(14, 2, 'Ukiran Kayu dan Kerang', 'Galeri seni tradisional', 'Aman', 'Mobil, Jalan kaki', 'Dekat kampung wisata', 140.146605, -2.343447),
(15, 2, 'Pusat Penyebaran Harta Budaya', 'Museum lokal', 'Aman', 'Mobil, Ojek', 'Dekat pusat desa', 140.248442, -2.663452),
(16, 2, 'Yono Waw', 'Situs budaya atau ritual', 'Perlu pendamping', 'Motor, Jalan kaki', 'Area terpencil', 140.104438, -2.638524),
(17, 3, 'Pantai Wesapan', 'Pasir putih, Spot santai', 'Aman', 'Mobil, Motor', 'Akses jalan kampung', 140.181874, -2.378312),
(18, 3, 'Pantai Yaugapsa', 'Bermain air, Spot foto', 'Aman', 'Motor, Ojek', 'Dekat permukiman', 140.151552, -2.323792),
(19, 3, 'Pantai Tarfia', 'Snorkeling, Bersih', 'Aman', 'Mobil', 'Pinggir kampung', 140.111148, -2.328523),
(20, 3, 'Pantai Muaif', 'Spot renang, Warung', 'Aman', 'Mobil, Ojek', 'Dekat akses utama', 140.034366, -2.359537),
(21, 3, 'Pantai Snamai', 'Pantai alami', 'Aman', 'Mobil', 'Jalur tanah', 140.317907, -2.448372),
(22, 3, 'Pantai Meukisi', 'Pasir halus, Sunyi', 'Aman', 'Ojek', 'Tepi hutan', 140.244904, -2.404998),
(23, 3, 'Pantai Buseryo', 'Bermain pasir, Sunset', 'Aman', 'Mobil, Motor', 'Akses baik', 140.327884, -2.450828),
(24, 3, 'Pantai Endokisi', 'Spot santai, Warung', 'Aman', 'Motor, Ojek', 'Dekat desa', 140.293802, -2.438534),
(25, 3, 'Pantai Bukisi', 'Pasir putih, Ombak kecil', 'Aman', 'Mobil', 'Jalur pinggir danau', 140.223399, -2.412721),
(26, 3, 'Pantai Sinokisi', 'Spot foto, Santai', 'Aman', 'Ojek, Jalan kaki', 'Pinggir pantai tersembunyi', 140.292701, -2.429033),
(27, 3, 'Pantai Drakisi', 'Pantai alami, Sepi', 'Aman', 'Motor', 'Dekat air terjun', 140.196320, -2.390571),
(28, 2, 'Lapter tanjung tanah merah', 'Tempat sampah', 'Tidak aman', 'Motor', 'Akses jalan sempit', 140.348072, -2.400971);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

--
-- Dumping data for table `nilai_alternatif`
--

INSERT INTO `nilai_alternatif` (`id_alternatif`, `lokasi_wisata_id`, `subkriteria_id`, `nilai`) VALUES
(16, 1, 1, 5),
(17, 1, 7, 2),
(18, 1, 9, 5),
(19, 1, 13, 1),
(20, 1, 14, 5),
(21, 2, 1, 5),
(22, 2, 7, 2),
(23, 2, 9, 5),
(24, 2, 11, 5),
(25, 2, 16, 3),
(26, 3, 1, 5),
(27, 3, 4, 5),
(28, 3, 10, 1),
(29, 3, 12, 3),
(30, 3, 17, 1),
(31, 9, 1, 5),
(32, 9, 7, 2),
(33, 9, 10, 1),
(34, 9, 11, 5),
(35, 9, 14, 5),
(36, 4, 1, 5),
(37, 4, 5, 4),
(38, 4, 9, 5),
(39, 4, 12, 3),
(40, 4, 16, 3),
(41, 5, 1, 5),
(42, 5, 7, 2),
(43, 5, 9, 5),
(44, 5, 11, 5),
(45, 5, 17, 1),
(46, 6, 1, 5),
(47, 6, 7, 2),
(48, 6, 10, 1),
(49, 6, 13, 1),
(50, 6, 14, 5),
(51, 7, 1, 5),
(52, 7, 4, 5),
(53, 7, 9, 5),
(54, 7, 12, 3),
(55, 7, 17, 1),
(56, 8, 1, 5),
(57, 8, 4, 5),
(58, 8, 10, 1),
(59, 8, 11, 5),
(60, 8, 16, 3),
(61, 10, 2, 3),
(62, 10, 8, 1),
(63, 10, 10, 1),
(64, 10, 12, 3),
(65, 10, 16, 3),
(66, 11, 2, 3),
(67, 11, 4, 5),
(68, 11, 9, 5),
(69, 11, 12, 3),
(70, 11, 15, 4),
(71, 12, 2, 3),
(72, 12, 4, 5),
(73, 12, 9, 5),
(74, 12, 11, 5),
(75, 12, 15, 4),
(76, 13, 2, 3),
(77, 13, 4, 5),
(78, 13, 9, 5),
(79, 13, 12, 3),
(80, 13, 16, 3),
(81, 14, 2, 3),
(82, 14, 8, 1),
(83, 14, 9, 5),
(84, 14, 12, 3),
(85, 14, 16, 3),
(86, 15, 2, 3),
(87, 15, 4, 5),
(88, 15, 10, 1),
(89, 15, 12, 3),
(90, 15, 14, 5),
(91, 16, 2, 3),
(92, 16, 6, 3),
(93, 16, 9, 5),
(94, 16, 12, 3),
(95, 16, 16, 3),
(96, 17, 3, 1),
(97, 17, 4, 5),
(98, 17, 9, 5),
(99, 17, 13, 1),
(100, 17, 14, 5),
(101, 18, 3, 1),
(102, 18, 5, 4),
(103, 18, 9, 5),
(104, 18, 11, 5),
(105, 18, 14, 5),
(106, 19, 3, 1),
(107, 19, 7, 2),
(108, 19, 9, 5),
(109, 19, 11, 5),
(110, 19, 17, 1),
(111, 20, 3, 1),
(112, 20, 7, 2),
(113, 20, 9, 5),
(114, 20, 12, 3),
(115, 20, 14, 5),
(116, 21, 3, 1),
(117, 21, 6, 3),
(118, 21, 9, 5),
(119, 21, 12, 3),
(120, 21, 16, 3),
(121, 22, 3, 1),
(122, 22, 8, 1),
(123, 22, 10, 1),
(124, 22, 12, 3),
(125, 22, 17, 1),
(126, 23, 3, 1),
(127, 23, 8, 1),
(128, 23, 10, 1),
(129, 23, 11, 5),
(130, 23, 15, 4),
(131, 24, 3, 1),
(132, 24, 5, 4),
(133, 24, 9, 5),
(134, 24, 12, 3),
(135, 24, 15, 4),
(136, 25, 3, 1),
(137, 25, 5, 4),
(138, 25, 9, 5),
(139, 25, 12, 3),
(140, 25, 16, 3),
(141, 26, 3, 1),
(142, 26, 7, 2),
(143, 26, 9, 5),
(144, 26, 13, 1),
(145, 26, 14, 5),
(146, 27, 3, 1),
(147, 27, 7, 2),
(148, 27, 9, 5),
(149, 27, 13, 1),
(150, 27, 14, 5),
(151, 28, 2, 3),
(152, 28, 8, 1),
(153, 28, 10, 1),
(154, 28, 12, 3),
(155, 28, 16, 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('aglTJr5sdzjh4aw58mee4gMGklxZFh2llbhKdpPn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUzlXSlVqZ1dIcThlRlFUSmFyZnRkaFJmcjlObE1WbVY0a2l3eVBaQyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3BlbWV0YWFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1752246125),
('l7iboFi8edQVlX338l3aFStLPklrmZfzmmIAehN0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoibGI0eUdDSHdPUEl0YnFJNnljOHFQREh3M0k5QWF5OFk5ZTVxNjlNNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319fQ==', 1752252597);

-- --------------------------------------------------------

--
-- Table structure for table `subkriteria`
--

CREATE TABLE `subkriteria` (
  `id_subkriteria` bigint UNSIGNED NOT NULL,
  `kriteria_id` bigint UNSIGNED NOT NULL,
  `nama_subkriteria` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Nama Baru', 'admin123', 'admin@gmail.com', NULL, '$2y$12$swnYRp8bQdh2zufYB1V1S.sCczlldEDdVKpfAgyrwT2E7CniaIQVa', 1, 'HC1o172wXUpI4q1F1BXPaXJaCZOv3HTWDsi0tcS2ZbSQpHAxVoP1df294kj9', '2025-06-03 16:27:04', '2025-06-05 07:24:13'),
(2, 'Kepala Dinas Kewisataan', 'kepala', 'kepaladinas@gmail.com', NULL, '$2y$12$6UYsFoiAdtvy3COOd5c8XOxEstakHEXawYKO.cjbqBMhYMZ5bAVf.', 2, NULL, NULL, '2025-07-11 05:17:27');

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
-- Indexes for table `hasil_topsis`
--
ALTER TABLE `hasil_topsis`
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
-- AUTO_INCREMENT for table `hasil_topsis`
--
ALTER TABLE `hasil_topsis`
  MODIFY `id_hasil` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
  MODIFY `id_lokasi_wisata` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `nilai_alternatif`
--
ALTER TABLE `nilai_alternatif`
  MODIFY `id_alternatif` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `subkriteria`
--
ALTER TABLE `subkriteria`
  MODIFY `id_subkriteria` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_topsis`
--
ALTER TABLE `hasil_topsis`
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
