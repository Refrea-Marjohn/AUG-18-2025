-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2025 at 09:19 AM
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
-- Database: `lawfirm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_documents`
--

CREATE TABLE `admin_documents` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `form_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_documents`
--

INSERT INTO `admin_documents` (`id`, `file_name`, `file_path`, `category`, `uploaded_by`, `upload_date`, `form_number`) VALUES
(3, 'Affidavit_of_Loss (10).pdf', 'uploads/admin/1754484266_Affidavit_of_Loss (10).pdf', 'Forms', 1, '2025-08-06 20:44:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_document_activity`
--

CREATE TABLE `admin_document_activity` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `form_number` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_document_activity`
--

INSERT INTO `admin_document_activity` (`id`, `document_id`, `action`, `user_id`, `form_number`, `file_name`, `timestamp`, `user_name`) VALUES
(5, 3, 'Uploaded', 1, 1, 'Affidavit_of_Loss (10).pdf', '2025-08-06 12:44:26', 'Mar John Refrea');

-- --------------------------------------------------------

--
-- Table structure for table `admin_messages`
--

CREATE TABLE `admin_messages` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_messages`
--

INSERT INTO `admin_messages` (`id`, `admin_id`, `recipient_id`, `message`, `sent_at`) VALUES
(1, 20, 30, 'dawdawdaw', '2025-08-14 02:56:29'),
(2, 20, 30, 'dawdaw', '2025-08-14 04:13:35'),
(3, 20, 30, 'dawdaw', '2025-08-14 04:14:11'),
(4, 20, 30, 'hoy', '2025-08-14 04:14:17'),
(5, 20, 30, 'ss', '2025-08-14 04:18:53'),
(6, 20, 30, 'd', '2025-08-14 04:19:10'),
(7, 20, 30, 'dawdwa', '2025-08-14 04:19:35'),
(8, 20, 30, 's', '2025-08-14 04:24:30'),
(9, 20, 30, 'dawd', '2025-08-14 04:28:21'),
(10, 20, 30, 's', '2025-08-14 04:28:38'),
(11, 20, 30, 'sda', '2025-08-14 04:29:41'),
(12, 20, 30, 'sd', '2025-08-14 04:31:12'),
(13, 20, 30, 'ca', '2025-08-14 04:36:04'),
(14, 20, 30, 'daw', '2025-08-14 04:36:26'),
(15, 20, 30, 'admin ako', '2025-08-14 04:37:06'),
(16, 20, 30, 'client ako\r\n', '2025-08-14 04:37:24'),
(17, 20, 30, 'dawdawda', '2025-08-14 05:04:45'),
(18, 20, 33, 'cscsa', '2025-08-14 07:11:51'),
(19, 33, 20, 'dawdwa', '2025-08-14 07:12:38'),
(20, 33, 20, 'dawdaw', '2025-08-14 07:12:55'),
(21, 33, 20, 'asdawdwa', '2025-08-14 07:13:02'),
(22, 33, 20, 'w', '2025-08-14 07:13:34');

-- --------------------------------------------------------

--
-- Table structure for table `attorney_cases`
--

CREATE TABLE `attorney_cases` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attorney_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `client_id` int(11) DEFAULT NULL,
  `case_type` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Active',
  `next_hearing` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attorney_cases`
--

INSERT INTO `attorney_cases` (`id`, `title`, `description`, `attorney_id`, `created_at`, `client_id`, `case_type`, `status`, `next_hearing`) VALUES
(34, 'dawda', 'dawdaw', 22, '2025-08-06 22:09:50', 5, 'Civil', 'Active', NULL),
(35, 'dnawjdawbkj', 'nadjkahwudawudawnkdnwioqnoidqwi', 22, '2025-08-07 15:00:25', 23, 'civil', 'Closed', NULL),
(36, 'dasda', 'dasdsa', 22, '2025-08-07 15:57:44', 23, 'criminal', 'Active', NULL),
(37, 'dawdaw', 'innaiwndkadkha kjdbjasd hofiasj opgajsio ghaosu biyr gahisor nuiasdr huiasdr uaishgiuad uyashro hasoyf huishefyu asgfase', 22, '2025-08-07 15:58:49', 23, 'criminal', 'Pending', NULL),
(38, 'dasdasd', 'dawdawdawd', 31, '2025-08-13 17:26:07', 30, 'family', 'Active', NULL),
(39, 'dawdaw', 'dawdawdaw', 31, '2025-08-14 12:47:49', 33, 'family', 'Active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attorney_documents`
--

CREATE TABLE `attorney_documents` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `case_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attorney_documents`
--

INSERT INTO `attorney_documents` (`id`, `file_name`, `file_path`, `category`, `uploaded_by`, `upload_date`, `case_id`) VALUES
(13, 'Sheesh.pdf', 'uploads/attorney/1754485301_Sheesh.pdf', 'Case Files', 22, '2025-08-06 21:01:41', 21),
(14, '312321.pdf', 'uploads/attorney/1755147086_312321.pdf', 'Case Files', 31, '2025-08-14 12:51:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attorney_document_activity`
--

CREATE TABLE `attorney_document_activity` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attorney_document_activity`
--

INSERT INTO `attorney_document_activity` (`id`, `document_id`, `action`, `user_id`, `case_id`, `file_name`, `timestamp`, `user_name`, `category`) VALUES
(21, 13, 'Uploaded', 22, 21, 'Sheesh.pdf', '2025-08-06 13:01:41', 'Laica Castillo', 'Case Files'),
(22, 14, 'Uploaded', 31, NULL, '312321.pdf', '2025-08-14 04:51:26', 'Laica Castillo Refrea', 'Case Files');

-- --------------------------------------------------------

--
-- Table structure for table `attorney_messages`
--

CREATE TABLE `attorney_messages` (
  `id` int(11) NOT NULL,
  `attorney_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attorney_messages`
--

INSERT INTO `attorney_messages` (`id`, `attorney_id`, `recipient_id`, `message`, `sent_at`) VALUES
(8, 22, 23, 'akdsjadhiowahduawidawdawd\r\n', '2025-08-07 15:00:01'),
(9, 31, 30, 'hey \r\n', '2025-08-13 17:24:54'),
(10, 31, 33, 'dasjdasjdkashj', '2025-08-14 12:46:43');

-- --------------------------------------------------------

--
-- Table structure for table `case_schedules`
--

CREATE TABLE `case_schedules` (
  `id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `attorney_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `type` enum('Hearing','Appointment') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Scheduled',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_schedules`
--

INSERT INTO `case_schedules` (`id`, `case_id`, `attorney_id`, `client_id`, `type`, `title`, `description`, `date`, `time`, `location`, `status`, `created_at`) VALUES
(6, 35, 22, 23, 'Appointment', 'dawdaw', 'tangena mo france', '2025-08-22', '03:41:00', 'khbfkabfkas', 'Scheduled', '2025-08-07 15:42:42'),
(7, 38, 31, 30, 'Appointment', 'dasdasdas', 'asdasdjhawjkdyawukdhaw', '0000-00-00', '00:53:00', 'adasdas', 'Scheduled', '2025-08-14 12:52:35');

-- --------------------------------------------------------

--
-- Table structure for table `client_cases`
--

CREATE TABLE `client_cases` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `client_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `attorney_id` int(11) DEFAULT NULL,
  `case_type` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `next_hearing` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_messages`
--

CREATE TABLE `client_messages` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_messages`
--

INSERT INTO `client_messages` (`id`, `client_id`, `recipient_id`, `message`, `sent_at`) VALUES
(8, 5, 22, 'A new case has been created for you: dawda', '2025-08-06 22:09:50'),
(9, 23, 6, 'haihi\r\n', '2025-08-07 14:57:12'),
(10, 23, 22, 'hiii', '2025-08-07 14:59:32'),
(11, 30, 31, 'dawdawd', '2025-08-13 17:25:26'),
(12, 30, 31, 'dawdaw', '2025-08-13 17:25:29'),
(13, 30, 31, 'dwa', '2025-08-14 12:19:30'),
(14, 33, 31, 'dsahdashas', '2025-08-14 12:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `document_requests`
--

CREATE TABLE `document_requests` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `attorney_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('Requested','Submitted','Reviewed','Approved','Rejected','Cancelled') DEFAULT 'Requested',
  `attorney_comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_requests`
--

INSERT INTO `document_requests` (`id`, `case_id`, `attorney_id`, `client_id`, `title`, `description`, `due_date`, `status`, `attorney_comment`, `created_at`) VALUES
(1, 38, 31, 30, 'djashdjashd', 'dawjdhwadhawiodn awawooekavowpjeawevwaej', '2025-08-30', 'Approved', 'ok na', '2025-08-13 09:38:31'),
(2, 39, 31, 33, 'dadadwdawdad', 'dsadaddwdaw', '2025-08-21', 'Requested', NULL, '2025-08-14 04:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `document_request_comments`
--

CREATE TABLE `document_request_comments` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `attorney_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_request_comments`
--

INSERT INTO `document_request_comments` (`id`, `request_id`, `attorney_id`, `comment`, `created_at`) VALUES
(1, 1, 31, 'ok na', '2025-08-13 10:43:59');

-- --------------------------------------------------------

--
-- Table structure for table `document_request_files`
--

CREATE TABLE `document_request_files` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_request_files`
--

INSERT INTO `document_request_files` (`id`, `request_id`, `client_id`, `file_path`, `original_name`, `uploaded_at`) VALUES
(1, 1, 30, 'uploads/client/30_1755077965_CODE-SCRIPT.JS_REFREA.pdf', 'CODE-SCRIPT.JS_REFREA.pdf', '2025-08-13 09:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `employee_documents`
--

CREATE TABLE `employee_documents` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `form_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_documents`
--

INSERT INTO `employee_documents` (`id`, `file_name`, `file_path`, `category`, `uploaded_by`, `upload_date`, `form_number`) VALUES
(1, 'Affidavit_of_Loss (10).pdf', 'uploads/employee/1754551384_Affidavit_of_Loss (10).pdf', 'Forms', 21, '2025-08-07 15:23:04', 1),
(3, 'affidavit of loss.pdf', 'uploads/employee/1754551429_affidavit of loss.pdf', 'Forms', 21, '2025-08-07 15:23:49', 2),
(4, 'affidavit of loss pwd id.pdf', 'uploads/employee/1754551759_affidavit of loss pwd id.pdf', 'Forms', 21, '2025-08-07 15:29:19', 3),
(5, 'attorney_documents.zip', 'uploads/employee/1755147297_attorney_documents.zip', 'Forms', 32, '2025-08-14 12:54:57', 5);

-- --------------------------------------------------------

--
-- Table structure for table `employee_document_activity`
--

CREATE TABLE `employee_document_activity` (
  `id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `form_number` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_document_activity`
--

INSERT INTO `employee_document_activity` (`id`, `document_id`, `action`, `user_id`, `form_number`, `file_name`, `timestamp`, `user_name`) VALUES
(1, 1, 'Uploaded', 21, 1, 'Affidavit_of_Loss (10).pdf', '2025-08-07 07:23:04', 'dawdwa'),
(2, 2, 'Uploaded', 21, 3, 'affidavit of loss.pdf', '2025-08-07 07:23:32', 'dawdwa'),
(3, 3, 'Uploaded', 21, 2, 'affidavit of loss.pdf', '2025-08-07 07:23:49', 'dawdwa'),
(4, 2, 'Deleted', 21, 3, 'affidavit of loss.pdf', '2025-08-07 07:29:05', 'dawdwa'),
(5, 4, 'Uploaded', 21, 3, 'affidavit of loss pwd id.pdf', '2025-08-07 07:29:19', 'dawdwa'),
(6, 5, 'Uploaded', 32, 5, 'attorney_documents.zip', '2025-08-14 04:54:57', 'Castillo Refrea');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('admin','attorney','employee','client') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `user_type`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(2, 30, 'client', 'New Document Request', 'Your attorney requested: djashdjashd (Due: 2025-08-30)', 'warning', 0, '2025-08-13 09:38:31'),
(3, 31, 'attorney', 'Client Document Submitted', 'Client uploaded files for request: djashdjashd', 'success', 1, '2025-08-13 09:39:25'),
(4, 30, 'client', 'Document Request Approved', 'Your document request \'djashdjashd\' has been Approved: ok na', 'success', 0, '2025-08-13 10:43:59'),
(5, 33, 'client', 'New Document Request', 'Your attorney requested: dadadwdawdad (Due: 2025-08-21)', 'warning', 0, '2025-08-14 04:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_type` enum('admin','attorney','client','employee') NOT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(100) NOT NULL,
  `description` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `status` enum('success','failed','warning','info') DEFAULT 'success',
  `priority` enum('low','medium','high','critical') DEFAULT 'low',
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `additional_data` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`id`, `user_id`, `user_name`, `user_type`, `action`, `module`, `description`, `ip_address`, `status`, `priority`, `timestamp`) VALUES
(1, 20, 'Mar John Refrea', 'admin', 'User Login', 'Authentication', 'Admin user logged in successfully', '192.168.1.100', 'success', 'low', '2025-08-14 14:00:00'),
(2, 20, 'Mar John Refrea', 'admin', 'Document Upload', 'Document Management', 'Uploaded Affidavit of Loss document', '192.168.1.100', 'success', 'medium', '2025-08-14 13:00:00'),
(3, 31, 'Laica Castillo Refrea', 'attorney', 'User Login', 'Authentication', 'Attorney user logged in successfully', '192.168.1.101', 'success', 'low', '2025-08-14 12:00:00'),
(4, 31, 'Laica Castillo Refrea', 'attorney', 'Case Update', 'Case Management', 'Updated case details for Case #2024-001', '192.168.1.101', 'success', 'medium', '2025-08-14 11:00:00'),
(5, 32, 'Castillo Refrea', 'employee', 'User Login', 'Authentication', 'Employee user logged in successfully', '192.168.1.102', 'success', 'low', '2025-08-14 10:00:00'),
(6, 32, 'Castillo Refrea', 'employee', 'Document Generation', 'Document Generation', 'Generated Sworn Affidavit document', '192.168.1.102', 'success', 'medium', '2025-08-14 09:00:00'),
(7, 30, 'Yuhan, Nerfy Sheesh', 'client', 'User Login', 'Authentication', 'Client user logged in successfully', '192.168.1.103', 'success', 'low', '2025-08-14 08:00:00'),
(8, 30, 'Yuhan, Nerfy Sheesh', 'client', 'Document Download', 'Document Access', 'Downloaded case document', '192.168.1.103', 'success', 'low', '2025-08-14 07:00:00'),
(9, 33, 'Gab, Sheesh Sheesh', 'client', 'User Registration', 'User Management', 'New client account created', '192.168.1.104', 'success', 'medium', '2025-08-14 06:00:00'),
(10, 34, 'nawe=dawdw', 'attorney', 'User Registration', 'User Management', 'New attorney account created', '192.168.1.105', 'success', 'medium', '2025-08-14 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('admin','attorney','client','employee') DEFAULT 'client',
  `login_attempts` int(11) DEFAULT 0,
  `last_failed_login` timestamp NULL DEFAULT NULL,
  `account_locked` tinyint(1) DEFAULT 0,
  `lockout_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`id`, `name`, `profile_image`, `last_login`, `email`, `phone_number`, `password`, `user_type`, `login_attempts`, `last_failed_login`, `account_locked`, `lockout_until`, `created_at`) VALUES
(20, 'Mar John Refrea', 'uploads/admin/20_1755155087.png', '2025-08-14 15:18:14', 'marjohnrefrea123456@gmail.com', '09283262333', '$2y$10$yrs9n1Z/Nrq1d5XLvNihTOeRiq037s.NGo9wtXMjbNOkqlWyLOOwy', 'admin', 0, NULL, 0, NULL, '2025-08-06 11:26:01'),
(30, 'Yuhan, Nerfy Sheesh', NULL, '2025-08-14 12:37:16', 'yuhanerfy@gmail.com', '09283227232', '$2y$10$JCt2h2tNiAUusyQGFKkESueGKlkzPs3n9quz1ICWvqq9rynB0UltK', 'client', 0, NULL, 0, NULL, '2025-08-13 08:54:01'),
(31, 'Laica Castillo Refrea', NULL, '2025-08-14 12:50:30', 'marjohnrefrea1215@gmail.com', '13123213131', '$2y$10$i/n9XTQkitbj/gf/e2A3h.MyMxszjzF0XyZbdN5GTgtdU29DligyS', 'attorney', 0, NULL, 0, NULL, '2025-08-13 09:20:05'),
(32, 'Castillo Refrea', NULL, '2025-08-14 12:53:56', 'baomacky99@gmail.com', '94590543905', '$2y$10$8IQfHT.wcGzrtcMNY3X5mOuTGh8ha6mHif1y2vJ/8DnAE1byJj.WO', 'employee', 0, NULL, 0, NULL, '2025-08-13 09:21:40'),
(33, 'Gab, Sheesh Sheesh', NULL, '2025-08-14 15:14:09', 'jardingabrielleanne31@gmail.com', '23113421424', '$2y$10$A7cPPRFLZxYjkNf05G2RY.m2SHlP14joUVUaXr.wCKw3.n/LvM9ou', 'client', 0, NULL, 0, NULL, '2025-08-14 04:43:09'),
(34, 'nawe=dawdw', NULL, NULL, 'nelmiranda145@gmail.com', '12332323242', '$2y$10$HfOO7mON/PdDrBKq323JmObzN/jzGONVgRjSH882dEn4TQtwhavVi', 'attorney', 0, NULL, 0, NULL, '2025-08-14 05:01:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_documents`
--
ALTER TABLE `admin_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_user_type` (`user_type`),
  ADD KEY `idx_module` (`module`),
  ADD KEY `idx_timestamp` (`timestamp`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `admin_document_activity`
--
ALTER TABLE `admin_document_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_messages`
--
ALTER TABLE `admin_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `attorney_cases`
--
ALTER TABLE `attorney_cases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attorney_documents`
--
ALTER TABLE `attorney_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attorney_document_activity`
--
ALTER TABLE `attorney_document_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attorney_messages`
--
ALTER TABLE `attorney_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `case_schedules`
--
ALTER TABLE `case_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_cases`
--
ALTER TABLE `client_cases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_messages`
--
ALTER TABLE `client_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_id` (`case_id`),
  ADD KEY `attorney_id` (`attorney_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `document_request_comments`
--
ALTER TABLE `document_request_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `document_request_files`
--
ALTER TABLE `document_request_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_document_activity`
--
ALTER TABLE `employee_document_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_documents`
--
ALTER TABLE `admin_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_document_activity`
--
ALTER TABLE `admin_document_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin_messages`
--
ALTER TABLE `admin_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `attorney_cases`
--
ALTER TABLE `attorney_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `attorney_documents`
--
ALTER TABLE `attorney_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `attorney_document_activity`
--
ALTER TABLE `attorney_document_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `attorney_messages`
--
ALTER TABLE `attorney_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `case_schedules`
--
ALTER TABLE `case_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `client_cases`
--
ALTER TABLE `client_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `client_messages`
--
ALTER TABLE `client_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `document_request_comments`
--
ALTER TABLE `document_request_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `document_request_files`
--
ALTER TABLE `document_request_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_documents`
--
ALTER TABLE `employee_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_document_activity`
--
ALTER TABLE `employee_document_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_messages`
--
ALTER TABLE `admin_messages`
  ADD CONSTRAINT `admin_messages_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `user_form` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `user_form` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD CONSTRAINT `document_requests_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `attorney_cases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_requests_ibfk_2` FOREIGN KEY (`attorney_id`) REFERENCES `user_form` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_requests_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `user_form` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_request_comments`
--
ALTER TABLE `document_request_comments`
  ADD CONSTRAINT `document_request_comments_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `document_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_request_files`
--
ALTER TABLE `document_request_files`
  ADD CONSTRAINT `document_request_files_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `document_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_request_files_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `user_form` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_form` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
