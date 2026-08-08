/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: pos_db
-- ------------------------------------------------------
-- Server version	10.11.18-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('laravel-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:2:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:15:\"manage products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:13:\"process sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:7:\"cashier\";s:1:\"c\";s:3:\"web\";}}}',1786059916);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
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
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_07_17_062901_create_products_table',1),
(5,'2026_07_18_001401_create_sales_table',1),
(6,'2026_07_18_001402_create_sale_items_table',1),
(7,'2026_07_18_020649_create_personal_access_tokens_table',1),
(8,'2026_07_25_223217_create_permission_tables',1),
(9,'2026_08_03_055711_add_category_to_products_table',2),
(10,'2026_08_05_161127_add_is_active_to_products_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES
(1,'App\\Models\\User',2),
(2,'App\\Models\\User',1);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'manage products','web','2026-07-30 02:48:01','2026-07-30 02:48:01'),
(2,'process sales','web','2026-07-30 02:48:01','2026-07-30 02:48:01');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES
(1,'App\\Models\\User',1,'pos_auth_token','192a96d441d86b0445229c61712f59b6cbfe7c38455fa760e3cdd5977a3c135b','[\"*\"]','2026-07-30 02:52:03',NULL,'2026-07-30 02:51:33','2026-07-30 02:52:03'),
(2,'App\\Models\\User',1,'pos_auth_token','4f7292b7e44cd9a038c84ac04a9afa40981cb3f1836f32289e3ea88ef39da3ff','[\"*\"]','2026-07-30 02:52:19',NULL,'2026-07-30 02:52:19','2026-07-30 02:52:19'),
(3,'App\\Models\\User',1,'pos_auth_token','9718bbe685e1fd247a37dcad862a4b44e1ebd5313af72204cacde81ce9fa7f01','[\"*\"]','2026-08-02 20:50:45',NULL,'2026-07-30 04:41:44','2026-08-02 20:50:45'),
(4,'App\\Models\\User',1,'pos_auth_token','a94c44fbe86fd397b2dd7f763175c46f779a07e9e148d0e174f276a20fdfaa2b','[\"*\"]','2026-07-30 13:33:02',NULL,'2026-07-30 13:29:40','2026-07-30 13:33:02'),
(5,'App\\Models\\User',1,'pos_auth_token','658245b9152a717dd14d65ae0bef582620eeb1d090b37982b86c5b27f7af2baf','[\"*\"]','2026-07-30 13:33:19',NULL,'2026-07-30 13:33:19','2026-07-30 13:33:19'),
(6,'App\\Models\\User',2,'pos_auth_token','b7a29c7f326190516685df496dfc3b56cb1b3ebc4b9025189f717ac44a8b73a6','[\"*\"]','2026-07-30 13:34:27',NULL,'2026-07-30 13:33:42','2026-07-30 13:34:27'),
(7,'App\\Models\\User',1,'pos_auth_token','4f5f7c4658ad698d9fae34422327ab487f366cf417368dfe57ee85b553bdb2fc','[\"*\"]','2026-07-30 13:43:03',NULL,'2026-07-30 13:34:42','2026-07-30 13:43:03'),
(8,'App\\Models\\User',1,'pos_auth_token','f3dcdd49a5027b39001a3f8c75704f100abb471671e44ddf55ed53427919bfdd','[\"*\"]','2026-07-30 13:59:57',NULL,'2026-07-30 13:43:32','2026-07-30 13:59:57'),
(9,'App\\Models\\User',1,'pos_auth_token','b4941a13bcdda450676e9bc68277a5271cce805c860111988b590afd0123438e','[\"*\"]','2026-08-02 21:01:30',NULL,'2026-08-02 20:50:57','2026-08-02 21:01:30'),
(10,'App\\Models\\User',1,'pos_auth_token','40dbf67d1bd84c303eb85fd21122760fd7be7d8c20ac03d7b6f5bf8276db5d5c','[\"*\"]','2026-08-05 23:32:00',NULL,'2026-08-02 21:01:43','2026-08-05 23:32:00'),
(11,'App\\Models\\User',1,'pos_auth_token','99f7255341369066f6783b5078e84970e6a44f6ed97aa0d3136f6bbd79735874','[\"*\"]','2026-08-05 23:38:50',NULL,'2026-08-05 23:32:14','2026-08-05 23:38:50'),
(12,'App\\Models\\User',1,'pos_auth_token','3dd347ea3135240c127d750584911bb2990ae51745af048b954fe8ae05a42ebe','[\"*\"]','2026-08-05 23:44:56',NULL,'2026-08-05 23:44:52','2026-08-05 23:44:56'),
(13,'App\\Models\\User',1,'pos_auth_token','869821de41b53a2f1b6aa8caf6858e7f867ea97f5f8e5281598dbca8e968e098','[\"*\"]','2026-08-05 23:45:16',NULL,'2026-08-05 23:45:16','2026-08-05 23:45:16'),
(14,'App\\Models\\User',1,'pos_auth_token','2d442e4e878932a3b6bcb9898de6224fb5b9be8744f72a79daf53c3174d5d982','[\"*\"]','2026-08-06 00:24:08',NULL,'2026-08-06 00:23:41','2026-08-06 00:24:08'),
(15,'App\\Models\\User',2,'pos_auth_token','441b7acdd59afbb5755e6cd865dad29933b9d49ba1746a242a6343f79726dcfd','[\"*\"]','2026-08-06 00:24:42',NULL,'2026-08-06 00:24:41','2026-08-06 00:24:42'),
(16,'App\\Models\\User',1,'pos_auth_token','9243dfb983575b8c484949dcdd66c7115121673b9958cbb0856c15056c3a592b','[\"*\"]','2026-08-06 00:24:53',NULL,'2026-08-06 00:24:52','2026-08-06 00:24:53'),
(17,'App\\Models\\User',2,'pos_auth_token','13e690191038a74c24845e81a05f7e9d5566e790170a73ce18c4043aa4613be3','[\"*\"]','2026-08-06 00:27:41',NULL,'2026-08-06 00:25:27','2026-08-06 00:27:41'),
(18,'App\\Models\\User',1,'pos_auth_token','a1f933f1a20a8c2e25760f6bd5eb1dfedfd9ecde5673f02f5af28822ebbbb04d','[\"*\"]','2026-08-06 00:28:07',NULL,'2026-08-06 00:28:06','2026-08-06 00:28:07'),
(19,'App\\Models\\User',2,'pos_auth_token','a026f3a62735179195a8843f59441eb61d1e524796bcab88d67e9164033c988b','[\"*\"]','2026-08-06 00:32:18',NULL,'2026-08-06 00:31:20','2026-08-06 00:32:18'),
(20,'App\\Models\\User',2,'pos_auth_token','d3fd9a3d5c01c090c9ca401fd5e97e6a44375251d16eef107fce846cf849f0c4','[\"*\"]','2026-08-06 00:32:35',NULL,'2026-08-06 00:32:31','2026-08-06 00:32:35'),
(21,'App\\Models\\User',1,'pos_auth_token','6f22dda88be4bda32d62dde7261af6ec6adb6b2dfa70b6565c74ab69a25e309e','[\"*\"]','2026-08-06 00:32:46',NULL,'2026-08-06 00:32:43','2026-08-06 00:32:46'),
(22,'App\\Models\\User',1,'pos_auth_token','8b62a09acc782b61882bc75cf309c3799bd996bcddaa04f60262a648359d1c10','[\"*\"]','2026-08-06 01:17:52',NULL,'2026-08-06 01:17:26','2026-08-06 01:17:52'),
(23,'App\\Models\\User',1,'pos_auth_token','7007ce612936bf44ac9104db6566e9753c57145800d91fb9678baf35311737ed','[\"*\"]','2026-08-06 01:17:59',NULL,'2026-08-06 01:17:59','2026-08-06 01:17:59'),
(24,'App\\Models\\User',1,'pos_auth_token','2ae14c3db1ce39507ae62bd10cc777e2db9dac160e68019ebaff3639d6d86d82','[\"*\"]','2026-08-06 01:18:16',NULL,'2026-08-06 01:18:13','2026-08-06 01:18:16'),
(25,'App\\Models\\User',1,'pos_auth_token','3c4ad53d80f0bf7c4fa03136c61339b384d24dbe2fecda8ffd44ca4039de7edf','[\"*\"]','2026-08-06 01:19:37',NULL,'2026-08-06 01:19:05','2026-08-06 01:19:37'),
(26,'App\\Models\\User',1,'pos_auth_token','fc9043fc92956288ae9e9b0fb8621ed22967ea8fa1976dc36ddaaf0f85a91b88','[\"*\"]','2026-08-06 04:30:21',NULL,'2026-08-06 01:23:52','2026-08-06 04:30:21'),
(27,'App\\Models\\User',1,'pos_auth_token','70f3186ea75318abfcead2d6de5d7aa719994bd60c39fc4d957274d3f71a6adb','[\"*\"]','2026-08-08 04:23:52',NULL,'2026-08-08 04:23:50','2026-08-08 04:23:52');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'General',
  `purchase_price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(1,'LAC001','Leche Lala 1L','General',18.00,24.00,40,1,'2026-07-30 02:48:01','2026-08-06 00:31:48'),
(2,'PAN001','Pan Bimbo Grande','General',42.00,55.00,30,1,'2026-07-30 02:48:01','2026-08-06 01:19:26'),
(3,'BEB001','Coca-Cola 600ml','General',12.00,18.00,50,1,'2026-07-30 02:48:01','2026-08-06 02:24:58'),
(4,'BOT001','Sabritas Clásicas 45g','General',10.00,15.00,20,1,'2026-07-30 02:48:01','2026-08-06 00:31:48'),
(5,'LIM001','Jabón Palmolive 150g','General',20.00,28.00,20,0,'2026-07-30 02:48:01','2026-08-05 22:17:00'),
(6,'BAS001','Arroz Morelos 1kg','General',24.00,34.00,20,0,'2026-07-30 02:48:01','2026-08-05 23:11:12'),
(7,'0815154022705','Predator Energy 473ml','General',0.00,0.00,20,1,'2026-08-03 05:36:36','2026-08-05 22:09:07');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES
(1,2),
(2,1),
(2,2);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'cashier','web','2026-07-30 02:48:01','2026-07-30 02:48:01'),
(2,'admin','web','2026-07-30 02:48:01','2026-07-30 02:48:01');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES
(1,1,3,1,18.00,'2026-07-30 07:07:25','2026-07-30 07:07:25'),
(2,1,4,5,15.00,'2026-07-30 07:07:25','2026-07-30 07:07:25'),
(3,2,6,2,34.00,'2026-07-30 07:16:16','2026-07-30 07:16:16'),
(4,3,6,1,34.00,'2026-07-30 07:16:37','2026-07-30 07:16:37'),
(5,4,6,1,34.00,'2026-07-30 13:30:43','2026-07-30 13:30:43'),
(6,4,4,1,15.00,'2026-07-30 13:30:43','2026-07-30 13:30:43'),
(7,4,3,1,18.00,'2026-07-30 13:30:43','2026-07-30 13:30:43'),
(8,4,2,1,55.00,'2026-07-30 13:30:43','2026-07-30 13:30:43'),
(9,4,1,1,24.00,'2026-07-30 13:30:43','2026-07-30 13:30:43'),
(10,4,5,1,28.00,'2026-07-30 13:30:43','2026-07-30 13:30:43'),
(11,5,6,1,34.00,'2026-08-03 05:05:48','2026-08-03 05:05:48'),
(12,6,4,1,15.00,'2026-08-03 05:26:13','2026-08-03 05:26:13'),
(13,7,1,7,24.00,'2026-08-06 00:31:48','2026-08-06 00:31:48'),
(14,7,2,1,55.00,'2026-08-06 00:31:48','2026-08-06 00:31:48'),
(15,7,3,9,18.00,'2026-08-06 00:31:48','2026-08-06 00:31:48'),
(16,7,4,8,15.00,'2026-08-06 00:31:48','2026-08-06 00:31:48'),
(17,8,3,5,18.00,'2026-08-06 01:25:14','2026-08-06 01:25:14'),
(18,9,3,5,18.00,'2026-08-06 02:24:58','2026-08-06 02:24:58');
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_ticket_number_unique` (`ticket_number`),
  KEY `sales_user_id_foreign` (`user_id`),
  CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES
