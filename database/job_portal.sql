-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 04:09 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `lowongan`
--

CREATE TABLE `lowongan` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `perusahaan` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `gaji` varchar(100) NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kualifikasi` text DEFAULT NULL,
  `batas_lamaran` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lowongan`
--

INSERT INTO `lowongan` (`id`, `judul`, `perusahaan`, `kategori`, `lokasi`, `gaji`, `tipe`, `slug`, `logo`, `deskripsi`, `kualifikasi`, `batas_lamaran`, `created_at`) VALUES
(1, 'Frontend Developer', 'Tech Corp', 'IT', 'Ambon, Indonesia', 'Rp 8.000.000 - Rp 12.000.000 per month', 'Remote', 'frontend-developer', '/miniproject/assets/logo perusahaan/logo 1.png', NULL, NULL, NULL, '2025-04-14 07:18:23'),
(2, 'Backend Developer', 'Code Labs', 'IT', 'Yogyakarta, Indonesia', 'Rp 5.000.000 - Rp 8.000.000 per month', 'Full-time', 'backend-developer', '/miniproject/assets/logo perusahaan/logo 2.png', NULL, NULL, NULL, '2025-04-14 07:18:23'),
(3, 'UI/UX Designer', 'Creative Studio', 'Desain', 'Surabaya, Indonesia', 'Rp 1.700.000 - Rp 3.000.000 per month', 'Freelance', 'uiux-designer', '/miniproject/assets/logo perusahaan/logo 3.png', NULL, NULL, NULL, '2025-04-14 07:18:23'),
(4, 'Kasir', 'Jogja Mart', 'Ritel', 'Yogyakarta, Indonesia', 'Rp 2.000.000 - Rp 3.500.000 per month', 'Full-time', 'kasir', '/miniproject/assets/logo perusahaan/logo 4.png', NULL, NULL, NULL, '2025-04-14 07:18:23'),
(5, 'Sales', 'Honda', 'Marketing', 'Jakarta, Indonesia', 'Rp 8.000.000 - Rp 12.000.000 per month', 'Full-time', 'satpam', '/miniproject/assets/logo perusahaan/logo 6.pn', NULL, NULL, NULL, '2025-04-14 08:45:05'),
(7, 'Barista', 'Zare Coffe', 'Food & Beverage', 'Yogyakarta, Indonesia', 'Rp 1.500.000 - Rp 2.000.000 per month', 'Part-time', 'barista', 'assets/logo_perusahaan/1744618756-logo 5.png', NULL, NULL, NULL, '2025-04-14 03:19:16'),
(8, 'ttt', 'ttrewt', 'IT', 'ertertert', 'Rp 1.500.000 - Rp 2.000.000 per month', 'Full-time', 'ttt', '/miniproject/assets/logo_perusahaan/1744618933-code 12.png', NULL, NULL, NULL, '2025-04-14 03:22:13'),
(9, 'sdfgsdfg', 'gdrge', 'Desain', 'dfghd', 'Rp 1.500.000 - Rp 2.000.000 per month', 'Full-time', 'sdfgsdfg', 'assets/logo_perusahaan/1744619808-logo-5.png', NULL, NULL, NULL, '2025-04-14 03:36:48'),
(12, 'tyhrty', 'ry6y756', 'IT', 'tyrtyr', 'Rp 1.000.000 - Rp 2.000.000 per month', 'Full-time', 'tyhrty', 'assets/logo_perusahaan/1744620591-code-12.png', NULL, NULL, NULL, '2025-04-14 03:49:51'),
(13, 'Jonathan', 'JOOO', 'IT', 'Yogyakarta, Indonesia', 'Rp 1.500.000 - Rp 2.000.000 per month', 'Full-time', 'jonathan', 'assets/logo_perusahaan/1744633611-Untitled-Diagram.drawio.png', NULL, NULL, NULL, '2025-04-14 07:26:51'),
(14, 'Jonathan ahahha', 'JOOO dcd', 'IT', 'Yogyakarta, Indonesia', 'Rp 1.500.000 - Rp 2.000.000 per month', 'Full-time', 'jonathan-ahahha', 'assets/logo_perusahaan/1744633959-code-2.png', NULL, NULL, NULL, '2025-04-14 07:32:39'),
(15, 'Jonathan WOI', 'JOOO WPI', 'IT', 'Yogyakarta, Indonesia', 'Rp 1.500.000 - Rp 2.000.000 per month', 'Full-time', 'jonathan-woi', 'assets/logo_perusahaan/1744634067-JOB.png', NULL, NULL, NULL, '2025-04-14 07:34:27'),
(16, 'cok', 'fertfer', 'Kesehatan', 'efwe', 'Rp 1.500.000 - Rp 3.000.000 per month', 'Full-time', 'cok', 'assets/logo_perusahaan/1744638534-layz-(3).png', NULL, NULL, NULL, '2025-04-14 08:48:54'),
(17, 'hahahaha', 'hahahas', 'Kesehatan', 'Yogyakarta, Indonesia', 'Rp 1.500.000 - Rp 2.000.000 per month', 'Part-time', 'hahahaha', 'assets/logo_perusahaan/1744638838-2024-11-16_09-29.png', 'TEST', 'TEST', '2025-04-14', '2025-04-14 08:53:58'),
(18, 'Adriano Jobs', 'Adriano', 'Hukum', 'Ngawi', 'Rp 1.000.000 - Rp 2.000.000 per month', 'Full-time', 'adriano-jobs', 'assets/logo_perusahaan/1746690518-Screenshot-2024-06-03-220555.png', 'Ngejomok', 'Rudal Hitam Besar', '2025-05-08', '2025-05-08 02:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `users_pelamar`
--

CREATE TABLE `users_pelamar` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_pelamar`
--

INSERT INTO `users_pelamar` (`id`, `nama_lengkap`, `email`, `password`, `no_telepon`, `alamat`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$/GsKNz51BHpiVrg1vabwsORm3k3f33eeqmu2jfEIalVkKG7ibeFme', '09812345', 'Indonesia', '2025-04-20 17:43:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lowongan`
--
ALTER TABLE `lowongan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users_pelamar`
--
ALTER TABLE `users_pelamar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lowongan`
--
ALTER TABLE `lowongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users_pelamar`
--
ALTER TABLE `users_pelamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
