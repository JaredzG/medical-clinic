-- MySQL dump 10.13  Distrib 8.0.31, for macos12 (x86_64)
--
-- Host: localhost    Database: med_clinic_db
-- ------------------------------------------------------
-- Server version	8.0.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `Address`
--

DROP TABLE IF EXISTS `Address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Address` (
  `address_ID` int NOT NULL AUTO_INCREMENT,
  `street_address` varchar(45) NOT NULL,
  `apt_num` varchar(20) DEFAULT NULL,
  `city` varchar(20) NOT NULL,
  `state` varchar(20) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `office_add` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`address_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Address`
--

LOCK TABLES `Address` WRITE;
/*!40000 ALTER TABLE `Address` DISABLE KEYS */;
INSERT INTO `Address` VALUES (1,'4349 Martin Luther King Blvd #2005',NULL,'Houston','TX','77204',1,0),(2,'5910 Scott St',NULL,'Houston','TX','77021',1,0),(3,'91 Orange Lane','','Miamisburg','OH','45342',0,0),(4,'402 Creekside St.','','Akron','CA','32183',0,0),(5,'30 E. Clark St.',NULL,'Lorain','UT','38219',0,0),(6,'791 Beacon St.',NULL,'Plainfield','TX','04823',0,0),(7,'731 W. Clark St.','32','Hendersonville','VA','48933',0,0),(8,'9864 San Juan St.','69','Deerfield','AL','69696',0,0),(9,'182 Edgewood Ave.',NULL,'Frankfort','MO','38928',0,0),(10,'77 N. Brookside Drive',NULL,'Perrysburg','IL','47824',0,0),(11,'260 Circle Ave.','37','Lake Zurich','OH','48923',0,0),(12,'372 Essex Drive',NULL,'Parkville','GA','38923',0,0),(13,'8809 Jefferson Street',NULL,'Enterprise','NV','49933',0,0),(14,'9120 George Court',NULL,'Ponte Vedra Beach','TX','11239',0,0),(15,'463 Shadow Brook Dr.',NULL,'East Stroudsburg','LA','38912',0,0),(16,'9363 Glen Ridge Street','89','Cranston','RI','84934',0,0),(17,'812 Fulton Lane',NULL,'Jenison','TN','38923',0,0),(18,'6 Mill Pond Court',NULL,'Easley','CA','88903',0,0),(19,'411 Young Avenue',NULL,'Medina','MS','37821',0,0),(20,'8284 Peninsula Rd.',NULL,'Stratford','AR','39023',0,0),(21,'8284 Peninsula Rd.',NULL,'Stratford','AR','39023',0,0),(22,'81 Marconi Street',NULL,'Missoula','AR','38912',0,0),(23,'75 Schoolhouse Lane',NULL,'Ronkonkoma','NE','83923',0,0),(24,'75 Schoolhouse Lane',NULL,'Ronkonkoma','NE','83923',0,0),(25,'531 East Briarwood Circle','','Venice','DC','47934',0,0),(26,'1839 Golden Ale Ct',NULL,'Rosenberg','TX','77469',0,0),(27,'200 Blueberry St',NULL,'Houston','TX','77904',0,0),(28,'53 Snake Hill St',NULL,'Ambler','PA','19002',0,0),(29,'59 Sussex St',NULL,'Gaithersburg','MD','20877',0,0),(30,'8820 Border St',NULL,'Longview','TX','75604',0,0),(31,'9333 Kent St',NULL,'Westwood','NJ','07675',0,0),(32,'21 West Pearl Ln',NULL,'Garland','TX','75043',0,0),(33,'6 North Miles St',NULL,'York','PA','17402',0,0);
/*!40000 ALTER TABLE `Address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Appointment`
--

DROP TABLE IF EXISTS `Appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Appointment` (
  `app_ID` int NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `office_ID` int NOT NULL,
  `doctor_ID` int NOT NULL,
  `patient_ID` int NOT NULL,
  `payment_ID` int DEFAULT NULL,
  `receptionist_ID` int DEFAULT NULL,
  `status_flag` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`app_ID`,`date_time`),
  KEY `office_ID` (`office_ID`),
  KEY `doctor_ID` (`doctor_ID`),
  KEY `patient_ID` (`patient_ID`),
  KEY `receptionist_ID` (`receptionist_ID`),
  KEY `fk_payment_ID` (`payment_ID`),
  CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`office_ID`) REFERENCES `Office` (`office_ID`),
  CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`doctor_ID`) REFERENCES `Doctor` (`doc_ID`),
  CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`patient_ID`) REFERENCES `Patient` (`patient_ID`),
  CONSTRAINT `appointment_ibfk_4` FOREIGN KEY (`receptionist_ID`) REFERENCES `Receptionist` (`rec_ID`),
  CONSTRAINT `fk_payment_ID` FOREIGN KEY (`payment_ID`) REFERENCES `Transaction` (`transaction_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Appointment`
--

LOCK TABLES `Appointment` WRITE;
/*!40000 ALTER TABLE `Appointment` DISABLE KEYS */;
INSERT INTO `Appointment` VALUES (1,'2022-12-02 00:00:00','Reason1',1,1,1,6,1,2),(2,'2022-12-03 00:00:00','Reason2',1,1,1,14,1,2),(3,'2022-12-04 00:00:00','Reason3',7,7,1,18,1,2),(4,'2022-12-19 00:00:00','Reason4',9,11,1,NULL,1,2),(5,'2022-12-12 00:00:00','Reason3',9,11,1,NULL,1,2),(6,'2022-12-05 00:00:00','Reason',1,1,2,NULL,1,2),(7,'2022-12-18 00:00:00','Reason33',1,1,2,NULL,1,2),(8,'2022-12-13 00:00:00','Reason3',1,2,3,NULL,1,2),(9,'2022-12-26 00:00:00','Reason',1,1,1,NULL,1,2),(10,'2022-12-26 00:00:00','Reason',10,13,1,NULL,1,2),(11,'2022-12-20 20:03:00','Reason',1,1,4,12,1,2),(12,'2022-12-16 00:00:00','Reason test',1,1,4,NULL,1,2),(13,'2022-12-07 04:05:00','Reason for Appointment',1,1,4,17,1,2),(14,'2022-12-22 00:00:00','reason unknown',1,1,1,NULL,NULL,0),(15,'2022-12-19 13:00:00','Reason blah',1,1,1,NULL,1,1),(16,'2022-12-19 13:00:00','Reason blah',1,1,1,NULL,NULL,0),(17,'2022-12-19 13:00:00','Reason blah',1,1,1,NULL,1,3),(18,'2022-12-19 13:00:00','Reason blah',1,1,1,NULL,1,3);
/*!40000 ALTER TABLE `Appointment` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `before_appointment_with_specialist` BEFORE INSERT ON `Appointment` FOR EACH ROW BEGIN
 IF (new.doctor_ID NOT IN (SELECT specialist_ID FROM Referral WHERE (pat_ID = new.patient_ID AND deleted_flag = 0))
	AND new.doctor_ID <> (SELECT prim_doc_ID FROM Patient WHERE Patient.patient_ID = new.patient_ID)) THEN
		SET new.patient_ID = NULL;
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'Appointment not made. No referral with specialist found.';
END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `after_completed_appointment` BEFORE UPDATE ON `Appointment` FOR EACH ROW BEGIN
IF new.status_flag = 2 THEN
	IF new.payment_ID IS NULL THEN
		INSERT INTO Transaction (patient_ID, app_ID, amount)
		VALUES (new.patient_ID, new.app_ID, 50.00);
	END IF;
END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Department`
--

DROP TABLE IF EXISTS `Department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Department` (
  `department_number` int NOT NULL AUTO_INCREMENT,
  `dep_name` varchar(45) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`department_number`),
  UNIQUE KEY `dep_name` (`dep_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Department`
--

LOCK TABLES `Department` WRITE;
/*!40000 ALTER TABLE `Department` DISABLE KEYS */;
INSERT INTO `Department` VALUES (1,'Family Care',0),(2,'Cardiology',0),(3,'Orthopedics',0),(4,'Radiology',0),(5,'Oncology',0),(6,'Gastroenterology',0);
/*!40000 ALTER TABLE `Department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Doctor`
--

DROP TABLE IF EXISTS `Doctor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Doctor` (
  `doc_ID` int NOT NULL AUTO_INCREMENT,
  `ssn` int NOT NULL,
  `dep_num` int NOT NULL DEFAULT '1',
  `f_name` varchar(45) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(45) NOT NULL,
  `address_ID` int NOT NULL,
  `credentials` varchar(45) DEFAULT NULL,
  `sex` char(1) NOT NULL,
  `doc_user` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`doc_ID`),
  UNIQUE KEY `ssn` (`ssn`,`doc_user`),
  KEY `dep_num` (`dep_num`),
  KEY `doc_user` (`doc_user`),
  KEY `address_ID` (`address_ID`),
  CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`dep_num`) REFERENCES `Department` (`department_number`),
  CONSTRAINT `doctor_ibfk_2` FOREIGN KEY (`doc_user`) REFERENCES `User_Account` (`user_ID`),
  CONSTRAINT `doctor_ibfk_3` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Doctor`
--

LOCK TABLES `Doctor` WRITE;
/*!40000 ALTER TABLE `Doctor` DISABLE KEYS */;
INSERT INTO `Doctor` VALUES (1,940610271,1,'Murphy','David','Haiden',3,'M.D.','M',2,0),(2,953871924,1,'Blake','','Madisen',4,'m deez','F',3,0),(3,957552096,1,'Ace',NULL,'Grant',5,NULL,'F',4,0),(4,972464835,1,'Brighton','Jared','Brock',6,NULL,'M',5,0),(5,964272372,1,'Dawn','Caprice','Zane',7,NULL,'F',6,0),(6,959813861,1,'Kingston',NULL,'Felix',8,NULL,'M',7,0),(7,982153043,2,'Dash','Dash','Dash',9,NULL,'M',8,0),(8,968589936,2,'Timothy',NULL,'Kaitlin',10,NULL,'F',9,0),(9,979542164,3,'Noah',NULL,'Fawn',11,NULL,'M',10,0),(10,991378416,3,'Carlen',NULL,'Oliver',12,NULL,'F',11,0),(11,980527629,4,'Adelaide','Jeremy','Caylen',13,NULL,'M',12,0),(12,947228062,4,'Lashon','Arthur','Aryn',14,NULL,'F',13,0),(13,957784128,5,'Arthur',NULL,'Trevor',15,NULL,'M',14,0),(14,960818584,5,'Bram','Imogen','Vivian',16,NULL,'F',15,0),(15,950631989,6,'Claudia',NULL,'Jude',17,NULL,'M',16,0),(16,955595685,6,'Noah',NULL,'Noah',18,NULL,'M',17,0);
/*!40000 ALTER TABLE `Doctor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Doctor_For_Patient`
--

DROP TABLE IF EXISTS `Doctor_For_Patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Doctor_For_Patient` (
  `doc_ID` int NOT NULL,
  `pat_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`doc_ID`,`pat_ID`),
  KEY `pat_ID` (`pat_ID`),
  CONSTRAINT `doctor_for_patient_ibfk_1` FOREIGN KEY (`doc_ID`) REFERENCES `Doctor` (`doc_ID`),
  CONSTRAINT `doctor_for_patient_ibfk_2` FOREIGN KEY (`pat_ID`) REFERENCES `Patient` (`patient_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Doctor_For_Patient`
--

LOCK TABLES `Doctor_For_Patient` WRITE;
/*!40000 ALTER TABLE `Doctor_For_Patient` DISABLE KEYS */;
INSERT INTO `Doctor_For_Patient` VALUES (1,1,0),(1,2,0),(1,3,0),(1,4,0),(2,3,0),(7,1,0),(11,1,0),(13,1,0);
/*!40000 ALTER TABLE `Doctor_For_Patient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Doctor_Maintains_Medical_Record`
--

DROP TABLE IF EXISTS `Doctor_Maintains_Medical_Record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Doctor_Maintains_Medical_Record` (
  `pat_ID` int NOT NULL,
  `doc_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`doc_ID`,`pat_ID`),
  KEY `pat_ID` (`pat_ID`),
  CONSTRAINT `doctor_maintains_medical_record_ibfk_1` FOREIGN KEY (`pat_ID`) REFERENCES `Medical_Record` (`pat_ID`),
  CONSTRAINT `doctor_maintains_medical_record_ibfk_2` FOREIGN KEY (`doc_ID`) REFERENCES `Doctor` (`doc_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Doctor_Maintains_Medical_Record`
--

LOCK TABLES `Doctor_Maintains_Medical_Record` WRITE;
/*!40000 ALTER TABLE `Doctor_Maintains_Medical_Record` DISABLE KEYS */;
INSERT INTO `Doctor_Maintains_Medical_Record` VALUES (1,1,0),(2,1,0),(3,1,0),(4,1,0),(3,2,0),(1,7,0),(1,11,0),(1,13,0);
/*!40000 ALTER TABLE `Doctor_Maintains_Medical_Record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Doctor_Prescribes_Medicine_To_Patient`
--

DROP TABLE IF EXISTS `Doctor_Prescribes_Medicine_To_Patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Doctor_Prescribes_Medicine_To_Patient` (
  `doc_ID` int NOT NULL,
  `med_ID` int NOT NULL,
  `pat_ID` int NOT NULL,
  PRIMARY KEY (`doc_ID`,`med_ID`,`pat_ID`),
  KEY `med_ID` (`med_ID`),
  KEY `pat_ID` (`pat_ID`),
  CONSTRAINT `doctor_prescribes_medicine_to_patient_ibfk_1` FOREIGN KEY (`doc_ID`) REFERENCES `Doctor` (`doc_ID`),
  CONSTRAINT `doctor_prescribes_medicine_to_patient_ibfk_2` FOREIGN KEY (`med_ID`) REFERENCES `Medicine` (`med_ID`),
  CONSTRAINT `doctor_prescribes_medicine_to_patient_ibfk_3` FOREIGN KEY (`pat_ID`) REFERENCES `Patient` (`patient_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Doctor_Prescribes_Medicine_To_Patient`
--

LOCK TABLES `Doctor_Prescribes_Medicine_To_Patient` WRITE;
/*!40000 ALTER TABLE `Doctor_Prescribes_Medicine_To_Patient` DISABLE KEYS */;
/*!40000 ALTER TABLE `Doctor_Prescribes_Medicine_To_Patient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Doctor_Works_In_Office`
--

DROP TABLE IF EXISTS `Doctor_Works_In_Office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Doctor_Works_In_Office` (
  `office_ID` int NOT NULL,
  `doctor_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`office_ID`,`doctor_ID`),
  KEY `doctor_ID` (`doctor_ID`),
  CONSTRAINT `doctor_works_in_office_ibfk_1` FOREIGN KEY (`office_ID`) REFERENCES `Office` (`office_ID`),
  CONSTRAINT `doctor_works_in_office_ibfk_2` FOREIGN KEY (`doctor_ID`) REFERENCES `Doctor` (`doc_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Doctor_Works_In_Office`
--

LOCK TABLES `Doctor_Works_In_Office` WRITE;
/*!40000 ALTER TABLE `Doctor_Works_In_Office` DISABLE KEYS */;
INSERT INTO `Doctor_Works_In_Office` VALUES (1,1,0),(1,2,0),(1,3,0),(4,4,0),(4,5,0),(4,6,0),(7,7,0),(8,9,0),(9,11,0),(10,13,0),(11,15,0),(12,8,0),(13,10,0),(14,12,0),(15,14,0),(16,16,1);
/*!40000 ALTER TABLE `Doctor_Works_In_Office` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Emergency_Contact`
--

DROP TABLE IF EXISTS `Emergency_Contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Emergency_Contact` (
  `patient_ID` int NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(20) NOT NULL,
  `relationship` varchar(20) DEFAULT NULL,
  `phone_num` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`patient_ID`),
  CONSTRAINT `emergency_contact_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `Patient` (`patient_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Emergency_Contact`
--

LOCK TABLES `Emergency_Contact` WRITE;
/*!40000 ALTER TABLE `Emergency_Contact` DISABLE KEYS */;
INSERT INTO `Emergency_Contact` VALUES (1,'Ellice',NULL,'Harriet','parent','15664809985','F',0),(2,'Natalie',NULL,'Merle','parent','13882344964','M',0),(3,'Trevor',NULL,'Ellory','spouse','13519094197','F',0),(4,'Porter',NULL,'Edward','spouse','14142849810','F',0);
/*!40000 ALTER TABLE `Emergency_Contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Medical_Record`
--

DROP TABLE IF EXISTS `Medical_Record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Medical_Record` (
  `pat_ID` int NOT NULL,
  `allergies` text,
  `diagnoses` text,
  `immunizations` text,
  `progress` text,
  `treatment_plan` text,
  `inch_height` int DEFAULT NULL,
  `pound_weight` int DEFAULT NULL,
  `b_date` date NOT NULL,
  `ethnicity` varchar(20) NOT NULL,
  `race` varchar(20) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pat_ID`),
  CONSTRAINT `medical_record_ibfk_1` FOREIGN KEY (`pat_ID`) REFERENCES `Patient` (`patient_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Medical_Record`
--

LOCK TABLES `Medical_Record` WRITE;
/*!40000 ALTER TABLE `Medical_Record` DISABLE KEYS */;
INSERT INTO `Medical_Record` VALUES (1,'','','','','',75,200,'2002-03-14','nhl','aian',0),(2,'','','','','',50,100,'2003-01-03','nhl','a',0),(3,'Bees\r\npenicillin','','','','',80,180,'1990-09-09','nhl','baf',0),(4,'','','','','',100,70,'1980-03-03','nhl','w',0);
/*!40000 ALTER TABLE `Medical_Record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Medical_Record_Contains_Medicine`
--

DROP TABLE IF EXISTS `Medical_Record_Contains_Medicine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Medical_Record_Contains_Medicine` (
  `pat_ID` int NOT NULL,
  `med_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pat_ID`,`med_ID`),
  KEY `med_ID` (`med_ID`),
  CONSTRAINT `medical_record_contains_medicine_ibfk_1` FOREIGN KEY (`pat_ID`) REFERENCES `Medical_Record` (`pat_ID`),
  CONSTRAINT `medical_record_contains_medicine_ibfk_2` FOREIGN KEY (`med_ID`) REFERENCES `Medicine` (`med_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Medical_Record_Contains_Medicine`
--

LOCK TABLES `Medical_Record_Contains_Medicine` WRITE;
/*!40000 ALTER TABLE `Medical_Record_Contains_Medicine` DISABLE KEYS */;
INSERT INTO `Medical_Record_Contains_Medicine` VALUES (1,1,0),(1,2,0),(1,3,0),(2,3,0),(2,5,0),(3,6,0),(3,7,0),(3,8,0),(4,9,0);
/*!40000 ALTER TABLE `Medical_Record_Contains_Medicine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Medicine`
--

DROP TABLE IF EXISTS `Medicine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Medicine` (
  `med_ID` int NOT NULL AUTO_INCREMENT,
  `brand` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`med_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Medicine`
--

LOCK TABLES `Medicine` WRITE;
/*!40000 ALTER TABLE `Medicine` DISABLE KEYS */;
INSERT INTO `Medicine` VALUES (1,'Ventolin','Albuterol','Take two tablets by mouth once a day.',0),(2,'Lipitor','Atorvastatin Calcium','Take one tablet by mouth twice a day, 12 hours apart.',0),(3,'Microzide','Hydrochlorothiazide','Take two tablets by mouth once a day.',0),(4,'Microzide','Hydrochlorothiazide','Take one tablet by mouth twice a day, 12 hours apart.',0),(5,'Axpinet','Metformin','Take two tablets by mouth once a day.',0),(6,'Lipitor','Atorvastatin Calcium','Take two tablets by mouth once a day.',0),(7,'Axpinet','Metformin','Take one tablet by mouth twice a day, 12 hours apart.',0),(8,'Microzide','Hydrochlorothiazide','Take one tablet by mouth twice a day, 12 hours apart.',0),(9,'Walmart','Hydrochlorothiazide','Take two tablets by mouth once a day.',0);
/*!40000 ALTER TABLE `Medicine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Nurse`
--

DROP TABLE IF EXISTS `Nurse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Nurse` (
  `nurse_ID` int NOT NULL AUTO_INCREMENT,
  `ssn` int NOT NULL,
  `dep_num` int DEFAULT NULL,
  `f_name` varchar(20) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL,
  `nurse_user` int NOT NULL,
  `registered` tinyint(1) NOT NULL DEFAULT '0',
  `address_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nurse_ID`),
  UNIQUE KEY `ssn` (`ssn`,`nurse_user`),
  KEY `dep_num` (`dep_num`),
  KEY `nurse_user` (`nurse_user`),
  KEY `address_ID` (`address_ID`),
  CONSTRAINT `nurse_ibfk_1` FOREIGN KEY (`dep_num`) REFERENCES `Department` (`department_number`),
  CONSTRAINT `nurse_ibfk_2` FOREIGN KEY (`nurse_user`) REFERENCES `User_Account` (`user_ID`),
  CONSTRAINT `nurse_ibfk_3` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Nurse`
--

LOCK TABLES `Nurse` WRITE;
/*!40000 ALTER TABLE `Nurse` DISABLE KEYS */;
INSERT INTO `Nurse` VALUES (1,123472918,1,'Mikaela',NULL,'Johnson','F',25,1,28,0),(2,247299266,2,'John',NULL,'Mason','M',26,1,29,0),(3,987328116,3,'Alex',NULL,'Chaplain','M',27,1,30,0),(4,992661032,4,'Pam',NULL,'Chavez','F',28,1,31,0),(5,987262122,5,'Nadia',NULL,'Delroba','F',29,1,32,0),(6,729415298,6,'Lucas',NULL,'Faraday','M',30,1,33,0);
/*!40000 ALTER TABLE `Nurse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Nurse_Works_In_Office`
--

DROP TABLE IF EXISTS `Nurse_Works_In_Office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Nurse_Works_In_Office` (
  `office_ID` int NOT NULL,
  `nurse_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`office_ID`,`nurse_ID`),
  KEY `nurse_ID` (`nurse_ID`),
  CONSTRAINT `nurse_works_in_office_ibfk_1` FOREIGN KEY (`office_ID`) REFERENCES `Office` (`office_ID`),
  CONSTRAINT `nurse_works_in_office_ibfk_2` FOREIGN KEY (`nurse_ID`) REFERENCES `Nurse` (`nurse_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Nurse_Works_In_Office`
--

LOCK TABLES `Nurse_Works_In_Office` WRITE;
/*!40000 ALTER TABLE `Nurse_Works_In_Office` DISABLE KEYS */;
INSERT INTO `Nurse_Works_In_Office` VALUES (1,1,0),(7,2,0),(8,3,0),(9,4,0),(10,5,0),(11,6,0);
/*!40000 ALTER TABLE `Nurse_Works_In_Office` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Nurse_Works_On_Appointment`
--

DROP TABLE IF EXISTS `Nurse_Works_On_Appointment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Nurse_Works_On_Appointment` (
  `nurse_ID` int NOT NULL,
  `appointment_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nurse_ID`,`appointment_ID`),
  KEY `appointment_ID` (`appointment_ID`),
  CONSTRAINT `nurse_works_on_appointment_ibfk_1` FOREIGN KEY (`nurse_ID`) REFERENCES `Nurse` (`nurse_ID`),
  CONSTRAINT `nurse_works_on_appointment_ibfk_2` FOREIGN KEY (`appointment_ID`) REFERENCES `Appointment` (`app_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Nurse_Works_On_Appointment`
--

LOCK TABLES `Nurse_Works_On_Appointment` WRITE;
/*!40000 ALTER TABLE `Nurse_Works_On_Appointment` DISABLE KEYS */;
/*!40000 ALTER TABLE `Nurse_Works_On_Appointment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Nurse_Works_With_Doctor`
--

DROP TABLE IF EXISTS `Nurse_Works_With_Doctor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Nurse_Works_With_Doctor` (
  `nurse_ID` int NOT NULL,
  `doc_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nurse_ID`,`doc_ID`),
  KEY `doc_ID` (`doc_ID`),
  CONSTRAINT `nurse_works_with_doctor_ibfk_1` FOREIGN KEY (`nurse_ID`) REFERENCES `Nurse` (`nurse_ID`),
  CONSTRAINT `nurse_works_with_doctor_ibfk_2` FOREIGN KEY (`doc_ID`) REFERENCES `Doctor` (`doc_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Nurse_Works_With_Doctor`
--

LOCK TABLES `Nurse_Works_With_Doctor` WRITE;
/*!40000 ALTER TABLE `Nurse_Works_With_Doctor` DISABLE KEYS */;
INSERT INTO `Nurse_Works_With_Doctor` VALUES (2,7,0),(3,9,0),(4,11,0),(5,13,0),(6,15,0);
/*!40000 ALTER TABLE `Nurse_Works_With_Doctor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Office`
--

DROP TABLE IF EXISTS `Office`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Office` (
  `office_ID` int NOT NULL AUTO_INCREMENT,
  `dep_number` int DEFAULT NULL,
  `address_ID` int NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`office_ID`),
  KEY `dep_number` (`dep_number`),
  KEY `address_ID` (`address_ID`),
  CONSTRAINT `office_ibfk_1` FOREIGN KEY (`dep_number`) REFERENCES `Department` (`department_number`),
  CONSTRAINT `office_ibfk_2` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Office`
--

LOCK TABLES `Office` WRITE;
/*!40000 ALTER TABLE `Office` DISABLE KEYS */;
INSERT INTO `Office` VALUES (1,1,1,'15677457309',0),(2,1,1,'14096545664',0),(3,1,1,'12528648673',0),(4,1,2,'17193801515',0),(5,1,2,'18208878776',0),(6,1,2,'18056068717',0),(7,2,1,'15515795451',0),(8,3,1,'12036404061',0),(9,4,1,'14788680414',0),(10,5,1,'13163091208',0),(11,6,1,'17193036412',0),(12,2,2,'15025940976',0),(13,3,2,'12052590752',0),(14,4,2,'17758285161',0),(15,5,2,'15599549159',0),(16,6,2,'18315760099',1),(17,5,1,'2897398273',1),(18,5,1,'3134445555',0);
/*!40000 ALTER TABLE `Office` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Patient`
--

DROP TABLE IF EXISTS `Patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Patient` (
  `patient_ID` int NOT NULL AUTO_INCREMENT,
  `ssn` int NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL,
  `pat_user` int NOT NULL,
  `address_ID` int NOT NULL,
  `clinic_ID` int NOT NULL,
  `prim_doc_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`patient_ID`),
  UNIQUE KEY `ssn` (`ssn`,`pat_user`),
  KEY `pat_user` (`pat_user`),
  KEY `address_ID` (`address_ID`),
  KEY `clinic_ID` (`clinic_ID`),
  KEY `prim_doc_ID` (`prim_doc_ID`),
  CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`pat_user`) REFERENCES `User_Account` (`user_ID`),
  CONSTRAINT `patient_ibfk_2` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`),
  CONSTRAINT `patient_ibfk_3` FOREIGN KEY (`clinic_ID`) REFERENCES `Address` (`address_ID`),
  CONSTRAINT `patient_ibfk_4` FOREIGN KEY (`prim_doc_ID`) REFERENCES `Doctor` (`doc_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Patient`
--

LOCK TABLES `Patient` WRITE;
/*!40000 ALTER TABLE `Patient` DISABLE KEYS */;
INSERT INTO `Patient` VALUES (1,934724786,'Xavier','Oren','Joseph','F',18,19,1,1,0),(2,947136350,'Fern',NULL,'Zane','M',19,20,1,1,0),(3,957050222,'Garrison',NULL,'Clementine','F',21,23,1,2,0),(4,936915549,'Blanche','Furkan','Dominick','F',22,25,1,1,0);
/*!40000 ALTER TABLE `Patient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Receptionist`
--

DROP TABLE IF EXISTS `Receptionist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Receptionist` (
  `rec_ID` int NOT NULL AUTO_INCREMENT,
  `ssn` int NOT NULL,
  `f_name` varchar(20) NOT NULL,
  `m_name` varchar(20) DEFAULT NULL,
  `l_name` varchar(20) NOT NULL,
  `sex` char(1) NOT NULL,
  `rec_user` int NOT NULL,
  `address_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rec_ID`),
  UNIQUE KEY `ssn` (`ssn`,`rec_user`),
  KEY `rec_user` (`rec_user`),
  KEY `address_ID` (`address_ID`),
  CONSTRAINT `receptionist_ibfk_1` FOREIGN KEY (`rec_user`) REFERENCES `User_Account` (`user_ID`),
  CONSTRAINT `receptionist_ibfk_2` FOREIGN KEY (`address_ID`) REFERENCES `Address` (`address_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Receptionist`
--

LOCK TABLES `Receptionist` WRITE;
/*!40000 ALTER TABLE `Receptionist` DISABLE KEYS */;
INSERT INTO `Receptionist` VALUES (1,938003084,'Brighton',NULL,'Amelia','F',20,22,0),(2,694205050,'Bradley',NULL,'Seibert','M',24,27,0);
/*!40000 ALTER TABLE `Receptionist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Referral`
--

DROP TABLE IF EXISTS `Referral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Referral` (
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `primary_ID` int NOT NULL,
  `pat_ID` int NOT NULL,
  `specialist_ID` int NOT NULL,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`date_time`,`primary_ID`),
  KEY `primary_ID` (`primary_ID`),
  KEY `pat_ID` (`pat_ID`),
  KEY `specialist_ID` (`specialist_ID`),
  CONSTRAINT `referral_ibfk_1` FOREIGN KEY (`primary_ID`) REFERENCES `Doctor` (`doc_ID`),
  CONSTRAINT `referral_ibfk_2` FOREIGN KEY (`pat_ID`) REFERENCES `Patient` (`patient_ID`),
  CONSTRAINT `referral_ibfk_3` FOREIGN KEY (`specialist_ID`) REFERENCES `Doctor` (`doc_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Referral`
--

LOCK TABLES `Referral` WRITE;
/*!40000 ALTER TABLE `Referral` DISABLE KEYS */;
INSERT INTO `Referral` VALUES ('2022-11-17 09:15:43',1,1,7,1),('2022-11-17 09:15:48',1,1,11,1),('2022-11-17 09:15:52',1,1,13,1),('2022-11-17 09:16:02',1,1,11,1),('2022-11-17 16:24:32',2,3,7,0),('2022-11-17 18:51:00',2,3,7,0),('2022-11-17 18:51:10',2,3,9,0);
/*!40000 ALTER TABLE `Referral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Transaction`
--

DROP TABLE IF EXISTS `Transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Transaction` (
  `transaction_ID` int NOT NULL AUTO_INCREMENT,
  `patient_ID` int NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `app_ID` int NOT NULL,
  `amount` decimal(6,2) DEFAULT NULL,
  `payment_ID` int DEFAULT NULL,
  PRIMARY KEY (`transaction_ID`),
  KEY `patient_ID` (`patient_ID`),
  KEY `app_ID` (`app_ID`),
  CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `Patient` (`patient_ID`),
  CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`app_ID`) REFERENCES `Appointment` (`app_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Transaction`
--

LOCK TABLES `Transaction` WRITE;
/*!40000 ALTER TABLE `Transaction` DISABLE KEYS */;
INSERT INTO `Transaction` VALUES (1,1,'2022-11-17 15:29:51',3,50.00,18),(2,1,'2022-11-17 15:29:52',5,50.00,NULL),(3,1,'2022-11-17 15:29:53',1,50.00,6),(4,2,'2022-11-17 15:29:54',6,50.00,NULL),(5,2,'2022-11-17 15:32:23',7,50.00,NULL),(6,1,'2022-11-17 15:32:50',1,-50.00,6),(7,1,'2022-11-17 17:57:25',2,50.00,14),(8,1,'2022-11-17 17:57:27',4,50.00,NULL),(9,3,'2022-11-17 17:57:28',8,50.00,NULL),(10,1,'2022-11-17 22:29:02',9,50.00,NULL),(11,4,'2022-11-17 22:29:07',11,50.00,12),(12,4,'2022-11-17 22:31:31',11,-50.00,12),(13,1,'2022-11-17 22:55:30',10,50.00,NULL),(14,1,'2022-11-17 22:57:39',2,-50.00,14),(15,4,'2022-11-17 23:31:53',12,50.00,NULL),(16,4,'2022-11-18 00:56:06',13,50.00,17),(17,4,'2022-11-18 00:57:37',13,-50.00,17),(18,1,'2022-11-18 18:15:06',3,-50.00,18);
/*!40000 ALTER TABLE `Transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Account`
--

DROP TABLE IF EXISTS `User_Account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `User_Account` (
  `user_ID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(35) NOT NULL,
  `user_pass` varchar(100) NOT NULL,
  `user_role` varchar(20) NOT NULL DEFAULT 'patient',
  `user_phone_num` varchar(20) NOT NULL,
  `user_email_address` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Account`
--

LOCK TABLES `User_Account` WRITE;
/*!40000 ALTER TABLE `User_Account` DISABLE KEYS */;
INSERT INTO `User_Account` VALUES (1,'admin1','adminpass','admin','1234567890','admin1@gmail.com','2022-11-16 19:55:44','2022-11-16 19:55:44',0),(2,'doc1','$2y$10$o4ZRe4G8K5Oji0nvkmJ96Orvm903fbFxH3UD5qD1vxgNkaevP61O.','doctor','15110600548','vivian_lesch87@yahoo.com','2022-11-16 20:11:42','2022-11-16 20:11:42',0),(3,'doc2','$2y$10$1.ItLWMUKTxpJfOLAKbvf.qiPS/QuHkVatSg/t8ePV59O39BQUGfG','doctor','14561952571','kassandra.hand@gmail.com','2022-11-16 20:19:52','2022-11-16 20:19:52',0),(4,'doc3','$2y$10$ya35IQAZF5Qor32EUka4auCeeMhDQyXRB4Z9UqHfVC7BtSeqdyD4S','doctor','10010226166','horace77@yahoo.com','2022-11-16 20:23:05','2022-11-16 20:23:05',0),(5,'doc4','$2y$10$oC8dwSNNxSdQ0y/cmKsuaO0qowqy7AA9JVJ5lNGLmD8CBLBKOvAAq','doctor','10558605982','diego.moore56@yahoo.com','2022-11-16 20:26:42','2022-11-16 20:26:42',0),(6,'doc5','$2y$10$T9R3hb8KrxL.pctn8DUCNePhraXPQFDabLAvVPwcLtuvq48ruGWRC','doctor','19098938354','norene61@gmail.com','2022-11-16 20:28:10','2022-11-16 20:28:10',0),(7,'doc6','$2y$10$mqPO.VHc4cpq.BeroB8L/ugMqH8mm1UsbB1FFM1leF9WrKDL/l5Kq','doctor','18169552299','beaulah_kovacek@yahoo.com','2022-11-16 20:30:18','2022-11-16 20:30:18',0),(8,'doc7','$2y$10$1k/zCagtt8BP4JM2BAG0zO.wL0YoUS67Hija0Yldm3jUJXWA0sqaW','doctor','13942961647','aric.oberbrunner82@hotmail.com','2022-11-16 20:33:18','2022-11-16 20:33:18',0),(9,'doc8','$2y$10$SvOYPuFHWlbCjAH/h3KocOcg8E52rtAYYElOHP/IXeUPgNZeyu6Ha','doctor','10960169120','keyshawn_cremin13@yahoo.com','2022-11-16 20:34:31','2022-11-16 20:34:31',0),(10,'doc9','$2y$10$31ECNm2R8v2Zl7f9BX/t..K6Xh6FmFABSyyu8lL/d/7zS/ncIxn8y','doctor','15318514992','loyal58@gmail.com','2022-11-16 20:40:34','2022-11-16 20:40:34',0),(11,'doc10','$2y$10$48PzV0hcgawstuIANJ9hGehLSs2ay2xBupexa79nVjX3dlneQBLjy','doctor','19076907607','jay66@yahoo.com','2022-11-16 20:42:09','2022-11-16 20:42:09',0),(12,'doc11','$2y$10$u.HRLtAqwiE7D.qU3ChMDeNWGwlBOj4WVZ.EA4GyYvDPLxBdjW/fW','doctor','13123864361','wendell_quigley42@gmail.com','2022-11-16 20:45:26','2022-11-16 20:45:26',0),(13,'doc12','$2y$10$j/dbun2Gy0u/BPQQ4LD5/OTViY0Ji0QaUWyJet6Cy.nEXtdeUB1dC','doctor','13399025331','dallin.keeling64@yahoo.com','2022-11-16 20:46:30','2022-11-16 20:46:30',0),(14,'doc13','$2y$10$vKhFbcZTXvLCmx5b1LZ3cuD5ebQpZQiKUyLoN/dAh6FfQO9aPYEmO','doctor','17537768622','maxine77@hotmail.com','2022-11-17 08:45:37','2022-11-17 08:45:37',0),(15,'doc14','$2y$10$gJseTtGxlZ1Jx0a3vj5/D.HK0yD19i6wVtq5SnkV98HjnZ2fFDTIm','doctor','17322164535','vernice.ruecker@yahoo.com','2022-11-17 08:46:47','2022-11-17 08:46:47',0),(16,'doc15','$2y$10$9Yns8U6WLnx3ntH3rRm4l.AwM3iZul76lwOQywtJdhaOJNOyWF8yW','doctor','19528190855','johnson.wilkinson99@hotmail.com','2022-11-17 08:49:57','2022-11-17 08:49:57',0),(17,'doc16','$2y$10$hdXZJ08IwblrtkVz66struKveZuzQpxlz8z5tukvG.VhHN1152YL2','doctor','12550047409','delpha95@gmail.com','2022-11-17 08:51:10','2022-11-17 08:51:10',0),(18,'pat1','$2y$10$qWPITod9bLY8P/jnH4ysI.0XLNEvBDY03w8rpwTczbm6dmVYPaFM.','patient','15674856062','letitia_carter@gmail.com','2022-11-17 09:08:31','2022-11-17 09:08:31',0),(19,'pat2','$2y$10$UeGQCUfayTJV7keoOKLmhufsYz9fvYe7w.YIxWBlKmLWgC6.TgZ5G','patient','12293625277','eloy15@gmail.com','2022-11-17 09:17:35','2022-11-17 09:17:35',0),(20,'rec1','$2y$10$Qzcz5nyQoS5iMRGkxeiHSuLPQJWtqsSQhmR21QLwd6AifxAaGtawO','receptionist','11475285390','tianna.steuber81@yahoo.com','2022-11-17 09:28:53','2022-11-17 09:28:53',0),(21,'pat3','$2y$10$2PG.Y6AG7UsHUJHu5qMsvefhqdqremgbqlW2bLZKCYJ9bDBDxGe1a','patient','11479532276','tyrese_balistreri77@yahoo.com','2022-11-17 10:59:53','2022-11-17 10:59:53',0),(22,'pat4','$2y$10$mqqAdopWSbrdsscOvi2XZOgYCPKrTKwYsaPjsM7Fl8hVJ0UKTjEM2','patient','14930972058','emiliano98@hotmail.com','2022-11-17 15:53:49','2022-11-17 15:53:49',0),(24,'rec2','$2y$10$Ghpw8s0G/3P88OQoJMbptuuRiDXA.AB0wa3zysvzGQMtgWT1S2nvm','receptionist','7773338888','rec2@gmail.com','2022-11-27 04:01:30','2022-11-27 04:01:30',0),(25,'nurse1','$2y$10$rueq9QKCVvgWajNVWw5Qzu5GnNJokjIRV9hj6C6hDLQ4pq2kxv6fC','nurse','1324567799','nurse1@gmail.com','2022-11-27 22:57:32','2022-11-27 22:57:32',0),(26,'nurse2','$2y$10$lyNCWDFMRpB8G6.mHXhmge0Q33IN.twOiRd1zfZpQpYf8VIIoBoxK','nurse','2347982944','JohnMason@gmail.com','2022-11-27 23:25:06','2022-11-27 23:25:06',0),(27,'nurse3','$2y$10$owzWcJNZRNVIf60Jpcur7ePxvmzPykazXZElMhlNY64LeRK4RNXHy','nurse','1269283699','AlexChaplain@gmail.com','2022-11-27 23:26:31','2022-11-27 23:26:31',0),(28,'nurse4','$2y$10$dV7/cAx2NXt71i31TB0Wq.X64aqaojpno3x6VLgcI9jVwZCTCaTpm','nurse','6629980019','PamChavez@gmail.com','2022-11-27 23:28:44','2022-11-27 23:28:44',0),(29,'nurse5','$2y$10$jnquVrbwz.73vJPd.56bOOIaYOWwtEtvaxS33t9Xv828oU4xBg3sy','nurse','6298826195','NadiaDelroba@gmail.com','2022-11-27 23:30:21','2022-11-27 23:30:21',0),(30,'nurse6','$2y$10$lXuQxL6B9Q2vMR/Twxe4UeIhW0F2YD4khNSZbVGCx12X/Kw.9RGR6','nurse','9876662921','LucasFaraday@gmail.com','2022-11-27 23:31:19','2022-11-27 23:31:19',0);
/*!40000 ALTER TABLE `User_Account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'med_clinic_db'
--

--
-- Dumping routines for database 'med_clinic_db'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-27 17:33:39
