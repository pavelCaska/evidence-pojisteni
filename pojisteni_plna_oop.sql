-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2023 at 03:52 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pojisteni_plna_oop`
--

-- --------------------------------------------------------

--
-- Table structure for table `hlaseni`
--

CREATE TABLE `hlaseni` (
  `hlaseni_id` int(11) NOT NULL,
  `nazev` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `datum_vzniku` date NOT NULL,
  `datum_nahlaseni` date NOT NULL,
  `vyse_skody` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `zpracovano` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `hlaseni`
--

INSERT INTO `hlaseni` (`hlaseni_id`, `nazev`, `datum_vzniku`, `datum_nahlaseni`, `vyse_skody`, `produkt_id`, `zpracovano`) VALUES
(1, 'krádež-loupež', '2023-02-07', '2023-02-07', 150000, 37, 1),
(10, 'krádež-loupež', '2023-02-02', '2023-02-07', 35000, 2, 1),
(11, 'pád stromu', '2023-02-06', '2023-02-07', 45789, 2, 1),
(12, 'nehoda při couvání', '2023-02-06', '2023-02-07', 25000, 2, 1),
(14, 'požár v kuchyni', '2023-01-01', '2023-02-10', 250000, 4, 1),
(15, 'živel', '2023-02-08', '2023-02-10', 149000, 2, 1),
(16, 'kroupy', '2023-02-03', '2023-02-10', 111200, 6, 1),
(17, 'nehoda na ledovce', '2023-02-01', '2023-02-14', 121000, 2, 1),
(19, 'krupobití', '2023-01-30', '2023-02-21', 51999, 4, 0),
(20, 'požár', '2023-02-15', '2023-02-21', 150000, 2, 0),
(21, 'poškození vrat', '2023-02-01', '2023-03-01', 45000, 6, 0),
(22, 'požár v ložnici', '2023-02-03', '2023-03-02', 45000, 4, 0),
(23, 'nehoda při parkování', '2023-03-15', '2023-03-28', 50000, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `plneni`
--

CREATE TABLE `plneni` (
  `plneni_id` int(11) NOT NULL,
  `vyse_plneni` int(11) DEFAULT NULL,
  `spoluucast` int(11) NOT NULL,
  `datum_schvaleni` date DEFAULT NULL,
  `datum_vyplaty` date DEFAULT NULL,
  `udalost_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `plneni`
--

INSERT INTO `plneni` (`plneni_id`, `vyse_plneni`, `spoluucast`, `datum_schvaleni`, `datum_vyplaty`, `udalost_id`) VALUES
(29, 176000, 25000, '2023-02-10', NULL, 31),
(30, 20000, 5001, '2023-02-16', '2023-02-17', 32),
(38, 0, 0, NULL, NULL, 40),
(41, 0, 25000, NULL, NULL, 43),
(44, 0, 5000, NULL, NULL, 46),
(46, 0, 0, NULL, NULL, 48),
(47, 0, 0, NULL, NULL, 49),
(48, 0, 10000, NULL, NULL, 50),
(49, 0, 5000, NULL, NULL, 51),
(50, 0, 5001, NULL, NULL, 52),
(51, 0, 25000, NULL, NULL, 53),
(53, 0, 10000, NULL, NULL, 55),
(54, 0, 0, NULL, NULL, 56),
(59, 45000, 5000, '2023-02-11', NULL, 62),
(61, 150000, 25000, '2023-02-10', NULL, 64),
(62, 0, 5000, NULL, NULL, 65),
(64, 900000, 100000, '2023-03-30', NULL, 67);

-- --------------------------------------------------------

--
-- Table structure for table `pojistenci`
--

