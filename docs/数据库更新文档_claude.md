# 数据库更新文档

## 更新背景

本次数据库更新配合以下功能的开发：

1. **签到/签退系统** — 求职者对已确认的工作进行签到和签退
2. **加急扩散功能** — 发布者为职位购买加急扩散服务
3. **签到管理后台** — 发布者查看各岗位签到概况和明细

## 远程数据库连接信息

| 项目 | 值 |
|------|-----|
| 主机 | 122.51.106.231 |
| 端口 | 3306 |
| 数据库 | zhaopin |
| 用户名 | zhaopin |
| 密码 | i4n5NxRD7BBNT57T |
| 表前缀 | fa_ |

## 变更表清单

### 1. fa_zpwxsys_jobrecord（求职记录表）

新增 3 个字段：

| 字段 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `signin_time` | varchar(30) | NULL | 签到时间，格式 Y-m-d H:i:s |
| `signout_time` | varchar(30) | NULL | 签退时间，格式 Y-m-d H:i:s |
| `signed_in` | tinyint(4) | 0 | 是否已签到：0=否，1=是 |

**用途**：支持求职者签到/签退功能。签到时写入 `signin_time` 并将 `signed_in` 设为 1；签退时写入 `signout_time` 并将 `status` 更新为 5（已完成）。

**关联后端方法**：
- `Job::doSignIn()` — 写入 signin_time、signed_in
- `Job::doSignOut()` — 写入 signout_time、更新 status
- `Company::getSigninList()` — 按 signed_in 统计签到人数
- `Company::getSigninDetail()` — 查询签到/签退明细，计算工时

### 2. fa_zpwxsys_job（职位表）

新增 1 个字段：

| 字段 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `spreadtime` | bigint(16) | NULL | 加急扩散到期时间（Unix 时间戳） |

**用途**：记录职位加急扩散服务的到期时间。逻辑与现有 `toptime`（置顶到期时间）一致。

**关联后端方法**：
- `Company::spreadJob()` — 设置 spreadtime 为当前时间 + 24小时

### 3. fa_zpwxsys_companyrecord（企业账户记录表）

新增 1 个字段：

| 字段 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `totalspreadnum` | bigint(16) | 0 | 加急扩散剩余次数 |

**用途**：记录企业加急扩散服务的可用次数余额。逻辑与现有 `totaltopnum`（置顶剩余次数）一致。

**关联后端方法**：
- `Company::spreadJob()` — 检查余额并扣减

## 执行 SQL

### 迁移脚本（对已有数据库执行）

```sql
-- 1. jobrecord 表：添加签到/签退字段
ALTER TABLE `fa_zpwxsys_jobrecord`
  ADD COLUMN `signin_time` varchar(30) DEFAULT NULL COMMENT '签到时间' AFTER `taskid`,
  ADD COLUMN `signout_time` varchar(30) DEFAULT NULL COMMENT '签退时间' AFTER `signin_time`,
  ADD COLUMN `signed_in` tinyint(4) DEFAULT 0 COMMENT '是否已签到 0否 1是' AFTER `signout_time`;

-- 2. job 表：添加加急扩散时间字段
ALTER TABLE `fa_zpwxsys_job`
  ADD COLUMN `spreadtime` bigint(16) DEFAULT NULL COMMENT '加急扩散到期时间' AFTER `toptime`;

-- 3. companyrecord 表：添加扩散次数余额字段
ALTER TABLE `fa_zpwxsys_companyrecord`
  ADD COLUMN `totalspreadnum` bigint(16) DEFAULT 0 COMMENT '加急扩散剩余次数' AFTER `totaltopnum`;
```

### 命令行执行方式

```bash
mysql -h 122.51.106.231 -P 3306 -u zhaopin -p'i4n5NxRD7BBNT57T' zhaopin < migration_signin.sql
```

> 注意：迁移脚本 `migration_signin.sql` 位于 `addons/zpwxsys/` 目录下，其中表名使用的是不带 `fa_` 前缀的 `zpwxsys_*` 格式。若直接执行该文件，需先将表名替换为带前缀的 `fa_zpwxsys_*`，或使用上方的 SQL 手动执行。

## install.sql 同步更新

以下 CREATE TABLE 语句已在 `addons/zpwxsys/install.sql` 中同步更新，新部署时自动包含所有新字段：

- `__PREFIX__zpwxsys_jobrecord` — 包含 signin_time, signout_time, signed_in
- `__PREFIX__zpwxsys_job` — 包含 spreadtime
- `__PREFIX__zpwxsys_companyrecord` — 包含 totalspreadnum

## 回滚 SQL（如需回退）

```sql
ALTER TABLE `fa_zpwxsys_jobrecord`
  DROP COLUMN `signin_time`,
  DROP COLUMN `signout_time`,
  DROP COLUMN `signed_in`;

ALTER TABLE `fa_zpwxsys_job`
  DROP COLUMN `spreadtime`;

ALTER TABLE `fa_zpwxsys_companyrecord`
  DROP COLUMN `totalspreadnum`;
```

## 影响范围

- 所有变更均为 **ADD COLUMN**，不修改/删除现有数据
- 新字段默认值为 NULL 或 0，不影响现有记录
- 无需停机，可在线执行

---

## 更新记录 2：评价系统（piclist）

### fa_zpwxsys_comment（评价表）

| 字段 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `piclist` | varchar(500) | NULL | 评价图片列表，逗号分隔 |