(1,1,'TK-FYMOQXQN-1785395245',93.00,'efectivo','2026-07-30 07:07:25','2026-07-30 07:07:25'),
(2,1,'TK-ATV5EEKP-1785395776',68.00,'efectivo','2026-07-30 07:16:16','2026-07-30 07:16:16'),
(3,1,'TK-SOHIWFZF-1785395797',34.00,'efectivo','2026-07-30 07:16:37','2026-07-30 07:16:37'),
(4,1,'TK-KMXHAB9R-1785418243',174.00,'efectivo','2026-07-30 13:30:43','2026-07-30 13:30:43'),
(5,1,'TK-NC1KPO4T-1785733548',34.00,'efectivo','2026-08-03 05:05:48','2026-08-03 05:05:48'),
(6,1,'TK-6IICPFCU-1785734773',15.00,'efectivo','2026-08-03 05:26:13','2026-08-03 05:26:13'),
(7,2,'TK-RVIPMEVL-1785976308',505.00,'efectivo','2026-08-06 00:31:48','2026-08-06 00:31:48'),
(8,1,'TK-X8NYRZQS-1785979514',90.00,'efectivo','2026-08-06 01:25:14','2026-08-06 01:25:14'),
(9,1,'TK-TTGX1EP6-1785983098',90.00,'efectivo','2026-08-06 02:24:58','2026-08-06 02:24:58');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('6bAD98Uu4TV1uLsXal1mnRpg2ScmQ4TRpAluHO2r',NULL,'189.226.254.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGpiZWRJdmxTNWNWZHdPS3dXdTZ1NUp1Rkwza0NIQWZiSjNncUJFRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYnVnLWZyZWUtcm9ib3QtNGo5Z3g3ajk2dnBqZmp2NjUtODAuYXBwLmdpdGh1Yi5kZXYiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785704492),
('6gnMkqxHGhbbQt051TeMZrJWRbo1o9SUuiU2acHd',NULL,'172.18.0.1','Mozilla/5.0 (compatible; Nmap Scripting Engine; https://nmap.org/book/nse.html)','YToyOntzOjY6Il90b2tlbiI7czo0MDoibDlWMG1FcW1zeUw2TTQ1UTNyNUMxSjdqUGx5RTFoRTYzaExqWnBZNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785736439),
('6qxjeHlehQZIqLzmsUT3XTLiM0EHcrSd3HYycrdX',NULL,'201.102.126.250','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2lQSmZoNHRXUGR0d1ZNOTcxd0MxSFNjYVhFNFM2VWJ5OTBUdkkxNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHBzOi8vYnVnLWZyZWUtcm9ib3QtNGo5Z3g3ajk2dnBqZmp2NjUtODAuYXBwLmdpdGh1Yi5kZXYvcmVwb3J0ZXMiO3M6NToicm91dGUiO3M6ODoicmVwb3J0ZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1785990619),
('9IiOXw9kJjwmFcRlEIbuBhbq7Rfh1SBh3y36fR13',NULL,'172.18.0.1','Mozilla/5.0 (compatible; Nmap Scripting Engine; https://nmap.org/book/nse.html)','YToyOntzOjY6Il90b2tlbiI7czo0MDoib0U4VVFualBtZ2VHQ3FGVVNaU2l6dUlzTjdFd1JlS3VjUXlLU2x2QyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785992577),
('bxwxz3YPV074ryWVeZzML7iyEXTHleyUwJxUkMft',NULL,'201.102.126.250','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicUZUMkxyNVpJQUhaVnFIWE9HTDFqSHVBTThZRzF2V1ZFWDhKUk9QWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHBzOi8vYnVnLWZyZWUtcm9ib3QtNGo5Z3g3ajk2dnBqZmp2NjUtODAuYXBwLmdpdGh1Yi5kZXYvaW52ZW50YXJpbyI7czo1OiJyb3V0ZSI7czoxMDoiaW52ZW50YXJpbyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785949517),
('GGPlfJrx6cM6XLhd06IZEWe3uCwhzujhyaChPw7E',NULL,'189.226.254.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVBPcDdKN3gzMEVNcHJySnZsWjEwNXNtcHBvMWdoUFc3MFU5Q09rTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njk6Imh0dHBzOi8vYnVnLWZyZWUtcm9ib3QtNGo5Z3g3ajk2dnBqZmp2NjUtODAuYXBwLmdpdGh1Yi5kZXYvaW52ZW50YXJpbyI7czo1OiJyb3V0ZSI7czoxMDoiaW52ZW50YXJpbyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1785737418),
('LlOo4ApgIuqUSfBMZivVYc0lNFed9cD6ZN055P4Y',NULL,'187.192.50.217','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0ZSWjFMU2xrbFk4ZkRVZzZqcTZDVDNaRUpleWV6WmtRbmo3SlA1UiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjI6Imh0dHBzOi8vYnVnLWZyZWUtcm9ib3QtNGo5Z3g3ajk2dnBqZmp2NjUtODAuYXBwLmdpdGh1Yi5kZXYvcG9zIjtzOjU6InJvdXRlIjtzOjM6InBvcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1786163031),
('UqYAPKfDBvKX4Uf8g0mKKBI82DmtdPxGc8A0cIjh',NULL,'172.18.0.1','Mozilla/5.0 (compatible; Nmap Scripting Engine; https://nmap.org/book/nse.html)','YToyOntzOjY6Il90b2tlbiI7czo0MDoiMFVmUFRmR21RcGk1UDZURllTZEZqb0hCVWpwSVMyOWxKSVVTd3NNRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785736443),
('y15YyKPRayQs4nnC1795UCREJ21JXsLO1bGrINOV',NULL,'172.18.0.1','Mozilla/5.0 (compatible; Nmap Scripting Engine; https://nmap.org/book/nse.html)','YToyOntzOjY6Il90b2tlbiI7czo0MDoiMkpYVG9JcmlSUkRjWVpTamNCellTckZzNGkxRWk4N3M1UzY0MFlpeCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1785992581);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin POS','admin@pos.com',NULL,'$2y$12$uQUMzC1fkamqMKkUfmtdUOvHxvGyFGXycju/ORzqAZ896Fw0eITlS',NULL,'2026-07-30 02:48:01','2026-07-30 02:48:01'),
(2,'Cajero de Turno','cajero@pos.com',NULL,'$2y$12$Pzy7Ds4Eb12zkuU/eXtCGuWsB16Kux2MNVTDeZM9lkzYZsXZghm7W',NULL,'2026-07-30 02:48:01','2026-07-30 02:48:01');
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

-- Dump completed on 2026-08-08  4:31:54
