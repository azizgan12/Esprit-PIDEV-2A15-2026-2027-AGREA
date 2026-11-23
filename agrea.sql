-- XAMPP-Lite
-- version 8.4.1
-- https://xampplite.sf.net/
--
-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: 127.0.0.1	Database: agrea
-- ------------------------------------------------------
-- Server version 	11.4.4-MariaDB-log
-- Date: Mon, 23 Nov 2026 12:52:29 +0000

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `farms`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `farms` (
  `farm_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `farm_name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `area_size` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`farm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `farms`
--

INSERT INTO `farms` (`farm_id`, `user_id`, `farm_name`, `location`, `area_size`, `created_at`) VALUES (1,1,'Green Valley Farm','Sfax, Tunisia',15.00,'2026-11-18 08:59:24');
INSERT INTO `farms` (`farm_id`, `user_id`, `farm_name`, `location`, `area_size`, `created_at`) VALUES (2,1,'Olive Garden','Sousse, Tunisia',10.00,'2026-11-18 08:59:24');
INSERT INTO `farms` (`farm_id`, `user_id`, `farm_name`, `location`, `area_size`, `created_at`) VALUES (3,3,'lola','tun',10.00,'2026-11-23 11:17:29');

-- Dumped table `farms` with 3 row(s)
--

--
-- Table structure for table `farm_trees`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `farm_trees` (
  `farm_tree_id` int(11) NOT NULL AUTO_INCREMENT,
  `farm_id` int(11) NOT NULL,
  `tree_type` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `area_size` decimal(10,2) NOT NULL DEFAULT 0.00,
  `planting_year` year(4) DEFAULT NULL,
  PRIMARY KEY (`farm_tree_id`),
  KEY `farm_id` (`farm_id`),
  CONSTRAINT `farm_trees_ibfk_1` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `farm_trees`
--

INSERT INTO `farm_trees` (`farm_tree_id`, `farm_id`, `tree_type`, `quantity`, `area_size`, `planting_year`) VALUES (1,1,'Olive',500,8.00,'2018');
INSERT INTO `farm_trees` (`farm_tree_id`, `farm_id`, `tree_type`, `quantity`, `area_size`, `planting_year`) VALUES (2,1,'Orange',200,4.00,'2020');
INSERT INTO `farm_trees` (`farm_tree_id`, `farm_id`, `tree_type`, `quantity`, `area_size`, `planting_year`) VALUES (3,1,'Lemon',100,3.00,'2022');
INSERT INTO `farm_trees` (`farm_tree_id`, `farm_id`, `tree_type`, `quantity`, `area_size`, `planting_year`) VALUES (4,2,'Olive',350,10.00,'2019');
INSERT INTO `farm_trees` (`farm_tree_id`, `farm_id`, `tree_type`, `quantity`, `area_size`, `planting_year`) VALUES (5,1,'Almond',150,3.00,'2023');

-- Dumped table `farm_trees` with 5 row(s)
--

--
-- Table structure for table `trees`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trees` (
  `farm_tree_id` int(11) NOT NULL AUTO_INCREMENT,
  `farm_id` int(11) NOT NULL,
  `tree_type` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `area_size` decimal(10,2) NOT NULL DEFAULT 0.00,
  `planting_year` year(4) NOT NULL,
  PRIMARY KEY (`farm_tree_id`),
  KEY `idx_trees_farm_id` (`farm_id`),
  CONSTRAINT `fk_trees_farm` FOREIGN KEY (`farm_id`) REFERENCES `farms` (`farm_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trees`
--

INSERT INTO `trees` (`farm_tree_id`, `farm_id`, `tree_type`, `quantity`, `area_size`, `planting_year`) VALUES (4,3,'olive',10,1.00,'2000');
INSERT INTO `trees` (`farm_tree_id`, `farm_id`, `tree_type`, `quantity`, `area_size`, `planting_year`) VALUES (5,3,'olive 2',1111,200.00,'2000');

-- Dumped table `trees` with 2 row(s)
--

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `mail` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('farmer','expert','admin') NOT NULL DEFAULT 'farmer',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `birth_date`, `country`, `phone_number`, `mail`, `password`, `profile_picture`, `role`, `created_at`) VALUES (3,'amira belhassen',NULL,'Tunisie','98553512','amira@gmail.com','$2y$12$CVV7zWgh/zdE1Ce20xOAFuxkZKzVtRotJyV/CSy8gnANe3GzrRMWS',NULL,'farmer','2026-11-23 09:58:47');
INSERT INTO `users` (`user_id`, `name`, `birth_date`, `country`, `phone_number`, `mail`, `password`, `profile_picture`, `role`, `created_at`) VALUES (4,'AGREA Admin',NULL,NULL,NULL,'admin@agrea.com','$2y$12$tbLnkb9Mk5uRRCRap523h.GjnDsL6hWahb32d7OI4fO6ilY/A9fHS',NULL,'admin','2026-11-23 10:29:31');
INSERT INTO `users` (`user_id`, `name`, `birth_date`, `country`, `phone_number`, `mail`, `password`, `profile_picture`, `role`, `created_at`) VALUES (5,'abdelaziz gandouz','2000-11-11','Tunisie','20007661','abdelaziz.gandouz@eprit.com','$2y$12$n/nEZLVd4p8u4GYYvohTv.GHZ9KZupDqs0D16ovtMiqb4ZYkneguS',NULL,'farmer','2026-11-23 11:05:55');

-- Dumped table `users` with 3 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Mon, 23 Nov 2026 12:52:29 +0000
