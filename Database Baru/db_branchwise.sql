-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Jun 2025 pada 12.18
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
-- Database: `db_branchwise`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `criteria`
--

CREATE TABLE `criteria` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `criteria_code` varchar(10) NOT NULL,
  `criteria_name` varchar(255) NOT NULL,
  `weight_percentage` int(3) NOT NULL,
  `type` enum('benefit','cost') NOT NULL,
  `value_unit` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `criteria`
--

INSERT INTO `criteria` (`id`, `project_id`, `criteria_code`, `criteria_name`, `weight_percentage`, `type`, `value_unit`, `created_at`, `updated_at`) VALUES
(1, 1, 'C1', 'TAK TAU', 25, 'benefit', '0-10', '2025-06-04 14:06:08', '2025-06-04 14:06:08'),
(2, 1, 'C2', 'ENTAH', 30, 'cost', '0-10', '2025-06-04 14:06:28', '2025-06-04 14:06:28'),
(3, 1, 'C3', 'DON NOW', 45, 'benefit', '0-10', '2025-06-04 14:07:18', '2025-06-04 14:07:18'),
(4, 2, 'C1', 'TAK TAU', 20, 'benefit', '0-10', '2025-06-04 16:14:34', '2025-06-04 16:14:34'),
(5, 2, 'C2', 'ENTAH', 10, 'cost', 'juta jiwa', '2025-06-04 16:15:03', '2025-06-04 16:15:03'),
(6, 2, 'C3', 'DON NOW', 70, 'benefit', '0-10', '2025-06-04 16:15:48', '2025-06-04 16:15:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `size_sqm` decimal(10,2) DEFAULT NULL,
  `status` enum('aktif','nonaktif','renovasi') NOT NULL,
  `gmaps_link` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `locations`
--

INSERT INTO `locations` (`id`, `project_id`, `branch_name`, `address`, `city`, `phone`, `email`, `size_sqm`, `status`, `gmaps_link`, `notes`, `created_at`, `updated_at`) VALUES
(4, 1, 'Rejowinangun', 'Magelang Tengah, Magelang City, Central Java', 'Kota Magelang', '6281228693783', 'darbanmaha@gmail.com', 100.00, 'aktif', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1977.9106164607044!2d110.22141947817425!3d-7.484982562435522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a8f454e7ff8f7%3A0xf60d08b60e1159ab!2sJl.%20Mataram%2C%20Kec.%20Magelang%20Tengah%2C%20Kota%20Magelang%2C%20Jawa%20Tengah!5e0!3m2!1sen!2sid!4v1749015925608!5m2!1sen!2sid', 'tidak ada', '2025-06-04 13:21:22', '2025-06-04 13:21:22'),
(5, 1, 'Panca Arga', 'Banyurojo, Mertoyudan, Magelang Regency, Central Java', 'Kota Magelang', '12345678', '22106050083@student.uin-suka.ac.id', 130.00, 'aktif', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.613088203598!2d110.21200717357533!3d-7.507893474064607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a8f30021f99ab%3A0xcfce394b1b523247!2sJl.%20Panca%20Arga%2C%20Banyurojo%2C%20Kec.%20Mertoyudan%2C%20Kabupaten%20Magelang%2C%20Jawa%20Tengah!5e0!3m2!1sen!2sid!4v1749018109756!5m2!1sen!2sid', 'lagi lagi tidak ada', '2025-06-04 13:22:45', '2025-06-04 13:22:45'),
(6, 1, 'RST Soedjono', 'Potrobangsan, Magelang Utara, Magelang City, Central Java 56116', 'Kota Magelang', '222222222', 'yusrina@mail.com', 110.00, 'aktif', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.9878349518!2d110.2190592735747!3d-7.466593973601004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a85f0510ed5fd%3A0xed7a0a87c49d4c8c!2sJl.%20RST%20Soedjono%2C%20Potrobangsan%2C%20Kec.%20Magelang%20Utara%2C%20Kota%20Magelang%2C%20Jawa%20Tengah%2056116!5e0!3m2!1sen!2sid!4v1749018264149!5m2!1sen!2sid', 'tentu saja tidak ada', '2025-06-04 13:25:24', '2025-06-04 13:25:24'),
(7, 2, 'Jalan Dr Sutomo', 'Blora, Blora Regency, Central Java', 'Blora', '1111111111', 'fakhri@mail.com', 120.00, 'aktif', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.3590758687474!2d111.41541567356617!3d-6.966898068212412!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7740fce55f93f9%3A0xc1504cb3fa4cec7!2sJl.%20Dr.%20Sutomo%2C%20Kec.%20Blora%2C%20Kabupaten%20Blora%2C%20Jawa%20Tengah!5e0!3m2!1sen!2sid!4v1749027837163!5m2!1sen!2sid', 'Jalan dekat dengan SMP di Blora', '2025-06-04 16:13:12', '2025-06-04 16:13:12'),
(8, 2, 'aaaaaa', 'aaaaaa', 'aaaaaa', '11111', 'darban@mail.com', 110.00, 'aktif', '', 'ddddddd', '2025-06-04 16:13:35', '2025-06-04 16:13:35'),
(9, 2, 'ddddd', 'cccc', 'hhhhh', '33333333', 'yusrina@mail.com', 100.00, 'aktif', '', 'shrrhrthrthrt', '2025-06-04 16:13:56', '2025-06-04 16:13:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `matrix_data`
--

CREATE TABLE `matrix_data` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `matrix_data`
--

INSERT INTO `matrix_data` (`id`, `project_id`, `location_id`, `criteria_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 2.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(2, 1, 5, 2, 4.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(3, 1, 5, 3, 9.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(4, 1, 4, 1, 6.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(5, 1, 4, 2, 6.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(6, 1, 4, 3, 7.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(7, 1, 6, 1, 7.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(8, 1, 6, 2, 9.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(9, 1, 6, 3, 3.00, '2025-06-04 15:11:43', '2025-06-04 15:11:43'),
(19, 2, 8, 4, 7.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32'),
(20, 2, 8, 5, 10.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32'),
(21, 2, 8, 6, 5.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32'),
(22, 2, 9, 4, 4.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32'),
(23, 2, 9, 5, 8.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32'),
(24, 2, 9, 6, 8.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32'),
(25, 2, 7, 4, 8.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32'),
(26, 2, 7, 5, 6.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32'),
(27, 2, 7, 6, 6.00, '2025-06-04 16:16:32', '2025-06-04 16:16:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('tinggi','sedang','rendah') NOT NULL,
  `status` enum('belum dimulai','dalam pengerjaan','selesai') NOT NULL,
  `deadline` date DEFAULT NULL,
  `assignee` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `project_name`, `description`, `priority`, `status`, `deadline`, `assignee`, `created_at`, `updated_at`) VALUES
(1, 2, 'Proyek coba coba', 'deskripsi coba coba', 'rendah', 'selesai', '2025-06-30', 'aku lah', '2025-06-04 12:43:54', '2025-06-04 12:43:54'),
(2, 2, 'Cabang baru Blondo', 'cabang baru di blondo untuk memperluan sebagai cabang kedua', 'sedang', 'belum dimulai', '2025-07-30', 'Dian', '2025-06-04 16:00:34', '2025-06-04 16:00:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin BranchWise', 'admin', 'admin123', 'admin', 'active', '2025-06-04 09:36:20', '2025-06-04 09:36:20'),
(2, 'Darban Maha Mursyidi', 'darban_mm', 'darban123', 'user', 'active', '2025-06-04 12:32:28', '2025-06-04 15:52:58'),
(3, 'DIAN NOVENDRIA MUTIARA SYAHARANI', 'dian', 'dian1234', 'admin', 'active', '2025-06-04 15:58:57', '2025-06-04 15:58:57');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`,`criteria_code`);

--
-- Indeks untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indeks untuk tabel `matrix_data`
--
ALTER TABLE `matrix_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id` (`project_id`,`location_id`,`criteria_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `criteria_id` (`criteria_id`);

--
-- Indeks untuk tabel `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT untuk tabel `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `matrix_data`
--
ALTER TABLE `matrix_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `criteria`
--
ALTER TABLE `criteria`
  ADD CONSTRAINT `criteria_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `matrix_data`
--
ALTER TABLE `matrix_data`
  ADD CONSTRAINT `matrix_data_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matrix_data_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matrix_data_ibfk_3` FOREIGN KEY (`criteria_id`) REFERENCES `criteria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
