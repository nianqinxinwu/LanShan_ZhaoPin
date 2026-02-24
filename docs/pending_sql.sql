-- 待执行SQL（数据库连接恢复后执行）
-- 原因：远程MySQL无法连接（公网IP变更导致白名单失效）

-- 1. 职位表增加结算方式字段
ALTER TABLE fa_zpwxsys_job ADD COLUMN settle_type TINYINT(4) NOT NULL DEFAULT 0 COMMENT '结算方式: 0=面议 1=日结 2=周结 3=月结 4=完工结' AFTER money;

-- 2. 执行完成后，还需在 Job model 的 getJobByWhere 和 getNearJobByWhere 的 field 列表中追加 j.settle_type AS settle_type
-- 位置：addons/zpwxsys/model/Job.php 第52行和第15行的 field() 字符串末尾（toptime 之后）
-- 示例：...,j.toptime AS toptime,j.settle_type AS settle_type ')
