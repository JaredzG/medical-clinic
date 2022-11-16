-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2022 at 06:30 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `med_clinic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Address`
--

CREATE TABLE `Address` (
  `address_ID` int(11) NOT NULL,
  `street_address` varchar(45) NOT NULL,
  `apt_num` varchar(20) DEFAULT NULL,
  `city` varchar(20) NOT NULL,
  `state` varchar(20) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `office_add` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Address`
--

INSERT INTO `Address` (`address_ID`, `street_address`, `apt_num`, `city`, `state`, `zip_code`, `office_add`, `deleted_flag`) VALUES
(1, '1 Clinic St', NULL, 'Houston', 'TX', '12345', 1, 0),
(2, '2 Clinic St', NULL, 'Houston', 'TX', '12345', 1, 0),
(3, '1 Doc St', '', 'Houston', 'TX', '12345', 0, 0),
(4, '1 Nurse St', '', 'Houston', 'TX', '12345', 0, 0),
(5, '1 Rec St', '', 'Houston', 'TX', '12345', 0, 0),
(6, '1 Pat St', '', 'Houston', 'TX', '12345', 0, 0),
(7, '2 Doc St', '', 'Houston', 'TX', '12345', 0, 0),
(8, '2 Patient St', '', 'Houston', 'TX', '12345', 0, 1),
(11, '3 Clinic St', NULL, 'Houston', 'TX', '12345', 1, 1),
(12, '3 Doc St', NULL, 'Houston', 'TX', '12345', 0, 1),
(13, 'kdsjf', '', 'haosdifh', 'TN', '23462', 0, 1),
(14, 'asdkjf', 'a', 'sdfnd', 'LA', '23432', 0, 1),
(15, 'd', NULL, 'k', 'OH', '23432', 0, 1),
(16, 'lkjl', NULL, 'nos', 'PA', '23422', 0, 0),
(17, 'aksdjf', NULL, 'ndnlsl', 'ID', '32462', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Appointment`
--

CREATE TABLE `Appointment` (
  `app_ID` int(11) NOT NULL,
  `date_time` datetime NOT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `office_ID` int(11) NOT NULL,
  `doctor_ID` int(11) NOT NULL,
  `patient_ID` int(11) NOT NULL,
  `payment_ID` int(11) DEFAULT NULL,
  `receptionist_ID` int(11) DEFAULT NULL,
  `status_flag` int(11) NOT NULL DEFAULT 0
) ;

--
-- Dumping data for table `Appointment`
--

INSERT INTO `Appointment` (`app_ID`, `date_time`, `reason`, `office_ID`, `doctor_ID`, `patient_ID`, `payment_ID`, `receptionist_ID`, `status_flag`) VALUES
(1, '2022-12-01 00:00:00', 'res1', 2, 2, 1, 2, 1, 2),
(5, '2022-12-02 00:00:00', 'res2', 1, 1, 1, 4, 1, 2),
(6, '2022-12-23 03:00:00', 'reasonlol', 1, 1, 1, 6, 1, 2),
(7, '2022-12-17 04:04:00', 'reason for app', 1, 1, 1, 8, 1, 2),
(8, '2022-12-12 06:00:00', 'reasonapp', 2, 2, 1, 10, 1, 2),
(9, '2022-12-18 05:03:00', 'adfkjsdjf', 1, 1, 1, 12, 1, 2),
(10, '2022-12-20 04:06:00', 'newreason', 1, 1, 1, 14, 1, 2),
(11, '2022-12-12 15:04:00', 'Reasonadfjadjjdjjdj', 2, 2, 1, NULL, 1, 2);

--
-- Triggers `Appointment`
--
DELIMITER $$
CREATE TRIGGER `after_completed_appointment` BEFORE UPDATE ON `Appointment` FOR EACH ROW BEGIN
IF new.status_flag = 2 THEN
	IF new.payment_ID IS NULL THEN
		INSERT INTO Transaction (patient_ID, app_ID, amount)
		VALUES (new.patient_ID, new.app_ID, 50.00);
	END IF;
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_appointment_with_specialist` BEFORE INSERT ON `Appointment` FOR EACH ROW BEGIN
 IF (new.doctor_ID NOT IN (SELECT specialist_ID FROM Referral WHERE (pat_ID = new.patient_ID AND deleted_flag = 0))
	AND new.doctor_ID <> (SELECT prim_doc_ID FROM Patient WHERE Patient.patient_ID = new.patient_ID)) THEN
		SET new.patient_ID = NULL;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Department`
--

CREATE TABLE `Department` (
  `department_number` int(11) NOT NULL,
  `dep_name` varchar(45) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Department`
--

INSERT INTO `Department` (`department_number`, `dep_name`, `deleted_flag`) VALUES
(1, 'Family Care', 0),
(2, 'Cardiology', 0),
(3, 'Orthopedics', 0),
(4, 'Radiology', 0),
(5, 'Medicine', 0),
(6, 'extra', 1),
(8, 'Neuro', 0),
(9, 'Physical Therapy', 0),
(10, 'blah', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Doctor`
--

CREATE TABLE `Doctor` (
  `doc_ID` int(11) NOT NULL,
  `ssn` int(11) NOT NULL,
  `dep_num` int(11) NOT NULL DEFAULT 1,
  `f_name` varchar(45) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(45) NOT NULL,
  `address_ID` int(11) NOT NULL,
  `credentials` varchar(45) DEFAULT NULL,
  `sex` char(1) NOT NULL,
  `doc_user` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ;

--
-- Dumping data for table `Doctor`
--

INSERT INTO `Doctor` (`doc_ID`, `ssn`, `dep_num`, `f_name`, `m_name`, `l_name`, `address_ID`, `credentials`, `sex`, `doc_user`, `deleted_flag`) VALUES
(1, 1, 1, 'doc1', '', 'doclast1', 3, 'blah', 'M', 2, 0),
(2, 5, 2, 'doc2', '', 'doclast', 7, 'm.d. Credentials', 'M', 6, 0),
(3, 10, 5, 'doc3', NULL, 'doclast', 12, NULL, 'M', 10, 1),
(4, 1328472, 3, 'doc3', '', 'doclast', 13, 'Credentials', 'M', 11, 1),
(5, 32423, 3, 'doc4', NULL, 'doclast', 14, 'asdfaf', 'M', 12, 1),
(6, 234234234, 1, 'doc5', NULL, 'doclast', 15, 'alol', 'M', 13, 1),
(7, 83829992, 1, 'doc6', NULL, 'jjlkjlj', 16, 'lol', 'M', 14, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Doctor_For_Patient`
--

CREATE TABLE `Doctor_For_Patient` (
  `doc_ID` int(11) NOT NULL,
  `pat_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Doctor_For_Patient`
--

INSERT INTO `Doctor_For_Patient` (`doc_ID`, `pat_ID`, `deleted_flag`) VALUES
(1, 1, 0),
(1, 3, 1),
(1, 4, 0),
(2, 1, 0),
(4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Doctor_Maintains_Medical_Record`
--

CREATE TABLE `Doctor_Maintains_Medical_Record` (
  `pat_ID` int(11) NOT NULL,
  `doc_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Doctor_Maintains_Medical_Record`
--

INSERT INTO `Doctor_Maintains_Medical_Record` (`pat_ID`, `doc_ID`, `deleted_flag`) VALUES
(1, 1, 0),
(4, 1, 0),
(1, 2, 0),
(1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Doctor_Prescribes_Medicine_To_Patient`
--

CREATE TABLE `Doctor_Prescribes_Medicine_To_Patient` (
  `doc_ID` int(11) NOT NULL,
  `med_ID` int(11) NOT NULL,
  `pat_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Doctor_Prescribes_Medicine_To_Patient`
--

INSERT INTO `Doctor_Prescribes_Medicine_To_Patient` (`doc_ID`, `med_ID`, `pat_ID`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Doctor_Works_In_Office`
--

CREATE TABLE `Doctor_Works_In_Office` (
  `office_ID` int(11) NOT NULL,
  `doctor_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Doctor_Works_In_Office`
--

INSERT INTO `Doctor_Works_In_Office` (`office_ID`, `doctor_ID`, `deleted_flag`) VALUES
(1, 1, 0),
(1, 6, 1),
(1, 7, 0),
(2, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Emergency_Contact`
--

CREATE TABLE `Emergency_Contact` (
  `patient_ID` int(11) NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(20) NOT NULL,
  `relationship` varchar(20) DEFAULT NULL,
  `phone_num` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ;

--
-- Dumping data for table `Emergency_Contact`
--

INSERT INTO `Emergency_Contact` (`patient_ID`, `f_name`, `m_name`, `l_name`, `relationship`, `phone_num`, `sex`, `deleted_flag`) VALUES
(1, 'em1', '', 'emlast', 'parent', '1234567890', 'M', 0),
(3, 'em2', '', 'emlast', 'spouse', '1234567890', 'M', 1),
(4, 'em3', NULL, 'patlast', 'parent', '32492722', 'M', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Medical_Record`
--

CREATE TABLE `Medical_Record` (
  `pat_ID` int(11) NOT NULL,
  `allergies` text DEFAULT NULL,
  `diagnoses` text DEFAULT NULL,
  `immunizations` text DEFAULT NULL,
  `progress` text DEFAULT NULL,
  `treatment_plan` text DEFAULT NULL,
  `inch_height` int(11) DEFAULT NULL,
  `pound_weight` int(11) DEFAULT NULL,
  `b_date` date NOT NULL,
  `ethnicity` varchar(20) NOT NULL,
  `race` varchar(20) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Medical_Record`
--

INSERT INTO `Medical_Record` (`pat_ID`, `allergies`, `diagnoses`, `immunizations`, `progress`, `treatment_plan`, `inch_height`, `pound_weight`, `b_date`, `ethnicity`, `race`, `deleted_flag`) VALUES
(1, 'Peanut', 'Diabetes', 'Influenza', 'No illicit drug use.', 'Take insulin.', 72, 170, '2022-01-01', 'hl', 'aian', 0),
(3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-01-01', 'hl', 'aian', 1),
(4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2000-01-01', 'hl', 'aian', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Medical_Record_Contains_Medicine`
--

CREATE TABLE `Medical_Record_Contains_Medicine` (
  `pat_ID` int(11) NOT NULL,
  `med_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Medical_Record_Contains_Medicine`
--

INSERT INTO `Medical_Record_Contains_Medicine` (`pat_ID`, `med_ID`, `deleted_flag`) VALUES
(1, 1, 1),
(1, 2, 0),
(1, 3, 0),
(1, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Medicine`
--

CREATE TABLE `Medicine` (
  `med_ID` int(11) NOT NULL,
  `brand` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Medicine`
--

INSERT INTO `Medicine` (`med_ID`, `brand`, `name`, `description`, `deleted_flag`) VALUES
(1, 'Humalog', 'Lispro', 'Rapid-acting Insulin.', 0),
(2, 'alkdsjf', 'asdjfd', 'adsfjadsljf', 0),
(3, 'coolguy', 'joe', 'focus', 0),
(4, 'drug1', 'drug1', 'drug feel good', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Nurse`
--

CREATE TABLE `Nurse` (
  `nurse_ID` int(11) NOT NULL,
  `ssn` int(11) NOT NULL,
  `dep_num` int(11) DEFAULT NULL,
  `f_name` varchar(20) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL,
  `nurse_user` int(11) NOT NULL,
  `registered` tinyint(1) NOT NULL DEFAULT 0,
  `address_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ;

--
-- Dumping data for table `Nurse`
--

INSERT INTO `Nurse` (`nurse_ID`, `ssn`, `dep_num`, `f_name`, `m_name`, `l_name`, `sex`, `nurse_user`, `registered`, `address_ID`, `deleted_flag`) VALUES
(1, 2, 1, 'nurse1', '', 'nurselast', 'M', 3, 1, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Nurse_Works_In_Office`
--

CREATE TABLE `Nurse_Works_In_Office` (
  `office_ID` int(11) NOT NULL,
  `nurse_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Nurse_Works_In_Office`
--

INSERT INTO `Nurse_Works_In_Office` (`office_ID`, `nurse_ID`, `deleted_flag`) VALUES
(1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Nurse_Works_On_Appointment`
--

CREATE TABLE `Nurse_Works_On_Appointment` (
  `nurse_ID` int(11) NOT NULL,
  `appointment_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Nurse_Works_On_Appointment`
--

INSERT INTO `Nurse_Works_On_Appointment` (`nurse_ID`, `appointment_ID`, `deleted_flag`) VALUES
(1, 5, 0),
(1, 6, 0),
(1, 7, 0),
(1, 9, 0),
(1, 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Nurse_Works_With_Doctor`
--

CREATE TABLE `Nurse_Works_With_Doctor` (
  `nurse_ID` int(11) NOT NULL,
  `doc_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Nurse_Works_With_Doctor`
--

INSERT INTO `Nurse_Works_With_Doctor` (`nurse_ID`, `doc_ID`, `deleted_flag`) VALUES
(1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Office`
--

CREATE TABLE `Office` (
  `office_ID` int(11) NOT NULL,
  `dep_number` int(11) DEFAULT NULL,
  `address_ID` int(11) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Office`
--

INSERT INTO `Office` (`office_ID`, `dep_number`, `address_ID`, `phone_number`, `deleted_flag`) VALUES
(1, 1, 1, '1234567890', 0),
(2, 2, 1, '1234567890', 0),
(3, 1, 2, '1234567890', 0),
(4, 2, 2, '1234567890', 0),
(5, 4, 2, '1234567890', 1),
(6, 4, 2, '1234567890', 0),
(7, 9, 1, '4440903214', 0),
(8, 10, 1, '3102223333', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Patient`
--

CREATE TABLE `Patient` (
  `patient_ID` int(11) NOT NULL,
  `ssn` int(11) NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL,
  `pat_user` int(11) NOT NULL,
  `address_ID` int(11) NOT NULL,
  `clinic_ID` int(11) NOT NULL,
  `prim_doc_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ;

--
-- Dumping data for table `Patient`
--

INSERT INTO `Patient` (`patient_ID`, `ssn`, `f_name`, `m_name`, `l_name`, `sex`, `pat_user`, `address_ID`, `clinic_ID`, `prim_doc_ID`, `deleted_flag`) VALUES
(1, 4, 'pat11', '', 'patlast', 'M', 5, 6, 1, 1, 0),
(3, 9, 'pat2', NULL, 'patlast', 'M', 9, 8, 1, 1, 1),
(4, 9233, 'pat2', NULL, 'patlast', 'M', 15, 17, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Receptionist`
--

CREATE TABLE `Receptionist` (
  `rec_ID` int(11) NOT NULL,
  `ssn` int(11) NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL,
  `rec_user` int(11) NOT NULL,
  `address_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ;

--
-- Dumping data for table `Receptionist`
--

INSERT INTO `Receptionist` (`rec_ID`, `ssn`, `f_name`, `m_name`, `l_name`, `sex`, `rec_user`, `address_ID`, `deleted_flag`) VALUES
(1, 3, 'rec1', '', 'reclast', 'M', 4, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Referral`
--

CREATE TABLE `Referral` (
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `primary_ID` int(11) NOT NULL,
  `pat_ID` int(11) NOT NULL,
  `specialist_ID` int(11) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Referral`
--

INSERT INTO `Referral` (`date_time`, `primary_ID`, `pat_ID`, `specialist_ID`, `deleted_flag`) VALUES
('2022-11-10 02:12:55', 1, 1, 2, 1),
('2022-11-15 19:28:53', 1, 1, 2, 1),
('2022-11-15 20:05:06', 1, 1, 2, 1),
('2022-11-15 20:30:43', 1, 1, 2, 1),
('2022-11-15 21:22:17', 1, 1, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Transaction`
--

CREATE TABLE `Transaction` (
  `transaction_ID` int(11) NOT NULL,
  `patient_ID` int(11) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `app_ID` int(11) NOT NULL,
  `amount` decimal(6,2) DEFAULT NULL,
  `payment_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Transaction`
--

INSERT INTO `Transaction` (`transaction_ID`, `patient_ID`, `transaction_date`, `app_ID`, `amount`, `payment_ID`) VALUES
(1, 1, '2022-11-10 08:15:38', 1, '50.00', 2),
(2, 1, '2022-11-10 08:25:43', 1, '-50.00', 2),
(3, 1, '2022-11-10 09:29:32', 5, '50.00', 4),
(4, 1, '2022-11-10 09:30:00', 5, '-50.00', 4),
(5, 1, '2022-11-12 20:43:07', 6, '50.00', 6),
(6, 1, '2022-11-16 01:32:36', 6, '-50.00', 6),
(7, 1, '2022-11-16 01:34:25', 7, '50.00', 8),
(8, 1, '2022-11-16 01:35:37', 7, '-50.00', 8),
(9, 1, '2022-11-16 02:12:10', 8, '50.00', 10),
(10, 1, '2022-11-16 02:21:05', 8, '-50.00', 10),
(11, 1, '2022-11-16 02:36:33', 9, '50.00', 12),
(12, 1, '2022-11-16 03:12:36', 9, '-50.00', 12),
(13, 1, '2022-11-16 03:27:18', 10, '50.00', 14),
(14, 1, '2022-11-16 03:29:54', 10, '-50.00', 14),
(15, 1, '2022-11-16 03:34:34', 11, '50.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `User_Account`
--

CREATE TABLE `User_Account` (
  `user_ID` int(11) NOT NULL,
  `username` varchar(35) NOT NULL,
  `user_pass` varchar(100) NOT NULL,
  `user_role` varchar(20) NOT NULL DEFAULT 'patient',
  `user_phone_num` varchar(20) NOT NULL,
  `user_email_address` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_flag` tinyint(1) NOT NULL DEFAULT 0
) ;

--
-- Dumping data for table `User_Account`
--

INSERT INTO `User_Account` (`user_ID`, `username`, `user_pass`, `user_role`, `user_phone_num`, `user_email_address`, `created_at`, `updated_at`, `deleted_flag`) VALUES
(1, 'admin1', 'adminpass', 'admin', '1234567890', 'admin1@gmail.com', '2022-11-09 16:24:28', '2022-11-09 16:24:28', 0),
(2, 'doc1', '$2y$10$9piaN2oYsyYMZJUcUQkbdOeiIGVUvWbG9bGgVTlir2bzTx5i4dag.', 'doctor', '1234567890', 'doc1@gmail.com', '2022-11-10 00:54:38', '2022-11-10 00:54:38', 0),
(3, 'nurse1', '$2y$10$x4ZUpsmpoA8F6Y/XqTSSqerm5i2.nNjlM05nnI6vRGeR6BUP2YhpC', 'nurse', '1234567890', 'nurse1@gmail.com', '2022-11-10 01:05:26', '2022-11-10 01:05:26', 0),
(4, 'rec1', '$2y$10$RFj5cyqLYIiSTZCHvKJam.jqWyMr1CYhVZSUBTvzXbtz0Yx4IvCWK', 'receptionist', '1234567890', 'rec1@gmail.com', '2022-11-10 01:06:27', '2022-11-10 01:06:27', 0),
(5, 'pat1', '$2y$10$NC.Ypfow/EuKB88vp8yMRutINLdzsGsG7biAY3UueH9hoR190B94m', 'patient', '1234567890', 'pat1@gmail.com', '2022-11-10 01:07:55', '2022-11-10 01:07:55', 0),
(6, 'doc2', '$2y$10$WZ2M/4odqB8K0/11J/lFQOJVSAUg3kyd16w/kP737laEwJhvatRo2', 'doctor', '1234567890', 'doc2@gmail.com', '2022-11-10 01:42:36', '2022-11-10 01:42:36', 0),
(9, 'pat2', '$2y$10$IplfxfexsLESa8hoG2doPOSJW06QSWD6x9kulz.Pmy4g4QEPIWOl.', 'patient', '1234567890', 'pat2@gmail.com', '2022-11-13 19:39:37', '2022-11-13 19:39:37', 1),
(10, 'doc3', '$2y$10$TOls.eKWCNaJDanyxdcAYO02Y69GNG2XlkosAhDzWGXZeub7R/E5O', 'doctor', '1234567890', 'doc3@gmail.com', '2022-11-13 22:25:16', '2022-11-13 22:25:16', 1),
(11, 'doc3', '$2y$10$P9Exlv/O1nb5EwovRywPwOHGrh15naqK/UaCBWO8z.HIukmWgMdD2', 'doctor', '1234567890', 'doc3@gmail.com', '2022-11-15 19:23:19', '2022-11-15 19:23:19', 1),
(12, 'doc4', '$2y$10$.QEIIiDRBmp6pYa17FX5kOyg.iWTNSw9lytd8zspU3JU6byEHbXBq', 'doctor', '12347623', 'doc4@gmail.com', '2022-11-15 21:59:16', '2022-11-15 21:59:16', 1),
(13, 'doc5', '$2y$10$33.RtOm3J.53i7eBaCe4xOiQC3oIvY2FepPeEEJHQh1ojl/6wgRQy', 'doctor', '2343243', 'doc5@gmail.com', '2022-11-15 23:12:42', '2022-11-15 23:12:42', 1),
(14, 'doc6', '$2y$10$u6uLqYHzBtDlkYZXsk4JKOn3c.1gUkqiPj1AJzDTPYcpI/jnm7Zcy', 'doctor', '234622666', 'doc6@gmail.com', '2022-11-15 23:14:16', '2022-11-15 23:14:16', 0),
(15, 'pat2', '$2y$10$xpjcWE6kwzj95TrNAW9TxeoZcCFyfGystOMiiG1DBnACdc41K4TrC', 'patient', '23748932', 'pat2@gmail.com', '2022-11-15 23:25:29', '2022-11-15 23:25:29', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Address`
--
ALTER TABLE `Address`
  ADD PRIMARY KEY (`address_ID`);

--
-- Indexes for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD PRIMARY KEY (`app_ID`,`date_time`),
  ADD KEY `office_ID` (`office_ID`),
  ADD KEY `doctor_ID` (`doctor_ID`),
  ADD KEY `patient_ID` (`patient_ID`),
  ADD KEY `receptionist_ID` (`receptionist_ID`),
  ADD KEY `fk_payment_ID` (`payment_ID`);

--
-- Indexes for table `Department`
--
ALTER TABLE `Department`
  ADD PRIMARY KEY (`department_number`),
  ADD UNIQUE KEY `dep_name` (`dep_name`);

--
-- Indexes for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD PRIMARY KEY (`doc_ID`),
  ADD UNIQUE KEY `ssn` (`ssn`,`doc_user`),
  ADD KEY `dep_num` (`dep_num`),
  ADD KEY `doc_user` (`doc_user`),
  ADD KEY `address_ID` (`address_ID`);

--
-- Indexes for table `Doctor_For_Patient`
--
ALTER TABLE `Doctor_For_Patient`
  ADD PRIMARY KEY (`doc_ID`,`pat_ID`),
  ADD KEY `pat_ID` (`pat_ID`);

--
-- Indexes for table `Doctor_Maintains_Medical_Record`
--
ALTER TABLE `Doctor_Maintains_Medical_Record`
  ADD PRIMARY KEY (`doc_ID`,`pat_ID`),
  ADD KEY `pat_ID` (`pat_ID`);

--
-- Indexes for table `Doctor_Prescribes_Medicine_To_Patient`
--
ALTER TABLE `Doctor_Prescribes_Medicine_To_Patient`
  ADD PRIMARY KEY (`doc_ID`,`med_ID`,`pat_ID`),
  ADD KEY `med_ID` (`med_ID`),
  ADD KEY `pat_ID` (`pat_ID`);

--
-- Indexes for table `Doctor_Works_In_Office`
--
ALTER TABLE `Doctor_Works_In_Office`
  ADD PRIMARY KEY (`office_ID`,`doctor_ID`),
  ADD KEY `doctor_ID` (`doctor_ID`);

--
-- Indexes for table `Emergency_Contact`
--
ALTER TABLE `Emergency_Contact`
  ADD PRIMARY KEY (`patient_ID`);

--
-- Indexes for table `Medical_Record`
--
ALTER TABLE `Medical_Record`
  ADD PRIMARY KEY (`pat_ID`);

--
-- Indexes for table `Medical_Record_Contains_Medicine`
--
ALTER TABLE `Medical_Record_Contains_Medicine`
  ADD PRIMARY KEY (`pat_ID`,`med_ID`),
  ADD KEY `med_ID` (`med_ID`);

--
-- Indexes for table `Medicine`
--
ALTER TABLE `Medicine`
  ADD PRIMARY KEY (`med_ID`);

--
-- Indexes for table `Nurse`
--
ALTER TABLE `Nurse`
  ADD PRIMARY KEY (`nurse_ID`),
  ADD UNIQUE KEY `ssn` (`ssn`,`nurse_user`),
  ADD KEY `dep_num` (`dep_num`),
  ADD KEY `nurse_user` (`nurse_user`),
  ADD KEY `address_ID` (`address_ID`);

--
-- Indexes for table `Nurse_Works_In_Office`
--
ALTER TABLE `Nurse_Works_In_Office`
  ADD PRIMARY KEY (`office_ID`,`nurse_ID`),
  ADD KEY `nurse_ID` (`nurse_ID`);

--
-- Indexes for table `Nurse_Works_On_Appointment`
--
ALTER TABLE `Nurse_Works_On_Appointment`
  ADD PRIMARY KEY (`nurse_ID`,`appointment_ID`),
  ADD KEY `appointment_ID` (`appointment_ID`);

--
-- Indexes for table `Nurse_Works_With_Doctor`
--
ALTER TABLE `Nurse_Works_With_Doctor`
  ADD PRIMARY KEY (`nurse_ID`,`doc_ID`),
  ADD KEY `doc_ID` (`doc_ID`);

--
-- Indexes for table `Office`
--
ALTER TABLE `Office`
  ADD PRIMARY KEY (`office_ID`),
  ADD KEY `dep_number` (`dep_number`),
  ADD KEY `address_ID` (`address_ID`);

--
-- Indexes for table `Patient`
--
ALTER TABLE `Patient`
  ADD PRIMARY KEY (`patient_ID`),
  ADD UNIQUE KEY `ssn` (`ssn`,`pat_user`),
  ADD KEY `pat_user` (`pat_user`),
  ADD KEY `address_ID` (`address_ID`),
  ADD KEY `clinic_ID` (`clinic_ID`),
  ADD KEY `prim_doc_ID` (`prim_doc_ID`);

--
-- Indexes for table `Receptionist`
--
ALTER TABLE `Receptionist`
  ADD PRIMARY KEY (`rec_ID`),
  ADD UNIQUE KEY `ssn` (`ssn`,`rec_user`),
  ADD KEY `rec_user` (`rec_user`),
  ADD KEY `address_ID` (`address_ID`);

--
-- Indexes for table `Referral`
--
ALTER TABLE `Referral`
  ADD PRIMARY KEY (`date_time`,`primary_ID`),
  ADD KEY `primary_ID` (`primary_ID`),
  ADD KEY `pat_ID` (`pat_ID`),
  ADD KEY `specialist_ID` (`specialist_ID`);

--
-- Indexes for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD PRIMARY KEY (`transaction_ID`),
  ADD KEY `patient_ID` (`patient_ID`),
  ADD KEY `app_ID` (`app_ID`);

--
-- Indexes for table `User_Account`
--
ALTER TABLE `User_Account`
  ADD PRIMARY KEY (`user_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Address`
--
ALTER TABLE `Address`
  MODIFY `address_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `Appointment`
--
ALTER TABLE `Appointment`
  MODIFY `app_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Department`
--
ALTER TABLE `Department`
  MODIFY `department_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Doctor`
--
ALTER TABLE `Doctor`
  MODIFY `doc_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Medicine`
--
ALTER TABLE `Medicine`
  MODIFY `med_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Nurse`
--
ALTER TABLE `Nurse`
  MODIFY `nurse_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Office`
--
ALTER TABLE `Office`
  MODIFY `office_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Patient`
--
ALTER TABLE `Patient`
  MODIFY `patient_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Receptionist`
--
ALTER TABLE `Receptionist`
  MODIFY `rec_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Transaction`
--
ALTER TABLE `Transaction`
  MODIFY `transaction_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `User_Account`
--
ALTER TABLE `User_Account`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`office_ID`) REFERENCES `Office` (`office_ID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`doctor_ID`) REFERENCES `Doctor` (`doc_ID`),
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`patient_ID`) REFERENCES `Patient` (`patient_ID`),
  ADD CONSTRAINT `appointment_ibfk_4` FOREIGN KEY (`receptionist_ID`) REFERENCES `Receptionist` (`rec_ID`),
  ADD CONSTRAINT `fk_payment_ID` FOREIGN KEY (`payment_ID`) REFERENCES `Transaction` (`transaction_ID`);

--
-- Constraints for table `Doctor`
--
ALTER TABLE `Doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`dep_num`) REFERENCES `Department` (`department_number`),
  ADD CONSTRAINT `doctor_ibfk_2` FOREIGN KEY (`doc_user`) REFERENCES `User_Account` (`user_ID`),
  ADD CONSTRAINT `doctor_ibfk_3` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`);

--
-- Constraints for table `Doctor_For_Patient`
--
ALTER TABLE `Doctor_For_Patient`
  ADD CONSTRAINT `doctor_for_patient_ibfk_1` FOREIGN KEY (`doc_ID`) REFERENCES `Doctor` (`doc_ID`),
  ADD CONSTRAINT `doctor_for_patient_ibfk_2` FOREIGN KEY (`pat_ID`) REFERENCES `Patient` (`patient_ID`);

--
-- Constraints for table `Doctor_Maintains_Medical_Record`
--
ALTER TABLE `Doctor_Maintains_Medical_Record`
  ADD CONSTRAINT `doctor_maintains_medical_record_ibfk_1` FOREIGN KEY (`pat_ID`) REFERENCES `Medical_Record` (`pat_ID`),
  ADD CONSTRAINT `doctor_maintains_medical_record_ibfk_2` FOREIGN KEY (`doc_ID`) REFERENCES `Doctor` (`doc_ID`);

--
-- Constraints for table `Doctor_Prescribes_Medicine_To_Patient`
--
ALTER TABLE `Doctor_Prescribes_Medicine_To_Patient`
  ADD CONSTRAINT `doctor_prescribes_medicine_to_patient_ibfk_1` FOREIGN KEY (`doc_ID`) REFERENCES `Doctor` (`doc_ID`),
  ADD CONSTRAINT `doctor_prescribes_medicine_to_patient_ibfk_2` FOREIGN KEY (`med_ID`) REFERENCES `Medicine` (`med_ID`),
  ADD CONSTRAINT `doctor_prescribes_medicine_to_patient_ibfk_3` FOREIGN KEY (`pat_ID`) REFERENCES `Patient` (`patient_ID`);

--
-- Constraints for table `Doctor_Works_In_Office`
--
ALTER TABLE `Doctor_Works_In_Office`
  ADD CONSTRAINT `doctor_works_in_office_ibfk_1` FOREIGN KEY (`office_ID`) REFERENCES `Office` (`office_ID`),
  ADD CONSTRAINT `doctor_works_in_office_ibfk_2` FOREIGN KEY (`doctor_ID`) REFERENCES `Doctor` (`doc_ID`);

--
-- Constraints for table `Emergency_Contact`
--
ALTER TABLE `Emergency_Contact`
  ADD CONSTRAINT `emergency_contact_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `Patient` (`patient_ID`);

--
-- Constraints for table `Medical_Record`
--
ALTER TABLE `Medical_Record`
  ADD CONSTRAINT `medical_record_ibfk_1` FOREIGN KEY (`pat_ID`) REFERENCES `Patient` (`patient_ID`);

--
-- Constraints for table `Medical_Record_Contains_Medicine`
--
ALTER TABLE `Medical_Record_Contains_Medicine`
  ADD CONSTRAINT `medical_record_contains_medicine_ibfk_1` FOREIGN KEY (`pat_ID`) REFERENCES `Medical_Record` (`pat_ID`),
  ADD CONSTRAINT `medical_record_contains_medicine_ibfk_2` FOREIGN KEY (`med_ID`) REFERENCES `Medicine` (`med_ID`);

--
-- Constraints for table `Nurse`
--
ALTER TABLE `Nurse`
  ADD CONSTRAINT `nurse_ibfk_1` FOREIGN KEY (`dep_num`) REFERENCES `Department` (`department_number`),
  ADD CONSTRAINT `nurse_ibfk_2` FOREIGN KEY (`nurse_user`) REFERENCES `User_Account` (`user_ID`),
  ADD CONSTRAINT `nurse_ibfk_3` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`);

--
-- Constraints for table `Nurse_Works_In_Office`
--
ALTER TABLE `Nurse_Works_In_Office`
  ADD CONSTRAINT `nurse_works_in_office_ibfk_1` FOREIGN KEY (`office_ID`) REFERENCES `Office` (`office_ID`),
  ADD CONSTRAINT `nurse_works_in_office_ibfk_2` FOREIGN KEY (`nurse_ID`) REFERENCES `Nurse` (`nurse_ID`);

--
-- Constraints for table `Nurse_Works_On_Appointment`
--
ALTER TABLE `Nurse_Works_On_Appointment`
  ADD CONSTRAINT `nurse_works_on_appointment_ibfk_1` FOREIGN KEY (`nurse_ID`) REFERENCES `Nurse` (`nurse_ID`),
  ADD CONSTRAINT `nurse_works_on_appointment_ibfk_2` FOREIGN KEY (`appointment_ID`) REFERENCES `Appointment` (`app_ID`);

--
-- Constraints for table `Nurse_Works_With_Doctor`
--
ALTER TABLE `Nurse_Works_With_Doctor`
  ADD CONSTRAINT `nurse_works_with_doctor_ibfk_1` FOREIGN KEY (`nurse_ID`) REFERENCES `Nurse` (`nurse_ID`),
  ADD CONSTRAINT `nurse_works_with_doctor_ibfk_2` FOREIGN KEY (`doc_ID`) REFERENCES `Doctor` (`doc_ID`);

--
-- Constraints for table `Office`
--
ALTER TABLE `Office`
  ADD CONSTRAINT `office_ibfk_1` FOREIGN KEY (`dep_number`) REFERENCES `Department` (`department_number`),
  ADD CONSTRAINT `office_ibfk_2` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`);

--
-- Constraints for table `Patient`
--
ALTER TABLE `Patient`
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`pat_user`) REFERENCES `User_Account` (`user_ID`),
  ADD CONSTRAINT `patient_ibfk_2` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`),
  ADD CONSTRAINT `patient_ibfk_3` FOREIGN KEY (`clinic_ID`) REFERENCES `Address` (`address_ID`),
  ADD CONSTRAINT `patient_ibfk_4` FOREIGN KEY (`prim_doc_ID`) REFERENCES `Doctor` (`doc_ID`);

--
-- Constraints for table `Receptionist`
--
ALTER TABLE `Receptionist`
  ADD CONSTRAINT `receptionist_ibfk_1` FOREIGN KEY (`rec_user`) REFERENCES `User_Account` (`user_ID`),
  ADD CONSTRAINT `receptionist_ibfk_2` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`);

--
-- Constraints for table `Referral`
--
ALTER TABLE `Referral`
  ADD CONSTRAINT `referral_ibfk_1` FOREIGN KEY (`primary_ID`) REFERENCES `Doctor` (`doc_ID`),
  ADD CONSTRAINT `referral_ibfk_2` FOREIGN KEY (`pat_ID`) REFERENCES `Patient` (`patient_ID`),
  ADD CONSTRAINT `referral_ibfk_3` FOREIGN KEY (`specialist_ID`) REFERENCES `Doctor` (`doc_ID`);

--
-- Constraints for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `Patient` (`patient_ID`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`app_ID`) REFERENCES `Appointment` (`app_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
