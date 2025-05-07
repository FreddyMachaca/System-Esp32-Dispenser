/*
 Navicat Premium Data Transfer

 Source Server         : xampp
 Source Server Type    : MySQL
 Source Server Version : 100428
 Source Host           : localhost:3306
 Source Schema         : proyecto_sco

 Target Server Type    : MySQL
 Target Server Version : 100428
 File Encoding         : 65001

 Date: 05/05/2025 23:02:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for detalle_transacciones
-- ----------------------------
DROP TABLE IF EXISTS `detalle_transacciones`;
CREATE TABLE `detalle_transacciones`  (
  `cod_detalle` int NOT NULL AUTO_INCREMENT,
  `cod_transaccion` int NULL DEFAULT NULL,
  `cod_producto_maquina` int NULL DEFAULT NULL,
  `cantidad` int NULL DEFAULT NULL,
  `precio_unitario` decimal(10, 2) NULL DEFAULT NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_detalle`) USING BTREE,
  INDEX `cod_transaccion`(`cod_transaccion` ASC) USING BTREE,
  INDEX `cod_producto_maquina`(`cod_producto_maquina` ASC) USING BTREE,
  INDEX `cod_estado`(`cod_estado` ASC) USING BTREE,
  CONSTRAINT `detalle_transacciones_ibfk_1` FOREIGN KEY (`cod_transaccion`) REFERENCES `transacciones` (`cod_transaccion`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `detalle_transacciones_ibfk_2` FOREIGN KEY (`cod_producto_maquina`) REFERENCES `productos_maquina` (`cod_producto_maquina`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of detalle_transacciones
-- ----------------------------

-- ----------------------------
-- Table structure for estado_producto_maquina
-- ----------------------------
DROP TABLE IF EXISTS `estado_producto_maquina`;
CREATE TABLE `estado_producto_maquina`  (
  `cod_estado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `color` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`cod_estado`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of estado_producto_maquina
-- ----------------------------

-- ----------------------------
-- Table structure for estado_saldo
-- ----------------------------
DROP TABLE IF EXISTS `estado_saldo`;
CREATE TABLE `estado_saldo`  (
  `cod_estado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `color` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`cod_estado`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of estado_saldo
-- ----------------------------
INSERT INTO `estado_saldo` VALUES (1, 'Sin deuda', 'success');
INSERT INTO `estado_saldo` VALUES (2, 'Con deuda', 'danger');
INSERT INTO `estado_saldo` VALUES (3, 'Sin saldo', 'warning');

-- ----------------------------
-- Table structure for estado_tarjeta
-- ----------------------------
DROP TABLE IF EXISTS `estado_tarjeta`;
CREATE TABLE `estado_tarjeta`  (
  `cod_estado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `color` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`cod_estado`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of estado_tarjeta
-- ----------------------------
INSERT INTO `estado_tarjeta` VALUES (1, 'Activo', 'success');
INSERT INTO `estado_tarjeta` VALUES (2, 'Inactivo', 'danger');

-- ----------------------------
-- Table structure for estados
-- ----------------------------
DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados`  (
  `cod_estado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `color` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_estado`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of estados
-- ----------------------------
INSERT INTO `estados` VALUES (1, 'Activo', 'Elemento habilitado para su uso', 'success', '2025-05-05 00:20:34', '2025-05-05 00:20:34');
INSERT INTO `estados` VALUES (2, 'Inactivo', 'Elemento deshabilitado', 'danger', '2025-05-05 00:20:34', '2025-05-05 00:20:34');

-- ----------------------------
-- Table structure for estudiantes
-- ----------------------------
DROP TABLE IF EXISTS `estudiantes`;
CREATE TABLE `estudiantes`  (
  `cod_estudiante` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `paterno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `materno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ci` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `celular` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `correo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fecha_nacimiento` date NULL DEFAULT NULL,
  `cod_genero` int NULL DEFAULT NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_estudiante`) USING BTREE,
  UNIQUE INDEX `correo`(`correo` ASC) USING BTREE,
  INDEX `cod_genero`(`cod_genero` ASC) USING BTREE,
  INDEX `cod_estado`(`cod_estado` ASC) USING BTREE,
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`cod_genero`) REFERENCES `genero` (`cod_genero`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`cod_estado`) REFERENCES `estados` (`cod_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of estudiantes
-- ----------------------------
INSERT INTO `estudiantes` VALUES (1, 'Juan', 'Perez', 'Mamani', '12345678', '71234567', 'juan.perez@example.com', '2002-04-15', 1, 1, '2025-05-05 00:22:41', '2025-05-05 00:22:41');
INSERT INTO `estudiantes` VALUES (2, 'Lucia', 'Gomez', 'Torres', '87654321', '78945612', 'lucia.gomez@example.com', '2001-09-22', 2, 1, '2025-05-05 00:22:41', '2025-05-05 00:22:41');
INSERT INTO `estudiantes` VALUES (3, 'Alex', 'Lopez', 'Rojas', '11223344', '70011223', 'alex.lopez@example.com', '2003-01-10', 3, 1, '2025-05-05 00:22:41', '2025-05-05 00:22:41');

-- ----------------------------
-- Table structure for genero
-- ----------------------------
DROP TABLE IF EXISTS `genero`;
CREATE TABLE `genero`  (
  `cod_genero` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_genero`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of genero
-- ----------------------------
INSERT INTO `genero` VALUES (1, 'Masculino', 1, '2025-05-05 00:22:05', '2025-05-05 00:22:05');
INSERT INTO `genero` VALUES (2, 'Femenino', 1, '2025-05-05 00:22:05', '2025-05-05 00:22:05');
INSERT INTO `genero` VALUES (3, 'Otro', 1, '2025-05-05 00:22:05', '2025-05-05 00:22:05');

-- ----------------------------
-- Table structure for historial_precios
-- ----------------------------
DROP TABLE IF EXISTS `historial_precios`;
CREATE TABLE `historial_precios`  (
  `cod_historial` int NOT NULL AUTO_INCREMENT,
  `cod_producto` int NOT NULL,
  `precio` decimal(10, 2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NULL DEFAULT NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT current_timestamp,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_historial`) USING BTREE,
  INDEX `cod_producto`(`cod_producto` ASC) USING BTREE,
  INDEX `cod_estado`(`cod_estado` ASC) USING BTREE,
  CONSTRAINT `historial_precios_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of historial_precios
-- ----------------------------

-- ----------------------------
-- Table structure for maquinas
-- ----------------------------
DROP TABLE IF EXISTS `maquinas`;
CREATE TABLE `maquinas`  (
  `cod_maquina` int NOT NULL AUTO_INCREMENT,
  `ubicacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_maquina`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of maquinas
-- ----------------------------

-- ----------------------------
-- Table structure for productos
-- ----------------------------
DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos`  (
  `cod_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `precio` decimal(10, 2) NULL DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_producto`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of productos
-- ----------------------------

-- ----------------------------
-- Table structure for productos_maquina
-- ----------------------------
DROP TABLE IF EXISTS `productos_maquina`;
CREATE TABLE `productos_maquina`  (
  `cod_producto_maquina` int NOT NULL AUTO_INCREMENT,
  `cod_producto` int NULL DEFAULT NULL,
  `cod_maquina` int NULL DEFAULT NULL,
  `cantidad` int NULL DEFAULT NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_producto_maquina`) USING BTREE,
  INDEX `cod_producto`(`cod_producto` ASC) USING BTREE,
  INDEX `cod_maquina`(`cod_maquina` ASC) USING BTREE,
  INDEX `cod_estado`(`cod_estado` ASC) USING BTREE,
  CONSTRAINT `productos_maquina_ibfk_1` FOREIGN KEY (`cod_producto`) REFERENCES `productos` (`cod_producto`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `productos_maquina_ibfk_2` FOREIGN KEY (`cod_maquina`) REFERENCES `maquinas` (`cod_maquina`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of productos_maquina
-- ----------------------------

-- ----------------------------
-- Table structure for saldos
-- ----------------------------
DROP TABLE IF EXISTS `saldos`;
CREATE TABLE `saldos`  (
  `cod_saldo` int NOT NULL AUTO_INCREMENT,
  `cod_tarjeta` int NULL DEFAULT NULL,
  `saldo_actual` decimal(10, 2) NULL DEFAULT NULL,
  `deuda` decimal(10, 2) NULL DEFAULT NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_saldo`) USING BTREE,
  INDEX `cod_tarjeta`(`cod_tarjeta` ASC) USING BTREE,
  INDEX `cod_estado`(`cod_estado` ASC) USING BTREE,
  CONSTRAINT `saldos_ibfk_1` FOREIGN KEY (`cod_tarjeta`) REFERENCES `tarjetas_rfid` (`cod_tarjeta`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of saldos
-- ----------------------------
INSERT INTO `saldos` VALUES (1, 1, 50.00, 0.00, 1, '2025-05-05 00:55:51', '2025-05-05 00:55:51');
INSERT INTO `saldos` VALUES (2, 2, 0.00, 0.00, 3, '2025-05-05 00:55:51', '2025-05-05 00:55:51');
INSERT INTO `saldos` VALUES (3, 3, 6.00, 0.00, 1, '2025-05-05 00:55:51', '2025-05-05 00:55:51');

-- ----------------------------
-- Table structure for serial_ayuda
-- ----------------------------
DROP TABLE IF EXISTS `serial_ayuda`;
CREATE TABLE `serial_ayuda`  (
  `cod_serial` int NOT NULL AUTO_INCREMENT,
  `serial` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `estado` int NULL DEFAULT NULL,
  PRIMARY KEY (`cod_serial`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of serial_ayuda
-- ----------------------------

-- ----------------------------
-- Table structure for tarjetas_rfid
-- ----------------------------
DROP TABLE IF EXISTS `tarjetas_rfid`;
CREATE TABLE `tarjetas_rfid`  (
  `cod_tarjeta` int NOT NULL AUTO_INCREMENT,
  `cod_estudiante` int NULL DEFAULT NULL,
  `codigo_rfid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_tarjeta`) USING BTREE,
  INDEX `cod_estudiante`(`cod_estudiante` ASC) USING BTREE,
  INDEX `cod_estado`(`cod_estado` ASC) USING BTREE,
  CONSTRAINT `tarjetas_rfid_ibfk_1` FOREIGN KEY (`cod_estudiante`) REFERENCES `estudiantes` (`cod_estudiante`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tarjetas_rfid
-- ----------------------------
INSERT INTO `tarjetas_rfid` VALUES (1, 1, '123456789', 1, '2025-05-05 00:46:06', '2025-05-05 00:46:06');
INSERT INTO `tarjetas_rfid` VALUES (2, 1, 'RFID123456B', 2, '2025-05-05 00:46:06', '2025-05-05 00:46:06');
INSERT INTO `tarjetas_rfid` VALUES (3, 2, 'RFID987654C', 1, '2025-05-05 00:46:06', '2025-05-05 00:46:06');

-- ----------------------------
-- Table structure for transacciones
-- ----------------------------
DROP TABLE IF EXISTS `transacciones`;
CREATE TABLE `transacciones`  (
  `cod_transaccion` int NOT NULL AUTO_INCREMENT,
  `cod_tarjeta` int NULL DEFAULT NULL,
  `fecha` datetime NULL DEFAULT NULL,
  `total` decimal(10, 2) NULL DEFAULT NULL,
  `cod_estado` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`cod_transaccion`) USING BTREE,
  INDEX `cod_tarjeta`(`cod_tarjeta` ASC) USING BTREE,
  INDEX `cod_estado`(`cod_estado` ASC) USING BTREE,
  CONSTRAINT `transacciones_ibfk_1` FOREIGN KEY (`cod_tarjeta`) REFERENCES `tarjetas_rfid` (`cod_tarjeta`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of transacciones
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
