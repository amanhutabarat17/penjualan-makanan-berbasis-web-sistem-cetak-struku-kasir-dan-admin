-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jul 2024 pada 07.16
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
-- Database: `penjualanbaru`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `idpelanggan` int(11) NOT NULL,
  `namapelanggan` varchar(30) NOT NULL,
  `totalharga` int(15) NOT NULL,
  `jumlahbayar` int(20) NOT NULL,
  `kembalian` int(20) NOT NULL,
  `tanggal` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`idpelanggan`, `namapelanggan`, `totalharga`, `jumlahbayar`, `kembalian`, `tanggal`) VALUES
(20, 'yai', 15000, 20000, 5000, '02 Jul 2024'),
(21, 'Willy', 10000, 15000, 5000, '02 Jul 2024'),
(22, 'aman', 10000, 15000, 5000, '04 Jul 2024'),
(23, 'aman', 20000, 20000, 0, '04 Jul 2024'),
(24, 'willy simatupang', 35000, 40000, 5000, '05 Jul 2024');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `idpenjualan` int(11) NOT NULL,
  `idBarang` int(20) NOT NULL,
  `id` int(20) NOT NULL,
  `idpelanggan` int(15) NOT NULL,
  `jumlahBarang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`idpenjualan`, `idBarang`, `id`, `idpelanggan`, `jumlahBarang`) VALUES
(17, 3, 4, 20, 1),
(18, 8, 4, 21, 2),
(19, 21, 2, 22, 1),
(20, 8, 2, 22, 1),
(21, 8, 2, 23, 1),
(22, 7, 2, 23, 1),
(23, 8, 2, 24, 1),
(24, 7, 2, 24, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `idBarang` int(11) NOT NULL,
  `namaBarang` varchar(30) NOT NULL,
  `harga` int(15) NOT NULL,
  `deskripsi` text NOT NULL,
  `stok` int(5) NOT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`idBarang`, `namaBarang`, `harga`, `deskripsi`, `stok`, `foto`) VALUES
(3, 'Ayam Panggang', 15000, 'Nasi + Ayam Panggang ', 5, '66833847796f3.jpg'),
(6, 'Ayam Geprek', 13000, 'Nasi + Ayam Geprek', 2, '6683387bee425.jpg'),
(7, 'Rendang Ayam', 15000, 'Rendang Ayam + Nasi', 3, '668338b754798.jpg'),
(8, 'Mandi', 5000, 'Teh Manis Dingin', 15, '668338e6e01e4.jpg'),
(11, 'Jus Jeruk', 6000, 'Jus Jeruk ', 8, '66833925d77aa.jpg'),
(15, 'Jus Alvukat', 7000, 'Jus Alvukat', 20, '6683399f68490.jpg'),
(16, 'Nasi Goreng', 12000, 'Nasi Goreng + Minuman Biasa', 39, '66833b41ec6ce.jpg'),
(17, 'Bandrek', 10000, 'Bandrek jahe rasa sirsak', 10, '6683556f16a3f.jpg'),
(18, 'Sate', 15000, 'Sate Ayam ', 9, '668355969213e.jpg'),
(19, 'siomai', 15000, 'siomai enak', 9, '668355c465c23.jpg'),
(20, 'Nuget', 20000, 'Nuget ayam', 12, '6683562a18fbe.jpg'),
(21, 'Nutri sari', 5000, 'Jeruk Peras', 7, '6683566a66259.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir') NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama`, `email`) VALUES
(1, 'admin', 'admin123', 'admin', '', ''),
(2, 'aman', '123', 'kasir', 'Aman Hutabarat', 'hutabarataman21@gmail.com'),
(4, 'man', 'man', 'kasir', 'man', 'man');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`idpelanggan`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`idpenjualan`),
  ADD KEY `id` (`id`),
  ADD KEY `idBarang` (`idBarang`),
  ADD KEY `idpelanggan` (`idpelanggan`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`idBarang`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `idpelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `idpenjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `idBarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `penjualan_ibfk_2` FOREIGN KEY (`idBarang`) REFERENCES `produk` (`idBarang`),
  ADD CONSTRAINT `penjualan_ibfk_3` FOREIGN KEY (`idpelanggan`) REFERENCES `pelanggan` (`idpelanggan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
