-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 01, 2026 at 06:41 AM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u390694310_cscro8`
--

-- --------------------------------------------------------

--
-- Table structure for table `itg_tbl`
--

CREATE TABLE `itg_tbl` (
  `itg_id` int(12) NOT NULL,
  `id` int(11) NOT NULL,
  `itg_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `itg_tbl`
--

INSERT INTO `itg_tbl` (`itg_id`, `id`, `itg_role`) VALUES
(1, 23, 'ITG Head'),
(2, 33, 'Co Chair'),
(3, 14, 'Member'),
(4, 45, 'Member'),
(5, 9, 'Member'),
(6, 13, 'Member'),
(7, 10, 'Member'),
(8, 47, 'Director IV'),
(9, 2, 'Director III'),
(10, 62, 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `itg_tbl`
--
ALTER TABLE `itg_tbl`
  ADD PRIMARY KEY (`itg_id`),
  ADD KEY `fk_itg_user` (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `itg_tbl`
--
ALTER TABLE `itg_tbl`
  ADD CONSTRAINT `fk_itg_user` FOREIGN KEY (`id`) REFERENCES `users_cscro8` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
