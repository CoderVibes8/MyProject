-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 12:21 PM
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
-- Database: `healthnet_version2`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(255) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `profile`, `first_name`, `middle_name`, `last_name`, `email`, `phone_number`, `password`, `role`) VALUES
(1, 'MyPogiSelf.jpg', 'Florence', 'Dinong', 'Facton', 'admin@gmail.com', '09306505898', 'testadmin', 'super admin');

-- --------------------------------------------------------

--
-- Table structure for table `answered_questions`
--

CREATE TABLE `answered_questions` (
  `aq_id` int(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `appointment_date` varchar(255) DEFAULT NULL,
  `appointment_time` varchar(255) NOT NULL,
  `total_expense` varchar(255) DEFAULT NULL,
  `doctor` varchar(255) NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `isCustomed` tinyint(1) NOT NULL,
  `otp` int(6) DEFAULT NULL,
  `otp_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `user_id`, `doc_id`, `first_name`, `last_name`, `gender`, `contact`, `email`, `appointment_date`, `appointment_time`, `total_expense`, `doctor`, `status`, `isCustomed`, `otp`, `otp_created_at`) VALUES
(103, 7, 5, 'Florence', 'Facton', 'male', '09306505898', 'florencefactondev@gmail.com', '2025-09-25', '08:00|08:30:00', '750.00', 'FDC Center', 'Pending', 1, 503928, '2025-09-16 03:47:40'),
(104, 7, 2, 'Misay', 'Jimenez', 'female', '09306505899', 'florencefactondev@gmail.com', '2025-08-15', '10:00:00|10:15:00', '850.00', 'Florencio Pogisismo', 'Pending', 0, 457467, '2025-09-16 04:04:50'),
(107, 7, 2, 'Florensiyo', 'Dagohoy', 'male', '09306505898', 'florencefactondev@gmail.com', '2025-09-30', '09:00|09:30:00', '850.00', 'Florencio Pogisismo', 'Pending', 1, 592885, '2025-09-21 14:37:15'),
(108, 7, 2, 'Tralalelo', 'Tralala', 'prefer-not-to-say', '12345678987', 'florencefactondev@gmail.com', '2025-09-29', '10:45:00|11:00:00', '850.00', 'Florencio Pogisismo', 'Pending', 0, 804240, '2025-09-21 14:41:16'),
(111, 7, 2, 'Tungtung', 'Sahur', 'male', '09205594676', 'florencefactondev@gmail.com', '2025-09-30', '10:15:00|10:30:00', '850.00', 'Florencio Pogisismo', 'Pending', 0, 171831, '2025-09-22 16:09:02'),
(112, 7, 2, 'Florenciooooo', 'POGI', 'male', '09306505898', 'florencefactondev@gmail.com', '2025-10-31', '12:00:00|12:15:00', '850.00', 'Florencio Pogisismo', 'Pending', 0, 118181, '2025-10-20 13:42:25'),
(113, 7, 2, 'Cardo', 'Verdano', 'male', '09123321123', 'florencefactondev@gmail.com', '2025-10-31', '09:30|10:30:00', '850.00', 'Florencio Pogisismo', 'Pending', 1, 479036, '2025-10-20 16:34:02'),
(114, 7, 2, 'Glitch', 'Squad', 'male', '09345678456', 'florencefactondev@gmail.com', '2025-10-31', '11:30:00|11:45:00', '1000.00', 'Florencio Pogisismo', 'Pending', 0, 168632, '2025-10-23 09:01:38'),
(115, 7, 2, 'Testing', '123', 'male', '09349568759', 'florencefactondev@gmail.com', '2025-10-26', '08:00|09:00:00', '1000.00', 'Florencio Pogisismo', 'Pending', 1, 789719, '2025-10-25 12:46:12'),
(116, 7, 2, 'Hey', 'Yow', 'male', '09306505898', 'florencefactondev@gmail.com', '2025-10-26', '09:00|10:00:00', '1000.00', 'Florencio Pogisismo', 'Pending', 1, 731655, '2025-10-25 13:08:15'),
(118, 7, 7, 'Jp', 'Cheche', 'male', '09205406978', 'florencefactondev@gmail.com', '2025-10-26', '16:00:00|17:00:00', '1200.00', 'John Doe', 'Pending', 0, 528478, '2025-10-25 13:48:38'),
(119, 7, 8, 'Jerrey', 'Jender', 'male', '09159478716', 'florencefactondev@gmail.com', '2025-10-30', '08:00:00|09:00:00', '2650.00', 'John Reinhardt', 'Pending', 0, 117743, '2025-10-26 10:58:43');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_files`
--

CREATE TABLE `appointment_files` (
  `file_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `personnel_id` int(11) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_files`
--

INSERT INTO `appointment_files` (`file_id`, `appointment_id`, `user_id`, `doc_id`, `personnel_id`, `file_path`, `uploaded_at`) VALUES
(26, 103, 7, 5, 24, 'Florence Resume.pdf', '2025-09-21 06:01:38'),
(28, 114, 7, 2, 24, 'analytics_report (2).pdf', '2025-10-23 09:04:01'),
(29, 118, 7, 7, 24, 'FlorenceResumeUIT.pdf.pdf', '2025-10-26 11:02:02');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_schedule`
--

CREATE TABLE `appointment_schedule` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `doc_id` int(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time_start` time NOT NULL,
  `appointment_time_end` time NOT NULL,
  `doc_fn` varchar(100) NOT NULL,
  `doc_ln` varchar(100) NOT NULL,
  `specialty` varchar(150) NOT NULL,
  `stat` enum('Upcoming','Cancelled','Done','Pending','Approved') NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_schedule`
--

INSERT INTO `appointment_schedule` (`id`, `appointment_id`, `doc_id`, `user_id`, `appointment_date`, `appointment_time_start`, `appointment_time_end`, `doc_fn`, `doc_ln`, `specialty`, `stat`, `created_at`) VALUES
(21, 103, 5, 7, '2025-09-25', '08:00:00', '08:30:00', 'FDC', 'Center', 'DNA', 'Done', '2025-09-16 13:15:12'),
(22, 104, 2, 7, '2025-10-15', '10:00:00', '10:15:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Pending', '2025-09-17 13:15:28'),
(25, 107, 2, 7, '2025-09-30', '09:00:00', '09:30:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Pending', '2025-09-21 22:37:43'),
(26, 108, 2, 7, '2025-09-29', '10:45:00', '11:00:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Approved', '2025-09-21 22:41:36'),
(29, 111, 2, 7, '2025-09-30', '10:15:00', '10:30:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Approved', '2025-09-23 00:09:23'),
(30, 112, 2, 7, '2025-10-31', '12:00:00', '12:15:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Approved', '2025-10-20 21:43:14'),
(31, 113, 2, 7, '2025-10-31', '09:30:00', '10:30:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Approved', '2025-10-21 00:34:35'),
(32, 114, 2, 7, '2025-10-31', '11:30:00', '11:45:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Done', '2025-10-23 17:02:28'),
(33, 115, 2, 7, '2025-10-26', '08:00:00', '09:00:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Pending', '2025-10-25 20:46:57'),
(34, 116, 2, 7, '2025-10-26', '09:00:00', '10:00:00', 'Florencio', 'Pogisismo', 'Ultrasound', 'Pending', '2025-10-25 21:08:34'),
(36, 118, 7, 7, '2025-10-26', '16:00:00', '17:00:00', 'John', 'Doe', 'Clinical Laboratory', 'Done', '2025-10-25 21:49:01'),
(37, 119, 8, 7, '2025-10-30', '08:00:00', '09:00:00', 'John', 'Reinhardt', 'Vascular Studies', 'Approved', '2025-10-26 19:01:04');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `sender` enum('user','personnel') NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `doctor_id`, `patient_id`, `sender`, `message`, `timestamp`) VALUES
(165, 24, 7, 'user', 'Hello', '2025-10-26 10:28:51'),
(166, 24, 7, 'user', 'Testing', '2025-10-26 10:30:59'),
(167, 24, 7, 'user', 'Good day!', '2025-10-26 10:36:29'),
(168, 24, 7, 'personnel', 'Hello Good day!', '2025-10-26 10:37:30'),
(169, 24, 7, 'user', 'Is Doctor Chuchu available this November 3, 2025 at 10:00am onwards?', '2025-10-26 10:38:35'),
(170, 24, 7, 'personnel', 'Let me check his schedule first', '2025-10-26 10:38:48'),
(171, 24, 7, 'user', 'Ok thank you!', '2025-10-26 10:49:53'),
(172, 24, 7, 'personnel', 'Your welcome', '2025-10-26 10:50:07'),
(173, 24, 8, 'user', 'Hello Doc! Good day po.', '2025-10-26 11:18:51'),
(174, 24, 8, 'user', 'Hello', '2025-10-26 11:29:35'),
(175, 24, 8, 'personnel', 'Hello', '2025-10-26 11:31:41'),
(176, 25, 8, 'user', 'Hello Doc, goodday!', '2025-10-26 11:32:50'),
(177, 25, 8, 'personnel', 'Hello Good day!', '2025-10-26 11:33:19'),
(178, 24, 8, 'user', 'Is Doc Martin available this thursday at 1pm?', '2025-10-26 11:37:25'),
(179, 25, 8, 'user', 'Is Doc Martin available this thursday at 1pm?', '2025-10-26 11:37:54'),
(180, 25, 8, 'personnel', 'Yes he\'s available that time. Just book an appointment to that time.', '2025-10-26 11:38:42'),
(181, 25, 8, 'user', 'Ok! Thank you!', '2025-10-26 11:39:33'),
(182, 24, 7, 'user', 'Goodevening!', '2025-10-26 18:33:22'),
(183, 24, 7, 'personnel', 'Hello Goodevening', '2025-10-26 18:33:32'),
(184, 24, 8, 'personnel', 'Hey!', '2025-10-26 18:33:37'),
(185, 25, 7, 'user', 'Goodevening', '2025-10-26 18:34:22'),
(186, 24, 7, 'user', 'Goodevening', '2025-10-26 18:35:21'),
(187, 25, 7, 'user', 'Hello', '2025-10-26 18:35:31'),
(188, 24, 8, 'personnel', 'Hello', '2025-10-26 18:36:00'),
(189, 24, 7, 'user', 'Ok', '2025-10-26 18:46:00'),
(190, 25, 7, 'user', 'ok', '2025-10-26 18:46:06');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_account`
--

CREATE TABLE `clinic_account` (
  `clinic_id` int(255) NOT NULL,
  `clinic_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `clinic_address` varchar(255) NOT NULL,
  `operating_hours` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `business_permit` varchar(255) NOT NULL,
  `accreditation_certificate` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected','') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clinic_credentials`
--

CREATE TABLE `clinic_credentials` (
  `clinic_credentials_id` int(11) NOT NULL,
  `clinic_id` int(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clinic_offers`
--

CREATE TABLE `clinic_offers` (
  `offer_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `service` varchar(255) NOT NULL,
  `pricing` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `consultation_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','paid','completed') DEFAULT 'pending',
  `paymongo_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_acc_creation`
--

CREATE TABLE `doctor_acc_creation` (
  `doc_id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `specialty` varchar(50) NOT NULL,
  `opw` varchar(255) NOT NULL,
  `status` enum('activated','not-activated','restricted') NOT NULL DEFAULT 'not-activated',
  `availability` enum('Available','Not Available') NOT NULL DEFAULT 'Not Available',
  `isOnline` enum('Offline','Online') NOT NULL DEFAULT 'Offline',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_acc_creation`
--

INSERT INTO `doctor_acc_creation` (`doc_id`, `email`, `password`, `specialty`, `opw`, `status`, `availability`, `isOnline`, `created_at`) VALUES
(24, 'florencefactondev@gmail.com', '$2y$10$fEX.anwXgUXtMB4pDvhgpOKSrx2ZWX0daOZgBqSR6GGW6nk7Sl5Xq', 'Optometrist', '$2y$10$fEX.anwXgUXtMB4pDvhgpOKSrx2ZWX0daOZgBqSR6GGW6nk7Sl5Xq', 'activated', 'Not Available', 'Online', '2025-08-24 16:33:39'),
(25, 'cabillonjohncedric@gmail.com', '$2y$10$OO6TuwcSEi7MpvN62vbkHOCIOEXsHXTEcSGmDWd/JjUdE5aDRLm8O', 'Dentist', '$2y$10$OO6TuwcSEi7MpvN62vbkHOCIOEXsHXTEcSGmDWd/JjUdE5aDRLm8O', 'activated', 'Not Available', 'Online', '2025-08-25 02:12:39');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_consultation`
--

CREATE TABLE `doctor_consultation` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `user_id` int(255) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `doctor_num` varchar(255) NOT NULL,
  `status` enum('paid','pending') NOT NULL DEFAULT 'pending',
  `doctor_joined` tinyint(50) NOT NULL,
  `oncall` tinyint(50) NOT NULL,
  `vc_stat` tinyint(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_consultation`
--

INSERT INTO `doctor_consultation` (`id`, `doc_id`, `user_id`, `patient_name`, `amount`, `tax`, `total_amount`, `description`, `doctor_name`, `doctor_num`, `status`, `doctor_joined`, `oncall`, `vc_stat`, `created_at`) VALUES
(139, 24, 7, 'Missy Jimenez', 750.00, 15.00, 765.00, 'Consultation with Dr. Florence Facton', 'Dr. Florence Facton', '09306505898', 'paid', 0, 0, 0, '2025-08-24 23:36:26'),
(140, 24, 7, 'Missy Jimenez', 750.00, 15.00, 765.00, 'Consultation with Dr. Florence Facton', 'Dr. Florence Facton', '09306505898', 'pending', 0, 0, 0, '2025-08-30 09:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_consultation_info`
--

CREATE TABLE `doctor_consultation_info` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `onsite_rate` decimal(10,2) NOT NULL,
  `online_rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_consultation_info`
--

INSERT INTO `doctor_consultation_info` (`id`, `doc_id`, `onsite_rate`, `online_rate`) VALUES
(8, 24, 900.00, 750.00),
(9, 25, 1200.00, 900.00);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_info`
--

CREATE TABLE `doctor_info` (
  `doctor_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `specialty` enum('2D Echo','DNA','Vascular Studies','Ultrasound','ECG','XRAY','Clinical Laboratory') DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_info`
--

INSERT INTO `doctor_info` (`doctor_id`, `firstname`, `lastname`, `specialty`, `price`) VALUES
(2, 'John', 'Doe', '2D Echo', 3350.00),
(3, 'Betty', 'Doe', 'Ultrasound', 2950.00),
(5, 'David', 'Robinson', 'DNA', 2750.00),
(7, 'Robert', 'Lune', 'Clinical Laboratory', 1500.00),
(8, 'John', 'Reinhardt', 'Vascular Studies', 2650.00),
(9, 'Isabel', 'Cruz', 'ECG', 3500.00),
(10, 'Camila', 'Tores', 'XRAY', 2850.00);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_notif`
--

CREATE TABLE `doctor_notif` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isRead` tinyint(4) NOT NULL DEFAULT 0,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_notif`
--

INSERT INTO `doctor_notif` (`id`, `doc_id`, `doctor_id`, `user_id`, `description`, `created_at`, `isRead`, `link`) VALUES
(3, 24, 5, 7, 'Your approved an appointment with Mr/Mrs. Miss Jimenez (ID 103).', '2025-10-23 08:50:20', 1, 'http://localhost/FDC/Personnel/my_records.php'),
(4, 24, 5, 7, 'Your approved an appointment with Mr/Mrs. Miss Jimenez (ID 103).', '2025-10-23 08:50:20', 1, 'http://localhost/FDC/Personnel/my_records.php'),
(5, 24, 5, 7, 'Your approved an appointment with Mr/Mrs. Miss Jimenez (ID 103).', '2025-10-23 08:50:20', 1, 'http://localhost/FDC/Personnel/my_records.php'),
(6, 24, 5, 7, 'Your approved an appointment with Mr/Mrs. Miss Jimenez (ID 103).', '2025-10-25 13:24:39', 1, 'http://localhost/FDC/Personnel/my_records.php'),
(7, 24, 2, 7, 'Your approved an appointment with Mr/Mrs. Miss Jimenez (ID 107).', '2025-10-23 08:50:20', 1, 'http://localhost/FDC/Personnel/my_records.php'),
(8, 24, 2, 7, 'Your approved an appointment with Mr/Mrs. Miss Jimenez (ID 107).', '2025-10-23 08:50:20', 1, 'http://localhost/FDC/Personnel/my_records.php'),
(9, 24, 2, 7, 'Your approved an appointment with Mr/Mrs. Miss Jimenez (ID 113).', '2025-10-23 08:50:20', 1, 'http://localhost/FDC/Personnel/my_records.php'),
(14, 24, 2, 7, 'Your approved an appointment with Mr/Mrs. Miss Jimenez (ID 113).', '2025-10-26 11:09:21', 1, 'http://localhost/FDC/Personnel/my_records.php');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_personal_info`
--

CREATE TABLE `doctor_personal_info` (
  `doc_personal_id` int(255) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(255) NOT NULL,
  `doc_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_personal_info`
--

INSERT INTO `doctor_personal_info` (`doc_personal_id`, `profile_pic`, `firstname`, `lastname`, `email`, `phone`, `dob`, `gender`, `doc_id`) VALUES
(18, '1748861128_MyPogiSelf.jpg', 'Florence', 'Facton', 'florencefactondev@gmail.com', '09306505898', '2004-04-29', 'male', 24),
(19, '1756088067_MyPogiSelf.jpg', 'John Cedric', 'Cabillon', 'cabillonjohncedric@gmail.com', '09683559195', '2004-04-03', 'male', 25);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_reviews`
--

CREATE TABLE `doctor_reviews` (
  `id` int(11) NOT NULL,
  `consultation_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedule`
--

CREATE TABLE `doctor_schedule` (
  `id` int(11) NOT NULL,
  `doc_id` int(50) NOT NULL,
  `consultation_type` varchar(50) DEFAULT NULL,
  `date_slots` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `availability` enum('Available','Booked','Expired') NOT NULL DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_schedule`
--

INSERT INTO `doctor_schedule` (`id`, `doc_id`, `consultation_type`, `date_slots`, `start_time`, `end_time`, `availability`, `created_at`) VALUES
(186, 2, 'Ultrasound', '2025-09-03', '10:15:00', '10:30:00', 'Available', '2025-09-16 04:53:59'),
(187, 2, 'Ultrasound', '2025-09-29', '10:45:00', '11:00:00', 'Booked', '2025-09-16 05:01:59'),
(188, 2, 'Ultrasound', '2025-09-30', '10:15:00', '10:30:00', 'Booked', '2025-09-22 13:55:03'),
(189, 2, 'Ultrasound', '2025-09-30', '12:15:00', '12:30:00', 'Available', '2025-09-22 13:55:03'),
(190, 2, 'Ultrasound', '2025-10-31', '11:30:00', '11:45:00', 'Booked', '2025-10-16 14:37:37'),
(191, 2, 'Ultrasound', '2025-10-31', '12:00:00', '12:15:00', 'Booked', '2025-10-16 14:37:59'),
(193, 4, 'Vascular Studies', '2025-11-11', '08:00:00', '09:00:00', 'Booked', '2025-10-23 08:28:32'),
(194, 7, 'Clinical Laboratory', '2025-10-26', '16:00:00', '17:00:00', 'Booked', '2025-10-25 13:47:44'),
(195, 8, 'Vascular Studies', '2025-10-30', '08:00:00', '09:00:00', 'Booked', '2025-10-26 10:56:01');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_timeslots`
--

CREATE TABLE `doctor_timeslots` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `slot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drug_info`
--

CREATE TABLE `drug_info` (
  `id` int(11) NOT NULL,
  `drug_name` varchar(100) NOT NULL,
  `use_case` text NOT NULL,
  `warning_message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_info`
--

INSERT INTO `drug_info` (`id`, `drug_name`, `use_case`, `warning_message`) VALUES
(1, 'ibuprofen', 'reduces fever, pain, and inflammation', 'Take with food to avoid stomach upset. Avoid if allergic.'),
(2, 'acetaminophen', 'relieves pain and reduces fever', 'Do not exceed recommended dose; can cause liver damage.'),
(3, 'amoxicillin', 'used to treat bacterial infections', 'Complete the full course even if you feel better.');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faqs_id` int(255) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faqs_id`, `question`, `answer`, `created_at`) VALUES
(1, 'What are your clinic hours?', 'Our clinic is open from 8 AM to 5 PM, Monday to Saturday.', '2025-10-20 16:02:51'),
(2, 'How do I book an appointment?', 'You can book an appointment online through our HealthNet portal or by calling our clinic.', '2025-10-20 16:02:51'),
(3, 'What services do you offer?', 'We offer general checkups, pediatrics, dental services, and laboratory tests.', '2025-10-20 16:02:51'),
(4, 'Do you accept insurance?', 'Yes, we accept most major insurance providers. Please bring your insurance card when you visit.', '2025-10-20 16:02:51'),
(6, 'Where is you clinic located?', 'We are located at 123 Main Street, Quezon City.', '2025-10-20 16:06:10');

-- --------------------------------------------------------

--
-- Table structure for table `illness_responses`
--

CREATE TABLE `illness_responses` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `empathy` text NOT NULL,
  `remedies` text NOT NULL,
  `medications` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `illness_responses`
--

INSERT INTO `illness_responses` (`id`, `keyword`, `empathy`, `remedies`, `medications`) VALUES
(1, 'toothache', 'I’m sorry to hear you’re experiencing a toothache.', 'Rinse your mouth with warm saltwater, use a cold compress, and avoid very hot or cold food.', 'You can take Biogesic (paracetamol) or Mefenamic acid.'),
(2, 'headache', 'That sounds uncomfortable. Headaches can be really frustrating.', 'Try resting in a quiet, dark room. Stay hydrated and avoid screen time.', 'You may take Biogesic (paracetamol) or Alaxan FR.'),
(3, 'fever', 'Oh no, dealing with a fever can be tough.', 'Stay hydrated, rest well, and use a cool damp cloth on your forehead.', 'You can take Biogesic or Tempra Forte.');

-- --------------------------------------------------------

--
-- Table structure for table `patient_records`
--

CREATE TABLE `patient_records` (
  `id` int(11) NOT NULL,
  `consultation_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `consultation_id` int(11) NOT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `xendit_invoice_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unanswered_questions`
--

CREATE TABLE `unanswered_questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `stat` enum('pending','done','','') NOT NULL DEFAULT 'pending',
  `asked_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unanswered_questions`
--

INSERT INTO `unanswered_questions` (`id`, `question`, `stat`, `asked_at`) VALUES
(31, 'dvnkfbndkvjn', 'pending', '2025-08-30 17:14:06'),
(32, 'what are your clinic hours?', 'pending', '2025-09-02 10:51:19'),
(33, 'what services do you offer?', 'pending', '2025-09-02 10:56:32'),
(34, 'what are your clinic hours?', 'pending', '2025-09-02 10:57:26');

-- --------------------------------------------------------

--
-- Table structure for table `user_credentials`
--

CREATE TABLE `user_credentials` (
  `user_credentials_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_credentials`
--

INSERT INTO `user_credentials` (`user_credentials_id`, `user_id`, `profile_picture`) VALUES
(4, 7, 'Minimalist White and Grey Professional Resume.jpg'),
(5, 8, 'Minimalist White and Grey Professional Resume.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_notif`
--

CREATE TABLE `user_notif` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isRead` tinyint(4) NOT NULL DEFAULT 0,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_notif`
--

INSERT INTO `user_notif` (`id`, `doc_id`, `user_id`, `description`, `created_at`, `isRead`, `link`) VALUES
(83, 2, 7, 'Your appointment with Dr. Florencio Pogisismo on Fri, Oct 31 at 12:00 PM-12:15 PM has been confirmed.', '2025-10-20 13:43:25', 1, 'http://localhost/Capstone2/User/appointment_schedule.php'),
(84, 2, 7, 'Your appointment with Dr. Florencio Pogisismo on Fri, Oct 31 at 9:30 AM-10:30 AM has been confirmed.', '2025-10-25 12:53:22', 1, 'http://localhost/Capstone2/User/appointment_schedule.php'),
(85, 2, 7, 'Your appointment with Dr. Florencio Pogisismo on Fri, Oct 31 at 11:30 AM-11:45 AM has been confirmed.', '2025-10-23 09:02:46', 1, 'http://localhost/FDC/User/appointment_schedule.php'),
(86, 2, 7, 'Your appointment with Dr. Florencio Pogisismo on Sun, Oct 26 at 8:00 AM-9:00 AM is Pending. Please wait for the confirmation.', '2025-10-25 12:53:03', 1, 'http://localhost/FDC/User/appointment_schedule.php'),
(87, 2, 7, 'Your appointment with Dr. Florencio Pogisismo on Sun, Oct 26 at 9:00 AM-10:00 AM is Pending. Please wait for the confirmation.', '2025-10-25 13:21:17', 1, 'http://localhost/FDC/User/appointment_schedule.php'),
(88, 4, 7, 'Your appointment with Dr. JC Cabillon on Tue, Nov 11 at 8:00 AM-9:00 AM has been confirmed.', '2025-10-25 13:21:17', 1, 'http://localhost/FDC/User/appointment_schedule.php'),
(89, 7, 7, 'Your appointment with Dr. John Doe on Sun, Oct 26 at 4:00 PM-5:00 PM has been confirmed.', '2025-10-25 13:51:02', 1, 'http://localhost/FDC/User/appointment_schedule.php'),
(90, NULL, 8, 'You updated your profile picture.', '2025-10-26 03:31:10', 0, 'http://localhost/FDC/User/patient_profile.php'),
(91, 8, 7, 'Your appointment with Dr. John Reinhardt on Thu, Oct 30 at 8:00 AM-9:00 AM has been confirmed.', '2025-10-26 11:01:04', 0, 'http://localhost/FDC/User/appointment_schedule.php');

-- --------------------------------------------------------

--
-- Table structure for table `user_patient`
--

CREATE TABLE `user_patient` (
  `user_id` int(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `home_address` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `isOnline` enum('Online','Offline') NOT NULL DEFAULT 'Offline',
  `status` enum('restricted','activated') NOT NULL DEFAULT 'activated',
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_patient`
--

INSERT INTO `user_patient` (`user_id`, `first_name`, `last_name`, `dob`, `home_address`, `gender`, `email`, `contact_number`, `role`, `isOnline`, `status`, `created_at`, `password`) VALUES
(7, 'Miss', 'Jimenez', '2000-06-23', 'Capiz', 'female', 'missyfadugajimenez23@gmail.com', '09124573867', 'patient', 'Online', 'activated', '2025-08-24', '$2y$10$pV5TFZOQwTXqG.Wxbc8M5uSp.bZ.RTDEvCebSN24eyU3b1Rc0k.Ci'),
(8, 'Cardo', 'Versosa', '2003-03-12', 'Iloilo City', 'male', 'cardo@gmail.com', '09345543345', 'patient', 'Online', 'activated', '2025-10-21', '$2y$10$K5PEnAUFtF3krKEcWP/h3OZP0QGXXIiuodh09mb2cuLmbxDxKqnGq'),
(10, 'Jamel', 'Halo', '1998-02-26', 'Iloilo City', 'male', 'jamel@gmail.com', '09345678876', 'patient', 'Offline', 'activated', '2025-10-21', '$2y$10$91ENmb25xaJ32kWG6PfGlOswm6MI3IZLMfTJvj6DN3bTXz69Efya6'),
(12, 'Val', 'Dorne', '1998-11-20', 'Iloilo', 'male', 'val@gmail.com', '09234432546', 'patient', 'Offline', 'activated', '2025-10-21', '$2y$10$/QQp0V9C0R1byScYUPGqnOSw7/8O2PqB0g8u6OVdJVrec8K/qrJy2'),
(15, 'last', 'hehe', '1985-05-25', 'Iloilo', 'male', 'last@gmail.com', '09384576933', 'patient', 'Online', 'activated', '2025-10-21', '$2y$10$QuLtABPKQoHol9HYAIMVvepMjLmy/bvGdzuKstRMwStosFU31rhiC');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `answered_questions`
--
ALTER TABLE `answered_questions`
  ADD PRIMARY KEY (`aq_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `appointment_files`
--
ALTER TABLE `appointment_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doc_id` (`doc_id`),
  ADD KEY `personnel_id` (`personnel_id`);

--
-- Indexes for table `appointment_schedule`
--
ALTER TABLE `appointment_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clinic_account`
--
ALTER TABLE `clinic_account`
  ADD PRIMARY KEY (`clinic_id`);

--
-- Indexes for table `clinic_credentials`
--
ALTER TABLE `clinic_credentials`
  ADD PRIMARY KEY (`clinic_credentials_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `clinic_offers`
--
ALTER TABLE `clinic_offers`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doc_id` (`doc_id`);

--
-- Indexes for table `doctor_acc_creation`
--
ALTER TABLE `doctor_acc_creation`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `doctor_consultation`
--
ALTER TABLE `doctor_consultation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doc_id` (`doc_id`);

--
-- Indexes for table `doctor_consultation_info`
--
ALTER TABLE `doctor_consultation_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doc_id` (`doc_id`);

--
-- Indexes for table `doctor_info`
--
ALTER TABLE `doctor_info`
  ADD PRIMARY KEY (`doctor_id`);

--
-- Indexes for table `doctor_notif`
--
ALTER TABLE `doctor_notif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `doc_id` (`doc_id`);

--
-- Indexes for table `doctor_personal_info`
--
ALTER TABLE `doctor_personal_info`
  ADD PRIMARY KEY (`doc_personal_id`),
  ADD KEY `fk_doctor_id` (`doc_id`);

--
-- Indexes for table `doctor_reviews`
--
ALTER TABLE `doctor_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_consultation` (`consultation_id`),
  ADD KEY `fk_doc` (`doc_id`);

--
-- Indexes for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_timeslots`
--
ALTER TABLE `doctor_timeslots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_doc_id` (`doc_id`);

--
-- Indexes for table `drug_info`
--
ALTER TABLE `drug_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drug_name` (`drug_name`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faqs_id`);

--
-- Indexes for table `illness_responses`
--
ALTER TABLE `illness_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_records`
--
ALTER TABLE `patient_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consultation_id` (`consultation_id`),
  ADD KEY `doc_id` (`doc_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consultation_id` (`consultation_id`);

--
-- Indexes for table `unanswered_questions`
--
ALTER TABLE `unanswered_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_credentials`
--
ALTER TABLE `user_credentials`
  ADD PRIMARY KEY (`user_credentials_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_notif`
--
ALTER TABLE `user_notif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fr_doc_id` (`doc_id`),
  ADD KEY `fr_user_id` (`user_id`);

--
-- Indexes for table `user_patient`
--
ALTER TABLE `user_patient`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `answered_questions`
--
ALTER TABLE `answered_questions`
  MODIFY `aq_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `appointment_files`
--
ALTER TABLE `appointment_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `appointment_schedule`
--
ALTER TABLE `appointment_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `clinic_account`
--
ALTER TABLE `clinic_account`
  MODIFY `clinic_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `clinic_credentials`
--
ALTER TABLE `clinic_credentials`
  MODIFY `clinic_credentials_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clinic_offers`
--
ALTER TABLE `clinic_offers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_acc_creation`
--
ALTER TABLE `doctor_acc_creation`
  MODIFY `doc_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `doctor_consultation`
--
ALTER TABLE `doctor_consultation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `doctor_consultation_info`
--
ALTER TABLE `doctor_consultation_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `doctor_info`
--
ALTER TABLE `doctor_info`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctor_notif`
--
ALTER TABLE `doctor_notif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `doctor_personal_info`
--
ALTER TABLE `doctor_personal_info`
  MODIFY `doc_personal_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `doctor_reviews`
--
ALTER TABLE `doctor_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `doctor_timeslots`
--
ALTER TABLE `doctor_timeslots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `drug_info`
--
ALTER TABLE `drug_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faqs_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `illness_responses`
--
ALTER TABLE `illness_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient_records`
--
ALTER TABLE `patient_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unanswered_questions`
--
ALTER TABLE `unanswered_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `user_credentials`
--
ALTER TABLE `user_credentials`
  MODIFY `user_credentials_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_notif`
--
ALTER TABLE `user_notif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `user_patient`
--
ALTER TABLE `user_patient`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_patient` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment_files`
--
ALTER TABLE `appointment_files`
  ADD CONSTRAINT `appointment_files_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointment_schedule` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_files_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_patient` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_files_ibfk_3` FOREIGN KEY (`doc_id`) REFERENCES `doctor_info` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_files_ibfk_4` FOREIGN KEY (`personnel_id`) REFERENCES `doctor_acc_creation` (`doc_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment_schedule`
--
ALTER TABLE `appointment_schedule`
  ADD CONSTRAINT `appointment_schedule_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user_patient` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `clinic_credentials`
--
ALTER TABLE `clinic_credentials`
  ADD CONSTRAINT `clinic_credentials_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinic_account` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `clinic_offers`
--
ALTER TABLE `clinic_offers`
  ADD CONSTRAINT `clinic_offers_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinic_account` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor_acc_creation` (`doc_id`);

--
-- Constraints for table `doctor_consultation`
--
ALTER TABLE `doctor_consultation`
  ADD CONSTRAINT `doctor_consultation_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor_acc_creation` (`doc_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_consultation_info`
--
ALTER TABLE `doctor_consultation_info`
  ADD CONSTRAINT `doctor_consultation_info_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctor_acc_creation` (`doc_id`);

--
-- Constraints for table `doctor_notif`
--
ALTER TABLE `doctor_notif`
  ADD CONSTRAINT `doctor_notif_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_patient` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_notif_ibfk_2` FOREIGN KEY (`doc_id`) REFERENCES `doctor_acc_creation` (`doc_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_personal_info`
--
ALTER TABLE `doctor_personal_info`
  ADD CONSTRAINT `fk_doctor_id` FOREIGN KEY (`doc_id`) REFERENCES `doctor_acc_creation` (`doc_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_reviews`
--
ALTER TABLE `doctor_reviews`
  ADD CONSTRAINT `fk_consultation` FOREIGN KEY (`consultation_id`) REFERENCES `doctor_consultation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_doc` FOREIGN KEY (`doc_id`) REFERENCES `doctor_acc_creation` (`doc_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_timeslots`
--
ALTER TABLE `doctor_timeslots`
  ADD CONSTRAINT `fk_doc_id` FOREIGN KEY (`doc_id`) REFERENCES `doctor_acc_creation` (`doc_id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_records`
--
ALTER TABLE `patient_records`
  ADD CONSTRAINT `patient_records_ibfk_1` FOREIGN KEY (`consultation_id`) REFERENCES `doctor_consultation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_records_ibfk_2` FOREIGN KEY (`doc_id`) REFERENCES `doctor_acc_creation` (`doc_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `patient_records_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user_patient` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`);

--
-- Constraints for table `user_credentials`
--
ALTER TABLE `user_credentials`
  ADD CONSTRAINT `user_credentials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_patient` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_notif`
--
ALTER TABLE `user_notif`
  ADD CONSTRAINT `fr_user_id` FOREIGN KEY (`user_id`) REFERENCES `user_patient` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
