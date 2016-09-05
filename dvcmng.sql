/*
Navicat MySQL Data Transfer

Source Server         : iMySql
Source Server Version : 50709
Source Host           : localhost:3306
Source Database       : dvcmng

Target Server Type    : MYSQL
Target Server Version : 50709
File Encoding         : 65001

Date: 2016-09-04 17:22:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for chgdev
-- ----------------------------
DROP TABLE IF EXISTS `chgdev`;
CREATE TABLE `chgdev` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opath` varchar(255) NOT NULL,
  `nid` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `info` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of chgdev
-- ----------------------------
INSERT INTO `chgdev` VALUES ('1', ',64', '188', '64', 'test reason');
INSERT INTO `chgdev` VALUES ('3', ',64,188', '189', '188', 'test reason');
INSERT INTO `chgdev` VALUES ('4', ',64,188,189', '190', '189', 'testinfo2');
INSERT INTO `chgdev` VALUES ('5', ',64,188,189,190', '191', '190', 'testoid');
INSERT INTO `chgdev` VALUES ('6', ',64,188,189,190,191', '192', '191', 'testagain');

-- ----------------------------
-- Table structure for depart
-- ----------------------------
DROP TABLE IF EXISTS `depart`;
CREATE TABLE `depart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `depart` varchar(15) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `comp` tinyint(3) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of depart
-- ----------------------------
INSERT INTO `depart` VALUES ('1', '新区竖炉', null, '1', null);
INSERT INTO `depart` VALUES ('2', '竖炉车间', '-1', '1', '1');
INSERT INTO `depart` VALUES ('3', '脱硫车间', '-1', '1', '1');
INSERT INTO `depart` VALUES ('4', '办公楼', null, '1', null);
INSERT INTO `depart` VALUES ('5', '办公室', '-4', '1', '4');
INSERT INTO `depart` VALUES ('6', '生产部', '-4', '1', '4');
INSERT INTO `depart` VALUES ('7', '运输部', '-4', '1', '4');
INSERT INTO `depart` VALUES ('8', '制氧厂', null, '1', null);
INSERT INTO `depart` VALUES ('9', '一期制氧', '-8', '1', '8');
INSERT INTO `depart` VALUES ('10', '二期制氧', '-8', '1', '8');
INSERT INTO `depart` VALUES ('11', '三期制氧', '-8', '1', '8');
INSERT INTO `depart` VALUES ('12', '四期制氧', '-8', '1', '8');
INSERT INTO `depart` VALUES ('13', '五期制氧', '-8', '1', '8');
INSERT INTO `depart` VALUES ('14', '六期制氧', '-8', '1', '8');
INSERT INTO `depart` VALUES ('15', '七期制氧', '-8', '1', '8');
INSERT INTO `depart` VALUES ('16', '3W制氧', '-8', '1', '8');
INSERT INTO `depart` VALUES ('17', '液体泵', '-8', '1', '8');
INSERT INTO `depart` VALUES ('18', '1250', null, '1', null);
INSERT INTO `depart` VALUES ('19', '办公室', '-18', '1', '18');
INSERT INTO `depart` VALUES ('20', '技术科', '-18', '1', '18');
INSERT INTO `depart` VALUES ('21', '设备科', '-18', '1', '18');
INSERT INTO `depart` VALUES ('22', '电气', '-18', '1', '18');
INSERT INTO `depart` VALUES ('23', '三铁厂', null, '1', null);
INSERT INTO `depart` VALUES ('24', '电气', '-23', '1', '23');
INSERT INTO `depart` VALUES ('25', '机械', '-23', '1', '23');
INSERT INTO `depart` VALUES ('26', '动力厂', null, '1', null);
INSERT INTO `depart` VALUES ('27', '普钢11KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('28', '中普11KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('29', '制氧35KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('30', '普阳35KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('31', '焦化35KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('32', '二铁35KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('33', '一铁35KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('34', '轧钢35KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('35', '炼钢35KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('36', '新区35KV变电站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('37', '新区50MW电厂升压站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('38', '新区25MW电厂升压站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('39', '一期焦炉50MV电厂升压站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('40', '新区35KV开关站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('41', '二期焦炉50MV电厂升压站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('42', '新区70MW电厂升压站', '-26', '1', '26');
INSERT INTO `depart` VALUES ('43', '三铁烧结', null, '1', null);
INSERT INTO `depart` VALUES ('44', '180烧结', '-43', '1', '43');
INSERT INTO `depart` VALUES ('45', '265烧结', '-43', '1', '43');
INSERT INTO `depart` VALUES ('46', '普阳炼钢', null, '1', null);
INSERT INTO `depart` VALUES ('47', '设备科', '-46', '1', '46');
INSERT INTO `depart` VALUES ('48', '机械', '-46', '1', '46');
INSERT INTO `depart` VALUES ('49', '电气', '-46', '1', '46');
INSERT INTO `depart` VALUES ('50', '天车', '-46', '1', '46');
INSERT INTO `depart` VALUES ('51', '精炼炉', '-46', '1', '46');
INSERT INTO `depart` VALUES ('52', '钢管厂', null, '1', null);
INSERT INTO `depart` VALUES ('53', '螺旋工段', '-52', '1', '52');
INSERT INTO `depart` VALUES ('54', '直缝工段', '-52', '1', '52');
INSERT INTO `depart` VALUES ('55', '设备', '-52-53', '1', '53');
INSERT INTO `depart` VALUES ('56', '电气', '-52-53', '1', '53');
INSERT INTO `depart` VALUES ('57', '设备', '-52-54', '1', '54');
INSERT INTO `depart` VALUES ('58', '电气', '-52-54', '1', '54');
INSERT INTO `depart` VALUES ('59', '25MW余热电厂', null, '1', null);
INSERT INTO `depart` VALUES ('60', '工艺组', '-59', '1', '59');
INSERT INTO `depart` VALUES ('61', '锅炉', '-59-60', '1', '60');
INSERT INTO `depart` VALUES ('62', '汽轮机', '-59-60', '1', '60');
INSERT INTO `depart` VALUES ('63', '发电机', '-59-60', '1', '60');
INSERT INTO `depart` VALUES ('64', '电仪组', '-59', '1', '59');
INSERT INTO `depart` VALUES ('65', '电气', '-59-64', '1', '64');
INSERT INTO `depart` VALUES ('66', '热控', '-59-64', '1', '64');
INSERT INTO `depart` VALUES ('67', '设备组', '-59', '1', '59');
INSERT INTO `depart` VALUES ('68', '机械', '-59-67', '1', '67');
INSERT INTO `depart` VALUES ('69', '电气', '-59-67', '1', '67');
INSERT INTO `depart` VALUES ('70', '1号50MW电厂', null, '1', null);
INSERT INTO `depart` VALUES ('71', '工艺组', '-70', '1', '70');
INSERT INTO `depart` VALUES ('75', '电仪组', '-70', '1', '70');
INSERT INTO `depart` VALUES ('76', '电气', '-70-75', '1', '75');
INSERT INTO `depart` VALUES ('77', '热控', '-70-75', '1', '75');
INSERT INTO `depart` VALUES ('78', '设备组', '-70', '1', '70');
INSERT INTO `depart` VALUES ('79', '电气', '-70-78', '1', '78');
INSERT INTO `depart` VALUES ('80', '机械', '-70-78', '1', '78');
INSERT INTO `depart` VALUES ('81', '一期白灰厂', null, '1', null);
INSERT INTO `depart` VALUES ('82', '电气', '-81', '1', '81');
INSERT INTO `depart` VALUES ('83', '仪表', '-81', '1', '81');
INSERT INTO `depart` VALUES ('84', '机械', '-81', '1', '81');
INSERT INTO `depart` VALUES ('85', '气管站', null, '1', null);
INSERT INTO `depart` VALUES ('86', '二、三期白灰厂', null, '1', null);
INSERT INTO `depart` VALUES ('87', '新区65MW电厂', null, '1', null);
INSERT INTO `depart` VALUES ('88', '工艺组', '-87', '1', '87');
INSERT INTO `depart` VALUES ('89', '电仪组', '-87', '1', '87');
INSERT INTO `depart` VALUES ('90', '设备组', '-87', '1', '87');
INSERT INTO `depart` VALUES ('91', '二期中板', null, '2', null);
INSERT INTO `depart` VALUES ('92', '机械', '-91', '2', '91');
INSERT INTO `depart` VALUES ('93', '电器', '-91', '2', '91');
INSERT INTO `depart` VALUES ('94', '一期中板', null, '2', null);
INSERT INTO `depart` VALUES ('95', '机械', '-94', '2', '94');
INSERT INTO `depart` VALUES ('96', '电器', '-94', '2', '94');
INSERT INTO `depart` VALUES ('97', '中板办公室', null, '2', null);
INSERT INTO `depart` VALUES ('98', '常化炉', null, '2', null);
INSERT INTO `depart` VALUES ('99', '机械', '-98', '2', '98');
INSERT INTO `depart` VALUES ('100', '电器', '-98', '2', '98');
INSERT INTO `depart` VALUES ('101', '中普炼钢', null, '2', null);
INSERT INTO `depart` VALUES ('102', '一号转炉', '-101', '2', '101');
INSERT INTO `depart` VALUES ('103', '新建连铸', '-101', '2', '101');
INSERT INTO `depart` VALUES ('104', '小板坯', '-101', '2', '101');
INSERT INTO `depart` VALUES ('105', '二号转炉', '-101', '2', '101');
INSERT INTO `depart` VALUES ('106', '三号转炉', '-101', '2', '101');
INSERT INTO `depart` VALUES ('107', '三号连铸', '-101', '2', '101');
INSERT INTO `depart` VALUES ('108', '四号连铸', '-101', '2', '101');
INSERT INTO `depart` VALUES ('109', '四号转炉', '-101', '2', '101');
INSERT INTO `depart` VALUES ('110', '新建精炼炉', '-101', '2', '101');
INSERT INTO `depart` VALUES ('111', '80T转炉一次除尘', '-101', '2', '101');
INSERT INTO `depart` VALUES ('112', '中普炼钢天车', '-101', '2', '101');
INSERT INTO `depart` VALUES ('113', '一铁厂', null, '2', null);
INSERT INTO `depart` VALUES ('114', '电气', '-113', '2', '113');
INSERT INTO `depart` VALUES ('115', '机械', '-113', '2', '113');
INSERT INTO `depart` VALUES ('116', '中宽带', null, '2', null);
INSERT INTO `depart` VALUES ('117', '电仪工段', '-116', '2', '116');
INSERT INTO `depart` VALUES ('118', '维修工段', '-116', '2', '116');
INSERT INTO `depart` VALUES ('119', '天车工段', '-116', '2', '116');
INSERT INTO `depart` VALUES ('120', '3MW余热电厂', null, '2', null);
INSERT INTO `depart` VALUES ('121', '工艺组', '-120', '2', '120');
INSERT INTO `depart` VALUES ('122', '电仪组', '-120', '2', '120');
INSERT INTO `depart` VALUES ('123', '设备组', '-120', '2', '120');
INSERT INTO `depart` VALUES ('124', '8MW余热电厂', null, '2', null);
INSERT INTO `depart` VALUES ('125', '工艺组', '-124', '2', '124');
INSERT INTO `depart` VALUES ('126', '电仪组', '-124', '2', '124');
INSERT INTO `depart` VALUES ('127', '设备组', '-124', '2', '124');
INSERT INTO `depart` VALUES ('128', '12MW余热电厂', null, '2', null);
INSERT INTO `depart` VALUES ('129', '工艺组', '-128', '2', '128');
INSERT INTO `depart` VALUES ('130', '电仪组', '-128', '2', '128');
INSERT INTO `depart` VALUES ('131', '设备组', '-128', '2', '128');
INSERT INTO `depart` VALUES ('132', '15MW电厂', null, '2', null);
INSERT INTO `depart` VALUES ('133', '工艺组', '-132', '2', '132');
INSERT INTO `depart` VALUES ('134', '电仪组', '-132', '2', '132');
INSERT INTO `depart` VALUES ('135', '设备组', '-132', '2', '132');
INSERT INTO `depart` VALUES ('136', '3号50MW电厂', null, '2', null);
INSERT INTO `depart` VALUES ('137', '工艺组', '-136', '2', '136');
INSERT INTO `depart` VALUES ('138', '电仪组', '-136', '2', '136');
INSERT INTO `depart` VALUES ('139', '设备组', '-136', '2', '136');
INSERT INTO `depart` VALUES ('140', '二铁烧结', null, '2', null);
INSERT INTO `depart` VALUES ('141', '脱硫', '-140', '2', '140');
INSERT INTO `depart` VALUES ('142', '仪表', '-140-141', '2', '141');
INSERT INTO `depart` VALUES ('143', '机械', '-140-141', '2', '141');
INSERT INTO `depart` VALUES ('144', '除尘', '-140', '2', '140');
INSERT INTO `depart` VALUES ('145', '280静电', '-140-144', '2', '144');
INSERT INTO `depart` VALUES ('146', '200静电', '-140-144', '2', '144');
INSERT INTO `depart` VALUES ('147', '筛分除尘', '-140-144', '2', '144');
INSERT INTO `depart` VALUES ('148', '6000布袋除尘', '-140-144', '2', '144');
INSERT INTO `depart` VALUES ('149', '烧结', '-140', '2', '140');
INSERT INTO `depart` VALUES ('150', '电气', '-140-149', '2', '149');
INSERT INTO `depart` VALUES ('151', '仪表', '-140-149', '2', '149');
INSERT INTO `depart` VALUES ('152', '机械', '-140-149', '2', '149');
INSERT INTO `depart` VALUES ('153', '天车', '-140', '2', '140');
INSERT INTO `depart` VALUES ('154', '主厂房50T', '-140-153', '2', '153');
INSERT INTO `depart` VALUES ('155', '风机房32T天车', '-140-153', '2', '153');
INSERT INTO `depart` VALUES ('156', '主厂房16T', '-140-153', '2', '153');
INSERT INTO `depart` VALUES ('157', '筛分天车', '-140-153', '2', '153');
INSERT INTO `depart` VALUES ('158', '变频器', '-140', '2', '140');
INSERT INTO `depart` VALUES ('159', '一铁烧结', null, '2', null);
INSERT INTO `depart` VALUES ('160', '烧结设备', '-159', '2', '159');
INSERT INTO `depart` VALUES ('161', '电气仪表', '-159-160', '2', '160');
INSERT INTO `depart` VALUES ('162', '机械', '-159-160', '2', '160');
INSERT INTO `depart` VALUES ('163', '天车', '-159', '2', '159');
INSERT INTO `depart` VALUES ('164', '主机天车', '-159-163', '2', '163');
INSERT INTO `depart` VALUES ('165', '风机房天车', '-159-163', '2', '163');
INSERT INTO `depart` VALUES ('166', '筛粉天车', '-159-163', '2', '163');
INSERT INTO `depart` VALUES ('167', '变频器', '-159', '2', '159');
INSERT INTO `depart` VALUES ('168', '脱硫设备', '-159', '2', '159');
INSERT INTO `depart` VALUES ('169', '二铁厂', null, '2', null);
INSERT INTO `depart` VALUES ('170', '机械', '-169', '2', '169');
INSERT INTO `depart` VALUES ('171', '电器', '-169', '2', '169');
INSERT INTO `depart` VALUES ('172', '热风炉', '-169', '2', '169');
INSERT INTO `depart` VALUES ('173', '高炉本体', '-169', '2', '169');
INSERT INTO `depart` VALUES ('174', '粒化器', '-169', '2', '169');
INSERT INTO `depart` VALUES ('175', '二铁265烧结余热电厂', null, '2', null);
INSERT INTO `depart` VALUES ('176', '工艺组', '-175', '2', '175');
INSERT INTO `depart` VALUES ('177', '电仪组', '-175', '2', '175');
INSERT INTO `depart` VALUES ('178', '设备组', '-175', '2', '175');
INSERT INTO `depart` VALUES ('179', '四期白灰', null, '2', null);
INSERT INTO `depart` VALUES ('180', '洗煤厂', '-179', '2', '179');
INSERT INTO `depart` VALUES ('181', '2号50MW', null, '3', null);
INSERT INTO `depart` VALUES ('182', '工艺组', '-181', '3', '181');
INSERT INTO `depart` VALUES ('183', '电仪组', '-181', '3', '181');
INSERT INTO `depart` VALUES ('184', '设备组', '-181', '3', '181');
INSERT INTO `depart` VALUES ('185', '焦化厂', null, '3', null);
INSERT INTO `depart` VALUES ('186', '电气仪表', '-185', '3', '185');
INSERT INTO `depart` VALUES ('187', '机械设备', '-185', '3', '185');
INSERT INTO `depart` VALUES ('188', '工艺', '-185', '3', '185');
INSERT INTO `depart` VALUES ('189', '水泵资料', '-185', '3', '185');

-- ----------------------------
-- Table structure for devdetail
-- ----------------------------
DROP TABLE IF EXISTS `devdetail`;
CREATE TABLE `devdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devid` int(11) NOT NULL,
  `paraid` int(11) NOT NULL,
  `paraval` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `paraid` (`paraid`),
  CONSTRAINT `devdetail_ibfk_1` FOREIGN KEY (`paraid`) REFERENCES `devpara` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of devdetail
-- ----------------------------
INSERT INTO `devdetail` VALUES ('18', '46', '32', '660V');
INSERT INTO `devdetail` VALUES ('19', '46', '33', '150A');
INSERT INTO `devdetail` VALUES ('20', '50', '35', '450V');
INSERT INTO `devdetail` VALUES ('21', '51', '35', '0-300V');
INSERT INTO `devdetail` VALUES ('22', '53', '35', '0-30V');
INSERT INTO `devdetail` VALUES ('23', '64', '36', '0-200A');
INSERT INTO `devdetail` VALUES ('27', '72', '41', '1.2A');
INSERT INTO `devdetail` VALUES ('28', '72', '42', '380V');
INSERT INTO `devdetail` VALUES ('29', '72', '43', '230.9A');
INSERT INTO `devdetail` VALUES ('30', '72', '44', '20V');
INSERT INTO `devdetail` VALUES ('31', '72', '45', 'B');
INSERT INTO `devdetail` VALUES ('32', '72', '46', 'Y/Y');
INSERT INTO `devdetail` VALUES ('35', '74', '38', 'DCAC24V');
INSERT INTO `devdetail` VALUES ('36', '74', '39', '黄色');
INSERT INTO `devdetail` VALUES ('37', '75', '38', 'AC220V');
INSERT INTO `devdetail` VALUES ('38', '75', '39', '绿色');
INSERT INTO `devdetail` VALUES ('40', '77', '47', 'C4/2P');
INSERT INTO `devdetail` VALUES ('41', '78', '47', 'C1/2P');
INSERT INTO `devdetail` VALUES ('42', '79', '47', 'C10/2P');
INSERT INTO `devdetail` VALUES ('46', '83', '49', '63V');
INSERT INTO `devdetail` VALUES ('47', '84', '50', '8A24H');
INSERT INTO `devdetail` VALUES ('49', '87', '35', '0-30V');
INSERT INTO `devdetail` VALUES ('50', '88', '35', '0-负30V');
INSERT INTO `devdetail` VALUES ('51', '89', '36', '0-25mA');
INSERT INTO `devdetail` VALUES ('52', '90', '38', 'AC220V');
INSERT INTO `devdetail` VALUES ('53', '90', '39', '绿色');
INSERT INTO `devdetail` VALUES ('54', '91', '51', '15A');
INSERT INTO `devdetail` VALUES ('55', '91', '52', '3P');
INSERT INTO `devdetail` VALUES ('56', '92', '47', 'C4/2P');
INSERT INTO `devdetail` VALUES ('58', '94', '47', 'C1/2P');
INSERT INTO `devdetail` VALUES ('59', '95', '47', 'C10/2P');
INSERT INTO `devdetail` VALUES ('60', '96', '47', 'C20/2P');
INSERT INTO `devdetail` VALUES ('61', '97', '47', 'D6/3P');
INSERT INTO `devdetail` VALUES ('62', '98', '54', '24VDC');
INSERT INTO `devdetail` VALUES ('63', '99', '55', '±3A');
INSERT INTO `devdetail` VALUES ('64', '99', '56', 'DC±15V');
INSERT INTO `devdetail` VALUES ('66', '100', '56', '120/240VAC 125VDC');
INSERT INTO `devdetail` VALUES ('67', '100', '57', '100W');
INSERT INTO `devdetail` VALUES ('68', '101', '58', '300MHZ');
INSERT INTO `devdetail` VALUES ('69', '102', '59', '24VDC');
INSERT INTO `devdetail` VALUES ('71', '103', '59', '24/48VDC');
INSERT INTO `devdetail` VALUES ('72', '103', '60', '0.5A');
INSERT INTO `devdetail` VALUES ('73', '104', '61', 'VME');
INSERT INTO `devdetail` VALUES ('74', '111', '35', '0-30V');
INSERT INTO `devdetail` VALUES ('76', '113', '35', '0-负30V');
INSERT INTO `devdetail` VALUES ('77', '114', '36', '0-25mA');
INSERT INTO `devdetail` VALUES ('78', '115', '38', 'AC220V');
INSERT INTO `devdetail` VALUES ('79', '115', '39', '绿色');
INSERT INTO `devdetail` VALUES ('80', '116', '51', '15A');
INSERT INTO `devdetail` VALUES ('81', '116', '52', '3P');
INSERT INTO `devdetail` VALUES ('83', '118', '47', 'C4/2P');
INSERT INTO `devdetail` VALUES ('84', '119', '47', 'C1/2P');
INSERT INTO `devdetail` VALUES ('85', '120', '47', 'C2/2P');
INSERT INTO `devdetail` VALUES ('86', '121', '47', 'C1/3P');
INSERT INTO `devdetail` VALUES ('87', '122', '47', 'C10/2P');
INSERT INTO `devdetail` VALUES ('88', '123', '54', '24VDC');
INSERT INTO `devdetail` VALUES ('89', '124', '55', '10A');
INSERT INTO `devdetail` VALUES ('90', '124', '56', 'DC24V');
INSERT INTO `devdetail` VALUES ('92', '125', '55', '±3A');
INSERT INTO `devdetail` VALUES ('93', '125', '56', 'DC±15V');
INSERT INTO `devdetail` VALUES ('95', '131', '61', 'VME');
INSERT INTO `devdetail` VALUES ('96', '136', '62', '7000KW');
INSERT INTO `devdetail` VALUES ('97', '136', '63', '2458A');
INSERT INTO `devdetail` VALUES ('98', '136', '64', '1650');
INSERT INTO `devdetail` VALUES ('99', '136', '65', '244.9');
INSERT INTO `devdetail` VALUES ('100', '136', '66', '523.6');
INSERT INTO `devdetail` VALUES ('101', '136', '67', '1.00');
INSERT INTO `devdetail` VALUES ('102', '136', '68', '16P');
INSERT INTO `devdetail` VALUES ('103', '136', '69', 'S9');
INSERT INTO `devdetail` VALUES ('104', '136', '70', '6.67-16');
INSERT INTO `devdetail` VALUES ('105', '136', '71', '50-120 ');
INSERT INTO `devdetail` VALUES ('106', '136', '72', 'IP44');
INSERT INTO `devdetail` VALUES ('107', '136', '73', 'F');
INSERT INTO `devdetail` VALUES ('108', '136', '74', '40℃');
INSERT INTO `devdetail` VALUES ('109', '136', '75', '85K');
INSERT INTO `devdetail` VALUES ('110', '136', '76', '90K');
INSERT INTO `devdetail` VALUES ('111', '136', '77', 'IC9A7W7');
INSERT INTO `devdetail` VALUES ('112', '137', '62', '8500KW');
INSERT INTO `devdetail` VALUES ('113', '137', '63', '3079A');
INSERT INTO `devdetail` VALUES ('114', '137', '64', '1650');
INSERT INTO `devdetail` VALUES ('115', '137', '65', '184.1');
INSERT INTO `devdetail` VALUES ('116', '137', '66', '795.6');
INSERT INTO `devdetail` VALUES ('117', '137', '67', '1.00');
INSERT INTO `devdetail` VALUES ('118', '137', '68', '16P');
INSERT INTO `devdetail` VALUES ('119', '137', '69', 'S9');
INSERT INTO `devdetail` VALUES ('120', '137', '70', '6.6667-16');
INSERT INTO `devdetail` VALUES ('121', '137', '71', '50-120');
INSERT INTO `devdetail` VALUES ('122', '137', '72', 'IP44');
INSERT INTO `devdetail` VALUES ('123', '137', '73', 'F');
INSERT INTO `devdetail` VALUES ('124', '137', '74', '40℃');
INSERT INTO `devdetail` VALUES ('125', '137', '75', '85K');
INSERT INTO `devdetail` VALUES ('126', '137', '76', '90K');
INSERT INTO `devdetail` VALUES ('127', '137', '77', 'IC8A6W7');
INSERT INTO `devdetail` VALUES ('128', '139', '36', '0-600A');
INSERT INTO `devdetail` VALUES ('129', '140', '38', 'AC220V');
INSERT INTO `devdetail` VALUES ('130', '140', '39', '红');
INSERT INTO `devdetail` VALUES ('131', '141', '38', 'AC220V');
INSERT INTO `devdetail` VALUES ('132', '141', '39', '绿');
INSERT INTO `devdetail` VALUES ('133', '142', '51', 'In630A');
INSERT INTO `devdetail` VALUES ('134', '145', '36', '0-600A');
INSERT INTO `devdetail` VALUES ('135', '146', '38', 'AC220V');
INSERT INTO `devdetail` VALUES ('136', '146', '39', '红');
INSERT INTO `devdetail` VALUES ('137', '147', '38', 'AC220V');
INSERT INTO `devdetail` VALUES ('138', '147', '39', '绿');
INSERT INTO `devdetail` VALUES ('139', '148', '51', 'In630A');
INSERT INTO `devdetail` VALUES ('140', '188', '36', '0-200A');
INSERT INTO `devdetail` VALUES ('141', '189', '36', '0-200A');
INSERT INTO `devdetail` VALUES ('142', '190', '36', '0-200A');
INSERT INTO `devdetail` VALUES ('143', '191', '36', '0-200A');
INSERT INTO `devdetail` VALUES ('144', '192', '36', '0-200A');

-- ----------------------------
-- Table structure for device
-- ----------------------------
DROP TABLE IF EXISTS `device`;
CREATE TABLE `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `no` varchar(20) DEFAULT NULL,
  `class` varchar(10) NOT NULL,
  `factory` varchar(10) NOT NULL,
  `depart` varchar(10) NOT NULL,
  `state` varchar(10) NOT NULL,
  `dateManu` varchar(10) DEFAULT NULL,
  `dateInstall` varchar(10) DEFAULT NULL,
  `periodVali` varchar(10) DEFAULT NULL,
  `dateEnd` varchar(10) DEFAULT NULL,
  `number` int(5) DEFAULT NULL,
  `brand` varchar(20) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `price` varchar(10) DEFAULT NULL,
  `supplier` varchar(20) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `dvdinfo` tinytext,
  `divide` text,
  `tgther` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of device
-- ----------------------------
INSERT INTO `device` VALUES ('45', '加热炉电源柜', 'PWR201', '', '机柜', '1', '2', '正常', '', '2016-05-30', '', null, '1', '', '0', '', '', null, null, null, null);
INSERT INTO `device` VALUES ('46', '熔断器', '', '105RSM', '熔断器', '1', '2', '正常', '', '2016-05-30', '', '', '1', '', '45', '', '', '-45', null, null, null);
INSERT INTO `device` VALUES ('50', '电压表', '', 'CP-96', '电压表', '1', '2', '正常', '', '2016-05-30', '', null, '1', '', '68', '', '上海康比利仪表有限公司', '-45-68', null, null, null);
INSERT INTO `device` VALUES ('51', '电压表', '', 'CP-96', '电压表', '1', '2', '正常', '', '2016-05-30', '', null, '3', '', '68', '', '上海康比利仪表有限公司', '-45-68', null, null, null);
INSERT INTO `device` VALUES ('53', '电压表', '', 'CP-96', '电压表', '1', '2', '正常', '', '2016-05-30', '', null, '1', '', '68', '', '上海康比利仪表有限公司', '-45-68', null, null, null);
INSERT INTO `device` VALUES ('64', '电流表', '', 'CP-96', '电流表', '1', '2', '更换', '', '2016-05-30', '', '2016-06-29', '1', '', '45', '5000', '上海康比利仪表有限公司', '-45', null, null, null);
INSERT INTO `device` VALUES ('68', '电压表', '', 'CP-96', '电压表', '1', '2', '正常', '', '2016-05-30', '', null, '1', '', '45', '', '', '-45', null, null, null);
INSERT INTO `device` VALUES ('69', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-05-30', '', null, '1', '', '45', '', '梅兰日兰', '-45', null, null, null);
INSERT INTO `device` VALUES ('72', '三相干式（伺服）变压器', '', 'DSG-8KVA', '变压器', '1', '2', '正常', '', '2016-05-31', '', null, '1', '', '45', '', '北京奥恒达电气设备有限公司', '-45', null, null, null);
INSERT INTO `device` VALUES ('73', '指示灯', '', 'AD16-22D/-S', '指示灯', '1', '2', '正常', '', '2016-05-31', '', null, '2', '', '45', '', '上海二工电气', '-45', null, null, null);
INSERT INTO `device` VALUES ('74', '指示灯', '', 'AD16-22D/-S', '指示灯', '1', '2', '正常', '', '2016-05-31', '', null, '1', '', '73', '', '上海二工电气', '-45-73', null, null, null);
INSERT INTO `device` VALUES ('75', '指示灯', '', 'AD16-22D/-S', '指示灯', '1', '2', '正常', '', '2016-05-31', '', null, '1', '', '73', '', '上海二工电气', '-45-73', null, null, null);
INSERT INTO `device` VALUES ('76', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-05-31', '', null, '13', '', '69', '', '梅兰日兰', '-45-69', null, null, null);
INSERT INTO `device` VALUES ('77', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '2016-04-25', '2016-05-31', '', null, '13', '', '69', '', '梅兰日兰', '-45-69', null, null, null);
INSERT INTO `device` VALUES ('78', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-05-31', '', null, '1', '', '69', '', '', '-45-69', null, null, null);
INSERT INTO `device` VALUES ('79', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-05-31', '', '', '4', '', '69', '', '梅兰日兰', '-45-69', null, null, null);
INSERT INTO `device` VALUES ('80', '塑料外壳式断路器', '', '', '塑料外壳式断路器', '1', '2', '正常', '', '2016-05-31', '', null, '2', 'ABS', '45', '', 'LS', '-45', null, null, null);
INSERT INTO `device` VALUES ('81', '塑料外壳式断路器', '', '103b', '塑料外壳式断路器', '1', '2', '正常', '', '2016-05-31', '', null, '1', 'ABS', '80', '', 'LS', '-45-80', null, null, null);
INSERT INTO `device` VALUES ('82', '塑料外壳式断路器', '', '33b', '塑料外壳式断路器', '1', '2', '正常', '', '2016-05-31', '', null, '1', 'ABS', '80', '', 'LS', '-45-80', null, null, null);
INSERT INTO `device` VALUES ('83', '电容', '', '22000uF', '电容', '1', '2', '正常', '', '2016-05-31', '', null, '1', '', '45', '', 'FAC', '-45', null, null, null);
INSERT INTO `device` VALUES ('84', '整流块', '', 'PD20116', '整流块', '1', '2', '正常', '', '2016-06-01', '', null, '1', '', '45', '', '', '-45', null, null, null);
INSERT INTO `device` VALUES ('85', '炉区控制器A柜', 'PLC201A', '', '机柜', '1', '2', '正常', '', '2016-06-01', '', null, '1', '', '0', '', '', null, null, null, null);
INSERT INTO `device` VALUES ('86', '直流电压表', '', 'KLY-670', '电压表', '1', '2', '正常', '', '2016-06-01', '', '', '4', '', '85', '', '上海康比利仪表有限公司', '-85', null, null, null);
INSERT INTO `device` VALUES ('87', '直流电压表', '', 'KLY-670', '电压表', '1', '2', '正常', '', '2016-06-01', '', '', '3', '', '86', '', '上海康比利仪表有限公司', '-85-86', null, null, null);
INSERT INTO `device` VALUES ('88', '直流电压表', '', 'KLY-670', '电压表', '1', '2', '正常', '', '2016-06-01', '', null, '1', '', '86', '', '上海康比利仪表有限公司', '-85-86', null, null, null);
INSERT INTO `device` VALUES ('89', '直流电流表', '', 'KLY-670', '电流表', '1', '2', '正常', '', '2016-06-01', '', null, '6', '', '86', '', '上海康比利仪表有限公司', '-85-86', null, null, null);
INSERT INTO `device` VALUES ('90', '指示灯', '', 'AD16-22D/-S', '指示灯', '1', '2', '正常', '', '2016-06-01', '', '', '1', '', '85', '', '上海二工电气', '-85', null, null, null);
INSERT INTO `device` VALUES ('91', '塑料外壳式断路器', '', '33b', '塑料外壳式断路器', '1', '2', '正常', '', '2016-06-01', '', null, '1', 'ABS', '85', '', 'LS', '-85', null, null, null);
INSERT INTO `device` VALUES ('92', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-06-02', '', '', '5', '', '93', '', '梅兰日兰', '-85-93', null, null, null);
INSERT INTO `device` VALUES ('93', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '2016-06-02', '2016-06-02', '', null, '10', '', '85', '', '梅兰日兰', '-85', null, null, null);
INSERT INTO `device` VALUES ('94', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-06-02', '', null, '2', '', '93', '', '梅兰日兰', '-85-93', null, null, null);
INSERT INTO `device` VALUES ('95', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-06-02', '', null, '1', '', '93', '', '', '-85-93', null, null, null);
INSERT INTO `device` VALUES ('96', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-06-02', '', null, '1', '', '93', '', '梅兰日兰', '-85-93', null, null, null);
INSERT INTO `device` VALUES ('97', '小型断路器', '', 'C65N', '小型短路器', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '93', '', '施耐德', '-85-93', null, null, null);
INSERT INTO `device` VALUES ('98', '小型继电器', '', 'SZR-MY2-H-N1', '小型继电器', '1', '2', '正常', '', '2016-06-02', '', null, '36', '', '85', '', 'Honeywell', '-85', null, null, null);
INSERT INTO `device` VALUES ('99', '电源', '', '4NIC-X30', '电源', '1', '2', '正常', '', '2016-06-02', '', '', '1', '', '85', '', '朝阳', '-85', null, null, null);
INSERT INTO `device` VALUES ('100', '电源模块', '', 'IC698PSA100', '电源', '1', '2', '正常', '', '2016-06-02', '', null, '1', '', '85', '', 'GE', '-85', null, null, null);
INSERT INTO `device` VALUES ('101', '处理器模块', '', 'IC698CPE010', '处理器', '1', '2', '正常', '', '2016-06-02', '', null, '1', '', '85', '', 'GE', '-85', null, null, null);
INSERT INTO `device` VALUES ('102', '数字量输入模板', '', 'IC697MDL653', '数字量输入/输出模块', '1', '2', '正常', '', '2016-06-02', '', null, '3', '', '85', '', 'GE', '-85', null, null, null);
INSERT INTO `device` VALUES ('103', '数字量输出模块', '', 'IC697MDL750 ', '数字量输入/输出模块', '1', '2', '正常', '', '2016-06-02', '', null, '1', '', '85', '', 'GE', '-85', null, null, null);
INSERT INTO `device` VALUES ('104', '底板', '', '01A201S', '底板', '1', '2', '正常', '', '2016-06-03', '', '', '2', '', '85', '', 'MEN', '-85', null, null, null);
INSERT INTO `device` VALUES ('105', '通讯模板', '', 'BK698CPA15A0', '通讯模板', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '85', '', 'MEN', '-85', null, null, null);
INSERT INTO `device` VALUES ('106', '总线发送器模块', '', 'IC697BEM713', '总线发送器', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '85', '', 'GE', '-85', null, null, null);
INSERT INTO `device` VALUES ('107', '内存映像网板', '', 'IC698CMX016', '内存映像网板', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '85', '', 'GE', '-85', null, null, null);
INSERT INTO `device` VALUES ('108', 'DP网板', '', 'BK684PBM57', '网板', '1', '2', '正常', '', '2016-06-03', '', null, '2', '', '85', '', 'GE', '-85', null, null, null);
INSERT INTO `device` VALUES ('109', '隔离器', '', 'PZD00', '隔离器', '1', '2', '正常', '', '2016-06-03', '', null, '6', '', '85', '', 'Parker', '-85', null, null, null);
INSERT INTO `device` VALUES ('110', '炉区控制器B柜', 'PLC201B', '', '44', '1', '2', '正常', '', '2016-06-03', '2020-07-22', null, '1', '', '0', '', '', null, null, null, null);
INSERT INTO `device` VALUES ('111', '直流电压表', '', 'KLY-670', '电压表', '1', '2', '正常', '', '2016-06-03', '', null, '3', '', '112', '', '上海康比利仪表有限公司', '-110-112', null, null, null);
INSERT INTO `device` VALUES ('112', '直流电压表', '', 'KLY-670', '电压表', '1', '2', '正常', '', '2016-06-03', '', null, '4', '', '110', '', '上海康比利仪表有限公司', '-110', null, null, null);
INSERT INTO `device` VALUES ('113', '直流电压表', '', 'KLY-670', '电压表', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '112', '', '上海康比利仪表有限公司', '-110-112', null, null, null);
INSERT INTO `device` VALUES ('114', '直流电流表', '', 'KLY-670', '电流表', '1', '2', '正常', '', '2016-06-03', '', null, '2', '', '110', '', '上海康比利仪表有限公司', '-110', null, null, null);
INSERT INTO `device` VALUES ('115', '指示灯', '', 'KLY-670', '指示灯', '1', '2', '正常', '', '2016-06-03', '', '', '1', '', '110', '', '上海二工电气', '-110', null, null, null);
INSERT INTO `device` VALUES ('116', '塑料外壳式断路器', '', '33b', '塑料外壳式断路器', '1', '2', '正常', '', '2016-06-03', '', null, '1', 'ABS', '110', '', 'MEN', '-110', null, null, null);
INSERT INTO `device` VALUES ('117', '小型断路器', '', 'C65N', '小型断路器', '1', '2', '正常', '', '2016-06-03', '', null, '10', '', '110', '', '梅兰日兰', '-110', null, null, null);
INSERT INTO `device` VALUES ('118', '小型断路器', '', 'C65N', '小型断路器', '1', '2', '正常', '', '2016-06-03', '', null, '5', '', '117', '', '梅兰日兰', '-110-117', null, null, null);
INSERT INTO `device` VALUES ('119', '小型断路器', '', 'C65N', '小型断路器', '1', '2', '正常', '', '2016-06-03', '', null, '2', '', '117', '', '梅兰日兰', '-110-117', null, null, null);
INSERT INTO `device` VALUES ('120', '小型断路器', '', 'C65N', '小型断路器', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '117', '', '梅兰日兰', '-110-117', null, null, null);
INSERT INTO `device` VALUES ('121', '小型断路器', '', 'C65N', '小型断路器', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '117', '', '梅兰日兰', '-110-117', null, null, null);
INSERT INTO `device` VALUES ('122', '小型断路器', '', 'C65N', '小型断路器', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '117', '', '梅兰日兰', '-110-117', null, null, null);
INSERT INTO `device` VALUES ('123', '小型继电器', '', 'SZR-MY2-H-N1', '小型继电器', '1', '2', '正常', '', '2016-06-03', '', null, '32', '', '110', '', '梅兰日兰', '-110', null, null, null);
INSERT INTO `device` VALUES ('124', '电源', '', '4NIC-X240', '电源', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '126', '', '朝阳', '-110-126', null, null, null);
INSERT INTO `device` VALUES ('125', '电源', '', '4NIC-X30', '电源', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '126', '', '朝阳', '-110-126', null, null, null);
INSERT INTO `device` VALUES ('126', '电源', '', '', '电源', '1', '2', '正常', '', '2016-06-03', '', null, '2', '', '110', '', '朝阳', '-110', null, null, null);
INSERT INTO `device` VALUES ('127', '电源模块', '', 'IC697PWR710', '电源', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '126', '', 'GE', '-110-126', null, null, null);
INSERT INTO `device` VALUES ('128', '总线接收器模块', '', 'IC697BEM711', '总线接收器', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '110', '', 'GE', '-110', null, null, null);
INSERT INTO `device` VALUES ('129', '数字量输入模块', '', 'IC697MDL653', '数字量输入/输出模块', '1', '2', '正常', '', '2016-06-03', '', null, '2', '', '110', '', 'GE', '-110', null, null, null);
INSERT INTO `device` VALUES ('130', '数字量输出模块', '', 'IC697MDL750', '数字量输入/输出模块', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '110', '', 'GE', '-110', null, null, null);
INSERT INTO `device` VALUES ('131', 'VME底板', '', 'BK698A201S12', '底板', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '110', '', 'MEN', '-110', null, null, null);
INSERT INTO `device` VALUES ('132', 'SSI输入模块', '', 'BK684SSI470', '输入/输出模块', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '110', '', 'MEN', '-110', null, null, null);
INSERT INTO `device` VALUES ('133', '模拟量输出模块', '', 'BK684AOM622', '输入/输出模块', '1', '2', '正常', '', '2016-06-03', '', null, '1', '', '110', '', 'MEN', '-110', null, null, null);
INSERT INTO `device` VALUES ('134', '隔离器', '', 'PZD00', '隔离器', '1', '2', '正常', '', '2016-06-03', '', null, '2', '', '110', '', 'Parker', '-110', null, null, null);
INSERT INTO `device` VALUES ('135', '精轧区电机', '', 'TBP8500-16/', '电机', '1', '2', '正常', '', '2016-06-03', '', '', '4', '', '0', '', '哈尔滨电机厂交直流电机有限责任公司', '-0', null, null, null);
INSERT INTO `device` VALUES ('136', '粗轧上下辊主电机', '', 'TBP7000-16/3000', '电机', '1', '2', '正常', '2010-03-01', '2016-06-03', '', null, '2', '', '135', '', '哈尔滨电机厂交直流电机有限责任公司', '-135', null, null, null);
INSERT INTO `device` VALUES ('137', '精轧上下辊主电机', '', 'TBP8500-16/3280', '电机', '1', '2', '正常', '2008-10-01', '2016-06-03', '', null, '2', '', '135', '', '哈尔滨电机厂交直流电机有限责任公司', '-135', null, null, null);
INSERT INTO `device` VALUES ('138', '1#鼓风机', '', '', '机柜', '1', '6', '正常', '', '2016-06-03', '', null, '1', '', '0', '', '', null, null, null, null);
INSERT INTO `device` VALUES ('139', '电流表', '', '42L6', '电流表', '1', '6', '正常', '', '2016-06-03', '', null, '3', '', '138', '', '北京自控厂', '-138', null, null, null);
INSERT INTO `device` VALUES ('140', '指示灯(红)', '', 'AD16-22D/-S ', '指示灯', '1', '6', '正常', '', '2016-06-03', '', null, '1', '', '138', '', '上海二工电气', '-138', null, null, null);
INSERT INTO `device` VALUES ('141', '指示灯(绿)', 'spare01', 'AD16-22D/-S', '指示灯', '1', '6', '备用', '', '2016-06-03', '2019-07-16', '', '1', 'testSpare', '138', '', '上海二工电气', '', 'test1 2016-07-22 test dvd', 'dname1 dno1 dtype1,dname2 dno2 dtype2,', 'test1,2016-07-22,test tgther,193');
INSERT INTO `device` VALUES ('142', '塑料外壳式断路器', '', 'GSM1-630L/33002', '塑料外壳式断路器', '1', '6', '正常', '', '2016-06-03', '', '', '1', '', '138', '', '天水二一三电器有限公司', '-138', null, null, null);
INSERT INTO `device` VALUES ('143', '电机软起动器', '', 'JJR2250KW', '电机软启动器', '1', '6', '正常', '', '2016-06-03', '', null, '1', '', '138', '', '上海雷诺尔', '-138', null, null, null);
INSERT INTO `device` VALUES ('144', '2#鼓风机', '', '', '机柜', '1', '6', '正常', '', '2016-06-03', '', '', '1', '', '0', '', '', '', null, null, null);
INSERT INTO `device` VALUES ('145', '电流表', '', '42L6', '电流表', '1', '6', '正常', '', '2016-06-03', '', '', '3', '', '144', '', '北京自控厂', '-144', null, null, null);
INSERT INTO `device` VALUES ('146', '指示灯(红)', '', 'AD16-22D/-S ', '指示灯', '1', '6', '正常', '', '2016-06-03', '', '', '1', '', '144', '', '上海二工电气', '-144', null, null, null);
INSERT INTO `device` VALUES ('147', '指示灯(绿)', '', 'AD16-22D/-S', '指示灯', '1', '6', '正常', '', '2016-06-03', '', '', '1', '', '144', '', '上海二工电气', '-144', null, null, null);
INSERT INTO `device` VALUES ('148', '塑料外壳式断路器', '', 'GSM1-630L/33002', '塑料外壳式断路器', '1', '6', '正常', '', '2016-06-03', '', '', '1', '', '144', '', '天水二一三电器有限公司', '-144', null, null, null);
INSERT INTO `device` VALUES ('149', '电机软起动器', '', 'JJR2250KW', '电机软启动器', '1', '6', '正常', '', '2016-06-03', '', '', '1', '', '144', '', '上海雷诺尔', '-144', null, null, null);
INSERT INTO `device` VALUES ('150', '粗轧电源柜', 'PWR301', '', '机柜', '1', '3', '正常', '', '2016-06-03', ' ', '', '1', '', '0', '', '', null, null, null, null);
INSERT INTO `device` VALUES ('151', '电压表', null, 'CP96', '电压表', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' 上海康比利仪表有限公司', '-150', null, null, null);
INSERT INTO `device` VALUES ('152', '电压表', null, 'CP96', '电压表', '1', '3', '正常', null, '2016-06-03', null, null, '3', null, '150', null, ' 上海康比利仪表有限公司', '-150', null, null, null);
INSERT INTO `device` VALUES ('153', '电压表', null, 'CP96', '电压表', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' 上海康比利仪表有限公司', '-150', null, null, null);
INSERT INTO `device` VALUES ('154', '电流表', null, 'CP96', '电流表', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' 上海康比利仪表有限公司', '-150', null, null, null);
INSERT INTO `device` VALUES ('155', '指示灯绿', null, 'AD1622DS', '指示灯绿', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' 上海二工电气', '-150', null, null, null);
INSERT INTO `device` VALUES ('156', '指示灯黄', null, 'AD1622DS', '指示灯黄', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' 苏州西门子电器有限公司', '-150', null, null, null);
INSERT INTO `device` VALUES ('157', '塑料外壳式断路器', null, 'ABS', '塑料外壳式断路器', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' LS', '-150', null, null, null);
INSERT INTO `device` VALUES ('158', '塑料外壳式断路器', null, 'ABS', '塑料外壳式断路器', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' LS', '-150', null, null, null);
INSERT INTO `device` VALUES ('159', '小型断路器', 'spare03', 'C65N', '小型断路器', '1', '2', '备用', null, '2016-06-03', null, null, '12', 'testSpare', '150', null, ' 梅兰日兰', '', null, null, null);
INSERT INTO `device` VALUES ('160', '小型断路器', 'spare02', 'C65N', '小型断路器', '1', '2', '备用', null, '2016-06-03', null, null, '5', 'testSpare', '150', null, ' 梅兰日兰', '', null, null, null);
INSERT INTO `device` VALUES ('161', '小型断路器', 'spare04', 'C65N', '小型断路器', '1', '2', '备用', null, '2016-06-03', null, null, '1', 'testSpare', '150', null, ' 梅兰日兰', '', null, null, null);
INSERT INTO `device` VALUES ('162', '小型断路器', null, 'C65N', '小型断路器', '1', '3', '正常', null, '2016-06-03', null, null, '3', null, '150', null, ' 梅兰日兰', '-150', null, null, null);
INSERT INTO `device` VALUES ('163', '小型断路器', null, 'C65N', '小型断路器', '1', '3', '正常', null, '2016-06-03', null, null, '2', null, '150', null, ' 梅兰日兰', '-150', null, null, null);
INSERT INTO `device` VALUES ('164', '熔断器', null, '105RSM150A', '熔断器', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' 陕西龙伸电器有限公司', '-150', null, null, null);
INSERT INTO `device` VALUES ('165', '电容', null, '63V', '电容', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' FAC', '-150', null, null, null);
INSERT INTO `device` VALUES ('166', '三相干式变压器', null, 'DSG8KVA', '三相干式变压器', '1', '3', '正常', null, '2016-06-03', null, null, '1', null, '150', null, ' 北京奥恒达电气设备有限公司', '-150', null, null, null);
INSERT INTO `device` VALUES ('167', '整流块', '', 'PD20116', '整流块', '1', '3', '正常', '', '2016-06-03', '', '', '3', '', '150', '', ' Parker', '-150', null, null, null);
INSERT INTO `device` VALUES ('186', '粗轧顺控控制器柜', 'PLC301', '', '机柜', '4', '5', '正常', '', '2016-06-20', '', null, '1', '', '0', '', '', null, null, null, null);
INSERT INTO `device` VALUES ('188', '电流表', '', 'CP-96', '电流表', '1', '2', '更换', '', '2016-06-29', '', '2016-07-18', '1', 'testChge1', '45', '4500', 'testChge1', '-45', null, null, null);
INSERT INTO `device` VALUES ('189', '电流表', '', 'CP-96', '电流表', '1', '2', '更换', '', '2016-07-16', '', null, '1', 'testChge2', '45', '4800', 'testChge2', '-45', null, null, null);
INSERT INTO `device` VALUES ('190', '电流表', '', 'CP-96', '电流表', '1', '2', '更换', '', '2016-07-18', '', '2016-07-18', '1', 'testinfo2', '45', '4785', 'testinfo2', '-45', null, null, null);
INSERT INTO `device` VALUES ('191', '电流表', '', 'CP-96', '电流表', '1', '2', '更换', '', '2016-07-18', '', '2016-07-18', '1', 'testoid', '45', '4300', 'testoid', '-45', null, null, null);
INSERT INTO `device` VALUES ('192', '电流表', '', 'CP-96', '电流表', '1', '2', '正常', '', '2016-07-18', '', null, '1', 'testagain', '45', '5600', 'testagain', '-45', null, null, null);
INSERT INTO `device` VALUES ('193', 'tname', 'tcode', 'tno', '小型继电器', '4', '3', '备用', null, null, null, null, null, null, null, null, null, null, null, null, 'test1,2016-07-22,test tgther,指示灯(绿)-141,tgther2,tgther3,');
INSERT INTO `device` VALUES ('194', 'testAddSpare', 'spare05', '', '小型断路器', '1', '2', '备用', '2016-07-22', null, null, '', '1', '', null, '', '', null, null, null, null);

-- ----------------------------
-- Table structure for devpara
-- ----------------------------
DROP TABLE IF EXISTS `devpara`;
CREATE TABLE `devpara` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `typeid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of devpara
-- ----------------------------
INSERT INTO `devpara` VALUES ('32', '额定电压', '46');
INSERT INTO `devpara` VALUES ('33', '额定电流', '46');
INSERT INTO `devpara` VALUES ('35', '额定电压', '48');
INSERT INTO `devpara` VALUES ('36', '额定电流', '49');
INSERT INTO `devpara` VALUES ('38', '灯电压', '50');
INSERT INTO `devpara` VALUES ('39', '颜色', '50');
INSERT INTO `devpara` VALUES ('41', '初级电流', '52');
INSERT INTO `devpara` VALUES ('42', '初级电压', '52');
INSERT INTO `devpara` VALUES ('43', '次级电压', '52');
INSERT INTO `devpara` VALUES ('44', '次级电流', '52');
INSERT INTO `devpara` VALUES ('45', '绝缘级', '52');
INSERT INTO `devpara` VALUES ('46', '接法', '52');
INSERT INTO `devpara` VALUES ('47', 'C/P', '51');
INSERT INTO `devpara` VALUES ('49', '额定电压', '55');
INSERT INTO `devpara` VALUES ('50', 'A/H', '56');
INSERT INTO `devpara` VALUES ('51', '额定电流', '53');
INSERT INTO `devpara` VALUES ('52', 'P', '53');
INSERT INTO `devpara` VALUES ('54', 'VDC', '57');
INSERT INTO `devpara` VALUES ('55', '额定电流', '58');
INSERT INTO `devpara` VALUES ('56', '额定电压', '58');
INSERT INTO `devpara` VALUES ('57', '额定功率', '58');
INSERT INTO `devpara` VALUES ('58', 'CPU', '59');
INSERT INTO `devpara` VALUES ('59', 'VDC', '60');
INSERT INTO `devpara` VALUES ('60', '额定电流', '60');
INSERT INTO `devpara` VALUES ('61', '总线', '61');
INSERT INTO `devpara` VALUES ('62', '额定功率', '67');
INSERT INTO `devpara` VALUES ('63', '额定电流', '67');
INSERT INTO `devpara` VALUES ('64', '额定电压', '67');
INSERT INTO `devpara` VALUES ('65', '励磁电流', '67');
INSERT INTO `devpara` VALUES ('66', '励磁电流', '67');
INSERT INTO `devpara` VALUES ('67', '功率因数', '67');
INSERT INTO `devpara` VALUES ('68', '极数', '67');
INSERT INTO `devpara` VALUES ('69', '工作方式', '67');
INSERT INTO `devpara` VALUES ('70', '频率Hz', '67');
INSERT INTO `devpara` VALUES ('71', '转速r/min', '67');
INSERT INTO `devpara` VALUES ('72', '防护形式', '67');
INSERT INTO `devpara` VALUES ('73', '绝缘等级', '67');
INSERT INTO `devpara` VALUES ('74', '环境参数', '67');
INSERT INTO `devpara` VALUES ('75', '温升限值定子', '67');
INSERT INTO `devpara` VALUES ('76', '温升限值转子', '67');
INSERT INTO `devpara` VALUES ('77', '冷却方式', '67');
INSERT INTO `devpara` VALUES ('78', '额定电流', '70');

-- ----------------------------
-- Table structure for devtype
-- ----------------------------
DROP TABLE IF EXISTS `devtype`;
CREATE TABLE `devtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of devtype
-- ----------------------------
INSERT INTO `devtype` VALUES ('44', '机柜', null, null);
INSERT INTO `devtype` VALUES ('45', '电器', null, null);
INSERT INTO `devtype` VALUES ('46', '熔断器', '45', '45-');
INSERT INTO `devtype` VALUES ('47', '仪表', null, null);
INSERT INTO `devtype` VALUES ('48', '电压表', '47', '47-');
INSERT INTO `devtype` VALUES ('49', '电流表', '47', '47-');
INSERT INTO `devtype` VALUES ('50', '指示灯', '45', '45-');
INSERT INTO `devtype` VALUES ('51', '小型断路器', '45', '45-');
INSERT INTO `devtype` VALUES ('52', '变压器', '45', '45-');
INSERT INTO `devtype` VALUES ('53', '塑料外壳式断路器', '45', '45-');
INSERT INTO `devtype` VALUES ('54', '电子配件', null, null);
INSERT INTO `devtype` VALUES ('55', '电容', '54', '54-');
INSERT INTO `devtype` VALUES ('56', '整流块', '54', '54-');
INSERT INTO `devtype` VALUES ('57', '小型继电器', '45', '45-');
INSERT INTO `devtype` VALUES ('58', '电源', '45', '45-');
INSERT INTO `devtype` VALUES ('59', '处理器', '45', '45-');
INSERT INTO `devtype` VALUES ('60', '输入/输出模块', '45', '45-');
INSERT INTO `devtype` VALUES ('61', '底板', '54', '54-');
INSERT INTO `devtype` VALUES ('62', '通讯模板', '54', '54-');
INSERT INTO `devtype` VALUES ('63', '总线发送器', '54', '54-');
INSERT INTO `devtype` VALUES ('64', '网板', '54', '54-');
INSERT INTO `devtype` VALUES ('65', '隔离器', '45', '45-');
INSERT INTO `devtype` VALUES ('66', '总线接收器', '45', '45-');
INSERT INTO `devtype` VALUES ('67', '电机', '68', '68-');
INSERT INTO `devtype` VALUES ('68', '电机', null, null);
INSERT INTO `devtype` VALUES ('69', '电机软启动器', '68', '68-');
INSERT INTO `devtype` VALUES ('70', '仪表a', '47', '47-');

-- ----------------------------
-- Table structure for dev_user
-- ----------------------------
DROP TABLE IF EXISTS `dev_user`;
CREATE TABLE `dev_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `devid` int(11) NOT NULL,
  `time` date NOT NULL,
  `info` varchar(4) NOT NULL DEFAULT '开始管理',
  `end` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `devid` (`devid`),
  CONSTRAINT `dev_user_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `dev_user_ibfk_2` FOREIGN KEY (`devid`) REFERENCES `device` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of dev_user
-- ----------------------------
INSERT INTO `dev_user` VALUES ('1', '2', '45', '2016-06-20', '开始管理', '2016-06-29');
INSERT INTO `dev_user` VALUES ('2', '3', '45', '2016-06-20', '开始管理', null);
INSERT INTO `dev_user` VALUES ('3', '4', '45', '2016-06-20', '开始管理', null);
INSERT INTO `dev_user` VALUES ('4', '5', '110', '2016-06-20', '开始管理', null);
INSERT INTO `dev_user` VALUES ('5', '2', '85', '2016-06-20', '开始管理', null);
INSERT INTO `dev_user` VALUES ('6', '3', '85', '2016-06-20', '开始管理', null);
INSERT INTO `dev_user` VALUES ('7', '3', '110', '2016-06-20', '开始管理', null);
INSERT INTO `dev_user` VALUES ('17', '2', '186', '2016-06-20', '开始管理', null);
INSERT INTO `dev_user` VALUES ('18', '3', '186', '2016-06-20', '开始管理', null);
INSERT INTO `dev_user` VALUES ('19', '9', '135', '2016-08-26', '开始管理', null);
INSERT INTO `dev_user` VALUES ('20', '9', '144', '2016-08-26', '开始管理', null);
INSERT INTO `dev_user` VALUES ('21', '9', '85', '2016-08-26', '开始管理', '2016-08-26');
INSERT INTO `dev_user` VALUES ('22', '9', '135', '2016-08-26', '开始管理', null);
INSERT INTO `dev_user` VALUES ('23', '9', '110', '2016-08-26', '开始管理', null);
INSERT INTO `dev_user` VALUES ('24', '9', '150', '2016-08-26', '开始管理', null);

-- ----------------------------
-- Table structure for insplist
-- ----------------------------
DROP TABLE IF EXISTS `insplist`;
CREATE TABLE `insplist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `result` varchar(10) NOT NULL,
  `liable` varchar(10) NOT NULL,
  `info` varchar(100) DEFAULT NULL,
  `devid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of insplist
-- ----------------------------
INSERT INTO `insplist` VALUES ('1', '2016-05-24 15:59:00', '正常', 'me test', '无', '45');
INSERT INTO `insplist` VALUES ('2', '2016-05-23 15:59:05', '正常', 'him', '无', '85');
INSERT INTO `insplist` VALUES ('3', '2016-05-26 16:00:23', '正常', 'me', '无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无无', '110');
INSERT INTO `insplist` VALUES ('4', '2016-05-25 16:30:00', '正常', 'me', '无', '135');
INSERT INTO `insplist` VALUES ('5', '2016-05-18 16:30:00', '正常', 'me', '无', '138');
INSERT INTO `insplist` VALUES ('6', '2016-05-18 16:30:00', '正常', 'me', '无', '45');
INSERT INTO `insplist` VALUES ('7', '2016-05-18 16:30:00', '正常', 'me', '无', '85');
INSERT INTO `insplist` VALUES ('9', '2016-05-18 16:30:00', '正常', 'me', '无', '110');
INSERT INTO `insplist` VALUES ('10', '2016-05-18 16:30:00', '正常', 'me', 'test update', '135');
INSERT INTO `insplist` VALUES ('12', '2016-05-26 16:35:00', '正常', 'test1', '无', '138');
INSERT INTO `insplist` VALUES ('14', '2016-05-27 15:30:00', '需保养', 'you', 'test123', '45');
INSERT INTO `insplist` VALUES ('15', '2016-05-27 15:30:00', '需保养', 'you', 'test123', '85');
INSERT INTO `insplist` VALUES ('16', '2016-05-27 15:30:00', '需保养', 'you', 'test123', '110');
INSERT INTO `insplist` VALUES ('17', '2016-05-28 10:40:00', '需保养', 'testflag', 'do ri mi', '135');
INSERT INTO `insplist` VALUES ('18', '2016-05-28 10:40:00', '需保养', 'testflag', 'do ri mi', '138');
INSERT INTO `insplist` VALUES ('20', '2016-06-14 10:30:00', '正常', 'test', '无', '144');
INSERT INTO `insplist` VALUES ('21', '2016-06-14 10:30:00', '正常', 'test', '无', '135');
INSERT INTO `insplist` VALUES ('22', '2016-06-14 10:30:00', '正常', 'test', '无', '138');
INSERT INTO `insplist` VALUES ('23', '2016-06-14 10:35:00', '需保养', 'test', 'test maintain', '138');
INSERT INTO `insplist` VALUES ('25', '2016-06-08 10:35:00', '需维修', 'test', 'test repair', '135');
INSERT INTO `insplist` VALUES ('26', '2016-06-08 10:35:00', '需维修', 'test', '无', '110');
INSERT INTO `insplist` VALUES ('28', '2016-06-14 11:35:00', '正常', 'test', '无', '135');
INSERT INTO `insplist` VALUES ('29', '2016-06-14 11:35:00', '需保养', 'test', 'test add err normal', '144');
INSERT INTO `insplist` VALUES ('30', '2016-06-14 11:35:00', '正常', 'test', '无', '138');
INSERT INTO `insplist` VALUES ('31', '2016-06-18 15:45:00', '正常', 'testadd', '无', '85');
INSERT INTO `insplist` VALUES ('32', '2016-06-18 15:45:00', '正常', 'testadd', '无', '135');
INSERT INTO `insplist` VALUES ('33', '2016-06-18 15:45:00', '正常', 'testadd', '无', '144');
INSERT INTO `insplist` VALUES ('34', '2016-07-17 17:20:00', '需维修', 'test2', 'test del time', '45');
INSERT INTO `insplist` VALUES ('35', '2016-07-19 15:50:00', '需维修', 'test1', 'test rep mis', '45');

-- ----------------------------
-- Table structure for inspmis
-- ----------------------------
DROP TABLE IF EXISTS `inspmis`;
CREATE TABLE `inspmis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devid` int(11) NOT NULL,
  `start` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inspmis
-- ----------------------------
INSERT INTO `inspmis` VALUES ('35', '45', '09:00:00');
INSERT INTO `inspmis` VALUES ('36', '45', '10:00:00');
INSERT INTO `inspmis` VALUES ('37', '45', '12:00:00');
INSERT INTO `inspmis` VALUES ('39', '110', '09:00:00');
INSERT INTO `inspmis` VALUES ('40', '135', '12:00:00');
INSERT INTO `inspmis` VALUES ('41', '85', '14:00:00');
INSERT INTO `inspmis` VALUES ('42', '110', '16:00:00');
INSERT INTO `inspmis` VALUES ('61', '135', '00:00:00');
INSERT INTO `inspmis` VALUES ('62', '135', '05:00:00');
INSERT INTO `inspmis` VALUES ('63', '135', '10:30:00');
INSERT INTO `inspmis` VALUES ('64', '144', '00:00:00');
INSERT INTO `inspmis` VALUES ('65', '144', '05:00:00');
INSERT INTO `inspmis` VALUES ('66', '144', '10:30:00');
INSERT INTO `inspmis` VALUES ('67', '85', '00:00:00');
INSERT INTO `inspmis` VALUES ('68', '85', '05:00:00');
INSERT INTO `inspmis` VALUES ('69', '85', '10:30:00');

-- ----------------------------
-- Table structure for inspmiscon
-- ----------------------------
DROP TABLE IF EXISTS `inspmiscon`;
CREATE TABLE `inspmiscon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `misid` int(11) NOT NULL,
  `devid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `misid` (`misid`),
  KEY `devid` (`devid`),
  CONSTRAINT `inspmiscon_ibfk_1` FOREIGN KEY (`misid`) REFERENCES `inspmis` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `inspmiscon_ibfk_2` FOREIGN KEY (`devid`) REFERENCES `device` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inspmiscon
-- ----------------------------

-- ----------------------------
-- Table structure for inspstd
-- ----------------------------
DROP TABLE IF EXISTS `inspstd`;
CREATE TABLE `inspstd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devid` int(11) NOT NULL,
  `iden` varchar(20) NOT NULL,
  `info` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inspstd
-- ----------------------------
INSERT INTO `inspstd` VALUES ('1', '45', '查看新鲜程度、腐烂程度', '主要查看水果是否有坏的，是否新鲜，还需要查看是否需要补货，腐烂的水果需要丢弃。');
INSERT INTO `inspstd` VALUES ('2', '85', '查看蔬菜是否摆放整齐', '查看蔬菜是否摆放整齐，是否需要补货');
INSERT INTO `inspstd` VALUES ('3', '45', '查看草莓是否新鲜', '草莓比较容易腐烂');

-- ----------------------------
-- Table structure for replist
-- ----------------------------
DROP TABLE IF EXISTS `replist`;
CREATE TABLE `replist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devid` int(11) NOT NULL,
  `liable` varchar(10) NOT NULL,
  `err` varchar(100) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `solve` varchar(300) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of replist
-- ----------------------------
INSERT INTO `replist` VALUES ('5', '144', 'test', 'test update', 'test addinfo', 'test addinfo', '2016-06-10 15:30:00');
INSERT INTO `replist` VALUES ('6', '138', 'me', 'test update', 'test addinfo', 'test addinfo', '2016-06-07 16:38:09');
INSERT INTO `replist` VALUES ('8', '85', 'test ', 'test addinfo in replist', 'test addinfo in replist', 'test addinfo in replist', '2016-06-11 14:40:00');
INSERT INTO `replist` VALUES ('9', '45', 'test ', 'test addinfo in replist', 'test addinfo in replist', 'test updateinfo', '2016-06-11 14:40:00');
INSERT INTO `replist` VALUES ('10', '135', 'test', 'test faildevice', 'test faildevice', 'test faildevice', '2016-06-10 15:30:00');
INSERT INTO `replist` VALUES ('11', '135', 'test1', 'test rep Add', 'test rep Add', 'test rep Add', '2016-07-20 11:12:00');

-- ----------------------------
-- Table structure for repmis
-- ----------------------------
DROP TABLE IF EXISTS `repmis`;
CREATE TABLE `repmis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devid` int(11) NOT NULL,
  `err` varchar(100) NOT NULL,
  `liable` varchar(10) NOT NULL,
  `result` tinyint(1) NOT NULL,
  `infoid` int(11) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL,
  `today` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of repmis
-- ----------------------------
INSERT INTO `repmis` VALUES ('1', '45', 'test', '1', '0', '0', '1', '0');
INSERT INTO `repmis` VALUES ('2', '85', 'test11', '2', '0', '0', '1', '0');
INSERT INTO `repmis` VALUES ('3', '138', 'test update', '3', '1', '6', '1', '1');
INSERT INTO `repmis` VALUES ('5', '144', 'test update', '4', '1', '5', '1', '1');
INSERT INTO `repmis` VALUES ('6', '135', 'test repair', '5', '0', '0', '1', '0');
INSERT INTO `repmis` VALUES ('7', '45', 'test del time', '6', '0', '0', '1', '0');
INSERT INTO `repmis` VALUES ('8', '135', 'test about time', '1', '0', '0', '1', '0');
INSERT INTO `repmis` VALUES ('9', '45', 'test rep mis', '2', '0', '0', '1', '0');
INSERT INTO `repmis` VALUES ('10', '150', 'test', '9', '0', '0', '1', '0');

-- ----------------------------
-- Table structure for spare
-- ----------------------------
DROP TABLE IF EXISTS `spare`;
CREATE TABLE `spare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `code` varchar(20) NOT NULL,
  `spec` varchar(20) DEFAULT NULL,
  `no` varchar(20) NOT NULL,
  `size` varchar(20) DEFAULT NULL,
  `class` varchar(10) NOT NULL,
  `factory` varchar(10) NOT NULL,
  `depart` varchar(10) NOT NULL,
  `dateManu` varchar(10) NOT NULL,
  `periodVali` varchar(10) NOT NULL,
  `liable` varchar(10) NOT NULL,
  `brand` varchar(20) NOT NULL,
  `price` float(10,0) DEFAULT NULL,
  `supplier` varchar(20) NOT NULL,
  `dvdinfo` tinytext,
  `divide` text,
  `tgther` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of spare
-- ----------------------------
INSERT INTO `spare` VALUES ('2', '橡皮', '45684', '大', 'white753', '1*4', '文具', '新世纪分店', '文具区', '2006-04-02', '2019-04-02', 'you', '宝克', '520', '国产', 'testman 2016-05-08 testinfo', null, null);
INSERT INTO `spare` VALUES ('3', '电话', 'phone123', '红色', '863', 'a6纸大小', '座机', '新华路分店', '办公区', '2016-04-24', '2016-05-31', 'me', '达尔讯', '300', '华润万家', 'testman 2016-05-08 testinfo', '听筒 ear123 座机听筒-拨码盘 dial123 座机拨码盘-', null);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `code` varchar(5) NOT NULL,
  `psw` char(6) NOT NULL,
  `departid` int(11) DEFAULT NULL,
  `permit` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', 'admin', '123456', '2', '0');
INSERT INTO `user` VALUES ('2', 'test1', 'test1', '123456', '3', '1');
INSERT INTO `user` VALUES ('3', 'test2', 'test2', '123456', '5', '1');
INSERT INTO `user` VALUES ('4', 'test3', 'test3', '123456', '6', '1');
INSERT INTO `user` VALUES ('5', 'test4', 'test4', '123456', '2', '1');
INSERT INTO `user` VALUES ('6', 'yb', 'yb', '123456', '2', '1');
INSERT INTO `user` VALUES ('8', 'test6', 'test6', '123456', '1', '1');
INSERT INTO `user` VALUES ('9', 'test7', 'test7', '123456', '2', '2');
