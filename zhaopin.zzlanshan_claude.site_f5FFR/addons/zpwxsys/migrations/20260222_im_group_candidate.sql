-- IM群组集成候选人管理系统 数据库变更
-- 执行时间: 2026-02-22

-- A1: zpwxsys_chat_message 表添加图片字段
ALTER TABLE `zpwxsys_chat_message` ADD COLUMN `img_url` varchar(500) DEFAULT '' COMMENT '图片消息URL' AFTER `msg_type`;

-- Sysmsg 表添加 link 字段（用于消息跳转）
ALTER TABLE `zpwxsys_sysmsg` ADD COLUMN `link` varchar(500) NOT NULL DEFAULT '' COMMENT '消息跳转链接';
