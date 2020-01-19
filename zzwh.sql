/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : zzwh

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-01-20 01:05:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `alarm_summary`
-- ----------------------------
DROP TABLE IF EXISTS `alarm_summary`;
CREATE TABLE `alarm_summary` (
  `areacode` varchar(255) NOT NULL DEFAULT '' COMMENT '区域名称',
  `place_num` int(10) NOT NULL DEFAULT '0' COMMENT '场所数量',
  `black_place_num` int(10) NOT NULL DEFAULT '0' COMMENT '黑名单场所',
  `report_place_num` int(10) NOT NULL DEFAULT '0' COMMENT '举报场所',
  `online_people_num` int(10) NOT NULL DEFAULT '0' COMMENT '实时上网人数',
  `ww_alarm_num` int(10) NOT NULL DEFAULT '0' COMMENT '文网卫士报警场所数',
  `ww_install_num` int(10) NOT NULL DEFAULT '0' COMMENT '已安装ww家数',
  `ww_online_num` int(10) NOT NULL DEFAULT '0' COMMENT 'ww在线家数',
  `case_num` int(10) NOT NULL DEFAULT '0' COMMENT '案件办理数',
  `xuncha_num` int(10) NOT NULL DEFAULT '0' COMMENT '日常巡查数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报警汇总';

-- ----------------------------
-- Records of alarm_summary
-- ----------------------------
