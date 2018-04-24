CREATE DATABASE  IF NOT EXISTS `test-admin` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `test-admin`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: test-admin
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.31-MariaDB

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
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `action` tinyint(4) NOT NULL,
  `result` tinyint(4) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `ip` varchar(150) DEFAULT '',
  `uri` varchar(1200) DEFAULT '',
  `data` text,
  `status` tinyint(4) DEFAULT '1',
  `details` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `adate` (`adate`),
  KEY `result` (`result`),
  KEY `action` (`action`),
  KEY `user` (`uid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `node` varchar(45) DEFAULT '',
  `pid` int(11) DEFAULT '0',
  `href` varchar(250) DEFAULT '',
  `icon` varchar(45) DEFAULT '',
  `perm_route` varchar(1000) DEFAULT '',
  `is_show` tinyint(4) DEFAULT '1',
  `status` tinyint(4) DEFAULT '0',
  `enable_edit` tinyint(4) DEFAULT '1',
  `list_order` float DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `node_UNIQUE` (`node`),
  KEY `is_show` (`is_show`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'2018-04-17 08:33:52','控制台',0,'/admin/index/dashboard/','home','/admin/index/dashboard/',1,0,0,1),(2,'2018-04-17 08:33:52','系统配置',0,'','set','',1,0,0,99),(3,'2018-04-17 08:33:52','用户数据',0,'','user','',1,0,1,3),(4,'2018-04-17 08:33:52','充值数据',0,'','diamond','',1,0,1,4),(5,'2018-04-17 08:33:52','运营管理',0,'','component','',1,0,1,5),(6,'2018-04-17 08:33:52','其他',0,'','wenjian','',1,0,1,999),(7,'2018-04-17 08:37:30','系统菜单',2,'/admin/system/menu/','','/admin/system/menu/,/admin/system/menuList/,',1,0,0,7),(8,'2018-04-17 08:37:30','系统用户',2,'/admin/system/admin/','','/admin/system/admin/,/admin/system/adminList/,',1,0,0,8),(9,'2018-04-17 08:37:30','系统权限',2,'/admin/system/permission/','','/admin/system/permission/,/admin/system/permList/,',1,0,0,9),(10,'2018-04-17 08:37:30','系统参数',2,'/admin/system/config/','','/admin/system/config/,/admin/system/configUpdate/,',1,-1,0,10),(11,'2018-04-17 08:37:30','系统日志',2,'/admin/system/logs/','','/admin/system/logs/,/admin/system/logsList/,',1,0,0,11);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(45) DEFAULT '',
  `status` tinyint(4) DEFAULT '0',
  `permissions` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_UNIQUE` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'2018-04-17 02:03:41','超级管理员',0,'{\"denied\": []}'),(2,'2018-04-17 02:03:41','管理人员',0,'{\"denied\": [7,10]}'),(3,'2018-04-17 02:03:41','运营人员',0,'{\"denied\": [2,6]}'),(4,'2018-04-17 02:03:41','游客',0,'{\"access\": [1]}'),(5,'2018-04-20 09:00:34','测试人员',0,'{\"denied\":[3,12,13,14,4,5,2,7,21,9,25,10,6,15]}');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` char(64) NOT NULL,
  `nickname` varchar(50) DEFAULT '',
  `status` tinyint(4) DEFAULT '0',
  `salt` varchar(20) DEFAULT '',
  `email` varchar(50) DEFAULT '',
  `role_id` int(11) DEFAULT '0',
  `last_login` varchar(45) DEFAULT '',
  `last_login_ip` varchar(45) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','e0489cd5c151d95f52ce708a6158bb8c3d05b6fc','Default Administrator',0,'admin','admin@123.com',1,'2018-04-22 20:57:12','192.168.0.7'),(2,'carl','28dca2a7b33b7413ad3bce1d58c26dd679c799f1','Dapianzi Carl',0,'admin','oowoolf@gmail.com',2,'2018-04-20 12:21:43','127.0.0.1'),(3,'test','c757a12a5c7b8ec3cbe2ebe259b793ec4a71ce0c','Test Users',-1,'POIH3','abd@123.com',5,'','');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'test-admin'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-23 14:13:35
