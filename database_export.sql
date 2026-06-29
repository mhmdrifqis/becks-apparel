-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: becksapparel
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `roster` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_user_id_foreign` (`user_id`),
  KEY `cart_items_package_id_foreign` (`package_id`),
  KEY `cart_items_material_id_foreign` (`material_id`),
  CONSTRAINT `cart_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cart_items_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `designs`
--

DROP TABLE IF EXISTS `designs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `designs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `design_json` longtext NOT NULL,
  `preview_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `designs_user_id_foreign` (`user_id`),
  CONSTRAINT `designs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `designs`
--

LOCK TABLES `designs` WRITE;
/*!40000 ALTER TABLE `designs` DISABLE KEYS */;
/*!40000 ALTER TABLE `designs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `live_chat_messages`
--

DROP TABLE IF EXISTS `live_chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `live_chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `live_chat_id` bigint unsigned NOT NULL,
  `sender` enum('user','admin') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `live_chat_messages_live_chat_id_foreign` (`live_chat_id`),
  CONSTRAINT `live_chat_messages_live_chat_id_foreign` FOREIGN KEY (`live_chat_id`) REFERENCES `live_chats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `live_chat_messages`
--

LOCK TABLES `live_chat_messages` WRITE;
/*!40000 ALTER TABLE `live_chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `live_chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `live_chats`
--

DROP TABLE IF EXISTS `live_chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `live_chats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `status` enum('active','closed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `live_chats`
--

LOCK TABLES `live_chats` WRITE;
/*!40000 ALTER TABLE `live_chats` DISABLE KEYS */;
/*!40000 ALTER TABLE `live_chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` enum('Standard','Premium') NOT NULL DEFAULT 'Standard',
  `allowed_categories` json DEFAULT NULL,
  `description` text,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('Ready','Out of Stock') NOT NULL DEFAULT 'Ready',
  `additional_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stock` decimal(10,2) NOT NULL DEFAULT '0.00',
  `product_types` json DEFAULT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'Meter',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (1,'Milano','Standard','[\"jersey\"]','Bahan jersey dengan motif zig-zag atau pori-pori menyerupai sisik. Sangat menyerap keringat, lentur, dan memberikan sirkulasi udara yang baik saat berolahraga.','assets/images/materials/milano.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(2,'Benzema','Standard','[\"jersey\"]','Memiliki tekstur pori-pori diagonal/segi enam. Sangat ringan, halus, dan nyaman dipakai untuk aktivitas dengan intensitas tinggi.','assets/images/materials/benzema.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(3,'Smash','Premium','[\"jersey\"]','Karakteristiknya halus, melar, dan pori-porinya rapi. Memberikan kesan jatuh dan pas di badan.','assets/images/materials/smash.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(4,'N-Tech','Premium','[\"jersey\"]','Material dengan teknologi anti-bakteri dan quick-dry (cepat kering). Sangat premium untuk jersey profesional.','assets/images/materials/n-tech.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(5,'Wafel','Standard','[\"jersey\"]','Permukaannya bertekstur kotak-kotak seperti kue wafel. Lumayan tebal namun tetap adem dan tidak mudah kusut.','assets/images/materials/wafel.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(6,'MU','Standard','[\"jersey\"]','Bahan licin, mengkilap, dan memiliki serat rapat. Memberikan kesan elegan saat dipakai.','assets/images/materials/mu.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(7,'Kultus','Standard','[\"jersey\"]','Kain yang cukup lembut dengan sedikit corak di permukaannya. Nyaman untuk penggunaan sehari-hari maupun olahraga santai.','assets/images/materials/kultus.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(8,'Thailand','Standard','[\"jersey\"]','Sering disebut bahan dry-fit Thailand, permukaannya halus, sangat elastis, dan menyerap keringat dengan sempurna.','assets/images/materials/thailand.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(9,'Bubin','Standard','[\"jersey\"]','Bahan tebal dengan rongga pori-pori besar, sirkulasi udara sangat lancar. Biasa digunakan untuk jersey basket.','assets/images/materials/bubin.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(10,'Adidas','Standard','[\"jersey\"]','Serat kainnya tebal namun bagian dalamnya tidak berbulu. Biasa digunakan untuk celana olahraga atau jaket training.','assets/images/materials/adidas.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(11,'Diamond','Standard','[\"jersey\"]','Teksturnya terlihat seperti permata/berlian kecil. Tahan lama, tebal, dan memberikan kesan mewah pada jersey.','assets/images/materials/diamond.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(12,'Holland','Standard','[\"jersey\"]','Tekstur bahan yang khas, tebal namun tidak kaku. Cocok untuk jersey e-sport atau voli.','assets/images/materials/holland.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:25'),(13,'Koba','Standard','[\"jersey\"]','Bahan premium dengan permukaan lembut dan sangat nyaman bersentuhan dengan kulit.','assets/images/materials/koba.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:26'),(14,'Virtual','Standard','[\"jersey\"]','Material modern yang dikembangkan khusus untuk printing sublimasi dengan hasil warna yang sangat tajam dan awet.','assets/images/materials/virtual.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:26'),(15,'Bambu','Standard','[\"jersey\"]','Terbuat dari serat bambu alami. Anti-bau, sangat lembut, dan eco-friendly.','assets/images/materials/bambu.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:26'),(16,'Brazil','Standard','[\"jersey\"]','Bahan yang mengkilap dan jatuh. Cocok untuk jersey sepak bola dengan look yang elegan.','assets/images/materials/brazil.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:26'),(17,'Ferari','Standard','[\"jersey\"]','Bahan yang cukup ringan, licin, dan memantulkan sedikit cahaya. Memberikan kesan sporty yang kuat.','assets/images/materials/ferari.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:26'),(18,'England','Standard','[\"jersey\"]','Mirip bahan wafel namun dengan pola yang lebih kecil. Awet dan tahan terhadap gesekan.','assets/images/materials/england.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:26'),(19,'Sena','Standard','[\"jersey\"]','Kain dengan pori-pori bulat yang padat. Menyerap keringat maksimal dan mudah dicuci.','assets/images/materials/sena.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:26'),(20,'Tepo','Standard','[\"jersey\"]','Sangat lentur, jatuh, dan memiliki serat yang rapat, memberikan sensasi dingin di kulit.','assets/images/materials/tepo.jpg','Ready',0.00,0.00,NULL,'Meter','2026-06-10 05:57:25','2026-06-10 05:57:26');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_04_18_002654_create_permission_tables',1),(5,'2026_04_18_024353_create_packages_table',1),(6,'2026_04_19_051117_create_materials_table',1),(7,'2026_04_19_051117_create_upgrades_table',1),(8,'2026_04_19_051118_create_designs_table',1),(9,'2026_04_19_051120_create_orders_table',1),(10,'2026_04_19_051121_create_order_items_table',1),(11,'2026_04_19_051133_create_order_item_upgrade_table',1),(12,'2026_04_19_160812_create_cart_items_table',1),(13,'2026_04_19_161052_create_notifications_table',1),(14,'2026_04_20_104500_create_return_requests_table',1),(15,'2026_04_20_151127_add_phone_to_users_table',1),(16,'2026_04_20_151129_add_shipping_fields_to_orders_table',1),(17,'2026_04_20_160502_add_material_id_to_cart_items_table',1),(18,'2026_04_21_162001_update_packages_images_column',1),(19,'2026_04_22_103259_add_recipient_fields_to_orders_table',1),(20,'2026_04_22_183003_add_notes_to_orders_table',1),(21,'2026_04_23_172812_add_name_to_designs_table',1),(22,'2026_04_24_034224_add_midtrans_order_id_to_orders_table',1),(23,'2026_04_24_041905_create_order_status_logs_table',1),(24,'2026_04_24_174042_add_material_usage_to_order_items_table',1),(25,'2026_04_29_181028_create_live_chats_table',1),(26,'2026_04_29_181029_create_live_chat_messages_table',1),(27,'2026_06_06_113223_create_payment_settings_table',1),(28,'2026_06_06_124405_add_product_types_to_materials_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
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
INSERT INTO `model_has_roles` VALUES (2,'App\\Models\\User',1),(3,'App\\Models\\User',2),(4,'App\\Models\\User',3),(1,'App\\Models\\User',4);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_item_upgrade`
--

DROP TABLE IF EXISTS `order_item_upgrade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_item_upgrade` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint unsigned NOT NULL,
  `upgrade_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_item_upgrade_order_item_id_foreign` (`order_item_id`),
  KEY `order_item_upgrade_upgrade_id_foreign` (`upgrade_id`),
  CONSTRAINT `order_item_upgrade_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_item_upgrade_upgrade_id_foreign` FOREIGN KEY (`upgrade_id`) REFERENCES `upgrades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_item_upgrade`
--

LOCK TABLES `order_item_upgrade` WRITE;
/*!40000 ALTER TABLE `order_item_upgrade` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_item_upgrade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `material_id` bigint unsigned NOT NULL,
  `design_id` bigint unsigned DEFAULT NULL,
  `roster` json DEFAULT NULL,
  `size_surcharge` decimal(15,2) NOT NULL DEFAULT '0.00',
  `quantity` int NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `material_usage` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_package_id_foreign` (`package_id`),
  KEY `order_items_material_id_foreign` (`material_id`),
  KEY `order_items_design_id_foreign` (`design_id`),
  CONSTRAINT `order_items_design_id_foreign` FOREIGN KEY (`design_id`) REFERENCES `designs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_logs`
--

DROP TABLE IF EXISTS `order_status_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_status_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `status` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_logs_order_id_foreign` (`order_id`),
  CONSTRAINT `order_status_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_logs`
--

LOCK TABLES `order_status_logs` WRITE;
/*!40000 ALTER TABLE `order_status_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_status_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `recipient_phone` varchar(255) DEFAULT NULL,
  `shipping_address` text,
  `notes` text,
  `status` enum('pending','paid','printing','sewing','qc','ready','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  `courier_name` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `deposit_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `payment_token` varchar(255) DEFAULT NULL,
  `midtrans_order_id` varchar(255) DEFAULT NULL,
  `snap_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category` enum('jersey','jacket','tshirt','kemeja','other') NOT NULL DEFAULT 'jersey',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `base_price` decimal(15,2) NOT NULL,
  `description` text,
  `specification` text,
  `features` json DEFAULT NULL,
  `images` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `packages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,'jersey','Paket A','jersey-paket-a',90000.00,NULL,'Jersey & Celana Non-printing, Logo/Sponsor DTF, Nameset DTF',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(2,'jersey','Paket B','jersey-paket-b',110000.00,NULL,'Badan Non-printing, Lengan Printing, DTF',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(3,'jersey','Paket C','jersey-paket-c',130000.00,NULL,'Jersey Full Printing, Celana Non-printing',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(4,'jersey','Paket D','jersey-paket-d',160000.00,NULL,'Jersey & Celana Full Printing',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(5,'jersey','Paket E','jersey-paket-e',170000.00,NULL,'Full Printing, Logo/Sponsor/Nameset DTF',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(6,'jacket','Paket A','jacket-paket-a',170000.00,NULL,'Jacket Full Printing, Bahan Lotto/Diadora',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(7,'jacket','Paket B','jacket-paket-b',155000.00,NULL,'Jacket Kombinasi Printing + Bahan, Lotto/Diadora',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(8,'jacket','Paket C','jacket-paket-c',250000.00,NULL,'Setelan Jacket Full Printing + Celana Training',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(9,'tshirt','Paket A (24s)','tshirt-paket-a-24s',80000.00,NULL,'Material Cotton Combed 24s, Sablon DTF',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(10,'tshirt','Paket B (30s)','tshirt-paket-b-30s',60000.00,NULL,'Material Cotton Combed 30s, Sablon DTF',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25'),(11,'kemeja','Kemeja Drill','kemeja-kemeja-drill',80000.00,NULL,'Material Verlando CP, Maryland Drill, dll',NULL,NULL,1,'2026-06-10 05:57:25','2026-06-10 05:57:25');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
-- Table structure for table `payment_settings`
--

DROP TABLE IF EXISTS `payment_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `midtrans_server_key` text,
  `midtrans_client_key` text,
  `is_production` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_settings`
--

LOCK TABLES `payment_settings` WRITE;
/*!40000 ALTER TABLE `payment_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_requests`
--

DROP TABLE IF EXISTS `return_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `return_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `reason` text NOT NULL,
  `proof_images` json DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `admin_note` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_requests_order_id_foreign` (`order_id`),
  KEY `return_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `return_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_requests`
--

LOCK TABLES `return_requests` WRITE;
/*!40000 ALTER TABLE `return_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `return_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
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
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Pelanggan','web','2026-06-10 05:57:25','2026-06-10 05:57:25'),(2,'Admin','web','2026-06-10 05:57:25','2026-06-10 05:57:25'),(3,'Tim Produksi','web','2026-06-10 05:57:25','2026-06-10 05:57:25'),(4,'Management/Owner','web','2026-06-10 05:57:25','2026-06-10 05:57:25');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('H1KMlawznPtDoL5dYB9Co8WqyDsSBrKhFRwBOmHr',NULL,'127.0.0.1','GuzzleHttp/7','YToyOntzOjY6Il90b2tlbiI7czo0MDoiNnRPWEU3bDEyaE5hbWpzdlB3NXV4bzJFS2JJR1ZXaEptMDZwVFphRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1781096749),('TR3E29rfDyW71lcgTI9uvsLcB8bIp6Xe5BIPNUho',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicnMxaTZPQVBKRVlxdU1jbVR5NzI2ZmtCVXNDVEMxV3dFSFFKUXdvRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTQ6Imh0dHA6Ly9iZWNrcy1hcHBhcmVsLnRlc3QvY2hhdGJvdC9wb2xsP2FsbD0xJmxhc3RfaWQ9MCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1781097545);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `upgrades`
--

DROP TABLE IF EXISTS `upgrades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `upgrades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` enum('Logo','Fitur','Aksesori') NOT NULL DEFAULT 'Fitur',
  `price` decimal(15,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upgrades`
--

LOCK TABLES `upgrades` WRITE;
/*!40000 ALTER TABLE `upgrades` DISABLE KEYS */;
INSERT INTO `upgrades` VALUES (1,'Logo Rubber','Logo',20000.00,'Additional per item','2026-06-10 05:57:25','2026-06-10 05:57:25'),(2,'Logo Semiwoven','Logo',25000.00,'Additional per item','2026-06-10 05:57:25','2026-06-10 05:57:25'),(3,'Logo Bordir','Logo',30000.00,'Additional per item','2026-06-10 05:57:25','2026-06-10 05:57:25'),(4,'Tangan Panjang','Fitur',20000.00,'Berlaku untuk Jersey/Tshirt/Kemeja','2026-06-10 05:57:25','2026-06-10 05:57:25'),(5,'Tangan Raglan','Fitur',15000.00,'Pola Raglan','2026-06-10 05:57:25','2026-06-10 05:57:25'),(6,'Kerah Kemeja & Wangki','Fitur',20000.00,'Upgrade dari kerah standar','2026-06-10 05:57:25','2026-06-10 05:57:25'),(7,'Kaos Kaki Custom','Aksesori',35000.00,'Minimum order berlaku','2026-06-10 05:57:25','2026-06-10 05:57:25'),(8,'Rompi Custom','Aksesori',25000.00,'Material standar','2026-06-10 05:57:25','2026-06-10 05:57:25'),(9,'Flag Custom','Aksesori',20000.00,'Bendera custom','2026-06-10 05:57:25','2026-06-10 05:57:25');
/*!40000 ALTER TABLE `upgrades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin Becks','admin@becks.com',NULL,NULL,'$2y$12$O5WOvVUBPdIjm69tJGIZmedBb8uSVop8FJUrNSfPJYOqUUyYtWOgK',NULL,'2026-06-10 05:57:27','2026-06-10 05:57:27'),(2,'Tim Workshop','produksi@becks.com',NULL,NULL,'$2y$12$yt2u4I7nfe1aRXEDewQdk.aDsirHGei4Vn5244K/tZ5.6R0v6n4Wq',NULL,'2026-06-10 05:57:28','2026-06-10 05:57:28'),(3,'Owner Becks','owner@becks.com',NULL,NULL,'$2y$12$HLB9Gf4geee.XUDouukzt.jHAvq4nwbNat.l7wpjPHp76F1L3u7iC',NULL,'2026-06-10 05:57:28','2026-06-10 05:57:28'),(4,'Budi Pelanggan','user@gmail.com',NULL,NULL,'$2y$12$qjTVU9xIpsWAwLEnr8p3ReDJUj.9iULHZLzs8drcJobiY6TRmLp2y',NULL,'2026-06-10 05:57:29','2026-06-10 05:57:29'),(5,'Test User','test@example.com',NULL,NULL,'$2y$12$5TeIeGeyzcLf1UZ5p9gToe41ltLU2hsv96AtGtotMK8quD4jUvfoe',NULL,'2026-06-10 05:57:30','2026-06-10 05:57:30');
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

-- Dump completed on 2026-06-10 20:37:45
