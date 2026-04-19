-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Apr 2026 pada 09.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ventura`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `harga_sewa` decimal(10,2) NOT NULL DEFAULT 0.00,
  `kondisi` varchar(50) NOT NULL,
  `foto_barang` text DEFAULT 'tenda.jpg',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kategori`, `stok`, `harga_sewa`, `kondisi`, `foto_barang`, `created_at`, `updated_at`) VALUES
(1, 'Tenda Dome Kapasitas 4', 'Tenda', 4, 50000.00, 'Baik', '1775965237_cf467619023f4dee7179.png,1775965251_0d4f2f1eca7d66c7210d.png', '2026-04-12 01:48:03', '2026-04-16 04:55:20'),
(2, 'Tracking pool', 'Tracking Pool', 9, 20000.00, 'Baik', '1775965180_2e90ae4b7d87a67e5bf2.png,1775965180_6ddd6344f4fe64209fff.png', '2026-04-12 03:39:40', '2026-04-17 02:28:34'),
(3, 'Sepatu Lavio', 'Sepatu', 20, 30000.00, 'Baik', '1775965375_70e66a5530020ab8508f.png,1775965388_d6034488336a4aa52373.png', '2026-04-12 03:42:38', '2026-04-17 02:31:19'),
(4, 'Sandal Reward', 'Sandal', 9, 20000.00, 'Baik', '1775965472_ba926e6f5c2fe4e16ea1.png,1775965472_ba7da16fd15565f542fd.png', '2026-04-12 03:44:32', '2026-04-12 04:47:31'),
(5, 'Sandal TapiLaku', 'Sandal', 23, 20000.00, 'Baik', '1775965521_d71d4e165bf01788919e.png,1775965543_39353c8cf2283053b51f.png', '2026-04-12 03:45:21', '2026-04-12 03:45:43'),
(6, 'Kompor Portable Hi-Cook', 'Kompor', 38, 15000.00, 'Baik', '1775965624_727f125b8a627635b317.png,1775965644_a29b35ad4b6dabc92c2f.png,1775965644_237c804605b7929ab8ec.png', '2026-04-12 03:47:04', '2026-04-12 03:47:24'),
(7, 'Tenda Dome Arpenaz 4P', 'Tenda', 49, 50000.00, 'Baik', '1775965693_c85c2cd3667fc10261c2.png,1775965711_353419dfd56893bed6f5.png,1775965711_30adc7954e1c73f7fca3.png', '2026-04-12 03:48:13', '2026-04-16 06:03:50'),
(8, 'sepatu salomon', 'Sepatu', 32, 50000.00, 'Baik', '1775965763_4eefeacaece93f529162.png', '2026-04-12 03:49:23', '2026-04-12 03:49:23'),
(10, 'jaket', 'Jaket', 12, 12000.00, 'Baik', '1776406697_ce2949f8ef0d9d7de47d.png,1776406708_b768a96506a45bc366bb.png', '2026-04-17 02:33:22', '2026-04-17 06:18:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `total_harga` decimal(12,2) DEFAULT 0.00,
  `denda` decimal(12,2) DEFAULT 0.00,
  `status_transaksi` enum('Waiting','Booking','Dipinjam','Selesai','Batal') DEFAULT 'Booking',
  `is_read` tinyint(1) DEFAULT 0,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_barang`, `tgl_pinjam`, `tgl_kembali`, `total_harga`, `denda`, `status_transaksi`, `is_read`, `bukti_bayar`, `created_at`, `updated_at`) VALUES
(6, 2, 1, '2026-04-14', '2026-04-17', 150000.00, 0.00, 'Selesai', 1, '1775970733_f5d23fc3b3b08e138e6d.png', '2026-04-12 05:12:13', '2026-04-13 03:38:10'),
(9, 2, 1, '2026-04-13', '2026-04-16', 150000.00, 0.00, 'Selesai', 1, '1776051772_a9be580878a23e9b165a.png', '2026-04-13 03:42:52', '2026-04-13 03:50:46'),
(10, 3, 1, '2026-05-11', '2026-05-16', 250000.00, 0.00, 'Selesai', 1, '1776052523_d556ff1ec00a81b32bab.png', '2026-04-13 03:55:23', '2026-04-13 04:41:10'),
(12, 2, 2, '2026-04-15', '2026-04-18', 60000.00, 0.00, '', 1, '1776233934_b512ad35fffe4a56fa93.png', '2026-04-15 06:18:54', '2026-04-16 02:49:51'),
(13, 2, 3, '2026-04-16', '2026-04-18', 60000.00, 0.00, 'Selesai', 1, '1776308257_46148d54d33f28efc15b.png', '2026-04-16 02:57:37', '2026-04-16 06:11:02'),
(14, 2, 1, '2026-04-16', '2026-04-18', 100000.00, 0.00, '', 1, '1776315277_229bd3d6dfdf05eccf13.png', '2026-04-16 04:54:37', '2026-04-16 04:55:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `foto` varchar(255) DEFAULT 'default.png',
  `no_wa` varchar(20) DEFAULT NULL,
  `ktp` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`, `foto`, `no_wa`, `ktp`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'fahmi', '$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S', 'admin', '1775970874_f8503dff7a155ca040c8.jpg', '08123456789', '1775970837_d3fddb7ccf7a26753806.png', '2026-04-12 01:42:37', '2026-04-15 13:28:17'),
(2, 'raafif', 'raafif', '$2y$10$v7IrPPncgswQyOkiTTHy6.I2acTdsOx/kBoEoF3JhZmgbEt5GgenK', 'user', '1776315231_f9df6384fd9f006afbb7.png', '122', '1776315231_2bc39a41324f57c293de.png', '2026-04-12 01:55:09', '2026-04-16 11:53:51'),
(3, 'ulhaqq', 'ulhaqq', '$2y$10$ebjKlSgWoSdf2SYNsRCck.R/57Pbkfiz3lTJfz4bHBQCHFhKlvUlm', '', '1776052312_60339fac82c909e4a339.png', '08123456789', '1776052312_378c1f88c234f3735521.png', '2026-04-13 10:51:52', '2026-04-13 11:46:35'),
(4, 'ijal', 'ijal', '$2y$10$KsN9DPbbPVFjpsfS25DY6.d4pwu/gqrtGuQ9ZYP0bX90Ej.HqxEfa', 'user', '1776395793_48d636d2b42267cb5580.png', '4044848494', '1776235329_273b02958c9c3611a9c2.png', '2026-04-15 13:42:09', '2026-04-17 10:16:33');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_user` (`id_user`),
  ADD KEY `fk_barang` (`id_barang`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
