-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2025 at 09:46 AM
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
(5, 'pop', 'term', 'Admin', '2025-02-11 08:06:17', '2025-02-11 08:06:30');

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
  `date_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `course_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `start_date`, `end_date`, `course_description`, `date_created`, `date_updated`, `course_code`) VALUES
(1, '4gt4t4g', '2025-02-12', '2025-02-28', 'trvbrt', '2025-02-11 07:21:04', '2025-02-11 07:21:04', 'tvtrv');

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
(10, 1, 2, 'ended', '2025-01-30 18:10:20', '2025-01-30 18:10:20'),
(11, 1, 1, 'start', '2025-02-11 07:21:33', '2025-02-11 07:21:33');

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
  `department` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `bio` text DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `email`, `phone`, `course`, `student_number`, `department`, `password_hash`, `bio`) VALUES
(1, 'asdasd', 'asdasdasd@sasdasdasd', 'dasda', 'dasdas', '', 'dasd', '', ''),
(3, 'noorul', 'noorul@student.com', '12345678', 'cdf', '123456x', 'iit', '', '');

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
('2305021d', 'AAI', 'CYFUN', 'A', 99.00, '2025-02-02', '2025-01-29'),
('123456', 'cdf', 'loma', 'A', 88.00, '2025-02-11', '2025-01-27');

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
(24, 'Cat', '$2y$10$chnTtGjCa9J5sKE2G3xRR.uDdQ8Wmtnw2Xr2v8NPAWfp4JdwTGc1e', 'cat@admin.com', 'Admin', '2025-02-09 13:37:04', '2025-02-09 13:37:04', NULL, NULL),
(25, 'Capy', '$2y$10$TFlh5sbvYCyNBvZVQV62o.GMhJBzQApSv.j1vQxrJVblS6fHOL29e', 'capy@faculty.com', 'Faculty', '2025-02-09 13:37:35', '2025-02-09 13:37:35', NULL, NULL),
(26, 'Snake', '$2y$10$gd1bSiTcGXyf1EGCH4163uBHTN/BVa4aeY1BR.3L9HuHpmHIjRvZu', 'snake@student.com', 'Student', '2025-02-09 13:38:33', '2025-02-09 13:38:53', NULL, NULL);

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
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `name_2` (`name`),
  ADD UNIQUE KEY `name_3` (`name`,`email`,`student_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_3` (`email`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_assignments`
--
ALTER TABLE `course_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
