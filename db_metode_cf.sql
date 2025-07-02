-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 05:43 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_metode_cf`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `nama_lengkap`, `username`, `password`, `level`) VALUES
(1, 'adam', 'adam', '12345', 'Admin'),
(15, 'nadila', 'dila', '123', 'Pasien'),
(16, 'juni', 'juni', '123', 'Pasien'),
(17, 'qonita', '', '', ''),
(18, 'nadila', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_aturan`
--

CREATE TABLE `tbl_aturan` (
  `id_aturan` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `id_penyakit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_aturan`
--

INSERT INTO `tbl_aturan` (`id_aturan`, `id_gejala`, `id_penyakit`) VALUES
(3, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_diagnosa`
--

CREATE TABLE `tbl_diagnosa` (
  `id_diagnosa` int(11) NOT NULL,
  `no_regdiagnosa` char(10) NOT NULL,
  `tgl_diagnosa` date NOT NULL,
  `id_akun` int(11) NOT NULL,
  `id_gejala` int(11) NOT NULL,
  `nilai_pasien` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gejala`
--

CREATE TABLE `tbl_gejala` (
  `id_gejala` int(11) NOT NULL,
  `nama_gejala` text NOT NULL,
  `nilai_gejala` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_gejala`
--

INSERT INTO `tbl_gejala` (`id_gejala`, `nama_gejala`, `nilai_gejala`) VALUES
(1, 'magh', 0.4),
(2, 'a', 0.1),
(3, 'b', 0.2),
(4, 'd', 0.6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hasil`
--

CREATE TABLE `tbl_hasil` (
  `id_hasil` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `no_regdiagnosa` char(10) NOT NULL,
  `tgl_diagnoas` date NOT NULL,
  `penyakit_cf` text NOT NULL,
  `nilai_cf` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pasien`
--

CREATE TABLE `tbl_pasien` (
  `id_pasien` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(50) NOT NULL,
  `umur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pasien`
--

INSERT INTO `tbl_pasien` (`id_pasien`, `id_admin`, `nama_lengkap`, `jenis_kelamin`, `umur`) VALUES
(1, 7, 'sayuri', 'Laki-Laki', 3),
(2, 9, 'muhamad rivaldi', 'Laki-Laki', 3),
(6, 1, 'nadila', 'Perempuan', 2),
(7, 16, 'juni', 'Laki-Laki', 2),
(14, 17, 'nadila', 'perempuan', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penyakit`
--

CREATE TABLE `tbl_penyakit` (
  `id_penyakit` int(11) NOT NULL,
  `nama_penyakit` text NOT NULL,
  `keterangan` text NOT NULL,
  `solusi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_penyakit`
--

INSERT INTO `tbl_penyakit` (`id_penyakit`, `nama_penyakit`, `keterangan`, `solusi`) VALUES
(2, 'diare', 'as', 'd'),
(3, 'g', 'd', 'v');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `tbl_aturan`
--
ALTER TABLE `tbl_aturan`
  ADD PRIMARY KEY (`id_aturan`);

--
-- Indexes for table `tbl_diagnosa`
--
ALTER TABLE `tbl_diagnosa`
  ADD PRIMARY KEY (`id_diagnosa`);

--
-- Indexes for table `tbl_gejala`
--
ALTER TABLE `tbl_gejala`
  ADD PRIMARY KEY (`id_gejala`);

--
-- Indexes for table `tbl_hasil`
--
ALTER TABLE `tbl_hasil`
  ADD PRIMARY KEY (`id_hasil`);

--
-- Indexes for table `tbl_pasien`
--
ALTER TABLE `tbl_pasien`
  ADD PRIMARY KEY (`id_pasien`);

--
-- Indexes for table `tbl_penyakit`
--
ALTER TABLE `tbl_penyakit`
  ADD PRIMARY KEY (`id_penyakit`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_aturan`
--
ALTER TABLE `tbl_aturan`
  MODIFY `id_aturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_diagnosa`
--
ALTER TABLE `tbl_diagnosa`
  MODIFY `id_diagnosa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_gejala`
--
ALTER TABLE `tbl_gejala`
  MODIFY `id_gejala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_hasil`
--
ALTER TABLE `tbl_hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_pasien`
--
ALTER TABLE `tbl_pasien`
  MODIFY `id_pasien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_penyakit`
--
ALTER TABLE `tbl_penyakit`
  MODIFY `id_penyakit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
