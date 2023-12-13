-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para bdblog
CREATE DATABASE IF NOT EXISTS `bdblog` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `bdblog`;

-- Volcando estructura para tabla bdblog.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `IDCAT` int(5) NOT NULL AUTO_INCREMENT,
  `NOMBRECAT` varchar(40) NOT NULL,
  PRIMARY KEY (`IDCAT`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla bdblog.categorias: ~11 rows (aproximadamente)
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` (`IDCAT`, `NOMBRECAT`) VALUES
	(1, 'Televisores'),
	(2, 'Electrodomésticos'),
	(24, 'Ciencias'),
	(25, 'Informática'),
	(30, 'Mascotas'),
	(31, 'Animales'),
	(32, 'Ordenadores'),
	(33, 'Asignaturas'),
	(34, 'Módulos'),
	(35, 'Vehículos'),
	(36, 'Otros');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;

-- Volcando estructura para tabla bdblog.entradas
CREATE TABLE IF NOT EXISTS `entradas` (
  `IDENT` int(5) NOT NULL AUTO_INCREMENT,
  `IDUSUARIO` int(5) NOT NULL,
  `IDCATEGORIA` int(5) NOT NULL,
  `TITULO` varchar(40) NOT NULL,
  `IMAGEN` varchar(40) NOT NULL,
  `DESCRIPCION` text NOT NULL,
  `FECHA` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`IDENT`),
  KEY `IDUSUARIO` (`IDUSUARIO`),
  KEY `IDCATEGORIA` (`IDCATEGORIA`),
  CONSTRAINT `ENTRADAS_IBFK_1` FOREIGN KEY (`IDUSUARIO`) REFERENCES `usuarios` (`IDUSER`) ON UPDATE CASCADE,
  CONSTRAINT `ENTRADAS_IBFK_2` FOREIGN KEY (`IDCATEGORIA`) REFERENCES `categorias` (`IDCAT`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla bdblog.entradas: ~15 rows (aproximadamente)
/*!40000 ALTER TABLE `entradas` DISABLE KEYS */;
INSERT INTO `entradas` (`IDENT`, `IDUSUARIO`, `IDCATEGORIA`, `TITULO`, `IMAGEN`, `DESCRIPCION`, `FECHA`) VALUES
	(1, 1, 1, 'Titulo', 'Fondo3.png', 'Esta es la descripcion', '2023-12-13 11:30:23'),
	(16, 2, 2, 'Nueva Entrada', 'Wallpaper.jpg', 'Hola, esto es una entrada de prueba', '2023-12-13 11:30:41'),
	(17, 2, 34, 'Nueva Entrada Modificada', 'Bloqueo.jpg', 'Prueba', '2023-12-13 14:35:06'),
	(22, 2, 2, 'Entrada de Ninja', 'Escritorio.jpg', 'Prueba de Entrada', '2023-12-11 19:22:44'),
	(23, 1, 36, 'Otra Entrada Más', 'Fondo3.png', 'Otra más', '2023-12-13 12:42:52'),
	(24, 1, 2, 'Prueba', 'Fondo3.png', 'Prueba', '2023-12-13 19:24:51'),
	(28, 1, 25, '1', 'Fondo4.png', '1212', '2023-12-13 12:09:50'),
	(33, 5, 1, 'Nueva', 'Fondo4.png', 'NuevaEntrada', '2023-12-13 13:25:14'),
	(34, 5, 30, 'Danna', 'Fondo2.png', 'Mi perro', '2023-12-11 22:49:09'),
	(35, 2, 25, 'PC nuevo', 'Perfil.jpg', 'Mi pc nuevo', '2023-12-11 22:58:46'),
	(36, 2, 36, 'Otra Entrada', 'Fondo2.png', 'Otra', '2023-12-11 23:30:56'),
	(37, 2, 33, 'Matemáticas', 'Fondo.png', '3 + 2', '2023-12-11 23:31:10'),
	(38, 5, 25, 'Nuevo PC', 'Perfil.jpg', 'Tengo un Nuevo PC y esta es la imagen de Perfil de mi Usuario', '2023-12-13 11:29:50'),
	(39, 5, 32, 'CKEDITOR', 'Escritorio.jpg', 'Edición de Entrada con CKEditor', '2023-12-13 12:42:38'),
	(40, 2, 24, 'Biologia', 'Fondo2.png', 'Bio', '2023-12-13 12:44:47');
/*!40000 ALTER TABLE `entradas` ENABLE KEYS */;

-- Volcando estructura para tabla bdblog.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `IDUSER` int(5) NOT NULL AUTO_INCREMENT,
  `NICK` varchar(40) NOT NULL,
  `NOMBRE` varchar(40) NOT NULL,
  `APELLIDOS` varchar(40) NOT NULL,
  `EMAIL` varchar(40) NOT NULL,
  `CONTRASENIA` varchar(40) NOT NULL,
  `AVATAR` varchar(50) NOT NULL,
  `ROL` varchar(40) NOT NULL,
  PRIMARY KEY (`IDUSER`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla bdblog.usuarios: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`IDUSER`, `NICK`, `NOMBRE`, `APELLIDOS`, `EMAIL`, `CONTRASENIA`, `AVATAR`, `ROL`) VALUES
	(1, 'malodo', 'Maria', 'Lopez Dominguez', 'maria@gmail.com', 'maria1234', 'Perfil.jpg', 'admin'),
	(2, 'ninja', 'Antonio', 'Gonzalez', 'antonio@gmail.com', '12345', 'Perfil.jpg', 'user'),
	(5, 'pepe', 'Pepe', 'López', 'pepe@gmail.com', 'pepe1234', 'Wallpaper.jpg', 'admin'),
	(15, 'franmark', 'Fran', 'Marquez', 'fmarz@gmail.com', 'sdf', 'Fondo2.png', 'user'),
	(16, 'juanito', 'Juan', 'Pérez', 'juaniiito@gmail.com', 'juan1234', 'Fondo4.png', 'user'),
	(17, 'admin', 'Administrador', 'Admin', 'admin@gmail.com', 'admin1234', 'Wallpaper.jpg', 'admin'),
	(18, 'jc12', 'jotac', '12', 'jc12@gmail.com', 'jc121234', 'Fondo4.png', 'user');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
