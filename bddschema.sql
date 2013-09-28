-- MySQL dump 10.13  Distrib 5.1.47, for unknown-linux-gnu (x86_64)
--
-- Host: localhost    Database: remates2
-- ------------------------------------------------------
-- Server version	5.1.47

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
) ENGINE=InnoDB AUTO_INCREMENT=65736 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `adjs`
--

DROP TABLE IF EXISTS `adjs`;
/*!50001 DROP VIEW IF EXISTS `adjs`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `adjs` (
  `precio` int(11),
  `id_lote` int(11)
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(30) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `msg` text,
  `receive` varchar(30) DEFAULT NULL,
  `id_remate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36311 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=103033 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=MyISAM AUTO_INCREMENT=122106 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Temporary table structure for view `precios_f`
--

DROP TABLE IF EXISTS `precios_f`;
/*!50001 DROP VIEW IF EXISTS `precios_f`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `precios_f` (
  `id_lote` int(11),
  `precio_min` int(11),
  `precio` bigint(20)
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
  `id_lote` int(11),
  `precio_min` int(11),
  `precio` bigint(11)
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
) ENGINE=InnoDB AUTO_INCREMENT=505 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=494 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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

-- Dump completed on 2013-09-28  1:37:32
