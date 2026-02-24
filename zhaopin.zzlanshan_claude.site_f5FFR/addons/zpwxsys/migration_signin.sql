-- ============================================================
-- 章鱼外快 数据库迁移脚本
-- 适用于：签到系统 + 加急扩散功能
-- 执行方式：在 MySQL 客户端中直接执行本文件
-- ============================================================

-- 1. zpwxsys_jobrecord 表：添加签到/签退字段
ALTER TABLE `zpwxsys_jobrecord`
  ADD COLUMN `signin_time` varchar(30) DEFAULT NULL COMMENT '签到时间' AFTER `taskid`,
  ADD COLUMN `signout_time` varchar(30) DEFAULT NULL COMMENT '签退时间' AFTER `signin_time`,
  ADD COLUMN `signed_in` tinyint(4) DEFAULT 0 COMMENT '是否已签到 0否 1是' AFTER `signout_time`;

-- 2. zpwxsys_job 表：添加加急扩散时间字段
ALTER TABLE `zpwxsys_job`
  ADD COLUMN `spreadtime` bigint(16) DEFAULT NULL COMMENT '加急扩散到期时间' AFTER `toptime`;

-- 3. zpwxsys_companyrecord 表：添加扩散次数余额字段
ALTER TABLE `zpwxsys_companyrecord`
  ADD COLUMN `totalspreadnum` bigint(16) DEFAULT 0 COMMENT '加急扩散剩余次数' AFTER `totaltopnum`;
