CREATE DATABASE  IF NOT EXISTS `myRestApi` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci */;
USE `myRestApi`;
-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: localhost    Database: myRestApi
-- ------------------------------------------------------
-- Server version	5.7.26-0ubuntu0.18.04.1

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
-- Table structure for table `dict_languages`
--

DROP TABLE IF EXISTS `dict_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dict_languages` (
  `dict_language_code` varchar(3) CHARACTER SET utf8 NOT NULL,
  `dict_language_lang` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`dict_language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dict_languages`
--

LOCK TABLES `dict_languages` WRITE;
/*!40000 ALTER TABLE `dict_languages` DISABLE KEYS */;
INSERT INTO `dict_languages` VALUES ('en','english'),('pl','polski');
/*!40000 ALTER TABLE `dict_languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_price` decimal(12,2) DEFAULT '0.00',
  `product_quantity` int(11) DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,200.00,12),(2,2.50,336),(3,63.12,25),(4,2.34,123),(5,12.50,10),(10,12.40,30),(12,250.00,61),(41,NULL,25),(44,9.00,3),(45,15.00,4),(46,9.00,3),(47,15.00,4),(49,56.00,56),(50,333.00,23),(51,12.50,22);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_descriptions`
--

DROP TABLE IF EXISTS `products_descriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products_descriptions` (
  `product_description_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `dict_language_code` varchar(3) CHARACTER SET utf8 NOT NULL,
  `product_description_name` varchar(150) CHARACTER SET utf8 NOT NULL,
  `product_description_description` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`product_description_id`),
  KEY `idx_products_descriptions_2` (`dict_language_code`),
  KEY `fk_products_descriptions_product_id_idx` (`product_id`),
  CONSTRAINT `fk_products_descriptions_dict_codes` FOREIGN KEY (`dict_language_code`) REFERENCES `dict_languages` (`dict_language_code`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_descriptions_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_descriptions`
--

LOCK TABLES `products_descriptions` WRITE;
/*!40000 ALTER TABLE `products_descriptions` DISABLE KEYS */;
INSERT INTO `products_descriptions` VALUES (1,1,'en','True novel story','Book describes a story about John Smith'),(2,1,'pl','Prawdziwa opowieść','Książka opisująca przygoty Jana Kowalskiego'),(3,2,'pl','Długopis Axcel','Długopis firmy Axcel, kolor niebieski, wkład zenith'),(4,3,'en','Glass \"Junior\"','250 ml glass with \"Cars\" logo for kids'),(5,4,'pl','test nazwy','test opisu'),(6,5,'pl','produkt testowy','test tego produktu'),(7,10,'en','test','test 2'),(9,12,'pl','Produkt testowy przez stronę','Podany opis produktu przez stronę'),(37,41,'pl','coś coś 2','jakaś nazwa 2'),(40,44,'pl','Wprowadź 2','Test wprowadź dane'),(41,45,'en','New test 2','New test description'),(42,46,'pl','Wprowadź 2','Test wprowadź dane'),(43,47,'en','New test 2','New test description 2'),(44,49,'pl','Nowy produkt ze strony','uykyk'),(45,50,'pl','Nowy produkt ze strony','efwefwef'),(46,51,'pl','Jest to produkt ze strony','Tetuję wprowadzanie produktu na stronie');
/*!40000 ALTER TABLE `products_descriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_key` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_key_UNIQUE` (`session_key`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  CONSTRAINT `fk_sessions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `user_surname` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `user_private_key` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `user_public_key` varchar(16) CHARACTER SET utf8 DEFAULT NULL,
  `user_web_login` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `user_web_password` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `user_dict_language_code` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `idUser_UNIQUE` (`user_id`),
  UNIQUE KEY `user_public_key_UNIQUE` (`user_public_key`),
  UNIQUE KEY `user_web_login_UNIQUE` (`user_web_login`),
  KEY `fk_users_1_idx` (`user_dict_language_code`),
  CONSTRAINT `fk_users_dict_code` FOREIGN KEY (`user_dict_language_code`) REFERENCES `dict_languages` (`dict_language_code`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Jan','Testowy','92DD55411AC58648','41AE6D819F28E22E','janik12','2ba1789404f8d4b36a20b04e5e9b0f39','pl'),(2,'Mirosław','Krawczyk',NULL,NULL,'krawiec12','26da7145e7155c31e2f25d819cea9816','en'),(4,'Justyna','Zalewska','B21C24859578DCF1','D7EB97D499649FDA',NULL,NULL,'en');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'myRestApi'
--

--
-- Dumping routines for database 'myRestApi'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-05 21:45:38
