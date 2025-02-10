-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2025 at 04:09 PM
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
-- Database: `swap`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class_type` enum('semester','term') NOT NULL,
  `created_by` enum('Admin','Faculty') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `class_type`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'wdwd', 'term', 'Admin', '2025-01-28 06:45:27', '2025-01-28 06:50:01'),
(2, 'dwd', 'semester', 'Admin', '2025-01-28 06:46:45', '2025-01-28 06:46:45'),
(4, '55', 'semester', 'Admin', '2025-01-28 06:51:20', '2025-01-28 06:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `course_description` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_assignments`
--

CREATE TABLE `course_assignments` (
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `status` enum('start','in-progress','ended') NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_assignments`
--

INSERT INTO `course_assignments` (`assignment_id`, `student_id`, `course_id`, `status`, `assigned_at`, `updated_at`) VALUES
(6, 1, 2, 'start', '2025-01-30 18:05:19', '2025-01-30 18:05:19'),
(7, 1, 2, 'in-progress', '2025-01-30 18:05:21', '2025-01-30 18:05:21'),
(8, 1, 2, 'ended', '2025-01-30 18:05:23', '2025-01-30 18:05:23'),
(9, 1, 2, 'ended', '2025-01-30 18:07:53', '2025-01-30 18:07:53'),
(10, 1, 2, 'ended', '2025-01-30 18:10:20', '2025-01-30 18:10:20');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `student_number` varchar(20) NOT NULL,
  `department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `email`, `phone`, `course`, `student_number`, `department`) VALUES
(1, 'asdasd', 'asdasdasd@sasdasdasd', 'dasda', 'dasdas', '', 'dasd');

-- --------------------------------------------------------

--
-- Table structure for table `student_grades`
--

CREATE TABLE `student_grades` (
  `student_id` varchar(10) NOT NULL,
  `course` varchar(100) NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `grade` varchar(5) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `date_recorded` date DEFAULT NULL,
  `course_end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_grades`
--

INSERT INTO `student_grades` (`student_id`, `course`, `module`, `grade`, `score`, `date_recorded`, `course_end_date`) VALUES
('2305021d', 'AAI', 'CYFUN', 'A', 99.00, '2025-02-02', '2025-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('Admin','Faculty','Student') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `email`, `role`, `created_at`, `updated_at`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'Hisham', '$2y$10$tKH8b0bx.vVGImoNUfjm9uIn9iSx9QV9F1jRhsoHJ./UJePHDM31C', 'hisham@hisham', 'Admin', '2025-01-28 07:09:49', '2025-01-28 07:09:49', NULL, NULL),
(2, 'hamza', '$2y$10$AOQ3ds.gYghralFcwZvT0.oiGLwCR4ojJegoIRSS4ZCezN7Pb4rIa', 'hamza@hamza', 'Student', '2025-01-28 07:12:42', '2025-01-28 07:51:43', 'f7167b625ef154ded52f13bf406eded1', 1738054303),
(3, 'sofia', '$2y$10$L5OdsKgHSPvIbvtFVK2y2.ic78jyxJVxF65BBDXAXY93xZ4ikGXBu', 'sofia@sofia', 'Admin', '2025-01-28 07:33:28', '2025-01-28 07:33:28', NULL, NULL),
(4, 'meow', '$2y$10$mQxMkK8AEH//FI9QO/xgtOWiklT1Kb.IclCy8zOgEfKLHBRJAVbia', 'meow@gmail.com', 'Faculty', '2025-01-31 07:48:59', '2025-01-31 07:48:59', NULL, NULL),
(5, 'stud', '$2y$10$JyjEMUR2G5ATp7wYbRgQB.Vglr9SIRPIYkRCTu93tAf2jzJ62i93e', 'stud@gmail.com', 'Student', '2025-01-31 08:29:25', '2025-01-31 08:29:25', NULL, NULL),
(9, 'hamzah', '$2y$10$BgepWbTJhNLiRE2hADdmQuAeOL4w4Yv8580uPVJAkCjXDDM/2sqbe', 'hamzah@gmail.com', 'Faculty', '2025-02-02 11:52:07', '2025-02-02 11:52:07', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `course_assignments`
--
ALTER TABLE `course_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_assignments`
--
ALTER TABLE `course_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
