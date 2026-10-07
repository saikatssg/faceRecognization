-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2026 at 09:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emp_payroll`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `att_id` int(11) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `att_date` date NOT NULL,
  `status` enum('Present','Absent','Leave','Half') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `dept_id` varchar(30) NOT NULL,
  `dept` int(11) NOT NULL,
  `user_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dept_master`
--

CREATE TABLE `dept_master` (
  `dept` int(11) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `abbr` varchar(30) NOT NULL,
  `flag` int(11) NOT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dept_master`
--

INSERT INTO `dept_master` (`dept`, `dept_name`, `abbr`, `flag`, `created_on`) VALUES
(1, 'Information Technology', 'IT', 1, '2026-07-27'),
(2, 'Human Resources', 'HR', 1, '2026-07-27'),
(3, 'Research and Development', 'R&D', 1, '2026-07-27'),
(4, 'Finance and Accounting', 'F&A', 1, '2026-07-27'),
(5, 'Sales and Marketing', 'S&M', 1, '2026-07-27'),
(6, 'Customer Support', 'CST', 1, '2026-07-27'),
(7, ' Quality Assurance', 'QA', 1, '2026-07-27'),
(8, 'Operations and Logistics', 'O&L', 1, '2026-07-27'),
(9, 'Legal and Compliance', 'L&C', 1, '2026-07-27'),
(10, 'Product Management', 'PM', 1, '2026-07-27'),
(11, 'Administrator', 'Admin', 1, '2026-07-27');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `emp_id` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `join_date` date NOT NULL,
  `dob` date NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `gender` int(11) DEFAULT 0,
  `city` varchar(30) DEFAULT NULL,
  `photo` varchar(225) DEFAULT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gender_master`
--

CREATE TABLE `gender_master` (
  `gid` int(11) NOT NULL,
  `gname` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gender_master`
--

INSERT INTO `gender_master` (`gid`, `gname`) VALUES
(1, 'Male'),
(2, 'Female'),
(3, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `payroll_id` varchar(30) NOT NULL,
  `emp_id` varchar(50) NOT NULL,
  `dept_id` varchar(30) NOT NULL,
  `sal_id` INT NOT NULL,
  `da` decimal(10,2) NOT NULL,
  `hra` decimal(10,2) NOT NULL,
  `ta` decimal(10,2) NOT NULL,
  `pf` decimal(10,2) NOT NULL,
  `ptax` decimal(10,2) NOT NULL,
  `net_salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_master`
--

CREATE TABLE `user_master` (
  `uid` varchar(10) DEFAULT NULL,
  `user_desc` varchar(25) NOT NULL,
  `user_abbr` varchar(10) NOT NULL,
  `user_type` int(11) NOT NULL,
  `user_active` int(11) NOT NULL,
  `created_by` varchar(10) NOT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`dept_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payroll_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
