-- MySQL dump 10.13  Distrib 5.5.34, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: gruposolar
-- ------------------------------------------------------
-- Server version	5.5.34-0ubuntu0.13.04.1

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
-- Table structure for table `acciones`
--

DROP TABLE IF EXISTS `acciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acciones` (
  `id_accion` int(11) NOT NULL AUTO_INCREMENT,
  `id_lote` int(11) NOT NULL,
  `rut` int(11) NOT NULL,
  `monto` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` varchar(300) DEFAULT 'Apuesta',
  `cantidad` int(11) DEFAULT '1',
  PRIMARY KEY (`id_accion`),
  KEY `fk_a_lote` (`id_lote`),
  KEY `fk_a_rut` (`rut`),
  CONSTRAINT `fk_a_lote` FOREIGN KEY (`id_lote`) REFERENCES `lotes` (`id_lote`),
  CONSTRAINT `fk_a_rut` FOREIGN KEY (`rut`) REFERENCES `users` (`rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acciones`
--

LOCK TABLES `acciones` WRITE;
/*!40000 ALTER TABLE `acciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `acciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `adjs`
--

DROP TABLE IF EXISTS `adjs`;
/*!50001 DROP VIEW IF EXISTS `adjs`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `adjs` (
  `precio` tinyint NOT NULL,
  `id_lote` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `automaticos`
--

DROP TABLE IF EXISTS `automaticos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automaticos` (
  `id_auto` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `minimo` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `descripcion` text,
  `foto` varchar(300) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_auto`)
) ENGINE=MyISAM AUTO_INCREMENT=252 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `automaticos`
--

