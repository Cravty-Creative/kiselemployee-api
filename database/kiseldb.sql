-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: localhost    Database: kiseldb
-- ------------------------------------------------------
-- Server version	8.0.32

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

--
-- Table structure for table `bobot_parameter`
--

DROP TABLE IF EXISTS `bobot_parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bobot_parameter` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int unsigned NOT NULL,
  `param_id` int unsigned NOT NULL,
  `bobot` int NOT NULL,
  `max` int NOT NULL,
  `max_x_bobot` decimal(3,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bobot_parameter_type_id_foreign` (`type_id`),
  KEY `bobot_parameter_param_id_foreign` (`param_id`),
  CONSTRAINT `bobot_parameter_param_id_foreign` FOREIGN KEY (`param_id`) REFERENCES `parameter` (`id`),
  CONSTRAINT `bobot_parameter_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `tipe_karyawan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bobot_parameter`
--

LOCK TABLES `bobot_parameter` WRITE;
/*!40000 ALTER TABLE `bobot_parameter` DISABLE KEYS */;
INSERT INTO `bobot_parameter` VALUES (1,1,1,40,5,2.00,'2022-12-21 10:08:14',NULL),(2,1,2,15,5,0.75,'2022-12-21 10:08:14',NULL),(3,1,3,25,5,1.25,'2022-12-21 10:08:14',NULL),(4,1,4,20,5,1.00,'2022-12-21 10:08:14',NULL),(5,2,1,45,5,2.25,'2022-12-21 10:08:14',NULL),(6,2,2,10,5,0.50,'2022-12-21 10:08:14',NULL),(7,2,3,35,5,1.75,'2022-12-21 10:08:14',NULL),(8,2,4,10,5,0.50,'2022-12-21 10:08:14',NULL);
/*!40000 ALTER TABLE `bobot_parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `karyawan`
--

DROP TABLE IF EXISTS `karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `karyawan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `type_id` int unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spv1_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spv2_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spv1_section` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spv2_section` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `karyawan_user_id_foreign` (`user_id`),
  KEY `karyawan_type_id_foreign` (`type_id`),
  CONSTRAINT `karyawan_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `tipe_karyawan` (`id`),
  CONSTRAINT `karyawan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `karyawan`
--

LOCK TABLES `karyawan` WRITE;
/*!40000 ALTER TABLE `karyawan` DISABLE KEYS */;
INSERT INTO `karyawan` VALUES (2,2,2,'Alfian Aji','Software','Jakarta','Programmer','Anto','Andi','Software','Software','2022-12-22 16:09:20','2023-02-13 14:59:42'),(3,3,1,'Alfiansyah','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:40:25','2022-12-23 14:40:25'),(4,4,1,'Alfiansyah2','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:45:47','2022-12-23 14:45:47'),(5,5,1,'Alfiansyah3','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:46:04','2022-12-23 14:46:04'),(6,6,1,'Alfiansyah4','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:46:12','2022-12-23 14:46:12'),(7,7,1,'Alfiansyah5','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:46:19','2022-12-23 14:46:19'),(8,8,1,'Alfiansyah6','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:46:30','2022-12-23 14:46:30'),(9,9,1,'Alfiansyah7','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:46:40','2022-12-23 14:46:40'),(10,10,1,'Alfiansyah8','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:47:21','2022-12-23 14:47:21'),(11,11,1,'Alfiansyah9','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:47:32','2022-12-23 14:47:32'),(12,12,1,'Alfiansyah10','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:47:40','2022-12-23 14:47:40'),(13,13,1,'Alfiansyah11','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:47:50','2022-12-23 14:47:50'),(14,14,1,'Alfiansyah12','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:47:59','2022-12-23 14:47:59'),(15,15,1,'Alfiansyah13','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:48:07','2022-12-23 14:48:07'),(16,16,1,'Alfiansyah14','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:48:18','2022-12-23 14:48:18'),(17,17,1,'Alfiansyah15','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:48:26','2022-12-23 14:48:26'),(18,18,1,'Alfiansyah16','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:48:36','2022-12-23 14:48:36'),(19,19,1,'Alfiansyah17','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:48:47','2022-12-23 14:48:47'),(20,20,1,'Alfiansyah18','Gudang','Jakarta','Operator Gudang','Anto','Andi','Gudang','Gudang','2022-12-23 14:48:55','2022-12-23 14:48:55');
/*!40000 ALTER TABLE `karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2022_11_17_061128_create_users_table',1),(2,'2022_11_17_062124_create_tipe_karyawan_table',1),(3,'2022_11_17_062730_create_karyawan_table',1),(4,'2022_11_17_063508_create_parameter_table',1),(5,'2022_11_17_071604_create_bobot_parameter_table',1),(6,'2022_11_17_072634_create_parameter_detail_table',1),(7,'2022_11_17_073005_create_nilai_karyawan_table',1),(8,'2022_12_18_094609_create_presensi_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilai_karyawan`
--

DROP TABLE IF EXISTS `nilai_karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai_karyawan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` int unsigned NOT NULL,
  `param_id` int unsigned NOT NULL,
  `bobot_param_id` int unsigned NOT NULL,
  `nilai` decimal(3,2) NOT NULL,
  `nilai_x_bobot` decimal(3,2) NOT NULL,
  `nilai_per_kriteria` decimal(5,2) NOT NULL,
  `periode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skor` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nilai_karyawan_emp_id_foreign` (`emp_id`),
  KEY `nilai_karyawan_param_id_foreign` (`param_id`),
  KEY `nilai_karyawan_bobot_param_id_foreign` (`bobot_param_id`),
  CONSTRAINT `nilai_karyawan_bobot_param_id_foreign` FOREIGN KEY (`bobot_param_id`) REFERENCES `bobot_parameter` (`id`),
  CONSTRAINT `nilai_karyawan_emp_id_foreign` FOREIGN KEY (`emp_id`) REFERENCES `karyawan` (`id`),
  CONSTRAINT `nilai_karyawan_param_id_foreign` FOREIGN KEY (`param_id`) REFERENCES `parameter` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai_karyawan`
--

LOCK TABLES `nilai_karyawan` WRITE;
/*!40000 ALTER TABLE `nilai_karyawan` DISABLE KEYS */;
INSERT INTO `nilai_karyawan` VALUES (1,3,1,1,4.37,1.75,87.39,'March 2023',NULL,'2023-03-31 06:24:41','2023-03-31 06:24:41'),(2,3,2,2,3.67,0.55,73.33,'March 2023','{\"olahraga\": 1, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:24:41','2023-04-02 12:46:20'),(3,3,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:24:41','2023-04-02 12:46:20'),(4,3,4,4,4.80,0.96,96.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 4, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:24:41','2023-04-02 12:46:20'),(5,4,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:25:16','2023-03-31 06:25:16'),(6,4,2,2,1.00,0.15,20.00,'March 2023','{\"olahraga\": 1, \"keagamaan\": 1, \"sharing_session\": 1}','2023-03-31 06:25:16','2023-04-02 12:54:14'),(7,4,3,3,1.00,0.25,20.00,'March 2023','{\"pengetahuan\": 1}','2023-03-31 06:25:16','2023-04-02 12:54:14'),(8,4,4,4,1.00,0.20,20.00,'March 2023','{\"agility\": 1, \"innovation\": 1, \"networking\": 1, \"open_mindset\": 1, \"customer_centric\": 1}','2023-03-31 06:25:16','2023-04-02 12:54:14'),(9,5,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:33:23','2023-03-31 06:33:23'),(10,5,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:33:23','2023-03-31 06:33:23'),(11,5,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:33:23','2023-03-31 06:33:23'),(12,5,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:33:23','2023-03-31 06:33:23'),(13,6,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:33:28','2023-03-31 06:33:28'),(14,6,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:33:28','2023-03-31 06:33:28'),(15,6,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:33:28','2023-03-31 06:33:28'),(16,6,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:33:28','2023-03-31 06:33:28'),(17,7,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:33:32','2023-03-31 06:33:32'),(18,7,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:33:32','2023-03-31 06:33:32'),(19,7,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:33:32','2023-03-31 06:33:32'),(20,7,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:33:32','2023-03-31 06:33:32'),(21,8,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:33:36','2023-03-31 06:33:36'),(22,8,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:33:36','2023-03-31 06:33:36'),(23,8,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:33:36','2023-03-31 06:33:36'),(24,8,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:33:36','2023-03-31 06:33:36'),(25,9,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:05','2023-03-31 06:34:05'),(26,9,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:05','2023-03-31 06:34:05'),(27,9,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:05','2023-03-31 06:34:05'),(28,9,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:05','2023-03-31 06:34:05'),(29,10,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:09','2023-03-31 06:34:09'),(30,10,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:09','2023-03-31 06:34:09'),(31,10,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:09','2023-03-31 06:34:09'),(32,10,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:09','2023-03-31 06:34:09'),(33,11,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:12','2023-03-31 06:34:12'),(34,11,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:12','2023-03-31 06:34:12'),(35,11,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:12','2023-03-31 06:34:12'),(36,11,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:12','2023-03-31 06:34:12'),(37,12,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:14','2023-03-31 06:34:14'),(38,12,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:14','2023-03-31 06:34:14'),(39,12,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:14','2023-03-31 06:34:14'),(40,12,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:14','2023-03-31 06:34:14'),(41,13,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:21','2023-03-31 06:34:21'),(42,13,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:21','2023-03-31 06:34:21'),(43,13,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:21','2023-03-31 06:34:21'),(44,13,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:21','2023-03-31 06:34:21'),(45,14,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:38','2023-03-31 06:34:38'),(46,14,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:38','2023-03-31 06:34:38'),(47,14,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:38','2023-03-31 06:34:38'),(48,14,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:38','2023-03-31 06:34:38'),(49,15,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:41','2023-03-31 06:34:41'),(50,15,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:41','2023-03-31 06:34:41'),(51,15,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:41','2023-03-31 06:34:41'),(52,15,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:41','2023-03-31 06:34:41'),(53,16,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:44','2023-03-31 06:34:44'),(54,16,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:44','2023-03-31 06:34:44'),(55,16,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:44','2023-03-31 06:34:44'),(56,16,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:44','2023-03-31 06:34:44'),(57,17,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:48','2023-03-31 06:34:48'),(58,17,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:48','2023-03-31 06:34:48'),(59,17,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:48','2023-03-31 06:34:48'),(60,17,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:48','2023-03-31 06:34:48'),(61,18,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:51','2023-03-31 06:34:51'),(62,18,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:51','2023-03-31 06:34:51'),(63,18,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:51','2023-03-31 06:34:51'),(64,18,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:51','2023-03-31 06:34:51'),(65,19,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:55','2023-03-31 06:34:55'),(66,19,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:55','2023-03-31 06:34:55'),(67,19,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:55','2023-03-31 06:34:55'),(68,19,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:55','2023-03-31 06:34:55'),(69,20,1,1,0.00,0.00,0.00,'March 2023',NULL,'2023-03-31 06:34:59','2023-03-31 06:34:59'),(70,20,2,2,5.00,0.75,100.00,'March 2023','{\"olahraga\": 5, \"keagamaan\": 5, \"sharing_session\": 5}','2023-03-31 06:34:59','2023-03-31 06:34:59'),(71,20,3,3,5.00,1.25,100.00,'March 2023','{\"pengetahuan\": 5}','2023-03-31 06:34:59','2023-03-31 06:34:59'),(72,20,4,4,5.00,1.00,100.00,'March 2023','{\"agility\": 5, \"innovation\": 5, \"networking\": 5, \"open_mindset\": 5, \"customer_centric\": 5}','2023-03-31 06:34:59','2023-03-31 06:34:59');
/*!40000 ALTER TABLE `nilai_karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parameter`
--

DROP TABLE IF EXISTS `parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parameter` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parameter`
--

LOCK TABLES `parameter` WRITE;
/*!40000 ALTER TABLE `parameter` DISABLE KEYS */;
INSERT INTO `parameter` VALUES (1,'Kehadiran','2022-12-21 10:02:02',NULL),(2,'Keaktifan Mengikuti Kegiatan','2022-12-21 10:02:02',NULL),(3,'Pengetahuan terhadap perkerjaan','2022-12-21 10:02:02',NULL),(4,'Implementasi ACTION','2022-12-21 10:02:02',NULL);
/*!40000 ALTER TABLE `parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parameter_detail`
--

DROP TABLE IF EXISTS `parameter_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parameter_detail` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `param_id` int unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parameter_detail_param_id_foreign` (`param_id`),
  CONSTRAINT `parameter_detail_param_id_foreign` FOREIGN KEY (`param_id`) REFERENCES `parameter` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parameter_detail`
--

LOCK TABLES `parameter_detail` WRITE;
/*!40000 ALTER TABLE `parameter_detail` DISABLE KEYS */;
INSERT INTO `parameter_detail` VALUES (1,1,'Absen Datang','Absen Sebelum Jam 08.00',5,'2022-12-21 10:02:02',NULL),(2,1,'Absen Datang','Absen antara Jam 08.00 - 09.00',4,'2022-12-21 10:02:02',NULL),(3,1,'Absen Datang','Absen diatas Jam 09.00',3,'2022-12-21 10:02:02',NULL),(4,1,'Absen Datang','Tidak Hadir (dengan keterangan)',2,'2022-12-21 10:02:02',NULL),(5,1,'Absen Datang','Tidak Hadir (Tanpa keterangan)',1,'2022-12-21 10:02:02',NULL),(6,1,'Absen Pulang','Absen diatas Jam 17.00',5,'2022-12-21 10:02:02',NULL),(7,1,'Absen Pulang','Absen antara Jam 16.00 - 17.00',4,'2022-12-21 10:02:02',NULL),(8,1,'Absen Pulang','Absen Sebelum Jam 16.00',3,'2022-12-21 10:02:02',NULL),(9,1,'Absen Pulang','Tidak Absen (dengan keterangan)',2,'2022-12-21 10:02:02',NULL),(10,1,'Absen Pulang','Tidak Absen (tanpa keterangan)',1,'2022-12-21 10:02:02',NULL),(11,2,'Olahraga','Hadir minimal 2 kali dalam kegiatan olahraga',5,'2022-12-21 10:02:02',NULL),(12,2,'Olahraga','Hadir 1 Kali dalam kegiatan olahraga',3,'2022-12-21 10:02:02',NULL),(13,2,'Olahraga','Tidak pernah hadir dalam kegiatan olahraga',1,'2022-12-21 10:02:02',NULL),(14,2,'Keagamaan','Melaksanakan Adzan sesuai jadwal',5,'2022-12-21 10:02:02',NULL),(15,2,'Keagamaan','Tidak Melaksanakan Adzan sesuai jadwal',3,'2022-12-21 10:02:02',NULL),(16,2,'Sharing Session','Menyiapkan sharing session sesuai jadwal',5,'2022-12-21 10:02:02',NULL),(17,2,'Sharing Session','Tidak menyiapkan sharing session sesuai jadwal',3,'2022-12-21 10:02:02',NULL),(18,3,'Knowledge','Mengetahui, antusias, dan memiliki rasa ingin tahu terhadap proses pekerjaan',5,'2022-12-21 10:02:02',NULL),(19,3,'Knowledge','Mengetahui dan antusias terhadap proses pekerjaan',4,'2022-12-21 10:02:02',NULL),(20,3,'Knowledge','Mengetahui dan menjalankan proses pekerjaan',3,'2022-12-21 10:02:02',NULL),(21,3,'Knowledge','Membutuhkan bimbingan dan pengawasan dalam pekerjaan',2,'2022-12-21 10:02:02',NULL),(22,3,'Knowledge','Membutuhkan Konseling',1,'2022-12-21 10:02:02',NULL),(23,4,'Agility','Cekatan dalam bekerja & Mudah beradaptasi',5,'2022-12-21 10:02:02',NULL),(24,4,'Customer Centric','Kompeten menyelesaikan pekerjaan',5,'2022-12-21 10:02:02',NULL),(25,4,'Innovation','Mampu memberikan ide-ide baru dalam bekerja',5,'2022-12-21 10:02:02',NULL),(26,4,'Open Mindset','Terbuka terhadap hal - hal baru',5,'2022-12-21 10:02:02',NULL),(27,4,'Networking','Mampu bekerja dalam team',5,'2022-12-21 10:02:02',NULL);
/*!40000 ALTER TABLE `parameter_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presensi`
--

DROP TABLE IF EXISTS `presensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presensi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `hari` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_absen` date NOT NULL,
  `jam` time DEFAULT NULL,
  `status` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `skor` decimal(3,2) NOT NULL DEFAULT '0.00',
  `tipe_absen` enum('Masuk','Pulang') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `presensi_user_id_foreign` (`user_id`),
  CONSTRAINT `presensi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presensi`
--

LOCK TABLES `presensi` WRITE;
/*!40000 ALTER TABLE `presensi` DISABLE KEYS */;
INSERT INTO `presensi` VALUES (1,3,'Rabu','2023-03-01','08:53:18','Alfa',1.00,'Masuk','2023-03-16 03:21:09','2023-03-16 03:21:09','Alfiansyah','Alfiansyah'),(2,3,'Rabu','2023-03-01','17:53:31','Alfa',2.00,'Pulang','2023-03-16 03:22:38','2023-03-16 03:22:38','Alfiansyah','Alfiansyah'),(3,3,'Kamis','2023-03-02','09:00:37','Alfa',2.00,'Masuk','2023-03-16 03:23:18','2023-03-16 03:23:18','Alfiansyah','Alfiansyah'),(4,3,'Kamis','2023-03-02','17:11:44','Alfa',2.00,'Pulang','2023-03-16 03:23:27','2023-03-16 03:23:27','Alfiansyah','Alfiansyah'),(5,3,'Jumat','2023-03-03','10:23:51','Hadir',3.00,'Masuk','2023-03-16 03:23:59','2023-03-16 03:23:59','Alfiansyah','Alfiansyah'),(6,3,'Jumat','2023-03-03','16:23:51','Hadir',4.00,'Pulang','2023-03-16 03:24:07','2023-03-16 03:24:07','Alfiansyah','Alfiansyah'),(7,3,'Senin','2023-03-06','07:23:51','Hadir',5.00,'Masuk','2023-03-16 03:24:33','2023-03-16 03:24:33','Alfiansyah','Alfiansyah'),(8,3,'Senin','2023-03-06','17:00:51','Hadir',5.00,'Pulang','2023-03-16 03:24:46','2023-03-16 03:24:46','Alfiansyah','Alfiansyah'),(9,3,'Selasa','2023-03-07','08:00:51','Hadir',5.00,'Masuk','2023-03-24 07:44:31','2023-03-24 07:44:31','Alfiansyah','Alfiansyah'),(10,3,'Selasa','2023-03-07','17:00:51','Hadir',5.00,'Pulang','2023-03-24 07:45:05','2023-03-24 07:45:05','Alfiansyah','Alfiansyah'),(12,3,'Rabu','2023-03-08','08:45:51','Hadir',4.00,'Masuk','2023-03-24 07:48:49','2023-03-24 07:48:49','Alfiansyah','Alfiansyah'),(13,3,'Rabu','2023-03-08','17:22:51','Hadir',5.00,'Pulang','2023-03-24 07:49:00','2023-03-24 07:49:00','Alfiansyah','Alfiansyah'),(14,3,'Minggu','2023-03-09','09:22:51','Hadir',3.00,'Masuk','2023-03-24 07:49:39','2023-03-24 07:49:39','Alfiansyah','Alfiansyah'),(15,3,'Minggu','2023-03-09','17:22:51','Hadir',5.00,'Pulang','2023-03-24 07:49:47','2023-03-24 07:49:47','Alfiansyah','Alfiansyah'),(16,3,'Jumat','2023-03-10','08:22:51','Hadir',4.00,'Masuk','2023-03-24 07:50:36','2023-03-24 07:50:36','Alfiansyah','Alfiansyah'),(17,3,'Jumat','2023-03-10','17:22:51','Hadir',5.00,'Pulang','2023-03-24 07:50:49','2023-03-24 07:50:49','Alfiansyah','Alfiansyah'),(18,3,'Senin','2023-03-13','08:12:51','Hadir',4.00,'Masuk','2023-03-24 07:51:53','2023-03-24 07:51:53','Alfiansyah','Alfiansyah'),(19,3,'Senin','2023-03-13','17:12:51','Hadir',5.00,'Pulang','2023-03-24 07:52:03','2023-03-24 07:52:03','Alfiansyah','Alfiansyah'),(20,3,'Selasa','2023-03-14','08:00:21','Hadir',4.00,'Masuk','2023-03-24 07:52:17','2023-03-24 07:52:17','Alfiansyah','Alfiansyah'),(21,3,'Selasa','2023-03-14','17:00:21','Hadir',5.00,'Pulang','2023-03-24 07:52:26','2023-03-24 07:52:26','Alfiansyah','Alfiansyah'),(22,3,'Jumat','2023-03-15','08:00:21','Hadir',4.00,'Masuk','2023-03-24 07:54:24','2023-03-24 07:54:24','Alfiansyah','Alfiansyah'),(23,3,'Jumat','2023-03-15','17:00:21','Hadir',5.00,'Pulang','2023-03-24 07:54:47','2023-03-24 07:54:47','Alfiansyah','Alfiansyah'),(24,3,'Kamis','2023-03-16','08:00:21','Hadir',4.00,'Masuk','2023-03-24 07:55:43','2023-03-24 07:55:43','Alfiansyah','Alfiansyah'),(25,3,'Kamis','2023-03-16','17:20:21','Hadir',5.00,'Pulang','2023-03-24 07:55:59','2023-03-24 07:55:59','Alfiansyah','Alfiansyah'),(26,3,'Jumat','2023-03-17','07:56:21','Hadir',5.00,'Masuk','2023-03-24 07:56:14','2023-03-24 07:56:14','Alfiansyah','Alfiansyah'),(27,3,'Jumat','2023-03-17','17:21:21','Hadir',5.00,'Pulang','2023-03-24 07:56:49','2023-03-24 07:56:49','Alfiansyah','Alfiansyah'),(28,3,'Senin','2023-03-20','07:54:21','Hadir',5.00,'Masuk','2023-03-24 07:57:33','2023-03-24 07:57:33','Alfiansyah','Alfiansyah'),(29,3,'Senin','2023-03-20','17:11:21','Hadir',5.00,'Pulang','2023-03-24 07:57:50','2023-03-24 07:57:50','Alfiansyah','Alfiansyah'),(30,3,'Selasa','2023-03-21','08:11:21','Hadir',4.00,'Masuk','2023-03-24 07:58:17','2023-03-24 07:58:17','Alfiansyah','Alfiansyah'),(31,3,'Selasa','2023-03-21','17:11:21','Hadir',5.00,'Pulang','2023-03-24 07:58:25','2023-03-24 07:58:25','Alfiansyah','Alfiansyah'),(32,3,'Rabu','2023-03-22','07:54:21','Hadir',5.00,'Masuk','2023-03-24 07:58:35','2023-03-24 07:58:35','Alfiansyah','Alfiansyah'),(33,3,'Rabu','2023-03-22','16:54:21','Hadir',4.00,'Pulang','2023-03-24 07:58:49','2023-03-24 07:58:49','Alfiansyah','Alfiansyah'),(34,3,'Kamis','2023-03-23','07:54:21','Hadir',5.00,'Masuk','2023-03-24 07:58:57','2023-03-24 07:58:57','Alfiansyah','Alfiansyah'),(35,3,'Kamis','2023-03-23','16:54:21','Hadir',4.00,'Pulang','2023-03-24 07:59:06','2023-03-24 07:59:06','Alfiansyah','Alfiansyah'),(36,3,'Jumat','2023-03-24','08:21:21','Hadir',4.00,'Masuk','2023-03-24 07:59:19','2023-03-24 07:59:19','Alfiansyah','Alfiansyah'),(37,3,'Jumat','2023-03-24','17:22:21','Hadir',5.00,'Pulang','2023-03-24 07:59:30','2023-03-24 07:59:30','Alfiansyah','Alfiansyah'),(38,3,'Senin','2023-03-27','07:22:21','Hadir',5.00,'Masuk','2023-03-24 08:00:08','2023-03-24 08:00:08','Alfiansyah','Alfiansyah'),(39,3,'Senin','2023-03-27','17:22:21','Hadir',5.00,'Pulang','2023-03-24 08:00:14','2023-03-24 08:00:14','Alfiansyah','Alfiansyah'),(40,3,'Selasa','2023-03-28','07:22:21','Hadir',5.00,'Masuk','2023-03-24 08:00:23','2023-03-24 08:00:23','Alfiansyah','Alfiansyah'),(41,3,'Selasa','2023-03-28','17:22:21','Hadir',5.00,'Pulang','2023-03-24 08:00:29','2023-03-24 08:00:29','Alfiansyah','Alfiansyah'),(42,3,'Rabu','2023-03-29','07:56:21','Hadir',5.00,'Masuk','2023-03-24 08:00:39','2023-03-24 08:00:39','Alfiansyah','Alfiansyah'),(43,3,'Rabu','2023-03-29','17:00:21','Hadir',5.00,'Pulang','2023-03-24 08:00:48','2023-03-24 08:00:48','Alfiansyah','Alfiansyah'),(44,3,'Kamis','2023-03-30','07:59:21','Hadir',5.00,'Masuk','2023-03-24 08:01:10','2023-03-24 08:01:10','Alfiansyah','Alfiansyah'),(45,3,'Kamis','2023-03-30','17:21:21','Hadir',5.00,'Pulang','2023-03-24 08:01:19','2023-03-24 08:01:19','Alfiansyah','Alfiansyah'),(46,3,'Jumat','2023-03-31','08:21:21','Hadir',4.00,'Masuk','2023-03-24 08:01:27','2023-03-24 08:01:27','Alfiansyah','Alfiansyah'),(47,3,'Jumat','2023-03-31','17:21:21','Hadir',5.00,'Pulang','2023-03-24 08:01:35','2023-03-24 08:01:35','Alfiansyah','Alfiansyah'),(48,4,'Rabu','2023-03-01','07:55:00','Hadir',5.00,'Masuk','2023-04-03 04:18:37','2023-04-03 06:10:43','Alfiansyah2','Alfiansyah2'),(49,4,'Rabu','2023-03-01','16:45:01','Hadir',4.00,'Pulang','2023-04-03 04:18:37','2023-04-03 06:10:43','Alfiansyah2','Alfiansyah2'),(50,4,'Kamis','2023-03-02','08:00:41','Hadir',5.00,'Masuk','2023-04-03 04:51:14','2023-04-03 04:51:14','Alfiansyah2','Alfiansyah2'),(51,4,'Kamis','2023-03-02','17:24:21','Hadir',5.00,'Pulang','2023-04-03 04:51:14','2023-04-03 04:51:14','Alfiansyah2','Alfiansyah2');
/*!40000 ALTER TABLE `presensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipe_karyawan`
--

DROP TABLE IF EXISTS `tipe_karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipe_karyawan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipe_karyawan`
--

LOCK TABLES `tipe_karyawan` WRITE;
/*!40000 ALTER TABLE `tipe_karyawan` DISABLE KEYS */;
INSERT INTO `tipe_karyawan` VALUES (1,'Inventory'),(2,'Distribution');
/*!40000 ALTER TABLE `tipe_karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role` enum('admin','karyawan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'karyawan',
  `username` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'admin','admin','eyJpdiI6Im0wSmZjdE96T0c3WG1obkt5UDM4SWc9PSIsInZhbHVlIjoiT2syeFlqVGlyR2dRTVg5cjZzZFVHNmpOMW1ycm9CWmtNczFrYVVPT2tJVT0iLCJtYWMiOiIzNjkwZGRjODE0YjljMGVkYmFmMGJmMzM0ZmRiYTgxZmEzN2FjZWFiZDgzNTQzNzM5YWJmMzU5MmQwOWY1Y2EyIiwidGFnIjoiIn0=','2022-12-22 16:09:20','2023-02-13 14:59:42'),(3,'karyawan','alfi123','eyJpdiI6Ik9Rd00wbU5kajBnNU1DQ2lJZnRrRWc9PSIsInZhbHVlIjoiVW02NDV0NjlBM2k5NHFpUCtGNW1wQT09IiwibWFjIjoiM2EwYzFiYTY3ZTRhZTdhZDFiNTM4ZjNhODUwY2NhNDVkNWM4MjkxYjQ4MjUzMTQzYTE0NDJjNDk3NzI3MTI1OSIsInRhZyI6IiJ9','2022-12-23 14:40:25','2022-12-23 14:40:25'),(4,'karyawan','alfi124','eyJpdiI6Im9kRmZ6R1dZcVM4V3dHdHBKYzhSSUE9PSIsInZhbHVlIjoieGQwN0wrM1A4QVZlOVNKcTFoVUh3QT09IiwibWFjIjoiNmEyN2Q3ODFmNzY5ZTE2YjQ5MmNiNWFkMDAyMzU1ZmMzM2ZmOTQwNDI3ZTJiNWIxNTQ5N2E3MmYyMzcwOTVjOCIsInRhZyI6IiJ9','2022-12-23 14:45:47','2022-12-23 14:45:47'),(5,'karyawan','alfi125','eyJpdiI6IjZrbkU4ZTlKa3oxdkVNaDc2Vm14Q3c9PSIsInZhbHVlIjoiYkZXTk5vOE05dkJsa3g3SnZwb2lJQT09IiwibWFjIjoiMDZmYzZhM2RhMmE3MTMzYmJlZDFiZDA3NjUzODNmYzJmYTc1ZTJhZTliNzQ2MmNhZmI0YWQ4NGJmZGQ1NzM3NiIsInRhZyI6IiJ9','2022-12-23 14:46:04','2022-12-23 14:46:04'),(6,'karyawan','alfi126','eyJpdiI6InlQck1mODV3MlpFa1NmcVdiK3VxYmc9PSIsInZhbHVlIjoiWm1ibDYwOXNOYStQUnJWdFZkQWIyZz09IiwibWFjIjoiNDIxYTkxNmU4NjU3YTdlNzVmYTZmOTBlNDRiNzJhMTJkOTRkYjlhNWEyOTBkMTU0MmIzNmYyM2U1NWJlNzBkOSIsInRhZyI6IiJ9','2022-12-23 14:46:12','2022-12-23 14:46:12'),(7,'karyawan','alfi127','eyJpdiI6InY1UHFtd0U3ODBqTm0rKzM2S1h0WXc9PSIsInZhbHVlIjoiMFMxTWFVcG9tQ0MwWDZSRlFVUE9hQT09IiwibWFjIjoiZTFkZDQ0OGRkMTc0Y2Q1MWIzNjFjNTMwYmY0ZjI0OTk1OGIzYzcxMmVlMDdlYWI1NzFlNzgxMDRmYmExMzcxNSIsInRhZyI6IiJ9','2022-12-23 14:46:19','2022-12-23 14:46:19'),(8,'karyawan','alfi128','eyJpdiI6IlBUMVlDaFBFZThjb0tBcTUxYlVDT3c9PSIsInZhbHVlIjoiLzNxU2VybVdMd25JVzBjc2FqN05iQT09IiwibWFjIjoiNGI3OGI4YjgwZTVmYTQ3ZDhmYmRmY2EyODk0MGEyNTI2ZjY3YzFlMzY2ODFmZTQxYjE1MmFkNGM4ZGRjYjkwZCIsInRhZyI6IiJ9','2022-12-23 14:46:30','2022-12-23 14:46:30'),(9,'karyawan','alfi129','eyJpdiI6ImVDd2lMbVVBcWU0V2IybnB1TW8zVHc9PSIsInZhbHVlIjoicW5YNFQrWGdhWjl6b0V6YkVmQjlmUT09IiwibWFjIjoiMGU4OGEwOGMzNzM1NzhhNDNlNzU2NWJlMTExNTZjZTg5MjEwOGVmZmFhN2QxYWMzZmI5N2ZhMTIzNzU5YjM4NCIsInRhZyI6IiJ9','2022-12-23 14:46:40','2022-12-23 14:46:40'),(10,'karyawan','alfi130','eyJpdiI6Iit2ZTVKYm56NGlGdjJ0alBlQVF4V1E9PSIsInZhbHVlIjoiaFV0SDI5ZVE2MjNFVnhXV3pRbC84UT09IiwibWFjIjoiNjQ1OTc4OTU5OTNkNDhiZDE1NWVmYTc2NmQ1OGExYWJlOGM1OGM2MTJlNzVkZTMyMjRjNmY2Yzc0MjI3ZDM4MiIsInRhZyI6IiJ9','2022-12-23 14:47:21','2022-12-23 14:47:21'),(11,'karyawan','alfi131','eyJpdiI6ImZ2ajNGaTkzcEJYdExvUWt3TVllamc9PSIsInZhbHVlIjoiblkwRFV1YnhvdjBpNTNHY2U4b3VuQT09IiwibWFjIjoiMDkzMTkyYjQ0ZmYwM2MxNzdiZGRhNGFkZWMzZjdiOWIyNTkyMjQxZjNlZTY2ZGJhNDIxOWE3NWUyZDQ1NjQwZSIsInRhZyI6IiJ9','2022-12-23 14:47:32','2022-12-23 14:47:32'),(12,'karyawan','alfi132','eyJpdiI6IjJWT0NGeWZ1QTNBQlArNDEyV2Z4OXc9PSIsInZhbHVlIjoiaGoraFZyVUhsYnNuRmhKTjZLa1JRdz09IiwibWFjIjoiY2EyYWIzMmJmMzFjMzUwMmYzZWNjNDk4MDQ3ZmQ4NTk4NTM1OTRmNTJkOWE5ODVhYzBkNmQ2OWYyNDllYjk1ZCIsInRhZyI6IiJ9','2022-12-23 14:47:40','2022-12-23 14:47:40'),(13,'karyawan','alfi133','eyJpdiI6Imw3bkxLbHdIbVZOaDRnMGRWZEhNb1E9PSIsInZhbHVlIjoic0VveHliQyt3eldwZWhPTFVRemh0Zz09IiwibWFjIjoiZGYwMTQ3MTViNTAxN2YzMWI0NjE1YTc2OTkzMmJiNzlkMGY4ODgzZTNiZmVlMDE3YjhiMTJkYmZiZDZkZTRlNiIsInRhZyI6IiJ9','2022-12-23 14:47:50','2022-12-23 14:47:50'),(14,'karyawan','alfi134','eyJpdiI6IjF5bjY1eGZQTHduUEhBTWF3empKZ2c9PSIsInZhbHVlIjoiNU1rQW5nZEpXUTZTSjFiWDdoRVN5Zz09IiwibWFjIjoiNGRkZDRhYzk4ZmI5ZTE1NWY0ZmU0NDIwZmNkNmQwYjFlZDAzM2FkMmFhODMxMzY1NzkzNjI2YmIxYjMzNDlmNyIsInRhZyI6IiJ9','2022-12-23 14:47:59','2022-12-23 14:47:59'),(15,'karyawan','alfi135','eyJpdiI6IjRTRjhaYTl2V1pDSWxTL3pPai9yUGc9PSIsInZhbHVlIjoiWU1rTDk4WVNWSDYrNVp5d0wrZU94Zz09IiwibWFjIjoiYjc1ZDc5Y2M4MjA5ZTNhM2E1ZGEzNjNlMWJlMDQ5MTcxMTI1NDdlNjUzOTY0OTEyOGQ3MjA0MDg1ZmQzODYwYyIsInRhZyI6IiJ9','2022-12-23 14:48:07','2022-12-23 14:48:07'),(16,'karyawan','alfi136','eyJpdiI6Ik9vZWNnSlI1RmsraDVaRnFueVZ3TWc9PSIsInZhbHVlIjoiQW1Vem9HOEE1cGVTY25nTS82c0FxUT09IiwibWFjIjoiZWEwZmRmMDRkYTRiZWE1OWI5ZWU4MzAxNDg3MWVlZGM5MDQ2YzhiNzA5YjNhOTVlYTc4NmFmNzE0MjEwZGE3YyIsInRhZyI6IiJ9','2022-12-23 14:48:18','2022-12-23 14:48:18'),(17,'karyawan','alfi137','eyJpdiI6Ik1GTVFsK0NzWFZKNFpiWkZLQUtuamc9PSIsInZhbHVlIjoiODNRWlNHL2RWMzBaRDg2Yjl5ckt4UT09IiwibWFjIjoiNmUzMWUyZTU4NGI0YjIyZDMwZDIxZjQ1MmZmNjc5ODE0NjUxZTNmMzk1YzIwYjUwMGVhZWQ2MDI4ZDA5ZjdkNSIsInRhZyI6IiJ9','2022-12-23 14:48:26','2022-12-23 14:48:26'),(18,'karyawan','alfi138','eyJpdiI6IkRkREN0aXFvNWROOHFhVnVhb1R3VFE9PSIsInZhbHVlIjoiNjRjSUdNUzMxd3VjcGQ3VDhtZnA1dz09IiwibWFjIjoiYWU5MWNiYTRlNzYyOTExMDZkNjI4OGVmYTQ4MzE3OWI5YjNkMjc5M2NmZDczNGYyN2U2MjU5MjJlODFlYzliMCIsInRhZyI6IiJ9','2022-12-23 14:48:36','2022-12-23 14:48:36'),(19,'karyawan','alfi139','eyJpdiI6IkJ1cEdJYllucDNrOXRXUmdMbGNIVEE9PSIsInZhbHVlIjoiSXpjTTJLbWtwTzdTbXRiVG40c0F1Zz09IiwibWFjIjoiYjA2ODZkNGRjZjg4NDU1NDAwNDQwOTczYjNiYjY5YjYxZjIxMWEyNmMxODlkMWU4NmM0NWJlZGFmMWFjY2U2NyIsInRhZyI6IiJ9','2022-12-23 14:48:47','2022-12-23 14:48:47'),(20,'karyawan','alfi140','eyJpdiI6ImdrU1dvbndIMis2UVVhU1E0L1hLblE9PSIsInZhbHVlIjoiK3MwdHhPRU9YTUlieGMxQm02V3A5Zz09IiwibWFjIjoiNDcxOTU0NGQ4ZjQ2OTIwMTEzZGVjNzhhMWQ4YTY1MWVlMTRlMjBhZWM0MWZiMGNlODBjNGVjNjdlODA2Y2E3OCIsInRhZyI6IiJ9','2022-12-23 14:48:55','2022-12-23 14:48:55');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-03 15:23:44
