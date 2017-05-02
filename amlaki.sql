CREATE DATABASE  IF NOT EXISTS `amlaki` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `amlaki`;
-- MySQL dump 10.13  Distrib 5.7.13, for linux-glibc2.5 (x86_64)
--
-- Host: 127.0.0.1    Database: amlaki
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.16-MariaDB

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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_code_unique` (`code`),
  KEY `category_code_index` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT  IGNORE INTO `category` (`id`, `name`, `code`) VALUES (1,'Cars','CARS'),(2,'Apartments','APARTMENTS'),(3,'Mobiles','MOBILES');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_property`
--

DROP TABLE IF EXISTS `category_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `required` tinyint(1) NOT NULL DEFAULT '1',
  `category_id` int(10) unsigned NOT NULL,
  `property_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_property_category_id_property_id_unique` (`category_id`,`property_id`),
  KEY `category_property_property_id_foreign` (`property_id`),
  CONSTRAINT `category_property_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `category_property_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_property`
--

LOCK TABLES `category_property` WRITE;
/*!40000 ALTER TABLE `category_property` DISABLE KEYS */;
INSERT  IGNORE INTO `category_property` (`id`, `required`, `category_id`, `property_id`) VALUES (1,1,1,1),(2,1,1,2),(3,1,1,3),(4,1,1,4),(5,1,1,5),(6,1,1,6);
/*!40000 ALTER TABLE `category_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=516 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT  IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES (504,'2014_10_12_000000_create_user_table',1),(505,'2014_10_12_100000_create_password_resets_table',1),(506,'2017_03_31_174744_create_property_table',1),(507,'2017_03_31_175058_create_category_table',1),(508,'2017_03_31_175114_create_post_table',1),(509,'2017_03_31_175127_create_post_media_table',1),(510,'2017_03_31_175139_create_request_table',1),(511,'2017_03_31_175244_create_post_property_table',1),(512,'2017_03_31_175408_create_request_property_table',1),(513,'2017_03_31_175420_create_category_property_table',1),(514,'2017_04_22_213313_add_address_column_to_user_table',1),(515,'2017_04_24_184003_create_jobs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'https://dummyimage.com/500x400/0099ff/ffffff.png?text=No+Image',
  `user_id` int(10) unsigned NOT NULL,
  `verified_by` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_user_id_foreign` (`user_id`),
  KEY `post_verified_by_foreign` (`verified_by`),
  KEY `post_category_id_foreign` (`category_id`),
  CONSTRAINT `post_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `post_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `post_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT  IGNORE INTO `post` (`id`, `title`, `image`, `user_id`, `verified_by`, `category_id`, `created_at`, `updated_at`) VALUES (1,'Audi 2003','https://s-media-cache-ak0.pinimg.com/736x/06/b2/e1/06b2e187edb394946759160aa09de94f.jpg',4,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(2,'Audi 2004','http://www.dynastyrentals.com/wp-content/gallery/luxury-cars/luxury-porsche-panamera.png',4,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(3,'Bentley 2015','https://imgct2.aeplcdn.com/img/500/cars/Volkswagen/Passat/Volkswagen-Passat-Top-Tech-Car-In-India.jpg',3,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(4,'Audi 2005','https://imgct2.aeplcdn.com/img/500/cars/Volkswagen/Passat/Volkswagen-Passat-Top-Tech-Car-In-India.jpg',2,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(5,'BMW 2004','https://dummyimage.com/500x400/0099ff/ffffff.png?text=No+Image',4,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(6,'Buick 2014','http://www.aucklandrentalcars.co.nz/uploads/cars/tiida-sedan.jpg',4,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(7,'Bentley 2013','https://static.wixstatic.com/media/85f891_4a8e2e8aa904478b9fae42aad9b37ced~mv2.jpg/v1/fill/w_500,h_500,al_c,q_90/file.jpg',3,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(8,'Bugatti 2009','http://www.dynastyrentals.com/wp-content/gallery/luxury-cars/luxury-porsche-panamera.png',2,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(9,'Audi 2012','http://www.ramsteinusedcars.com/wp-content/uploads/2017/04/3-1-500x400.jpg',4,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(10,'BMW 2013','http://4.bp.blogspot.com/-eJL_KmJ3K7I/VpYtjtcHLKI/AAAAAAAAAQk/lcOMCRWiXbs/s1600/exotic-audi-r8.png',4,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(11,'Bentley 2003','http://www.ramsteinusedcars.com/wp-content/uploads/2017/04/5-500x400.jpg',2,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(12,'Acura 2000','http://4.bp.blogspot.com/-eJL_KmJ3K7I/VpYtjtcHLKI/AAAAAAAAAQk/lcOMCRWiXbs/s1600/exotic-audi-r8.png',2,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(13,'Acura 2009','https://imgct2.aeplcdn.com/img/500/cars/Volkswagen/Passat/Volkswagen-Passat-Top-Tech-Car-In-India.jpg',4,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(14,'Bentley 2009','https://imgct2.aeplcdn.com/img/500/cars/Volkswagen/Passat/Volkswagen-Passat-Top-Tech-Car-In-India.jpg',2,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(15,'Buick 2002','https://static.wixstatic.com/media/85f891_4a8e2e8aa904478b9fae42aad9b37ced~mv2.jpg/v1/fill/w_500,h_500,al_c,q_90/file.jpg',3,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41'),(16,'Bugatti 2012','http://www.ramsteinusedcars.com/wp-content/uploads/2017/04/5-500x400.jpg',3,1,1,'2017-04-29 09:10:41','2017-04-29 09:10:41');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_media`
--

DROP TABLE IF EXISTS `post_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('VIEDO','IMAGE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_media_post_id_foreign` (`post_id`),
  CONSTRAINT `post_media_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_media`
--

LOCK TABLES `post_media` WRITE;
/*!40000 ALTER TABLE `post_media` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_property`
--

DROP TABLE IF EXISTS `post_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `property_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_property_post_id_property_id_unique` (`post_id`,`property_id`),
  KEY `post_property_property_id_foreign` (`property_id`),
  CONSTRAINT `post_property_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  CONSTRAINT `post_property_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_property`
--

LOCK TABLES `post_property` WRITE;
/*!40000 ALTER TABLE `post_property` DISABLE KEYS */;
INSERT  IGNORE INTO `post_property` (`id`, `value`, `post_id`, `property_id`) VALUES (1,'241722',1,1),(2,'Audi 2003',1,2),(3,'2003',1,3),(4,'أزرق داكن',1,4),(5,'3 + 1',1,5),(6,'1959',1,6),(7,'324550',2,1),(8,'Audi 2004',2,2),(9,'2004',2,3),(10,'زيتوني',2,4),(11,'3 + 1',2,5),(12,'1923',2,6),(13,'470631',3,1),(14,'Bentley 2015',3,2),(15,'2015',3,3),(16,'ليمي',3,4),(17,'3 + 1',3,5),(18,'1738',3,6),(19,'994632',4,1),(20,'Audi 2005',4,2),(21,'2005',4,3),(22,'أخضر',4,4),(23,'3 + 1',4,5),(24,'1353',4,6),(25,'491067',5,1),(26,'BMW 2004',5,2),(27,'2004',5,3),(28,'بني',5,4),(29,'3 + 1',5,5),(30,'827',5,6),(31,'313609',6,1),(32,'Buick 2014',6,2),(33,'2014',6,3),(34,'أبيض',6,4),(35,'3 + 1',6,5),(36,'1501',6,6),(37,'338021',7,1),(38,'Bentley 2013',7,2),(39,'2013',7,3),(40,'قرمزي',7,4),(41,'3 + 1',7,5),(42,'1335',7,6),(43,'799688',8,1),(44,'Bugatti 2009',8,2),(45,'2009',8,3),(46,'أسود',8,4),(47,'6 + 1',8,5),(48,'1483',8,6),(49,'361297',9,1),(50,'Audi 2012',9,2),(51,'2012',9,3),(52,'أصفر',9,4),(53,'6 + 1',9,5),(54,'1143',9,6),(55,'800067',10,1),(56,'BMW 2013',10,2),(57,'2013',10,3),(58,'أخضر',10,4),(59,'6 + 1',10,5),(60,'1412',10,6),(61,'499121',11,1),(62,'Bentley 2003',11,2),(63,'2003',11,3),(64,'زيتوني',11,4),(65,'3 + 1',11,5),(66,'884',11,6),(67,'731795',12,1),(68,'Acura 2000',12,2),(69,'2000',12,3),(70,'أحمر',12,4),(71,'3 + 1',12,5),(72,'815',12,6),(73,'546034',13,1),(74,'Acura 2009',13,2),(75,'2009',13,3),(76,'بني',13,4),(77,'3 + 1',13,5),(78,'1293',13,6),(79,'289213',14,1),(80,'Bentley 2009',14,2),(81,'2009',14,3),(82,'فضي',14,4),(83,'6 + 1',14,5),(84,'856',14,6),(85,'153412',15,1),(86,'Buick 2002',15,2),(87,'2002',15,3),(88,'أرجواني',15,4),(89,'6 + 1',15,5),(90,'1178',15,6),(91,'315900',16,1),(92,'Bugatti 2012',16,2),(93,'2012',16,3),(94,'أبيض',16,4),(95,'6 + 1',16,5),(96,'909',16,6);
/*!40000 ALTER TABLE `post_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property`
--

DROP TABLE IF EXISTS `property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_type` enum('STRING','NUMBER','FLOAT','COLOR','RANGE','SELECT','MULTI_SELECT','MEDIA','RADIO','MULTI_SELECT_CHECKBOX','DATE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'STRING',
  `extra_settings` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `property_code_unique` (`code`),
  KEY `property_code_index` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property`
--

LOCK TABLES `property` WRITE;
/*!40000 ALTER TABLE `property` DISABLE KEYS */;
INSERT  IGNORE INTO `property` (`id`, `title`, `code`, `value_type`, `extra_settings`) VALUES (1,'Price','PRICE','NUMBER','{\"hint\":\"40000\",\"currency\":\"NIS\"}'),(2,'Car Model','CAR_MODEL','STRING','{\"hint\":\"Peugeot i3 2016\"}'),(3,'Model Year','CAR_MODEL_YEAR','DATE','{\"min\":\"1965\",\"max\":\"NOW\",\"hint\":\"2016\"}'),(4,'Car Color','CAR_COLOR','STRING','{\"hint\":\"silver\"}'),(5,'Number OF Passengers','CAR_PASSENGERS_COUNT','STRING','{\"hint\":\"3 + 1\"}'),(6,'Engine Power','CAR_ENGINE_POWER','NUMBER','{\"hint\":\"1600\"}');
/*!40000 ALTER TABLE `property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `request_user_id_foreign` (`user_id`),
  KEY `request_category_id_foreign` (`category_id`),
  CONSTRAINT `request_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `request_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT  IGNORE INTO `request` (`id`, `user_id`, `category_id`, `created_at`, `updated_at`) VALUES (1,2,1,'2017-04-29 09:10:42','2017-04-29 09:10:42');
/*!40000 ALTER TABLE `request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_property`
--

DROP TABLE IF EXISTS `request_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_id` int(10) unsigned NOT NULL,
  `property_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_property_request_id_property_id_unique` (`request_id`,`property_id`),
  KEY `request_property_property_id_foreign` (`property_id`),
  CONSTRAINT `request_property_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`),
  CONSTRAINT `request_property_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_property`
--

LOCK TABLES `request_property` WRITE;
/*!40000 ALTER TABLE `request_property` DISABLE KEYS */;
INSERT  IGNORE INTO `request_property` (`id`, `value`, `request_id`, `property_id`) VALUES (1,'40000',1,1),(2,'Peugeot i3 2016',1,2),(3,'2016',1,3),(4,'ALL',1,4),(5,'3 + 1',1,5),(6,'1600',1,6);
/*!40000 ALTER TABLE `request_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `phone` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username_unique` (`username`),
  UNIQUE KEY `user_email_unique` (`email`),
  UNIQUE KEY `user_phone_unique` (`phone`),
  KEY `user_username_index` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT  IGNORE INTO `user` (`id`, `name`, `username`, `email`, `password`, `is_admin`, `phone`, `api_token`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `address`) VALUES (1,'Admin','admin','admin@amlaki.com','$2y$10$nu0LG.MzTS7PMk4kem1obuguILwlCmAR7ljrj8/JtuHX2DCiuqS3.',1,'059316438',NULL,NULL,'2017-04-29 09:10:41','2017-04-29 09:10:41',NULL,'847 Arturo Mountain Suite 561\nPort Eva, IN 70234'),(2,'test','test','test@amlaki.com','$2y$10$nu0LG.MzTS7PMk4kem1obuguILwlCmAR7ljrj8/JtuHX2DCiuqS3.',0,'0562912552','62398a78$b16d$54dd$a56b$41d2b4876877','cM4RToPDgEmdMNEKmxUSHUgtNl4RaagZSgUfdy8ejzobMXpDihyy9YznM2po','2017-04-29 09:10:41','2017-04-29 09:18:56',NULL,'719 Huels Inlet\nSouth Rupert, MD 64672-0918'),(3,'Rosendo Jacobi','mzboncak','grunolfsson@example.org','$2y$10$nu0LG.MzTS7PMk4kem1obuguILwlCmAR7ljrj8/JtuHX2DCiuqS3.',0,'0594603910',NULL,NULL,'2017-04-29 09:10:41','2017-04-29 09:10:41',NULL,'5078 Delmer Dam\nO\'Reillyberg, UT 13618-8862'),(4,'Keshaun Denesik','pagac.zoie','west.dawson@example.org','$2y$10$nu0LG.MzTS7PMk4kem1obuguILwlCmAR7ljrj8/JtuHX2DCiuqS3.',0,'0568096930',NULL,NULL,'2017-04-29 09:10:41','2017-04-29 09:10:41',NULL,'726 Smith Valley\nJeraldside, CT 97026-8443'),(5,'Rashad Bernhard','elwin29','brown67@example.org','$2y$10$nu0LG.MzTS7PMk4kem1obuguILwlCmAR7ljrj8/JtuHX2DCiuqS3.',0,'0594720754',NULL,NULL,'2017-04-29 09:10:41','2017-04-29 09:10:41',NULL,'6580 Parker Knoll\nTonitown, CA 43207'),(6,'Terrance Gusikowski','zturner','lmills@example.com','$2y$10$nu0LG.MzTS7PMk4kem1obuguILwlCmAR7ljrj8/JtuHX2DCiuqS3.',0,'0561520202',NULL,NULL,'2017-04-29 09:10:41','2017-04-29 09:10:41',NULL,'43274 Trantow Skyway\nSouth Mayramouth, OR 44718-7980'),(7,'Lane Johnson','ckshlerin','houston86@example.com','$2y$10$nu0LG.MzTS7PMk4kem1obuguILwlCmAR7ljrj8/JtuHX2DCiuqS3.',0,'0593408003',NULL,NULL,'2017-04-29 09:10:41','2017-04-29 12:38:31','2017-04-29 12:38:31','315 Kuvalis Spurs\nAaliyahshire, PA 50146-3430');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-05-01 23:10:27
