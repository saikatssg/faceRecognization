-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2026 at 08:05 AM
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
-- Database: `emp`
--

-- --------------------------------------------------------

--
-- Table structure for table `dept_master`
--

CREATE TABLE `dept_master` (
  `deptid` int(11) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `abbr` varchar(30) NOT NULL,
  `flag` int(11) NOT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dept_master`
--

INSERT INTO `dept_master` (`deptid`, `dept_name`, `abbr`, `flag`, `created_on`) VALUES
(1, 'Physics', 'PHYS', 1, '2026-07-27'),
(2, 'Mathematics', 'MATH', 1, '2026-07-27'),
(3, 'Chemistry', 'CHEM', 1, '2026-07-27'),
(4, 'Bio Science', 'BIOS', 1, '2026-07-27'),
(5, 'Computer Science', 'CSC', 1, '2026-07-27'),
(6, 'Geography', 'GEOG', 1, '2026-07-27'),
(7, 'Bengali', 'BNGA', 1, '2026-07-27'),
(8, 'English', 'ENGL', 1, '2026-07-27'),
(9, 'Economics ', 'ECOG', 1, '2026-07-27'),
(10, 'Psychology', 'PSYC', 1, '2026-07-27');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `empid` varchar(30) NOT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `deptid` int(11) NOT NULL,
  `password` varchar(20) NOT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_on` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`empid`, `fname`, `lname`, `username`, `deptid`, `password`, `dob`, `email`, `phone`, `gender`, `city`, `photo`, `created_on`) VALUES
('EMP/BIOS/072026/001', 'Saikat', 'Sengupta', 'saikat', 4, 'Saikat@123', '2000-07-14', 'saikat@gmail.com', '9874563210', 1, 'kolkata', '../others/uploads/PASSPORT.jpg', '2026-07-27'),
('EMP/MATH/072026/001', 'Srija', 'Mondal', 'srija', 2, 'SRija@123', '2005-08-15', 'srija@gmail.com', '9876543210', 2, 'Kolkata', '../others/uploads/card1.jpg', '2026-07-25'),
('EMP/MATH/072026/002', 'Tanuska', 'Misra', 'tanuska', 2, 'Tanuska@123', '2007-01-06', 'tanu@gmail.com', '7986543012', 2, 'Pune', '../others/uploads/card2.jpg', '2026-07-25'),
('EMP/MATH/072026/003', 'Swagata', 'Dey', 'swagata', 2, 'Swagata@123', '2006-09-22', 'swagata@gmail.com', '9874565789', 2, 'mumbai', '../others/uploads/photo.jpeg', '2026-07-28');

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`empid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
