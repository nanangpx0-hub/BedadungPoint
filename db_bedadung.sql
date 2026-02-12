/*
 Navicat Premium Dump SQL

 Source Server         : Laragon
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : db_bedadung

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 12/02/2026 16:09:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for points
-- ----------------------------
DROP TABLE IF EXISTS `points`;
CREATE TABLE `points`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` decimal(10, 7) NOT NULL,
  `lng` decimal(10, 7) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_points_waktu`(`waktu` ASC) USING BTREE,
  INDEX `idx_points_nama`(`nama` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of points
-- ----------------------------
INSERT INTO `points` VALUES (5, 'BPS Jember', -8.1551073, 113.6945830, '2026-02-12 15:41:16');
INSERT INTO `points` VALUES (6, 'Kantor', -8.1550789, 113.6946080, '2026-02-12 15:51:15');

SET FOREIGN_KEY_CHECKS = 1;
