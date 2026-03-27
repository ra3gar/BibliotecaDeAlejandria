-- MySQL dump 10.13  Distrib 8.4.8, for Linux (x86_64)
--
-- Host: localhost    Database: biblioteca_alejandria
-- ------------------------------------------------------
-- Server version	8.4.8-0ubuntu0.25.10.1

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
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_audit_user` (`user_id`),
  CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `book_store_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:03:56'),(2,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:04:01'),(3,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: juan@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:04:12'),(4,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:05:40'),(5,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:05:43'),(6,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: juan@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:06:14'),(7,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: juan@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:06:18'),(8,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:12:44'),(9,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:13:21'),(10,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:13:25'),(11,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: user@biblioteca.com desde IP: 127.0.0.1','2026-03-18 21:13:33'),(12,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: user@biblioteca.com desde IP: 127.0.0.1','2026-03-18 22:04:10'),(13,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: user@biblioteca.com desde IP: 127.0.0.1','2026-03-18 22:04:13'),(14,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: user@biblioteca.com desde IP: 127.0.0.1','2026-03-18 22:04:22'),(15,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@ine.com desde IP: 127.0.0.1','2026-03-18 23:37:22'),(16,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@ine.com desde IP: 127.0.0.1','2026-03-18 23:37:26'),(17,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@ine.com desde IP: 127.0.0.1','2026-03-18 23:37:37'),(18,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: admin@ine.com desde IP: 127.0.0.1','2026-03-18 23:38:14'),(19,5,'created','Book',1,'Libro creado: \"El camino de los reyes [The Way of Kings]\"','2026-03-18 23:53:52'),(20,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: user@biblioteca.com desde IP: 127.0.0.1','2026-03-18 23:54:14'),(21,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: user@biblioteca.com desde IP: 127.0.0.1','2026-03-18 23:54:18'),(22,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: user@biblioteca.com desde IP: 127.0.0.1','2026-03-18 23:56:33'),(23,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: user@biblioteca.com desde IP: 127.0.0.1','2026-03-18 23:56:37'),(24,NULL,'login_failed','Auth',NULL,'Intento fallido de login para: juan@biblioteca.com desde IP: 127.0.0.1','2026-03-19 00:16:38'),(25,5,'created','Book',2,'Libro creado: \"El problema de los tres cuerpos\"','2026-03-19 00:56:46'),(26,5,'created','Book',3,'Libro creado: \"El bosque oscuro/ The Dark Forest\"','2026-03-19 01:01:19'),(27,5,'created','Book',4,'Libro creado: \"El fin de la muerte [The End of Death]\"','2026-03-19 01:04:55'),(28,5,'updated','Book',4,'Libro actualizado: \"El fin de la muerte [The End of Death]\"','2026-03-19 01:05:10'),(29,5,'updated','Book',3,'Libro actualizado: \"El bosque oscuro/ The Dark Forest\"','2026-03-19 03:46:07'),(30,5,'updated','Book',1,'Libro actualizado: \"El camino de los reyes [The Way of Kings]\"','2026-03-19 03:46:17');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'BRANDON','SANDERSON','(Nebraska, 1975) es el gran autor de fantasía del siglo XXI. Tras debutar en 2006 con su novela Elantris, ha deslumbrado a más de cuarenta y cinco millones de lectores en casi cuarenta lenguas con el Cosmere, el fascinante universo de magia que comparten la mayoría de sus obras. Sus best sellers son considerados clásicos instantáneos, como la saga Mistborn, la decalogía El Archivo de las Tormentas, la saga Escuadrón y las cuatro novelas secretas con las que, en 2021, protagonizó la mayor campaña de financiación de Kickstarter. Con un plan de publicación de más de veinte futuras obras (que contempla la interconexión de todas ellas), el Cosmere se convertirá en el universo más extenso e impresionante jamás escrito en el ámbito de la fantasía épica. Sanderson vive en Utah con su esposa e hijos y enseña escritura creativa en la Universidad Brigham Young. Curso de escritura creativa es el libro que recoge sus valiosos consejos.','authors/3Jjhyz67d1sgHAvNDrMwDcirHt4z2TZcvpv5mWqJ.jpg','2026-03-18 23:51:25','2026-03-19 00:34:40'),(2,'Liu','Cixin','Liu Cixin, nacido en junio de 1963, es un representante de la nueva generación de autores chinos de ciencia ficción y una figura destacada en el género. Fue galardonado con el Premio China Galaxy de Ciencia Ficción durante ocho años consecutivos, de 1999 a 2006 y nuevamente en 2010. Su obra más representativa, El problema de los tres cuerpos, fue elegida MEJOR HISTORIA en los Premios Hugo de 2015, quedó en tercer lugar entre los finalistas de los Premios Campbell de 2015 y fue nominada a los Premios Nebula de 2015.\r\n\r\nSus obras han recibido un amplio reconocimiento por su poderosa atmósfera y brillante imaginación. Los relatos de Liu Cixin combinan con éxito lo efímero con la cruda realidad, centrándose siempre en revelar la esencia y la estética de la ciencia. Se ha esforzado por crear un estilo de ciencia ficción distintivamente chino. Liu Cixin es miembro de la Asociación de Escritores de China y de la Asociación de Escritores de Shanxi.','authors/S6zNYS1TBcnDWmg9KRgt9D3egWvan4vefilB1dsU.jpg','2026-03-19 00:56:25','2026-03-19 00:56:25');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book_author`
--

DROP TABLE IF EXISTS `book_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `book_author` (
  `book_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`book_id`,`author_id`),
  KEY `fk_ba_author` (`author_id`),
  CONSTRAINT `fk_ba_author` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ba_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_author`
--

LOCK TABLES `book_author` WRITE;
/*!40000 ALTER TABLE `book_author` DISABLE KEYS */;
INSERT INTO `book_author` VALUES (1,1),(2,2),(3,2),(4,2);
/*!40000 ALTER TABLE `book_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book_store_users`
--

DROP TABLE IF EXISTS `book_store_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `book_store_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_store_users`
--

LOCK TABLES `book_store_users` WRITE;
/*!40000 ALTER TABLE `book_store_users` DISABLE KEYS */;
INSERT INTO `book_store_users` VALUES (5,'Admin','Sistema','admin@biblioteca.com','$2y$12$Nk/9YEf8GzKgHnjB2VWv9uVCXwy9HI9pGYbCf66aKX.FccAxHTlMu','admin',1,'SStroyhrpHIhzt67FKVw5JMp0GHcx8ez7qnSIE2Q7PqYYgo30BRcxoIwOsR8','2026-03-18 15:11:49','2026-03-18 15:11:49'),(6,'User','Sistema','user@biblioteca.com','$2y$12$wKtYKt0DuVjNgEG88lqp1u/f5tHx3a1y.ZKP6Zpbqwnz0inx/R83y','user',1,NULL,'2026-03-18 15:11:51','2026-03-18 15:11:51'),(7,'pedro','pedro','pedro@biblioteca.com','$2y$12$vxIyhermyITpEv5Z7xL8G.obPyqG0TgAGHLDNbA8B0mrOWlSsSA9a','user',1,'ri77ph4k9pa6SyAmyP3rRfJzs6e4eny1ydU8S15hS0fjiCqs91X6csqxqhSy','2026-03-19 00:21:29','2026-03-19 00:21:29');
/*!40000 ALTER TABLE `book_store_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `publisher` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `book_cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `published_at` date DEFAULT NULL,
  `año` int DEFAULT NULL,
  `stock_total` int unsigned NOT NULL DEFAULT '0',
  `available_copies` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `fk_books_category` (`category_id`),
  CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (1,1,'El camino de los reyes [The Way of Kings]','978-8466657662','y escritura de universos, convertido en una obra maestra de la fantasía contemporánea en diez volúmenes. Con ella, Brandon Sanderson se postula como el autor del género que más lectores está ganando en todo el mundo.\r\n\r\nAnhelo los días previos a la Última Desolación.\r\n\r\nLos días en que los Heraldos nos abandonaron y los Caballeros Radiantes se giraron en nuestra contra. Un tiempo en que aún había magia en el mundo y honor en el corazón de los hombres.\r\n\r\nEl mundo fue nuestro, pero lo perdimos. Probablemente no hay nada más estimulante para las almas de los hombres que la victoria.\r\n\r\n¿O tal vez fue la victoria una ilusión durante todo ese tiempo? ¿Comprendieron nuestros enemigos que cuanto más duramente luchaban, más resistíamos nosotros? Quizá vieron que el fuego y el martillo tan solo producían mejores espadas. Pero ignoraron el acero durante el tiempo suficiente para oxidarse.\r\n\r\nHay cuatro personas a las que observamos. La primera es el médico, quien dejó de curar para convertirse en soldado durante la guerra más brutal de nuestro tiempo. La segunda es el asesino, un homicida que llora siempre que mata. La tercera es la mentirosa, una joven que viste un manto de erudita sobre un corazón de ladrona. Por último está el alto príncipe, un guerrero que mira al pasado mientras languidece su sed de guerra.\r\n\r\nEl mundo puede cambiar. La potenciación y el uso de las esquirlas pueden aparecer de nuevo, la magia de los días pasados puede volver a ser nuestra. Esas cuatro personas son la clave.','NOVA','books/8aDGnRtm9Rp5R3krtSNuR9UEAgasEPovn1TAsWW4.jpg','2015-07-01',2015,12,11,'2026-03-18 23:53:52','2026-03-19 03:46:17'),(2,1,'El problema de los tres cuerpos','978-8466659734','El primer libro de la «Trilogía de los Tres Cuerpos», el fenómeno editorial chino que ha conquistado al mundo y ha ganado el premio Hugo 2015 a la mejor novela.\r\n\r\nEl problema de los tres cuerpos es la primera novela no escrita originariamente en inglés galardonada con el premio Hugo, el Nobel del género de la ciencia ficción.\r\n\r\nSu autor, Cixin Liu, ha sido considerado el gran descubrimiento del género y es capaz de vender cuatro millones de ejemplares solamente en China y de hacerse con prescriptores de la talla de Barack Obama, quien seleccionó El problema de los tres cuerposcomo una de sus lecturas navideñas de 2015, y Mark Zuckerberg, que lo convirtió en la primera novela de su club de lectura.\r\n\r\nEl público y la crítica de los cinco continentes se rinden ante esta obra maestra, enormemente visionaria, sobre el papel de la ciencia en nuestras sociedades, que nos ayuda a comprender el pasado y el futuro de China, pero también, leída en clave geopolítica, del mundo en que vivimos.','Nova','books/WmsEkjOD7AKhAZUD9oP2UX15qrUUttlAkNnwZ3lC.jpg','2016-11-02',2016,15,15,'2026-03-19 00:56:46','2026-03-19 00:56:46'),(3,1,'El bosque oscuro/ The Dark Forest','978-8466660921','La esperada continuación de El problema de los tres cuerpos, el mejor libro de ciencia ficción y fantasía de 2016 según El Periódico, y uno de los diez mejores libros de ficción de 2016 según Playground.\r\n\r\nAhora la Tierra tiene cuatro siglos para defenderse de lo inevitable: la llegada de los Trisolaris. Los colaboracionistas humanos pueden haber sido derrotados, pero los sofones permiten a los extraterrestres acceder a la información de la humanidad, dejando al descubierto toda estrategia de defensa.\r\n\r\nSolo la mente humana sigue siendo un secreto, y ahora también la clave del acuciante plan que urdirán tres estadistas, un científico y un sociólogo.\r\n\r\n«La trilogía de los Tres Cuerpos» es el gran fenómeno editorial que ha conquistado Occidente tras vender cuatro millones de ejemplares solamente en China y haberse hecho con prescriptores de la talla de Barack Obama, George R.R. Martin o Mark Zuckeberg.\r\n\r\nCixin Liu se considera el gran descubrimiento de la ciencia ficción internacional tras alzarse con el Premio Hugo 2015 a la mejor novela, siendo la primera vez que una obra no escrita originariamente en inglés recibe este auténtico Nobel del género.','Nova','books/6RijFs2S0f7sMdYkvyD0SBnK2pJcU8QdAoxFedI9.jpg','2018-02-27',2018,10,9,'2026-03-19 01:01:19','2026-03-19 03:46:07'),(4,1,'El fin de la muerte [The End of Death]','978-6073164528','Tras \"El problema de los tres cuerpos\" y \"El bosque oscuro\", la tensa espera de la humanidad concluye ahora con un último episodio, tan extraordinario como los anteriores, lleno de ideas electrizantes y una calidad de obra maestra.\r\n\r\nHa pasado medio siglo de la batalla del Día del Juicio Final y la Tierra goza de una prosperidad sin precedentes gracias al conocimiento transferido por Trisolaris. Mientras la ciencia humana avance y los trisolarianos adopten la cultura terrícola, ambas civilizaciones podrán convivir sin temor a ser destruidas. Pero con la paz la humanidad se ha vuelto autocomplaciente. Después de una larga hibernación, Cheng Xin, una ingeniera aeroespacial de comienzos del siglo XX, despierta en esta nueva era. Su mera presencia, sumada a cierta información sobre un proyecto olvidado desde el principio de la Crisis Trisolariana, podría alterar el frágil equilibrio entre ambos mundos... ¿Alcanzará el ser humano las estrellas, o morirá en su cuna?\r\n\r\n\"El fin de la muerte\", galardonado con el Premio Locus 2017 y nominado al Hugo 2017, es el desenlace de la magistral trilogía de ciencia ficción china que ha conquistado a cinco millones de lectores en todo el mundo.\r\n\r\nCixin Liu es el escritor de ciencia ficción más relevante de China, capaz de llevarse el Premio Hugo 2015 a la mejor novela, deslumbrar a lectores y medios de los cinco continentes y conseguir prescriptores de la talla de Barack Obama, Mark Zuckerberg o George R.R. Martin.','Nova','books/GVW7RjSgY5QAzJfxIJ4pfKkpgTwuwBFUHnYRQcmm.jpg','2015-01-01',2015,8,8,'2026-03-19 01:04:55','2026-03-19 01:05:10');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Ciencia Ficción','La ciencia ficción es un género literario especulativo que narra historias basadas en el impacto de supuestos avances científicos, tecnológicos o sociales en la humanidad. A diferencia de la fantasía, busca la verosimilitud narrativa, planteando futuros posibles, distopías, viajes interestelares o realidades alternativas.\r\n\r\nCaracterísticas Clave de la Ciencia Ficción:\r\nEspeculación Científica: Se fundamenta en ciencias físicas, naturales y sociales para crear escenarios imaginarios, pero lógicos.\r\nTemas Comunes: Robots, inteligencia artificial, conquista del espacio, ingeniería genética, distopías y viajes en el tiempo.\r\n\r\nCrítica Social: Utiliza mundos imaginarios para reflexionar sobre problemas actuales como la política, el medio ambiente o la tecnología.\r\n\r\nTipos principales:\r\nCiencia Ficción Dura (Hard): Se apega rigurosamente a las leyes científicas conocidas, con gran detalle técnico.\r\n\r\nCiencia Ficción Blanda (Soft): Se centra más en las ciencias sociales (sociología, psicología, política) y sus efectos, que en la tecnología misma.\r\n\r\nSubgéneros: Incluye la distopía (futuro opresivo), la utopía (sociedad ideal), la ucronía (historia alternativa) y el cyberpunk','2026-03-18 23:51:50','2026-03-18 23:51:50');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `loans`
--

DROP TABLE IF EXISTS `loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `status` enum('active','returned','overdue') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `loan_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_loans_user` (`user_id`),
  KEY `fk_loans_book` (`book_id`),
  CONSTRAINT `fk_loans_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_loans_user` FOREIGN KEY (`user_id`) REFERENCES `book_store_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loans`
--

LOCK TABLES `loans` WRITE;
/*!40000 ALTER TABLE `loans` DISABLE KEYS */;
INSERT INTO `loans` VALUES (1,7,3,'active','2026-03-18',NULL,'2026-03-19 03:46:07','2026-03-19 03:46:07'),(2,6,1,'active','2026-03-18',NULL,'2026-03-19 03:46:17','2026-03-19 03:46:17');
/*!40000 ALTER TABLE `loans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('90D9KuworpZb7xCumbVy0TGrzKVjQFgQGOw1i3x6',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicGZHSkNqang2alJDU0JBeUtsQ056Z0JxamR0RkFKSkxZUTA0UWM5biI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=',1773870691),('cfavawLt2N6Zx1iac4mbHFKP9yM2hlVzeIfC9X07',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicEQ5V0VDWjNJQmdETEZzajFSUnM3M2I5NGl6ZGpTS0o3dFZDVGxaNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=',1773861699),('PoaJWDa1Ti9K4C7TBjUN1ypODyzgWcvniX4wcUUh',5,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUTJkTXBQVWxUUFB2QmdJYWx3YVNNWllRTmJ3QmZXcUsxcENTbDFzdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jYXRlZ29yaWVzL2NyZWF0ZSI7czo1OiJyb3V0ZSI7czoyMzoiYWRtaW4uY2F0ZWdvcmllcy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=',1773852234),('XAux89ZvAbxmTlXwyugoIx1olOsN9F1BgAe1jimA',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzZRejBsVzFpZXcwdlhvd0tvMWRUd3owQjdJNzU4T0I0Q0NXUnRVQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=',1773858525),('zZJWRIir1fLN56q3z24IyTEa3xqYoPPQgrRHNpJj',NULL,'127.0.0.1','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:148.0) Gecko/20100101 Firefox/148.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM0hlMjBkOHF5N1d3S2hNRmloVDRzRzkzRlhVN0VLQ2R4cWlQUUs1UyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=',1773858207);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'biblioteca_alejandria'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-26  7:27:06
