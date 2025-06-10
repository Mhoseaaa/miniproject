-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 05:27 AM
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
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `id` int(11) NOT NULL,
  `nama_perusahaan` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`id`, `nama_perusahaan`, `email`, `password`, `no_telepon`, `alamat`, `logo`, `created_at`) VALUES
(5, 'King', 'king@gmail.com', '$2y$10$LN4NY7wyTsD3l.cG4pbmEuz1tKp9ye4pyuz9TEP.WdovtjCNiaF22', '08978978', 'Indonesia', 'assets/logo_perusahaan/1749525844-kids-super-o.png', '2025-06-10 03:24:04');

-- --------------------------------------------------------

--
-- Table structure for table `lowongan`
--

CREATE TABLE `lowongan` (
  `id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `perusahaan` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `gaji_min` int(11) NOT NULL,
  `gaji_max` int(11) NOT NULL,
  `gaji_display` varchar(100) NOT NULL,
  `tipe` varchar(20) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `kualifikasi` text NOT NULL,
  `batas_lamaran` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lowongan`
--

INSERT INTO `lowongan` (`id`, `employer_id`, `judul`, `perusahaan`, `kategori`, `lokasi`, `gaji_min`, `gaji_max`, `gaji_display`, `tipe`, `logo`, `deskripsi`, `kualifikasi`, `batas_lamaran`, `created_at`, `updated_at`) VALUES
(1, 5, 'Manager Marketing', 'King', 'Marketing', 'Yogyakarta, Indonesia', 1500000, 2000000, 'Rp 1.500.000 - Rp 2.000.000 per month', 'Full-time', 'assets/logo_perusahaan/1749525844-kids-super-o.png', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2025-06-30', '2025-06-10 03:25:50', '2025-06-10 03:25:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$srdP9nejkmKPfS75EHA5sObU9mpj8B5EyUsdkfLXnBy.PvTgmRf8m', '2025-06-07 15:23:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `lowongan`
--
ALTER TABLE `lowongan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employer_id` (`employer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employers`
--
ALTER TABLE `employers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lowongan`
--
ALTER TABLE `lowongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lowongan`
--
ALTER TABLE `lowongan`
  ADD CONSTRAINT `lowongan_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
