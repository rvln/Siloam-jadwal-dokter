-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 10:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siloam`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `poli` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `poli`) VALUES
(23, 'dr. Iddo Posangi, SpAn', 'Poli Anestesi'),
(24, 'dr. Mordekhai Leopold Laihad, SpAn-KIC', 'Poli Anestesi'),
(25, 'dr. Hubert Ignatius Tatara, SpA', 'Poli Anak'),
(26, 'dr. Joy Christy Carol Lengkey, SpA', 'Poli Anak'),
(27, 'dr. Eunike Lay, SpTHT-BKL', 'Poli THT'),
(28, 'dr. Ivan Caesar Rumengan, SpPD', 'Poli Penyakit Dalam'),
(29, 'Dr. dr. Corry Novita Mahama, Sp.N., Subsp. ENK(K)', 'Poli Saraf'),
(30, 'dr. Dwayne Giovano Pandaleke, SpDV', 'Poli Dermatologi dan Venereologi'),
(31, 'dr. Benny Mulyanto Setiadi, SpJP(K), FIHA', 'Poli Jantung dan Pembuluh Darah'),
(32, 'dr. Marshell Luntungan, SpJP (K)', 'Poli Jantung dan Pembuluh Darah'),
(34, 'dr. Ladylove Raytong Walakandou, SpA', 'Poli Anak'),
(35, 'dr. Harlinda Haroen, SpPD-KHOM', 'Poli Hermatologi-Onkologi Medik'),
(36, 'dr. Priscilia Kalitouw, SpB', 'Poli Bedah'),
(37, 'dr. Stivano Rizky Valentino Torry, SpU', 'Poli Urologi'),
(38, 'dr. Denny Saleh, SpB Subsp. Onk(K)', 'Poli Bedah Onkologi'),
(39, 'dr. Lidwina Sima Sengkey SpKFR, N.M.(K)', 'Poli Fisio Terapi'),
(40, 'dr. Christha Zenithy Tamburian, SpBTKV, Subsp JD(K)', 'Poli Bedah Toraks'),
(41, 'Dr. dr. Eko Prasetyo, SpBS (K)', 'Poli Bedah Saraf'),
(42, 'dr. Stefanus Gunawan, Spa(K)', 'Poli Anak'),
(43, 'Dr. dr. Johnny Lambert Rompis, SpA(K)', 'Poli Anak'),
(44, 'dr. Gloria Elisabet Rondonuwu, Sp.KFR, FIPM (USG), AIFO-K', 'Poli Fisio Terapi'),
(45, 'dr. Patricia Jeanette Kalangi, SpKFR, AIFO - K', 'Poli Fisio Terapi'),
(46, 'dr. Billy E.C.R. Salem, SpB-KBD', 'Poli Bedah Disgestive'),
(48, 'dr. Linda M. Mamengko, SpOG-KFER', 'Poli Kandungan'),
(49, 'dr. Novanita S Satolom, SpM (K)', 'Poli Mata'),
(50, 'dr. Timotius Jonfrits Sumampouw, SpJP, FIHA', 'Poli Jantung'),
(51, 'dr. Djony Edward Tjandra, SpB Sub BVE', 'Poli Bedah Vaskular'),
(52, 'dr. Wahyuddin Suleman, SpAn (K)', 'Poli Anestesi'),
(53, 'dr. Olivia Cicilia Walewangko, SpPD-KEMD', 'Poli Endokrim'),
(54, 'dr. Gilbert Tangkudung, SpN, Subsp. NIOO(K), FINS, FIN', 'Poli Saraf'),
(55, 'DR. dr. Maximillian Ch Oley, SpBS (K)', 'Poli Bedah Saraf'),
(56, 'dr. Theresia Runtuwene, SpN (K)', 'Poli Saraf'),
(57, 'dr. Jimmy Alfa Marthie Abraham Karouw, SpOG', 'Poli Kandungan'),
(58, 'dr. Marselus A. Merung, SpB (K) Onk', 'Poli Bedah Onkologi'),
(59, 'DR. dr. Harsali F Lampus, MHSM, SpBA(K)', 'Poli Bedah Anak'),
(62, 'Prof.Dr.dr. Max Jozef Mantik, SpA (K)', 'Poli Anak'),
(63, 'dr. Sesca Mokalu, SpKJ', 'Poli Jiwa'),
(64, 'dr. Diane Paparang, SpGK, AIFO - K', 'Poli Gizi'),
(65, 'dr. Michael Tendean, SpB-KBD', 'Poli Bedah Digestiv'),
(66, 'dr. Harold F. Tambajong, SpAn', 'Poli Anestesi'),
(67, 'dr. Christian Kawengian, SpPD', 'Poli Penyakit Dalam'),
(68, 'dr. Jonathan Gidion Montolalu, SpB', 'Poli Bedah'),
(69, 'dr. Caroline Monigoei, SpN', 'Poli Saraf'),
(70, 'dr. Tonny S. S. Rumbayan, SpOG', 'Poli Kandungan'),
(71, 'dr. Olivia Fransisca Moniaga, SpB, FINACS, SH, MM', 'Poli Bedah'),
(72, 'dr. Andre Karema, SpAn-KIC', 'Poli Anestesi'),
(73, 'dr. Vekky Sariowan, SpJP, FIHA', 'Poli Jantung'),
(74, 'DR. dr. Olivia Claudia Pingkan Pelealu SpTHT-KL(K)', 'Poli THT'),
(75, 'dr. Richard Sumangkut, SpB(K)V', 'Poli Bedah Vaskular'),
(76, 'dr. Angela Sarah Sumual, SpOG', 'Poli Kandungan'),
(77, 'dr. David Loing, SpB', 'Poli Bedah'),
(78, 'dr. Ari Astram, SpU', 'Poli Urologi'),
(79, 'dr. Samuel A.R. Malingkas, SpM (K)', 'Poli Mata'),
(80, 'dr. Vania Tryanni, SpPD', 'Poli Penyakit Dalam'),
(81, 'dr. David Waworuntu, SpA (K)', 'Poli Anak'),
(82, 'dr. Adrian Noldi Tangkilisan, SpBTKV', 'Poli Bedah Toraks'),
(83, 'dr. Haryanto Karmansyah Sunaryo, SpOT', 'Poli Orthopaedic'),
(85, 'dr. Merina Pingkan Kalvariana Matheos, SpDVE', 'Poli Dermatologi'),
(86, 'dr. Efata Bilvian Ivano Polii, SpPD-KPMK', 'Poli Penyakit Dalam'),
(87, 'dr. Paola C. Pandeiroth, SpKFR', 'Poli Fisioterapi'),
(88, 'dr. Rangga Bayu Valentino Rawung, SpOT (K)', 'Poli Orthopaedic'),
(89, 'dr. Albertus Djarot Noersasongko, SpOT', 'Poli Orthopaedic'),
(90, 'Dr. dr. Mendy Hatibie Oley, SpBP-RE (K)', 'Poli Bedah Plastik'),
(91, 'dr. Bismarck Joel Laihad, SpOG (K)', 'Poli Kandungan'),
(92, 'dr. Maya Esther Mewengkang, SpOG', 'Poli Kandungan'),
(93, 'Dr. dr. Ferra Olivia Mawu, M.Med., SpDV (Subsp.DKE)', 'Poli Dermatologi'),
(94, 'dr. Christofan Lantu, SpP(K)', 'Poli Paru'),
(95, 'dr. Emanuela Deapaskah Waleleng, SpPD', 'Poli Penyakit Dalam'),
(96, 'dr. Clief Christoforus Runtung, SpA', 'Poli Anak');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `dokter_id` int(11) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') DEFAULT NULL,
  `status` enum('Cuti','Aktif') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `dokter_id`, `jam_mulai`, `jam_selesai`, `hari`, `status`) VALUES
(24, 23, '11:00:00', '15:00:00', 'Senin', 'Aktif'),
(25, 23, '11:00:00', '15:00:00', 'Selasa', 'Aktif'),
(26, 23, '11:00:00', '15:00:00', 'Rabu', 'Aktif'),
(27, 23, '11:00:00', '15:00:00', 'Kamis', 'Aktif'),
(28, 23, '11:00:00', '15:00:00', 'Jumat', 'Aktif'),
(29, 23, '11:00:00', '13:00:00', 'Sabtu', 'Aktif'),
(30, 24, '11:00:00', '15:00:00', 'Senin', 'Aktif'),
(31, 24, '11:00:00', '15:00:00', 'Selasa', 'Aktif'),
(32, 24, '11:00:00', '15:00:00', 'Rabu', 'Aktif'),
(33, 24, '11:00:00', '15:00:00', 'Kamis', 'Aktif'),
(34, 24, '11:00:00', '15:00:00', 'Jumat', 'Aktif'),
(35, 24, '10:00:00', '13:00:00', 'Sabtu', 'Aktif'),
(36, 25, '11:00:00', '12:00:00', 'Senin', 'Aktif'),
(37, 25, '11:00:00', '12:00:00', 'Rabu', 'Aktif'),
(38, 26, '10:00:00', '11:00:00', 'Rabu', 'Aktif'),
(39, 27, '10:00:00', '11:00:00', 'Senin', 'Aktif'),
(40, 27, '10:00:00', '11:00:00', 'Rabu', 'Aktif'),
(41, 27, '09:00:00', '10:30:00', 'Jumat', 'Aktif'),
(42, 28, '09:00:00', '11:00:00', 'Senin', 'Aktif'),
(43, 28, '09:00:00', '11:00:00', 'Rabu', 'Aktif'),
(53, 29, '14:00:00', '16:00:00', 'Kamis', 'Aktif'),
(54, 29, '14:00:00', '16:00:00', 'Jumat', 'Aktif'),
(55, 30, '08:30:00', '09:30:00', 'Rabu', 'Aktif'),
(56, 30, '09:00:00', '10:00:00', 'Jumat', 'Aktif'),
(57, 31, '14:00:00', '16:00:00', 'Senin', 'Aktif'),
(58, 31, '14:00:00', '16:00:00', 'Selasa', 'Aktif'),
(59, 31, '14:00:00', '16:00:00', 'Rabu', 'Aktif'),
(60, 31, '14:00:00', '16:00:00', 'Kamis', 'Aktif'),
(61, 31, '14:30:00', '16:00:00', 'Jumat', 'Aktif'),
(62, 34, '10:00:00', '11:00:00', 'Senin', 'Aktif'),
(63, 34, '10:00:00', '11:00:00', 'Selasa', 'Aktif'),
(64, 34, '10:00:00', '11:00:00', 'Rabu', 'Aktif'),
(65, 34, '10:00:00', '11:00:00', 'Kamis', 'Aktif'),
(66, 34, '10:00:00', '11:00:00', 'Jumat', 'Aktif'),
(67, 35, '14:00:00', '16:00:00', 'Senin', 'Aktif'),
(68, 35, '14:00:00', '16:00:00', 'Rabu', 'Aktif'),
(69, 35, '14:00:00', '16:00:00', 'Jumat', 'Aktif'),
(70, 36, '07:30:00', '11:30:00', 'Jumat', 'Aktif'),
(71, 37, '12:00:00', '14:00:00', 'Senin', 'Aktif'),
(72, 37, '12:00:00', '14:00:00', 'Selasa', 'Aktif'),
(73, 37, '12:00:00', '14:00:00', 'Rabu', 'Aktif'),
(74, 37, '12:00:00', '14:00:00', 'Kamis', 'Aktif'),
(75, 37, '12:00:00', '14:00:00', 'Jumat', 'Aktif'),
(76, 38, '14:30:00', '15:30:00', 'Selasa', 'Aktif'),
(77, 38, '14:30:00', '15:30:00', 'Kamis', 'Aktif'),
(78, 39, '14:30:00', '15:30:00', 'Selasa', 'Aktif'),
(79, 39, '14:30:00', '15:30:00', 'Kamis', 'Aktif'),
(80, 40, '15:00:00', '16:00:00', 'Selasa', 'Aktif'),
(81, 40, '15:00:00', '16:00:00', 'Kamis', 'Aktif'),
(82, 41, '13:00:00', '14:00:00', 'Senin', 'Aktif'),
(83, 41, '15:00:00', '16:00:00', 'Selasa', 'Aktif'),
(84, 41, '13:00:00', '14:00:00', 'Kamis', 'Aktif'),
(85, 42, '15:00:00', '16:00:00', 'Selasa', 'Aktif'),
(86, 42, '15:00:00', '16:00:00', 'Kamis', 'Aktif'),
(87, 43, '12:00:00', '13:00:00', 'Rabu', 'Aktif'),
(88, 43, '12:00:00', '13:00:00', 'Jumat', 'Aktif'),
(89, 44, '14:30:00', '16:00:00', 'Senin', 'Aktif'),
(90, 44, '14:30:00', '16:00:00', 'Rabu', 'Aktif'),
(91, 44, '15:00:00', '16:00:00', 'Kamis', 'Aktif'),
(92, 45, '13:00:00', '14:00:00', 'Selasa', 'Aktif'),
(93, 45, '13:00:00', '14:00:00', 'Rabu', 'Aktif'),
(94, 45, '13:00:00', '14:00:00', 'Jumat', 'Aktif'),
(95, 46, '11:30:00', '13:30:00', 'Senin', 'Aktif'),
(96, 46, '11:30:00', '13:30:00', 'Rabu', 'Aktif'),
(97, 46, '14:30:00', '16:00:00', 'Jumat', 'Aktif'),
(98, 32, '12:00:00', '14:30:00', 'Senin', 'Aktif'),
(99, 32, '11:00:00', '14:00:00', 'Rabu', 'Aktif'),
(100, 32, '11:00:00', '14:00:00', 'Jumat', 'Aktif'),
(101, 48, '14:00:00', '15:00:00', 'Selasa', 'Aktif'),
(102, 48, '14:00:00', '15:00:00', 'Kamis', 'Aktif'),
(103, 49, '14:00:00', '15:00:00', 'Senin', 'Aktif'),
(104, 49, '14:00:00', '15:00:00', 'Rabu', 'Aktif'),
(105, 49, '14:00:00', '15:00:00', 'Jumat', 'Aktif'),
(106, 50, '08:00:00', '09:00:00', 'Senin', 'Aktif'),
(107, 50, '08:00:00', '09:00:00', 'Selasa', 'Aktif'),
(108, 50, '08:00:00', '09:00:00', 'Kamis', 'Aktif'),
(109, 51, '15:00:00', '16:00:00', 'Senin', 'Aktif'),
(110, 51, '15:00:00', '16:00:00', 'Selasa', 'Aktif'),
(111, 51, '15:00:00', '16:00:00', 'Rabu', 'Aktif'),
(112, 51, '15:00:00', '16:00:00', 'Kamis', 'Aktif'),
(113, 51, '15:00:00', '16:00:00', 'Jumat', 'Aktif'),
(114, 52, '15:00:00', '16:00:00', 'Senin', 'Aktif'),
(115, 52, '15:00:00', '16:00:00', 'Kamis', 'Aktif'),
(116, 52, '15:00:00', '16:00:00', 'Jumat', 'Aktif'),
(117, 53, '11:00:00', '14:00:00', 'Selasa', 'Aktif'),
(118, 53, '11:00:00', '14:00:00', 'Kamis', 'Aktif'),
(119, 54, '12:30:00', '15:00:00', 'Senin', 'Aktif'),
(120, 54, '12:30:00', '15:00:00', 'Selasa', 'Aktif'),
(121, 54, '12:00:00', '15:00:00', 'Rabu', 'Aktif'),
(122, 54, '12:00:00', '15:00:00', 'Kamis', 'Aktif'),
(123, 54, '12:30:00', '15:00:00', 'Jumat', 'Aktif'),
(124, 55, '14:30:00', '16:00:00', 'Senin', 'Aktif'),
(125, 55, '14:30:00', '16:00:00', 'Selasa', 'Aktif'),
(126, 55, '14:30:00', '16:00:00', 'Rabu', 'Aktif'),
(127, 55, '14:30:00', '16:00:00', 'Kamis', 'Aktif'),
(128, 55, '14:30:00', '16:00:00', 'Jumat', 'Aktif'),
(129, 56, '15:00:00', '16:00:00', 'Selasa', 'Aktif'),
(130, 56, '15:00:00', '16:00:00', 'Jumat', 'Aktif'),
(131, 57, '08:00:00', '09:00:00', 'Senin', 'Aktif'),
(132, 57, '08:00:00', '09:00:00', 'Jumat', 'Aktif'),
(133, 58, '12:00:00', '14:00:00', 'Senin', 'Aktif'),
(134, 58, '10:00:00', '12:00:00', 'Selasa', 'Aktif'),
(135, 58, '14:00:00', '16:00:00', 'Kamis', 'Aktif'),
(136, 59, '14:30:00', '16:00:00', 'Senin', 'Aktif'),
(137, 59, '14:30:00', '16:00:00', 'Rabu', 'Aktif'),
(138, 59, '14:30:00', '16:00:00', 'Jumat', 'Aktif'),
(139, 62, '14:00:00', '14:30:00', 'Selasa', 'Aktif'),
(140, 62, '14:00:00', '14:30:00', 'Kamis', 'Aktif'),
(141, 62, '14:00:00', '14:30:00', 'Jumat', 'Aktif'),
(142, 63, '09:00:00', '11:00:00', 'Jumat', 'Aktif'),
(143, 64, '09:00:00', '10:30:00', 'Selasa', 'Aktif'),
(144, 64, '09:00:00', '10:30:00', 'Kamis', 'Aktif'),
(145, 65, '14:30:00', '16:00:00', 'Selasa', 'Aktif'),
(146, 65, '14:30:00', '16:00:00', 'Kamis', 'Aktif'),
(147, 66, '11:00:00', '15:00:00', 'Senin', 'Aktif'),
(148, 66, '11:00:00', '15:00:00', 'Selasa', 'Aktif'),
(149, 66, '11:00:00', '15:00:00', 'Rabu', 'Aktif'),
(150, 66, '11:00:00', '15:00:00', 'Kamis', 'Aktif'),
(151, 66, '10:00:00', '15:00:00', 'Jumat', 'Aktif'),
(152, 66, '10:00:00', '14:00:00', 'Sabtu', 'Aktif'),
(153, 67, '14:00:00', '16:00:00', 'Selasa', 'Aktif'),
(154, 67, '15:00:00', '16:00:00', 'Rabu', 'Aktif'),
(155, 67, '15:00:00', '16:00:00', 'Jumat', 'Aktif'),
(156, 68, '09:12:00', '12:00:00', 'Senin', 'Aktif'),
(157, 68, '09:00:00', '12:00:00', 'Selasa', 'Aktif'),
(158, 68, '09:00:00', '12:00:00', 'Rabu', 'Aktif'),
(159, 68, '09:00:00', '12:00:00', 'Kamis', 'Aktif'),
(160, 68, '09:00:00', '12:00:00', 'Jumat', 'Aktif'),
(161, 69, '09:00:00', '10:00:00', 'Senin', 'Aktif'),
(162, 70, '15:00:00', '16:00:00', 'Jumat', 'Aktif'),
(163, 71, '10:00:00', '11:00:00', 'Selasa', 'Aktif'),
(164, 72, '11:00:00', '15:00:00', 'Senin', 'Aktif'),
(165, 72, '11:00:00', '15:00:00', 'Selasa', 'Aktif'),
(166, 72, '11:00:00', '15:00:00', 'Rabu', 'Aktif'),
(167, 72, '11:00:00', '15:00:00', 'Kamis', 'Aktif'),
(168, 72, '10:00:00', '15:00:00', 'Jumat', 'Aktif'),
(169, 72, '10:00:00', '14:00:00', 'Sabtu', 'Aktif'),
(170, 73, '15:00:00', '16:00:00', 'Senin', 'Aktif'),
(171, 73, '15:00:00', '16:00:00', 'Kamis', 'Aktif'),
(172, 74, '15:30:00', '16:00:00', 'Senin', 'Aktif'),
(173, 74, '14:30:00', '15:30:00', 'Selasa', 'Aktif'),
(174, 74, '14:30:00', '15:30:00', 'Rabu', 'Aktif'),
(175, 74, '14:30:00', '15:30:00', 'Kamis', 'Aktif'),
(176, 74, '14:30:00', '15:30:00', 'Jumat', 'Aktif'),
(177, 75, '12:00:00', '13:00:00', 'Rabu', 'Aktif'),
(178, 75, '12:00:00', '13:00:00', 'Kamis', 'Aktif'),
(179, 76, '12:00:00', '13:00:00', 'Selasa', 'Aktif'),
(180, 77, '09:00:00', '10:00:00', 'Selasa', 'Aktif'),
(181, 77, '09:00:00', '10:00:00', 'Kamis', 'Aktif'),
(182, 78, '14:00:00', '16:00:00', 'Senin', 'Aktif'),
(183, 78, '14:00:00', '16:00:00', 'Selasa', 'Aktif'),
(184, 78, '14:00:00', '16:00:00', 'Rabu', 'Aktif'),
(185, 78, '14:00:00', '16:00:00', 'Kamis', 'Aktif'),
(186, 78, '14:30:00', '16:00:00', 'Jumat', 'Aktif'),
(187, 79, '15:00:00', '16:00:00', 'Senin', 'Aktif'),
(188, 79, '15:00:00', '16:00:00', 'Selasa', 'Aktif'),
(189, 79, '12:00:00', '13:00:00', 'Rabu', 'Aktif'),
(190, 79, '15:30:00', '16:00:00', 'Kamis', 'Aktif'),
(191, 80, '11:00:00', '13:00:00', 'Senin', 'Aktif'),
(192, 80, '11:00:00', '13:00:00', 'Kamis', 'Aktif'),
(193, 80, '11:00:00', '13:00:00', 'Jumat', 'Aktif'),
(194, 81, '15:00:00', '16:00:00', 'Senin', 'Aktif'),
(195, 81, '15:00:00', '16:00:00', 'Rabu', 'Aktif'),
(196, 81, '15:00:00', '16:00:00', 'Jumat', 'Aktif'),
(197, 82, '14:30:00', '16:00:00', 'Senin', 'Aktif'),
(198, 82, '14:30:00', '16:00:00', 'Rabu', 'Aktif'),
(199, 82, '14:30:00', '16:00:00', 'Jumat', 'Aktif'),
(200, 83, '15:00:00', '16:00:00', 'Selasa', 'Aktif'),
(201, 85, '14:30:00', '15:30:00', 'Senin', 'Aktif'),
(202, 85, '14:30:00', '15:30:00', 'Jumat', 'Aktif'),
(203, 86, '14:00:00', '16:00:00', 'Selasa', 'Aktif'),
(204, 86, '14:00:00', '16:00:00', 'Rabu', 'Aktif'),
(205, 86, '14:00:00', '16:00:00', 'Kamis', 'Aktif'),
(206, 87, '15:00:00', '16:00:00', 'Senin', 'Aktif'),
(207, 87, '15:00:00', '16:00:00', 'Jumat', 'Aktif'),
(208, 88, '13:00:00', '14:00:00', 'Selasa', 'Aktif'),
(209, 89, '15:00:00', '16:00:00', 'Senin', 'Aktif'),
(210, 89, '15:00:00', '16:00:00', 'Rabu', 'Aktif'),
(211, 89, '15:00:00', '16:00:00', 'Jumat', 'Aktif'),
(212, 90, '12:00:00', '14:00:00', 'Rabu', 'Aktif'),
(213, 91, '14:00:00', '16:00:00', 'Rabu', 'Aktif'),
(214, 91, '14:00:00', '16:00:00', 'Jumat', 'Aktif'),
(215, 92, '12:00:00', '14:00:00', 'Senin', 'Aktif'),
(216, 92, '12:00:00', '14:00:00', 'Rabu', 'Aktif'),
(217, 93, '14:30:00', '15:00:00', 'Rabu', 'Aktif'),
(218, 94, '13:00:00', '15:00:00', 'Senin', 'Aktif'),
(219, 94, '13:00:00', '15:00:00', 'Jumat', 'Aktif'),
(220, 95, '14:00:00', '16:00:00', 'Selasa', 'Aktif'),
(221, 95, '14:00:00', '16:00:00', 'Rabu', 'Aktif'),
(222, 96, '09:00:00', '10:00:00', 'Selasa', 'Aktif'),
(223, 96, '09:00:00', '10:00:00', 'Jumat', 'Aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dokter` (`dokter_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `fk_dokter` FOREIGN KEY (`dokter_id`) REFERENCES `dokter` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
