-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Okt 2024 pada 13.34
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
-- Database: `db_tekno`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'sigma', 'technokel4');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `harga`, `foto`) VALUES
(1, 'Pisang Oyeng', 10000, 'https://o-cdn-cas.oramiland.com/parenting/images/makanan-indonesia-yang-mendunia.width-800.format-webp.webp'),
(2, 'Rica Rica Mengkudu', 10000, 'https://cdn.antaranews.com/cache/1200x800/2022/08/03/ayam-geprek.png'),
(4, 'Es Mangga Rifky', 10000, 'https://awsimages.detik.net.id/content/2015/04/08/1208/oli.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nota`
--

CREATE TABLE `nota` (
  `id_nota` int(11) NOT NULL,
  `id_pembelian` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `total_harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nota`
--

INSERT INTO `nota` (`id_nota`, `id_pembelian`, `id_admin`, `tanggal`, `jam`, `nama_menu`, `total_harga`) VALUES
(69, 19, 1, '2024-10-23', '18:00:22', 'Pisang Oyeng', 10000),
(70, 19, 1, '2024-10-23', '18:00:22', 'Rica Rica Mengkudu', 10000),
(71, 20, 1, '2024-10-23', '18:00:35', 'Rica Rica Mengkudu', 10000),
(72, 20, 1, '2024-10-23', '18:00:35', 'Es Mangga Rifky', 10000),
(73, 22, 1, '2024-10-23', '18:11:05', 'Pisang Oyeng', 10000),
(74, 22, 1, '2024-10-23', '18:11:05', 'Rica Rica Mengkudu', 10000),
(75, 22, 1, '2024-10-23', '18:11:05', 'Soto Sumanto', 10000),
(76, 22, 1, '2024-10-23', '18:11:05', 'Es Mangga Rifky', 10000),
(77, 26, 1, '2024-10-23', '18:17:28', 'Pisang Oyeng', 30000),
(78, 26, 1, '2024-10-23', '18:17:28', 'Rica Rica Mengkudu', 50000),
(79, 26, 1, '2024-10-23', '18:17:28', 'Soto Sumanto', 50000),
(80, 26, 1, '2024-10-23', '18:17:28', 'Es Mangga Rifky', 50000),
(81, 23, 1, '2024-10-23', '18:18:02', 'Pisang Oyeng', 10000),
(82, 23, 1, '2024-10-23', '18:18:02', 'Rica Rica Mengkudu', 10000),
(83, 23, 1, '2024-10-23', '18:18:02', 'Soto Sumanto', 10000),
(84, 23, 1, '2024-10-23', '18:18:02', 'Es Mangga Rifky', 10000),
(85, 24, 1, '2024-10-23', '18:20:04', 'Pisang Oyeng', 10000),
(86, 24, 1, '2024-10-23', '18:20:04', 'Rica Rica Mengkudu', 20000),
(87, 24, 1, '2024-10-23', '18:20:04', 'Soto Sumanto', 20000),
(88, 24, 1, '2024-10-23', '18:20:04', 'Es Mangga Rifky', 10000),
(89, 25, 1, '2024-10-23', '18:23:25', 'Pisang Oyeng', 10000),
(90, 25, 1, '2024-10-23', '18:23:25', 'Rica Rica Mengkudu', 20000),
(91, 25, 1, '2024-10-23', '18:23:25', 'Soto Sumanto', 20000),
(92, 25, 1, '2024-10-23', '18:23:25', 'Es Mangga Rifky', 20000),
(93, 27, 1, '2024-10-23', '18:28:26', 'Pisang Oyeng', 10000),
(94, 27, 1, '2024-10-23', '18:28:26', 'Rica Rica Mengkudu', 10000),
(95, 27, 1, '2024-10-23', '18:28:26', 'Es Mangga Rifky', 10000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `tanggal_pembelian` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `tanggal_pembelian`) VALUES
(8, '2024-10-23 17:03:15'),
(9, '2024-10-23 17:03:27'),
(10, '2024-10-23 17:06:12'),
(11, '2024-10-23 17:20:30'),
(12, '2024-10-23 17:22:55'),
(13, '2024-10-23 17:28:56'),
(14, '2024-10-23 17:29:04'),
(15, '2024-10-23 17:40:43'),
(16, '2024-10-23 17:50:26'),
(17, '2024-10-23 17:54:55'),
(18, '2024-10-23 17:55:52'),
(19, '2024-10-23 17:59:19'),
(20, '2024-10-23 18:00:15'),
(21, '2024-10-23 18:06:49'),
(22, '2024-10-23 18:07:48'),
(23, '2024-10-23 18:08:01'),
(24, '2024-10-23 18:13:08'),
(25, '2024-10-23 18:13:48'),
(26, '2024-10-23 18:17:03'),
(27, '2024-10-23 18:23:20'),
(28, '2024-10-23 18:25:12'),
(29, '2024-10-23 18:28:02'),
(30, '2024-10-23 18:28:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `id_pembelian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_menu`, `nama_menu`, `jumlah`, `total_harga`, `id_pembelian`) VALUES
(136, 1, 'Pisang Oyeng', 1, 10000, 28),
(137, 2, 'Rica Rica Mengkudu', 1, 10000, 28),
(138, 4, 'Es Mangga Rifky', 1, 10000, 28),
(139, 1, 'Pisang Oyeng', 1, 10000, 29),
(140, 2, 'Rica Rica Mengkudu', 1, 10000, 29),
(141, 4, 'Es Mangga Rifky', 1, 10000, 29),
(142, 1, 'Pisang Oyeng', 3, 30000, 30),
(143, 2, 'Rica Rica Mengkudu', 4, 40000, 30),
(144, 4, 'Es Mangga Rifky', 3, 30000, 30);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id_nota`),
  ADD KEY `fk_admin_nota` (`id_admin`),
  ADD KEY `fk_pembelian_nota` (`id_pembelian`);

--
-- Indeks untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `fk_menu_pesanan` (`id_menu`),
  ADD KEY `fk_pembelian_pesanan` (`id_pembelian`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `nota`
--
ALTER TABLE `nota`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT untuk tabel `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `nota`
--
ALTER TABLE `nota`
  ADD CONSTRAINT `fk_admin_nota` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembelian_nota` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `fk_menu_pesanan` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembelian_pesanan` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