```sql
ALTER TABLE `fa_zpwxsys_comment` ADD COLUMN `piclist` varchar(500) DEFAULT NULL COMMENT '评价图片列表';
```

---

## 更新记录 3：群聊系统

新增 5 张表：

| 表名 | 说明 |
|------|------|
| `fa_zpwxsys_chat_group` | 群组表 |
| `fa_zpwxsys_chat_member` | 群成员表 |
| `fa_zpwxsys_chat_message` | 群消息表 |
| `fa_zpwxsys_chat_rule` | 群聊公约表 |
| `fa_zpwxsys_sensitive_word` | 敏感词库表 |

详见 `install.sql` 中对应的 CREATE TABLE 语句。

---

## 更新记录 4：名片系统

### fa_zpwxsys_user（用户表）

新增 3 个字段：

| 字段 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `school` | varchar(100) | NULL | 学校 |
| `major` | varchar(100) | NULL | 专业 |
| `experience_count` | int(11) | 0 | 兼职经历次数 |

### fa_zpwxsys_company（企业表）

新增 1 个字段：

| 字段 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `business_license` | varchar(255) | NULL | 营业执照图片路径 |

```sql
ALTER TABLE `fa_zpwxsys_user` ADD COLUMN `school` varchar(100) DEFAULT NULL COMMENT '学校';
ALTER TABLE `fa_zpwxsys_user` ADD COLUMN `major` varchar(100) DEFAULT NULL COMMENT '专业';
ALTER TABLE `fa_zpwxsys_user` ADD COLUMN `experience_count` int(11) DEFAULT 0 COMMENT '兼职经历次数';
ALTER TABLE `fa_zpwxsys_company` ADD COLUMN `business_license` varchar(255) DEFAULT NULL COMMENT '营业执照';
```

**关联后端方法**：
- `Usercard::getApplicantCard()` — 获取报名者名片（头像、脱敏手机号、学校、专业、兼职经历次数、评分）
- `Usercard::getPublisherCard()` — 获取发布者名片（公司名、Logo、营业执照、联系人、发布岗位数、评分）
- `Usercard::saveApplicantCard()` — 保存报名者名片信息
- `Usercard::savePublisherCard()` — 保存发布者名片信息
- `Usercard::unlockPhone()` — 解锁手机号（消耗1章鱼币）

---

## 更新记录 5：章鱼币/积分系统

### 新增表：fa_zpwxsys_coin_record（章鱼币/积分流水记录表）

| 字段 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `id` | int(11) | AUTO_INCREMENT | 主键 |
| `uid` | int(11) | — | 用户ID |
| `type` | tinyint(4) | 1 | 类型 1=章鱼币 2=积分 |
| `amount` | decimal(10,2) | 0.00 | 变动金额（正=收入，负=支出） |
| `balance` | decimal(10,2) | 0.00 | 变动后余额 |
| `action` | varchar(30) | '' | 动作类型：recharge/consume/signin/referral/exchange/topjob/spreadjob/creategroup/tempcall |
| `remark` | varchar(200) | '' | 备注 |
| `related_id` | int(11) | NULL | 关联业务ID |
| `createtime` | int(11) | NULL | 创建时间 |

### fa_zpwxsys_user（用户表）

新增 2 个字段：

| 字段 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| `coin_balance` | decimal(10,2) | 0.00 | 章鱼币余额 |
| `point_balance` | int(11) | 0 | 积分余额 |

```sql
-- 创建章鱼币/积分流水记录表
CREATE TABLE IF NOT EXISTS `fa_zpwxsys_coin_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(4) DEFAULT 1 COMMENT '类型 1=章鱼币 2=积分',
  `amount` decimal(10,2) DEFAULT 0.00 COMMENT '变动金额',
  `balance` decimal(10,2) DEFAULT 0.00 COMMENT '变动后余额',
  `action` varchar(30) DEFAULT '' COMMENT '动作类型',
  `remark` varchar(200) DEFAULT '' COMMENT '备注',
  `related_id` int(11) DEFAULT NULL COMMENT '关联业务ID',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid`(`uid`),
  INDEX `idx_type`(`type`),
  INDEX `idx_createtime`(`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='章鱼币/积分流水记录表';

-- 用户表添加余额字段
ALTER TABLE `fa_zpwxsys_user` ADD COLUMN `coin_balance` decimal(10,2) DEFAULT 0.00 COMMENT '章鱼币余额' AFTER `experience_count`;
ALTER TABLE `fa_zpwxsys_user` ADD COLUMN `point_balance` int(11) DEFAULT 0 COMMENT '积分余额' AFTER `coin_balance`;
```

### 业务规则

- **充值档位**：10/30/50/100/200/500 章鱼币（1章鱼币=1元）
- **积分获取**：签到+1，推荐好友+2
- **积分兑换**：10积分 = 1章鱼币（向下取整）
- **临时通话**：解锁手机号消耗1章鱼币

### 关联后端方法

- `Coin::getBalance()` — 获取章鱼币和积分余额
- `Coin::recharge()` — 充值章鱼币
- `Coin::getCoinRecords()` — 章鱼币流水记录
- `Coin::getPointRecords()` — 积分流水记录
- `Coin::consume()` — 消费章鱼币
- `Coin::exchangePoints()` — 积分兑换章鱼币
- `Coin::getRechargeOptions()` — 获取充值档位列表
- `Job::doSignIn()` — 签到时自动奖励1积分
- `Usercard::unlockPhone()` — 解锁手机号时消耗1章鱼币