LOCK TABLES `automaticos` WRITE;
/*!40000 ALTER TABLE `automaticos` DISABLE KEYS */;
/*!40000 ALTER TABLE `automaticos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avisar_remate`
--

DROP TABLE IF EXISTS `avisar_remate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avisar_remate` (
  `id_remate` int(11) NOT NULL,
  `rut` int(11) NOT NULL,
  `avisado` tinyint(1) DEFAULT '0',
  UNIQUE KEY `id_remate_2` (`id_remate`,`rut`),
  KEY `id_remate` (`id_remate`,`rut`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avisar_remate`
--

LOCK TABLES `avisar_remate` WRITE;
/*!40000 ALTER TABLE `avisar_remate` DISABLE KEYS */;
/*!40000 ALTER TABLE `avisar_remate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(90) NOT NULL,
  `rut_owner` int(11) NOT NULL,
  `foto` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_cat`),
  KEY `rut_owner` (`rut_owner`),
  CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`rut_owner`) REFERENCES `users` (`rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias_portada`
--

DROP TABLE IF EXISTS `categorias_portada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias_portada` (
  `id_cp` int(11) NOT NULL AUTO_INCREMENT,
  `texto` varchar(200) DEFAULT NULL,
  `foto` varchar(250) DEFAULT NULL,
  `url_pag` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_cp`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias_portada`
--

LOCK TABLES `categorias_portada` WRITE;
/*!40000 ALTER TABLE `categorias_portada` DISABLE KEYS */;
/*!40000 ALTER TABLE `categorias_portada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` bigint(20) DEFAULT NULL,
  `sender` varchar(100) DEFAULT NULL,
  `msg` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
INSERT INTO `chat` VALUES (7,NULL,'Invitado3634','asd'),(8,NULL,'Invitado9774','test'),(9,NULL,'Invitado3982','holi'),(10,NULL,'Invitado3982','afsda'),(11,NULL,'Invitado713','fdsa'),(12,NULL,'Invitado','3refdzx');
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comunas`
--

DROP TABLE IF EXISTS `comunas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comunas` (
  `codigo` int(11) DEFAULT NULL,
  `nombre` varchar(70) DEFAULT NULL,
  `region` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comunas`
--

LOCK TABLES `comunas` WRITE;
/*!40000 ALTER TABLE `comunas` DISABLE KEYS */;
INSERT INTO `comunas` VALUES (0,'Ninguna','Ninguna'),(1,'Arica ','Arica y Parinacota'),(2,'Camarones ','Arica y Parinacota'),(3,'Putre ','Arica y Parinacota'),(4,'General Lagos ','Arica y Parinacota'),(5,'Iquique ','Tarapaca'),(6,'Alto Hospicio ','Tarapaca'),(7,'Pozo Almonte ','Tarapaca'),(8,'CamiÃ±a ','Tarapaca'),(9,'Colchane ','Tarapaca'),(10,'Huara ','Tarapaca'),(11,'Pica ','Tarapaca'),(12,'Antofagasta ','Antofagasta'),(13,'Mejillones ','Antofagasta'),(14,'Sierra Gorda ','Antofagasta'),(15,'Taltal ','Antofagasta'),(16,'Calama ','Antofagasta'),(17,'OllagÃ¼e ','Antofagasta'),(18,'San Pedro de Atacama ','Antofagasta'),(19,'Tocopilla ','Antofagasta'),(20,'MarÃ­a Elena ','Antofagasta'),(21,'CopiapÃ³ ','Atacama'),(22,'Caldera ','Atacama'),(23,'Tierra Amarilla ','Atacama'),(24,'ChaÃ±aral ','Atacama'),(25,'Diego de Almagro ','Atacama'),(26,'Vallenar ','Atacama'),(27,'Alto del Carmen ','Atacama'),(28,'Freirina ','Atacama'),(29,'Huasco ','Atacama'),(30,'La Serena ','Coquimbo'),(31,'Coquimbo ','Coquimbo'),(32,'Andacollo ','Coquimbo'),(33,'La Higuera ','Coquimbo'),(34,'Paiguano ','Coquimbo'),(35,'VicuÃ±a ','Coquimbo'),(36,'Illapel ','Coquimbo'),(37,'Canela ','Coquimbo'),(38,'Los Vilos ','Coquimbo'),(39,'Salamanca ','Coquimbo'),(40,'Ovalle ','Coquimbo'),(41,'CombarbalÃ¡ ','Coquimbo'),(42,'Monte Patria ','Coquimbo'),(43,'Punitaqui ','Coquimbo'),(44,'RÃ­o Hurtado ','Coquimbo'),(45,'ValparaÃ­so ','Valparaiso'),(46,'Casablanca ','Valparaiso'),(47,'ConcÃ³n ','Valparaiso'),(48,'Juan FernÃ¡ndez ','Valparaiso'),(49,'PuchuncavÃ­ ','Valparaiso'),(50,'Quintero ','Valparaiso'),(51,'ViÃ±a del Mar ','Valparaiso'),(52,'Isla de Pascua ','Valparaiso'),(53,'Los Andes ','Valparaiso'),(54,'Calle Larga ','Valparaiso'),(55,'Rinconada ','Valparaiso'),(56,'San Esteban ','Valparaiso'),(57,'La Ligua ','Valparaiso'),(58,'Cabildo ','Valparaiso'),(59,'Papudo ','Valparaiso'),(60,'Petorca ','Valparaiso'),(61,'Zapallar ','Valparaiso'),(62,'Quillota ','Valparaiso'),(63,'Calera ','Valparaiso'),(64,'Hijuelas ','Valparaiso'),(65,'La Cruz ','Valparaiso'),(66,'Nogales ','Valparaiso'),(67,'San Antonio ','Valparaiso'),(68,'Algarrobo ','Valparaiso'),(69,'Cartagena ','Valparaiso'),(70,'El Quisco ','Valparaiso'),(71,'El Tabo ','Valparaiso'),(72,'Santo Domingo ','Valparaiso'),(73,'San Felipe ','Valparaiso'),(74,'Catemu ','Valparaiso'),(75,'Llaillay ','Valparaiso'),(76,'Panquehue ','Valparaiso'),(77,'Putaendo ','Valparaiso'),(78,'Santa MarÃ­a ','Valparaiso'),(79,'QuilpuÃ© ','Valparaiso'),(80,'Limache ','Valparaiso'),(81,'OlmuÃ© ','Valparaiso'),(82,'Villa Alemana ','Valparaiso'),(83,'Rancagua ','Libertador General Bernardo O\'Higgins'),(84,'Codegua ','Libertador General Bernardo O\'Higgins'),(85,'Coinco ','Libertador General Bernardo O\'Higgins'),(86,'Coltauco ','Libertador General Bernardo O\'Higgins'),(87,'DoÃ±ihue ','Libertador General Bernardo O\'Higgins'),(88,'Graneros ','Libertador General Bernardo O\'Higgins'),(89,'Las Cabras ','Libertador General Bernardo O\'Higgins'),(90,'MachalÃ­ ','Libertador General Bernardo O\'Higgins'),(91,'Malloa ','Libertador General Bernardo O\'Higgins'),(92,'Mostazal ','Libertador General Bernardo O\'Higgins'),(93,'Olivar ','Libertador General Bernardo O\'Higgins'),(94,'Peumo ','Libertador General Bernardo O\'Higgins'),(95,'Pichidegua ','Libertador General Bernardo O\'Higgins'),(96,'Quinta de Tilcoco ','Libertador General Bernardo O\'Higgins'),(97,'Rengo ','Libertador General Bernardo O\'Higgins'),(98,'RequÃ­noa ','Libertador General Bernardo O\'Higgins'),(99,'San Vicente ','Libertador General Bernardo O\'Higgins'),(100,'Pichilemu ','Libertador General Bernardo O\'Higgins'),(101,'La Estrella ','Libertador General Bernardo O\'Higgins'),(102,'Litueche ','Libertador General Bernardo O\'Higgins'),(103,'Marchihue ','Libertador General Bernardo O\'Higgins'),(104,'Navidad ','Libertador General Bernardo O\'Higgins'),(105,'Paredones ','Libertador General Bernardo O\'Higgins'),(106,'San Fernando ','Libertador General Bernardo O\'Higgins'),(107,'ChÃ©pica ','Libertador General Bernardo O\'Higgins'),(108,'Chimbarongo ','Libertador General Bernardo O\'Higgins'),(109,'Lolol ','Libertador General Bernardo O\'Higgins'),(110,'Nancagua ','Libertador General Bernardo O\'Higgins'),(111,'Palmilla ','Libertador General Bernardo O\'Higgins'),(112,'Peralillo ','Libertador General Bernardo O\'Higgins'),(113,'Placilla ','Libertador General Bernardo O\'Higgins'),(114,'Pumanque ','Libertador General Bernardo O\'Higgins'),(115,'Santa Cruz ','Libertador General Bernardo O\'Higgins'),(116,'Talca ','Maule'),(117,'ConstituciÃ³n ','Maule'),(118,'Curepto ','Maule'),(119,'Empedrado ','Maule'),(120,'Maule ','Maule'),(121,'Pelarco ','Maule'),(122,'Pencahue ','Maule'),(123,'RÃ­o Claro ','Maule'),(124,'San Clemente ','Maule'),(125,'San Rafael ','Maule'),(126,'Cauquenes ','Maule'),(127,'Chanco ','Maule'),(128,'Pelluhue ','Maule'),(129,'CuricÃ³ ','Maule'),(130,'HualaÃ±Ã© ','Maule'),(131,'LicantÃ©n ','Maule'),(132,'Molina ','Maule'),(133,'Rauco ','Maule'),(134,'Romeral ','Maule'),(135,'Sagrada Familia ','Maule'),(136,'Teno ','Maule'),(137,'VichuquÃ©n ','Maule'),(138,'Linares ','Maule'),(139,'ColbÃºn ','Maule'),(140,'LongavÃ­ ','Maule'),(141,'Parral ','Maule'),(142,'Retiro ','Maule'),(143,'San Javier ','Maule'),(144,'Villa Alegre ','Maule'),(145,'Yerbas Buenas ','Maule'),(146,'ConcepciÃ³n ','Biobio'),(147,'Coronel ','Biobio'),(148,'Chiguayante ','Biobio'),(149,'Florida ','Biobio'),(150,'Hualqui ','Biobio'),(151,'Lota ','Biobio'),(152,'Penco ','Biobio'),(153,'San Pedro de La Paz ','Biobio'),(154,'Santa Juana ','Biobio'),(155,'Talcahuano ','Biobio'),(156,'TomÃ© ','Biobio'),(157,'HualpÃ©n ','Biobio'),(158,'Lebu ','Biobio'),(159,'Arauco ','Biobio'),(160,'CaÃ±ete ','Biobio'),(161,'Contulmo ','Biobio'),(162,'Curanilahue ','Biobio'),(163,'Los Ãlamos ','Biobio'),(164,'TirÃºa ','Biobio'),(165,'Los Ãngeles ','Biobio'),(166,'Antuco ','Biobio'),(167,'Cabrero ','Biobio'),(168,'Laja ','Biobio'),(169,'MulchÃ©n ','Biobio'),(170,'Nacimiento ','Biobio'),(171,'Negrete ','Biobio'),(172,'Quilaco ','Biobio'),(173,'Quilleco ','Biobio'),(174,'San Rosendo ','Biobio'),(175,'Santa BÃ¡rbara ','Biobio'),(176,'Tucapel ','Biobio'),(177,'Yumbel ','Biobio'),(178,'Alto BiobÃ­o ','Biobio'),(179,'ChillÃ¡n ','Biobio'),(180,'Bulnes ','Biobio'),(181,'Cobquecura ','Biobio'),(182,'Coelemu ','Biobio'),(183,'Coihueco ','Biobio'),(184,'ChillÃ¡n Viejo ','Biobio'),(185,'El Carmen ','Biobio'),(186,'Ninhue ','Biobio'),(187,'Ã‘iquÃ©n ','Biobio'),(188,'Pemuco ','Biobio'),(189,'Pinto ','Biobio'),(190,'Portezuelo ','Biobio'),(191,'QuillÃ³n ','Biobio'),(192,'Quirihue ','Biobio'),(193,'RÃ¡nquil ','Biobio'),(194,'San Carlos ','Biobio'),(195,'San FabiÃ¡n ','Biobio'),(196,'San Ignacio ','Biobio'),(197,'San NicolÃ¡s ','Biobio'),(198,'Treguaco ','Biobio'),(199,'Yungay ','Biobio'),(200,'Temuco ','La Araucania'),(201,'Carahue ','La Araucania'),(202,'Cunco ','La Araucania'),(203,'Curarrehue ','La Araucania'),(204,'Freire ','La Araucania'),(205,'Galvarino ','La Araucania'),(206,'Gorbea ','La Araucania'),(207,'Lautaro ','La Araucania'),(208,'Loncoche ','La Araucania'),(209,'Melipeuco ','La Araucania'),(210,'Nueva Imperial ','La Araucania'),(211,'Padre Las Casas ','La Araucania'),(212,'Perquenco ','La Araucania'),(213,'PitrufquÃ©n ','La Araucania'),(214,'PucÃ³n ','La Araucania'),(215,'Saavedra ','La Araucania'),(216,'Teodoro Schmidt ','La Araucania'),(217,'ToltÃ©n ','La Araucania'),(218,'VilcÃºn ','La Araucania'),(219,'Villarrica ','La Araucania'),(220,'Cholchol ','La Araucania'),(221,'Angol ','La Araucania'),(222,'Collipulli ','La Araucania'),(223,'CuracautÃ­n ','La Araucania'),(224,'Ercilla ','La Araucania'),(225,'Lonquimay ','La Araucania'),(226,'Los Sauces ','La Araucania'),(227,'Lumaco ','La Araucania'),(228,'PurÃ©n ','La Araucania'),(229,'Renaico ','La Araucania'),(230,'TraiguÃ©n ','La Araucania'),(231,'Victoria ','La Araucania'),(232,'Valdivia ','Los Rios'),(233,'Corral ','Los Rios'),(234,'Lanco ','Los Rios'),(235,'Los Lagos ','Los Rios'),(236,'MÃ¡fil ','Los Rios'),(237,'Mariquina ','Los Rios'),(238,'Paillaco ','Los Rios'),(239,'Panguipulli ','Los Rios'),(240,'La UniÃ³n ','Los Rios'),(241,'Futrono ','Los Rios'),(242,'Lago Ranco ','Los Rios'),(243,'RÃ­o Bueno ','Los Rios'),(244,'Puerto Montt ','Los Lagos'),(245,'Calbuco ','Los Lagos'),(246,'CochamÃ³ ','Los Lagos'),(247,'Fresia ','Los Lagos'),(248,'Frutillar ','Los Lagos'),(249,'Los Muermos ','Los Lagos'),(250,'Llanquihue ','Los Lagos'),(251,'MaullÃ­n ','Los Lagos'),(252,'Puerto Varas ','Los Lagos'),(253,'Castro ','Los Lagos'),(254,'Ancud ','Los Lagos'),(255,'Chonchi ','Los Lagos'),(256,'Curaco de VÃ©lez ','Los Lagos'),(257,'Dalcahue ','Los Lagos'),(258,'PuqueldÃ³n ','Los Lagos'),(259,'QueilÃ©n ','Los Lagos'),(260,'QuellÃ³n ','Los Lagos'),(261,'Quemchi ','Los Lagos'),(262,'Quinchao ','Los Lagos'),(263,'Osorno ','Los Lagos'),(264,'Puerto Octay ','Los Lagos'),(265,'Purranque ','Los Lagos'),(266,'Puyehue ','Los Lagos'),(267,'RÃ­o Negro ','Los Lagos'),(268,'San Juan de la Costa ','Los Lagos'),(269,'San Pablo ','Los Lagos'),(270,'ChaitÃ©n ','Los Lagos'),(271,'FutaleufÃº ','Los Lagos'),(272,'HualaihuÃ© ','Los Lagos'),(273,'Palena ','Los Lagos'),(274,'Coihaique ','Aysen del General Carlos Ibanez del Campo'),(275,'Lago Verde ','Aysen del General Carlos Ibanez del Campo'),(276,'AysÃ©n ','Aysen del General Carlos Ibanez del Campo'),(277,'Cisnes ','Aysen del General Carlos Ibanez del Campo'),(278,'Guaitecas ','Aysen del General Carlos Ibanez del Campo'),(279,'Cochrane ','Aysen del General Carlos Ibanez del Campo'),(280,'O\'Higgins ','Aysen del General Carlos Ibanez del Campo'),(281,'Tortel ','Aysen del General Carlos Ibanez del Campo'),(282,'Chile Chico ','Aysen del General Carlos Ibanez del Campo'),(283,'RÃ­o IbÃ¡Ã±ez ','Aysen del General Carlos Ibanez del Campo'),(284,'Punta Arenas ','Magallanes y de la Antartica Chilena'),(285,'Laguna Blanca ','Magallanes y de la Antartica Chilena'),(286,'RÃ­o Verde ','Magallanes y de la Antartica Chilena'),(287,'San Gregorio ','Magallanes y de la Antartica Chilena'),(288,'Cabo de Hornos ','Magallanes y de la Antartica Chilena'),(289,'AntÃ¡rtica ','Magallanes y de la Antartica Chilena'),(290,'Porvenir ','Magallanes y de la Antartica Chilena'),(291,'Primavera ','Magallanes y de la Antartica Chilena'),(292,'Timaukel ','Magallanes y de la Antartica Chilena'),(293,'Natales ','Magallanes y de la Antartica Chilena'),(294,'Torres del Paine ','Magallanes y de la Antartica Chilena'),(295,'Santiago ','Metropolitana de Santiago'),(296,'Cerrillos ','Metropolitana de Santiago'),(297,'Cerro Navia ','Metropolitana de Santiago'),(298,'ConchalÃ­ ','Metropolitana de Santiago'),(299,'El Bosque ','Metropolitana de Santiago'),(300,'EstaciÃ³n Central ','Metropolitana de Santiago'),(301,'Huechuraba ','Metropolitana de Santiago'),(302,'Independencia ','Metropolitana de Santiago'),(303,'La Cisterna ','Metropolitana de Santiago'),(304,'La Florida ','Metropolitana de Santiago'),(305,'La Granja ','Metropolitana de Santiago'),(306,'La Pintana ','Metropolitana de Santiago'),(307,'La Reina ','Metropolitana de Santiago'),(308,'Las Condes ','Metropolitana de Santiago'),(309,'Lo Barnechea ','Metropolitana de Santiago'),(310,'Lo Espejo ','Metropolitana de Santiago'),(311,'Lo Prado ','Metropolitana de Santiago'),(312,'Macul ','Metropolitana de Santiago'),(313,'MaipÃº ','Metropolitana de Santiago'),(314,'Ã‘uÃ±oa ','Metropolitana de Santiago'),(315,'Pedro Aguirre Cerda ','Metropolitana de Santiago'),(316,'PeÃ±alolÃ©n ','Metropolitana de Santiago'),(317,'Providencia ','Metropolitana de Santiago'),(318,'Pudahuel ','Metropolitana de Santiago'),(319,'Quilicura ','Metropolitana de Santiago'),(320,'Quinta Normal ','Metropolitana de Santiago'),(321,'Recoleta ','Metropolitana de Santiago'),(322,'Renca ','Metropolitana de Santiago'),(323,'San JoaquÃ­n ','Metropolitana de Santiago'),(324,'San Miguel ','Metropolitana de Santiago'),(325,'San RamÃ³n ','Metropolitana de Santiago'),(326,'Vitacura ','Metropolitana de Santiago'),(327,'Puente Alto ','Metropolitana de Santiago'),(328,'Pirque ','Metropolitana de Santiago'),(329,'San JosÃ© de Maipo ','Metropolitana de Santiago'),(330,'Colina ','Metropolitana de Santiago'),(331,'Lampa ','Metropolitana de Santiago'),(332,'Tiltil ','Metropolitana de Santiago'),(333,'San Bernardo ','Metropolitana de Santiago'),(334,'Buin ','Metropolitana de Santiago'),(335,'Calera de Tango ','Metropolitana de Santiago'),(336,'Paine ','Metropolitana de Santiago'),(337,'Melipilla ','Metropolitana de Santiago'),(338,'AlhuÃ© ','Metropolitana de Santiago'),(339,'CuracavÃ­ ','Metropolitana de Santiago'),(340,'MarÃ­a Pinto ','Metropolitana de Santiago'),(341,'San Pedro ','Metropolitana de Santiago'),(342,'Talagante ','Metropolitana de Santiago'),(343,'El Monte ','Metropolitana de Santiago'),(344,'Isla de Maipo ','Metropolitana de Santiago'),(345,'Padre Hurtado ','Metropolitana de Santiago'),(346,'PeÃ±aflor ','Metropolitana de Santiago');
/*!40000 ALTER TABLE `comunas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conectados_hoy`
--

DROP TABLE IF EXISTS `conectados_hoy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conectados_hoy` (
  `rut` int(11) NOT NULL,
  `logged` tinyint(1) DEFAULT '1',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_remate` int(11) NOT NULL,
  PRIMARY KEY (`rut`),
  UNIQUE KEY `rut` (`rut`,`id_remate`),
  KEY `logged` (`logged`),
  KEY `fecha` (`fecha`),
  KEY `id_remate` (`id_remate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conectados_hoy`
--

LOCK TABLES `conectados_hoy` WRITE;
/*!40000 ALTER TABLE `conectados_hoy` DISABLE KEYS */;
/*!40000 ALTER TABLE `conectados_hoy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emails`
--

DROP TABLE IF EXISTS `emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emails` (
  `id_email` int(11) NOT NULL AUTO_INCREMENT,
  `asunto` varchar(300) DEFAULT NULL,
  `cuerpo` text,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_email`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emails`
--

LOCK TABLES `emails` WRITE;
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `envios`
--

DROP TABLE IF EXISTS `envios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `envios` (
  `id_envio` int(11) NOT NULL AUTO_INCREMENT,
  `orden_envio` varchar(20) DEFAULT NULL,
  `n_factura` bigint(20) NOT NULL,
  `n_despacho` bigint(20) NOT NULL,
  `empresa` varchar(60) DEFAULT NULL,
  `ciudad` varchar(90) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `tracker` text,
  PRIMARY KEY (`id_envio`),
  UNIQUE KEY `n_factura` (`n_factura`,`n_despacho`)
) ENGINE=MyISAM AUTO_INCREMENT=2806 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `envios`
--

LOCK TABLES `envios` WRITE;
/*!40000 ALTER TABLE `envios` DISABLE KEYS */;
/*!40000 ALTER TABLE `envios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galeria`
--

DROP TABLE IF EXISTS `galeria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `galeria` (
  `id_remate` int(11) NOT NULL,
  `foto` varchar(250) NOT NULL,
  `texto` text,
  `link` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id_remate`,`foto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galeria`
--

LOCK TABLES `galeria` WRITE;
/*!40000 ALTER TABLE `galeria` DISABLE KEYS */;
/*!40000 ALTER TABLE `galeria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes`
--

DROP TABLE IF EXISTS `imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagenes` (
  `id_imagen` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id_imagen`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes`
--

LOCK TABLES `imagenes` WRITE;
/*!40000 ALTER TABLE `imagenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `imagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotes`
--

DROP TABLE IF EXISTS `lotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lotes` (
  `id_lote` int(11) NOT NULL AUTO_INCREMENT,
  `id_remate` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_termino` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `orden` int(11) DEFAULT '0',
  `repartido` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_lote`),
  KEY `fk_remate` (`id_remate`),
  KEY `fk_producto` (`id_producto`),
  CONSTRAINT `fk_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  CONSTRAINT `fk_remate` FOREIGN KEY (`id_remate`) REFERENCES `remates` (`id_remate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotes`
--

LOCK TABLES `lotes` WRITE;
/*!40000 ALTER TABLE `lotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `lotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_queue`
--

DROP TABLE IF EXISTS `mail_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_queue` (
  `id_task` int(11) NOT NULL,
  `id_email` int(11) NOT NULL,
  `entregado` tinyint(1) DEFAULT '0',
  `fecha_entrega` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `email_destino` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_queue`
--

LOCK TABLES `mail_queue` WRITE;
/*!40000 ALTER TABLE `mail_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `miniremates`
--

DROP TABLE IF EXISTS `miniremates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `miniremates` (
  `id_miniremate` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `fecha_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_termino` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rut_ganador` int(11) NOT NULL,
  `monto_actual` int(11) NOT NULL,
  `incremento` int(11) DEFAULT '10',
  `finalizado` tinyint(1) DEFAULT '0',
  `monto_inicial` int(11) NOT NULL,
  `foto` varchar(250) DEFAULT NULL,
  `texto` text,
  `titulo` varchar(40) DEFAULT NULL,
  `notificado` tinyint(1) DEFAULT '0',
  `auto` tinyint(1) DEFAULT '0',
  `limpio` tinyint(1) DEFAULT '1',
  `delta` int(11) DEFAULT '0',
  `pagado` tinyint(1) DEFAULT '0',
  `info` text,
  PRIMARY KEY (`id_miniremate`)
) ENGINE=MyISAM AUTO_INCREMENT=122109 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `miniremates`
--

LOCK TABLES `miniremates` WRITE;
/*!40000 ALTER TABLE `miniremates` DISABLE KEYS */;
INSERT INTO `miniremates` VALUES (122108,1,'2013-10-25 01:05:48','2013-10-31 04:00:00',17596597,10200,1,0,10000,'uploads/minir/icon.png','Escriba descripciÃ³n','Escriba tÃ­tulo',0,0,0,134,0,NULL);
/*!40000 ALTER TABLE `miniremates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notasdeventa`
--

DROP TABLE IF EXISTS `notasdeventa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notasdeventa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_remate` varchar(9) DEFAULT NULL,
  `rut` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_remate` (`id_remate`,`rut`)
) ENGINE=MyISAM AUTO_INCREMENT=8788 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notasdeventa`
--

LOCK TABLES `notasdeventa` WRITE;
/*!40000 ALTER TABLE `notasdeventa` DISABLE KEYS */;
/*!40000 ALTER TABLE `notasdeventa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `noticias` (
  `id_noticia` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `cuerpo` text,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_noticia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticias`
--

LOCK TABLES `noticias` WRITE;
/*!40000 ALTER TABLE `noticias` DISABLE KEYS */;
/*!40000 ALTER TABLE `noticias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ofertas`
--

DROP TABLE IF EXISTS `ofertas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ofertas` (
  `id_oferta` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` text,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_termino` datetime DEFAULT NULL,
  `cant_maxima` int(11) DEFAULT NULL,
  `fono` varchar(50) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(250) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `banner` varchar(400) DEFAULT NULL,
  `procedimiento` varchar(400) DEFAULT NULL,
  `stock` int(11) DEFAULT '0',
  `parametro_nom` varchar(100) DEFAULT NULL,
  `parametro_op1` varchar(50) DEFAULT NULL,
  `parametro_op2` varchar(50) DEFAULT NULL,
  `parametro_op3` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_oferta`)
) ENGINE=MyISAM AUTO_INCREMENT=181 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ofertas`
--

LOCK TABLES `ofertas` WRITE;
/*!40000 ALTER TABLE `ofertas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ofertas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ofertas_compradas`
--

DROP TABLE IF EXISTS `ofertas_compradas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ofertas_compradas` (
  `id_oferta` int(11) NOT NULL,
  `rut_usuario` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT '1',
  `fecha_compra` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parametro` int(11) DEFAULT '1',
  `comment` text,
  `pagado` tinyint(1) DEFAULT '0',
  UNIQUE KEY `ofertas_por_persona` (`id_oferta`,`rut_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ofertas_compradas`
--

LOCK TABLES `ofertas_compradas` WRITE;
/*!40000 ALTER TABLE `ofertas_compradas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ofertas_compradas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pertenece_categoria`
--

DROP TABLE IF EXISTS `pertenece_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pertenece_categoria` (
  `id_producto` int(11) DEFAULT NULL,
  `id_cat` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  UNIQUE KEY `pk_producto_cat` (`id_producto`,`id_cat`),
  KEY `fk_cat` (`id_cat`),
  KEY `id_producto` (`id_producto`,`id_cat`),
  CONSTRAINT `fk_cat` FOREIGN KEY (`id_cat`) REFERENCES `categorias` (`id_cat`),
  CONSTRAINT `fk_prod_cat` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pertenece_categoria`
--

LOCK TABLES `pertenece_categoria` WRITE;
/*!40000 ALTER TABLE `pertenece_categoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `pertenece_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `precios_f`
--

DROP TABLE IF EXISTS `precios_f`;
/*!50001 DROP VIEW IF EXISTS `precios_f`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `precios_f` (
  `id_lote` tinyint NOT NULL,
  `precio_min` tinyint NOT NULL,
  `precio` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `precios_f2`
--

DROP TABLE IF EXISTS `precios_f2`;
/*!50001 DROP VIEW IF EXISTS `precios_f2`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `precios_f2` (
  `id_lote` tinyint NOT NULL,
  `precio_min` tinyint NOT NULL,
  `precio` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `descripcion` mediumtext,
  `precio_min` int(11) DEFAULT NULL,
  `foto1` varchar(70) DEFAULT NULL,
  `foto2` varchar(70) DEFAULT NULL,
  `foto3` varchar(70) DEFAULT NULL,
  `foto4` varchar(70) DEFAULT NULL,
  `subunidades` int(11) DEFAULT '1',
  `ultimo_orden` int(11) NOT NULL DEFAULT '3000',
  `rut_owner` int(11) DEFAULT '17596597',
  `foto_ref` varchar(200) DEFAULT NULL,
  `link` varchar(400) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_producto`),
  KEY `fk_owner_producto` (`rut_owner`),
  CONSTRAINT `fk_owner_producto` FOREIGN KEY (`rut_owner`) REFERENCES `users` (`rut`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'prod1','descr',10000,'uploads/0a74bf9baa6fbe38.png',NULL,NULL,NULL,1,3000,17596597,NULL,'',1);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publicidades`
--

DROP TABLE IF EXISTS `publicidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publicidades` (
  `id_pub` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` enum('imagen','flash','youtube','custom') DEFAULT NULL,
  `html` text,
  PRIMARY KEY (`id_pub`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicidades`
--

LOCK TABLES `publicidades` WRITE;
/*!40000 ALTER TABLE `publicidades` DISABLE KEYS */;
/*!40000 ALTER TABLE `publicidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remate_owners`
--

DROP TABLE IF EXISTS `remate_owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remate_owners` (
  `id_remate` int(11) DEFAULT NULL,
  `rut_owner` int(11) DEFAULT NULL,
  KEY `fk_own_remate` (`id_remate`),
  KEY `fk_rut_own_remate` (`rut_owner`),
  CONSTRAINT `fk_own_remate` FOREIGN KEY (`id_remate`) REFERENCES `remates` (`id_remate`),
  CONSTRAINT `fk_rut_own_remate` FOREIGN KEY (`rut_owner`) REFERENCES `users` (`rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remate_owners`
--

LOCK TABLES `remate_owners` WRITE;
/*!40000 ALTER TABLE `remate_owners` DISABLE KEYS */;
/*!40000 ALTER TABLE `remate_owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remates`
--

DROP TABLE IF EXISTS `remates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remates` (
  `id_remate` int(11) NOT NULL AUTO_INCREMENT,
  `lugar` text,
  `descripcion` text,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `en_curso` tinyint(1) DEFAULT '1',
  `tipo` enum('Online','Presencial') DEFAULT 'Online',
  `tipo_puja` enum('Porcentual','Fijo','Sin Minimo') DEFAULT 'Porcentual',
  `valor_puja` double DEFAULT '10',
  `duracion_lote` int(11) DEFAULT '25',
  `lote_actual` int(11) DEFAULT NULL,
  `publico` tinyint(1) DEFAULT '1',
  `tiempo_pausa` datetime DEFAULT NULL,
  `ciudad` varchar(40) DEFAULT 'Santiago',
  `comision` int(11) DEFAULT '0',
  `iva_comision` tinyint(1) DEFAULT '1',
  `tipo_productos` varchar(40) DEFAULT 'Nuevos',
  `contacto` varchar(40) DEFAULT NULL,
  `id_sala` int(11) DEFAULT '1',
  `banner_size` int(11) DEFAULT '3',
  `banner` varchar(250) DEFAULT NULL,
  `finalizado` tinyint(1) DEFAULT '0',
  `rut_owner` int(11) NOT NULL DEFAULT '17596597',
  `factor` float DEFAULT '0',
  `procedimiento` varchar(300) DEFAULT NULL,
  `contacto_email` varchar(100) DEFAULT '',
  `contacto_fijo` varchar(100) DEFAULT '',
  `contacto_movil` varchar(100) DEFAULT '',
  `afecto_a_iva` tinyint(1) DEFAULT '1',
  `requiere_auth` tinyint(1) DEFAULT '0',
  `texto_usuario_noauth` text,
  PRIMARY KEY (`id_remate`),
  KEY `fk_salaremate` (`id_sala`),
  KEY `fk_rut_remate` (`rut_owner`),
  CONSTRAINT `fk_rut_remate` FOREIGN KEY (`rut_owner`) REFERENCES `users` (`rut`),
  CONSTRAINT `fk_salaremate` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remates`
--

LOCK TABLES `remates` WRITE;
/*!40000 ALTER TABLE `remates` DISABLE KEYS */;
/*!40000 ALTER TABLE `remates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salas`
--

DROP TABLE IF EXISTS `salas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salas` (
  `id_sala` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(90) DEFAULT NULL,
  `rut_owner` int(11) DEFAULT '17596597',
  PRIMARY KEY (`id_sala`),
  KEY `fk_owner_sala` (`rut_owner`),
  CONSTRAINT `fk_owner_sala` FOREIGN KEY (`rut_owner`) REFERENCES `users` (`rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salas`
--

LOCK TABLES `salas` WRITE;
/*!40000 ALTER TABLE `salas` DISABLE KEYS */;
/*!40000 ALTER TABLE `salas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmp`
--

DROP TABLE IF EXISTS `tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp` (
  `codigo` int(11) DEFAULT NULL,
  `nombre` varchar(70) DEFAULT NULL,
  `region` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmp`
--

LOCK TABLES `tmp` WRITE;
/*!40000 ALTER TABLE `tmp` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `rut` int(11) NOT NULL,
  `dv` char(1) DEFAULT NULL,
  `apellidop` varchar(40) DEFAULT NULL,
  `apellidom` varchar(40) DEFAULT NULL,
  `nombres` varchar(40) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `telefono2` varchar(20) DEFAULT NULL,
  `direccion` text,
  `banned` tinyint(1) DEFAULT '0',
  `activated` tinyint(1) DEFAULT '1',
  `logged` tinyint(1) DEFAULT '0',
  `region` text,
  `comuna` int(11) DEFAULT NULL,
  `nacionalidad` varchar(30) DEFAULT NULL,
  `fecha_inscripcion` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_ultimavisita` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `garantia` tinyint(1) DEFAULT '1',
  `pseudopass` varchar(50) DEFAULT NULL,
  `inscrito` tinyint(1) DEFAULT '1',
  `f_rut` int(11) DEFAULT NULL,
  `f_nombre` varchar(60) DEFAULT NULL,
  `f_giro` varchar(60) DEFAULT NULL,
  `f_direccion` varchar(150) DEFAULT NULL,
  `f_telefono` varchar(20) DEFAULT NULL,
  `f_region` varchar(50) DEFAULT NULL,
  `f_comuna` int(11) DEFAULT NULL,
  `f_email` varchar(100) DEFAULT NULL,
  `f_dv` char(1) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `causal` text,
  `link_empresa` varchar(100) DEFAULT NULL,
  `datos_bancarios` text,
  `ultimocambio` datetime DEFAULT NULL,
  `autorizado_rsm` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (17596597,'1','Canales','Caceres','Juan Ignacio','195e6c74b3d36fe1d0b00aea50481dff',NULL,NULL,NULL,NULL,0,1,1,NULL,327,NULL,'0000-00-00 00:00:00','2013-10-25 01:05:01',1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_lote` int(11) NOT NULL,
  `rut` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '0',
  `precio_venta` int(11) NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `fk_v_lote` (`id_lote`),
  KEY `fk_v_rut` (`rut`),
  CONSTRAINT `fk_v_lote` FOREIGN KEY (`id_lote`) REFERENCES `lotes` (`id_lote`),
  CONSTRAINT `fk_v_rut` FOREIGN KEY (`rut`) REFERENCES `users` (`rut`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `adjs`
--

/*!50001 DROP TABLE IF EXISTS `adjs`*/;
/*!50001 DROP VIEW IF EXISTS `adjs`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `adjs` AS select max(`acciones`.`monto`) AS `precio`,`acciones`.`id_lote` AS `id_lote` from `acciones` where (`acciones`.`tipo` = _latin1'Adjudicacion') group by `acciones`.`id_lote` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `precios_f`
--

/*!50001 DROP TABLE IF EXISTS `precios_f`*/;
/*!50001 DROP VIEW IF EXISTS `precios_f`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`juantio`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `precios_f` AS select `lotes`.`id_lote` AS `id_lote`,`productos`.`precio_min` AS `precio_min`,if(isnull(`acciones`.`monto`),`productos`.`precio_min`,max(`acciones`.`monto`)) AS `precio` from ((`lotes` left join `acciones` on((`lotes`.`id_lote` = `acciones`.`id_lote`))) left join `productos` on((`productos`.`id_producto` = `lotes`.`id_producto`))) group by `lotes`.`id_lote` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `precios_f2`
--

/*!50001 DROP TABLE IF EXISTS `precios_f2`*/;
/*!50001 DROP VIEW IF EXISTS `precios_f2`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `precios_f2` AS select `lotes`.`id_lote` AS `id_lote`,`productos`.`precio_min` AS `precio_min`,if(isnull(`acciones`.`monto`),`productos`.`precio_min`,`acciones`.`monto`) AS `precio` from ((`lotes` join `productos` on((`lotes`.`id_producto` = `productos`.`id_producto`))) left join `acciones` on((`lotes`.`id_lote` = `acciones`.`id_lote`))) where (`acciones`.`tipo` = _latin1'Adjudicacion') group by `acciones`.`id_lote` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-10-28  0:33:03