CREATE TABLE `pojistenci` (
  `pojistenci_id` int(11) NOT NULL,
  `jmeno` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `telefon` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `ulice_cp` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `mesto` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `psc` varchar(6) COLLATE utf8_czech_ci NOT NULL,
  `registr_kod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `pojistenci`
--

INSERT INTO `pojistenci` (`pojistenci_id`, `jmeno`, `prijmeni`, `email`, `telefon`, `ulice_cp`, `mesto`, `psc`, `registr_kod`) VALUES
(10, 'Josef', 'Nový', 'jnovy@atlas.com', '725 458 111', 'Hlavní 145/9', 'Praha 9', '999 91', 726722633),
(52, 'Jan', 'Novák', 'jannovak88@seznam.cz', '731 584 972', 'Ve Svahu 3', 'Praha 1', '100 02', 469543251),
(82, 'Petra', 'Mazancová', 'pmova@email.com', '789 789 789', 'Na konci světa 1', 'Ostrava Hrabůvka', '560 00', 819837057),
(84, 'Petr', 'Mazanec', 'pm@email.com', '789 789 789', 'Pražská 123', 'Ostrava', '450 00', 269157125),
(92, 'Filipa', 'Citrón', 'citron@zelenina.cz', '123 543 322', 'Tržiště 191', 'Brno', '345 00', 681385681),
(94, 'Petr', 'Bezruč', 'kopac@ostravak.info', '731 456 316', 'Důlní 991', 'Ostrava - Hrabůvka', '400 04', 356830949),
(102, 'Pepa', 'Bican', 'bican@fotbal.cz', '605 111 222', 'U stadionu 13a', 'Brno-Hrabůvka', '300 02', 417616745),
(117, 'Pepek', 'Námořník', 'parnik@ocean.com', '735 735 736', 'Přístavní 98A', 'Děčín', '245 56', 751266585),
(119, 'Petr', 'Pavel', 'pp@pp.info', '603 456 789', 'Jelení příkop 234a', 'Praha 2', '120 22', 454678199);

-- --------------------------------------------------------

--
-- Table structure for table `pojisteni`
--

CREATE TABLE `pojisteni` (
  `pojisteni_id` int(11) NOT NULL,
  `poj_produkt` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `poj_castka` int(11) NOT NULL,
  `predmet_poj` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `platnost_od` date NOT NULL,
  `platnost_do` date NOT NULL,
  `pojistenec_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `pojisteni`
--

INSERT INTO `pojisteni` (`pojisteni_id`, `poj_produkt`, `poj_castka`, `predmet_poj`, `platnost_od`, `platnost_do`, `pojistenec_id`) VALUES
(2, 'Havarijní pojištění', 500000, 'Automobil Golf', '2022-01-01', '2022-12-31', 10),
(3, 'Pojištění zákonné odpovědnosti z provozu motorových vozidel', 50000001, 'Automobil', '2023-01-01', '2023-12-31', 10),
(4, 'Pojištění nemovitosti', 1000000, 'Byt', '2023-01-01', '2023-12-31', 10),
(6, 'Pojištění nemovitosti', 111111, 'Garáž', '2021-01-01', '2023-05-30', 10),
(10, 'Havarijní pojištění', 444444, 'Domácnost', '2020-05-05', '2025-05-04', 52),
(32, 'Havarijní pojištění', 999, 'Trakař', '2021-03-03', '2023-03-02', 82),
(34, 'Pojištění nemovitosti', 330000, 'Auto Škoda', '2022-02-01', '2023-01-31', 84),
(37, 'Pojištění domácnosti', 333444, 'Garsonka vybavení', '2023-02-01', '2025-01-31', 92),
(39, 'Pojištění nemovitosti', 5000500, 'Byt', '2022-01-01', '2025-12-31', 52),
(41, 'Pojištění nemovitosti', 555444, 'Chata', '2022-01-01', '2023-12-31', 92),
(42, 'Pojištění nemovitosti', 10111222, 'Dílna 1', '2022-01-01', '2023-12-31', 94),
(47, 'Pojištění nemovitosti', 12333444, 'Vila', '2022-11-30', '2024-11-29', 102),
(48, 'Pojištění domácnosti', 500000, 'Vybavení ve vile', '2022-11-30', '2024-11-29', 102),
(49, 'Pojištění zákonné odpovědnosti z provozu motorových vozidel', 50111222, 'Pickup', '2022-01-01', '2023-01-31', 94),
(50, 'Pojištění domácnosti', 150666, 'Malá domácnost', '2022-05-01', '2024-04-30', 94),
(51, 'Pojištění nemovitosti', 10222333, 'Přístavní dok', '2022-02-01', '2023-01-31', 117),
(52, 'Pojištění nemovitosti', 3444555, 'Plachetnice', '2022-05-01', '2024-04-30', 117),
(53, 'Pojištění zákonné odpovědnosti z provozu motorových vozidel', 55000000, 'Automobil', '2022-03-14', '2023-03-13', 84),
(56, 'Pojištění nemovitosti', 100111222, 'Hrad', '2023-03-01', '2028-03-01', 119);

-- --------------------------------------------------------

--
-- Table structure for table `udalosti`
--

CREATE TABLE `udalosti` (
  `udalosti_id` int(11) NOT NULL,
  `nazev` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `datum_vzniku` date NOT NULL,
  `datum_nahlaseni` date NOT NULL,
  `vyse_skody` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `udalosti`
--

INSERT INTO `udalosti` (`udalosti_id`, `nazev`, `datum_vzniku`, `datum_nahlaseni`, `vyse_skody`, `produkt_id`) VALUES
(31, 'požár v kuchyni', '2023-02-01', '2023-02-10', 250000, 4),
(32, 'nehoda při couvání', '2023-01-15', '2023-02-11', 35001, 10),
(40, 'nehoda', '2023-01-01', '2023-01-12', 10000, 10),
(43, 'vichřice', '2023-01-01', '2023-01-12', 250111, 4),
(46, 'krupobití', '2023-01-30', '2023-02-21', 51999, 4),
(48, 'živel', '2023-02-08', '2023-02-10', 149000, 2),
(49, 'živel', '2023-02-08', '2023-02-10', 149000, 2),
(50, 'kroupy', '2023-02-03', '2023-02-10', 115300, 6),
(51, 'nehoda při couvání', '2023-02-06', '2023-02-07', 25000, 2),
(52, 'krádež-loupež', '2023-02-02', '2023-02-07', 35000, 2),
(53, 'vichřice', '2022-03-22', '2022-03-30', 2111333, 47),
(55, 'pád stromu', '2023-02-06', '2023-02-07', 45789, 2),
(56, 'krádež', '2023-02-02', '2023-02-10', 999, 32),
(62, 'krádež-loupež', '2023-02-02', '2023-02-10', 56000, 48),
(64, 'požár v kuchyni', '2023-01-01', '2023-02-10', 250000, 4),
(65, 'nehoda na ledovce', '2023-02-01', '2023-02-14', 121000, 2),
(67, 'vichřice', '2023-03-20', '2023-03-25', 1000000, 56);

-- --------------------------------------------------------

--
-- Table structure for table `uzivatele`
--

CREATE TABLE `uzivatele` (
  `uzivatele_id` int(11) NOT NULL,
  `prezdivka` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `heslo` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `pojistenec_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Dumping data for table `uzivatele`
--

INSERT INTO `uzivatele` (`uzivatele_id`, `prezdivka`, `heslo`, `admin`, `pojistenec_id`) VALUES
(0, 'prezident', '$2y$10$nQG33WUJvzr81VMb.LWlzOkbrCgrnO2J4GHzwwDUVIgjlU.a5lYnO', 0, 119),
(4, 'josefnovy', '$2y$10$sZ1ODvFuA3.tB/UV64IqdeZQQKrQQCokE352eXTpqnG6/8JivqXs2', 0, 10),
(6, 'jannovak', '$2y$10$BEwJoXL5H0d24GbrPmXO9uICLvdv57zmsmKZ50tvLZKUWe/nnDDmy', 0, 52),
(7, 'adminnn', '$2y$10$zy5TH0YhF0XlEGybg5xhTuGtpBybkuWvqvzEFhsZ1.oGvJB5Gn3lG', 1, 0),
(8, 'cintronela', '$2y$10$wHuybi6q.ZgwiIf5xGX56ONUI3..5mlVqCNnDwsh47VlW.QvWwn0K', 0, 92);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hlaseni`
--
ALTER TABLE `hlaseni`
  ADD PRIMARY KEY (`hlaseni_id`),
  ADD KEY `pojisteniHlaseni` (`produkt_id`);

--
-- Indexes for table `plneni`
--
ALTER TABLE `plneni`
  ADD PRIMARY KEY (`plneni_id`),
  ADD KEY `plneniUdalost` (`udalost_id`);

--
-- Indexes for table `pojistenci`
--
ALTER TABLE `pojistenci`
  ADD PRIMARY KEY (`pojistenci_id`);

--
-- Indexes for table `pojisteni`
--
ALTER TABLE `pojisteni`
  ADD PRIMARY KEY (`pojisteni_id`);

--
-- Indexes for table `udalosti`
--
ALTER TABLE `udalosti`
  ADD PRIMARY KEY (`udalosti_id`),
  ADD KEY `pojisteniUdalost` (`produkt_id`);

--
-- Indexes for table `uzivatele`
--
ALTER TABLE `uzivatele`
  ADD PRIMARY KEY (`uzivatele_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hlaseni`
--
ALTER TABLE `hlaseni`
  MODIFY `hlaseni_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `plneni`
--
ALTER TABLE `plneni`
  MODIFY `plneni_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `pojistenci`
--
ALTER TABLE `pojistenci`
  MODIFY `pojistenci_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `pojisteni`
--
ALTER TABLE `pojisteni`
  MODIFY `pojisteni_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `udalosti`
--
ALTER TABLE `udalosti`
  MODIFY `udalosti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hlaseni`
--
ALTER TABLE `hlaseni`
  ADD CONSTRAINT `pojisteniHlaseni` FOREIGN KEY (`produkt_id`) REFERENCES `pojisteni` (`pojisteni_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `plneni`
--
ALTER TABLE `plneni`
  ADD CONSTRAINT `plneniUdalost` FOREIGN KEY (`udalost_id`) REFERENCES `udalosti` (`udalosti_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `udalosti`
--
ALTER TABLE `udalosti`
  ADD CONSTRAINT `pojisteniUdalost` FOREIGN KEY (`produkt_id`) REFERENCES `pojisteni` (`pojisteni_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
