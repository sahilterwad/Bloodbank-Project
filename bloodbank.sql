-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 09:07 PM
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
-- Database: `bloodbank`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_inventory`
--

CREATE TABLE `blood_inventory` (
  `id` int(11) NOT NULL,
  `blood_type` varchar(10) NOT NULL,
  `available_units` int(11) DEFAULT 0,
  `reserved_units` int(11) DEFAULT 0,
  `expired_units` int(11) DEFAULT 0,
  `donor_id` int(11) NOT NULL,
  `collection_date` date NOT NULL,
  `status` enum('Available','Reserved','Expired') NOT NULL,
  `expiry_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `blood_inventory`
--

INSERT INTO `blood_inventory` (`id`, `blood_type`, `available_units`, `reserved_units`, `expired_units`, `donor_id`, `collection_date`, `status`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, 'A+', 0, 0, 0, 2, '2024-11-13', 'Available', '2024-12-25', '2024-11-13 09:45:18', '2024-11-13 09:45:18'),
(2, 'A+', 0, 0, 0, 5, '2024-11-13', 'Available', '2024-12-25', '2024-11-13 09:45:36', '2024-11-13 09:45:36'),
(3, 'o+', 0, 0, 0, 2, '2024-11-13', 'Available', '2024-12-25', '2024-11-13 09:46:25', '2024-11-13 09:46:25'),
(4, 'o+', 0, 0, 0, 2, '2024-11-13', 'Available', '2024-12-25', '2024-11-13 09:53:39', '2024-11-13 09:53:39'),
(5, 'A+', 0, 0, 0, 1, '2024-11-13', 'Available', '2024-12-25', '2024-11-13 10:40:18', '2024-11-13 10:40:18'),
(6, 'A+', 0, 0, 0, 1, '2024-11-14', 'Available', '2024-12-26', '2024-11-14 06:03:31', '2024-11-14 06:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `request_id` int(11) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `donor_name` varchar(100) NOT NULL,
  `blood_type` varchar(5) NOT NULL,
  `request_status` enum('Pending','Cancelled','Accepted') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`request_id`, `patient_name`, `donor_name`, `blood_type`, `request_status`) VALUES
(1, 'Unknown', 'Alice Johnson', 'A+', 'Accepted'),
(2, 'Unknown', 'Alice Johnson', 'A+', 'Cancelled'),
(3, 'Unknown', 'David Brown', 'AB+', 'Cancelled'),
(4, 'Unknown', 'David Brown', 'AB+', 'Cancelled'),
(5, 'Unknown', 'Carol White', 'B+', 'Cancelled'),
(6, 'Unknown', 'Carol White', 'B+', 'Cancelled'),
(7, 'Emma Clark', 'John Doe', 'O+', 'Accepted'),
(8, 'Subodh Ambekar', 'Carol White', 'B+', 'Accepted'),
(9, 'John Doe', 'Alice Johnson', 'A+', 'Accepted'),
(10, 'KHUSHI CHARI', 'Alice Johnson', 'A+', 'Cancelled'),
(11, 'John Doe', 'Alice Johnson', 'A+', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `donor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `blood_type` varchar(3) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `last_donation_date` date DEFAULT NULL,
  `date_registered` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`donor_id`, `name`, `blood_type`, `contact`, `email`, `location`, `last_donation_date`, `date_registered`) VALUES
(1, 'John Doe', 'O+', '1221221212', 'johndoe@mail.com', '', NULL, '2024-11-13 06:18:33'),
(2, 'Alice Johnson', 'A+', '555-1234', 'alice.j@example.com', 'New York', '2024-10-10', '2024-11-13 06:49:52'),
(3, 'Bob Smith', 'O-', '555-5678', 'bob.s@example.com', 'Los Angeles', '2024-09-12', '2024-11-13 06:49:52'),
(4, 'Carol White', 'B+', '555-8765', 'carol.w@example.com', 'Chicago', '2024-08-05', '2024-11-13 06:49:52'),
(5, 'David Brown', 'AB+', '555-4321', 'david.b@example.com', 'Houston', '2024-07-20', '2024-11-13 06:49:52'),
(6, 'Emily Davis', 'O+', '555-6789', 'emily.d@example.com', 'Phoenix', '2024-09-30', '2024-11-13 06:49:52'),
(7, 'John Doe', 'O+', '1221221212', 'johndoe@mail.com', '', NULL, '2024-11-14 05:59:07'),
(8, 'devshree chari', 'A+', '8778876627', 'devshree09chari@gmail.com', '', NULL, '2025-04-02 10:56:47');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `hospital` varchar(255) NOT NULL,
  `emergency_status` enum('Yes','No') NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `blood_type` enum('O-','O+','A-','A+','B-','B+','AB-','AB+') NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `name`, `contact`, `hospital`, `emergency_status`, `diagnosis`, `blood_type`, `registration_date`) VALUES
(1, 'KHUSHI CHARI', '123456789', 'ABC', '', 'Blood loss due to falling from three steps', 'A+', '2024-11-12 07:36:15'),
(2, 'KHUSHI CHARI', '1234567890', 'ABC', '', 'Blood loss due to falling from three steps', 'A+', '2024-11-12 07:39:45'),
(3, 'Mr unknown', '024681012', 'BCD', '', 'shot on hand', 'O-', '2024-11-12 09:16:12'),
(4, 'Michael Jackson', '8778876627', 'Pcce ', '', 'overuse of drugs', 'AB-', '2024-11-12 10:40:22'),
(5, 'jOWN', '23322112672', 'PCCE', '', 'head trauma', 'AB+', '2024-11-12 10:42:12'),
(6, 'Sophie Turner', '555-1234', 'City Hospital', '', 'Severe Anemia', 'A+', '2024-10-31 18:30:00'),
(7, 'James Evans', '555-5678', 'St. Mary\'s Hospital', '', 'Post-surgery Recovery', 'O-', '2024-10-19 18:30:00'),
(8, 'Olivia Martinez', '555-8765', 'General Hospital', '', 'Chronic Kidney Disease', 'B+', '2024-09-14 18:30:00'),
(9, 'William Harris', '555-4321', 'HealthCare Clinic', '', 'Heart Surgery', 'AB+', '2024-08-21 18:30:00'),
(10, 'Emma Clark', '555-6789', 'Central Hospital', '', 'Appendicitis', 'O+', '2024-09-29 18:30:00'),
(11, 'Subodh Ambekar', '123456789', 'PCCE', '', 'Blood Loss', 'B+', '2024-11-13 09:24:18'),
(12, 'Subodh Ambekar', '123456789', 'PCCE', '', 'head trauma', 'AB-', '2024-11-13 11:11:37');

-- --------------------------------------------------------

--
-- Table structure for table `successful_matches`
--

CREATE TABLE `successful_matches` (
  `match_id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `blood_type` varchar(10) NOT NULL,
  `match_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `successful_matches`
--

INSERT INTO `successful_matches` (`match_id`, `patient_name`, `donor_name`, `blood_type`, `match_date`) VALUES
(1, 'John Doe', 'Alice Johnson', 'A+', '2024-11-13 10:27:12'),
(2, 'Subodh Ambekar', 'Carol White', 'B+', '2024-11-13 11:13:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_blood_type` (`blood_type`),
  ADD KEY `idx_donor_id` (`donor_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_expiration_date` (`expiry_date`);

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`donor_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `successful_matches`
--
ALTER TABLE `successful_matches`
  ADD PRIMARY KEY (`match_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `successful_matches`
--
ALTER TABLE `successful_matches`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  ADD CONSTRAINT `blood_inventory_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
