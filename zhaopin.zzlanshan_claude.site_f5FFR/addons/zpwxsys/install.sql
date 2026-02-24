

CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_active`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(200)  NULL DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `content` text  NOT NULL COMMENT '文章内容',
  `sort` bigint(16) DEFAULT NULL,
  `hits` bigint(16) DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `thumb` varchar(200)  NULL DEFAULT NULL,
  `begintime` varchar(100)  NULL DEFAULT NULL,
  `endtime` varchar(100)  NULL DEFAULT NULL,
  `mainwork` varchar(100)  NULL DEFAULT NULL,
  `money` decimal(10, 2)  DEFAULT 0.00,
  `companyid` varchar(200)  NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_oldhouse` (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cityid` bigint(16) DEFAULT NULL,
  `areaid` bigint(16) DEFAULT NULL,
  `title` varchar(60) DEFAULT NULL,
  `money` float(10,2) NOT NULL DEFAULT '0.00',
  `direction` varchar(30) DEFAULT NULL,
  `area` varchar(30) DEFAULT NULL,
  `floor1` varchar(60) DEFAULT NULL,
  `special` varchar(100) DEFAULT NULL,
  `lng` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `lat` decimal(10,6) NOT NULL DEFAULT '0.000000',
  `address` varchar(60) DEFAULT NULL,
  `housetype` varchar(30) DEFAULT NULL,
  `letdate` varchar(30) DEFAULT NULL,
  `setinfo` varchar(60) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `tel` varchar(30) DEFAULT NULL,
  `status` tinyint(10) NOT NULL DEFAULT '0',
  `createtime` bigint(16) DEFAULT NULL,
  `updatetime` bigint(16) DEFAULT NULL,
  `thumb` text,
  `thumb_url` text,
  `enabled` bigint(16) DEFAULT NULL,
  `isrecommand` tinyint(10) NOT NULL DEFAULT '0',
  `floor2` bigint(16) DEFAULT NULL,
  `sort` bigint(16) DEFAULT NULL,
  `type` tinyint(10) NOT NULL DEFAULT '0',
  `content` text,
  `uid` bigint(16) DEFAULT NULL,
  `videourl` varchar(200) DEFAULT NULL,
  `dmoney` float(10,2) NOT NULL DEFAULT '0.00',
  `islet` tinyint(10) NOT NULL DEFAULT '0',
  `agentuid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_activerecord`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `aid` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `create_time` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_adv`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `advname` varchar(60)  NULL DEFAULT NULL,
  `link` varchar(255)  NULL DEFAULT '',
  `thumb` varchar(255)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `toway` varchar(30)  NULL DEFAULT NULL,
  `appid` varchar(50)  NULL DEFAULT NULL,
  `cityid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_agent`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `name` varchar(200)  NULL DEFAULT NULL,
  `tel` varchar(60)  NULL DEFAULT NULL,
  `weixin` varchar(30)  NULL DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `tid` bigint(16) DEFAULT NULL,
  `fxuid1` bigint(16) DEFAULT NULL,
  `fxuid2` bigint(16) DEFAULT NULL,
  `qrcode` varchar(100)  NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;

CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_ploite`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `type` tinyint(10) DEFAULT 0,
  `content` varchar(200)  NULL DEFAULT NULL,
  `uid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `pid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`createtime`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_area`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(50)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `cityid` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_askjob`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `jobid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `companyid` bigint(16) DEFAULT NULL,
  `content` varchar(200)  NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_baoming`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `name` varchar(200)  NULL DEFAULT NULL,
  `tel` varchar(60)  NULL DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `tid` bigint(16) DEFAULT NULL,
  `jobid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_cate`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(50)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;


CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_city`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(50)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `firstname` varchar(30)  NULL DEFAULT NULL,
  `ison` tinyint(10) DEFAULT 0,
  `ishot` tinyint(10) DEFAULT 0,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_company`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `companyname` varchar(50)  NOT NULL DEFAULT '',
  `companycate` varchar(50)  NOT NULL,
  `companytype` varchar(50)  NOT NULL,
  `companyworker` varchar(30)  NOT NULL,
  `mastername` varchar(50)  NOT NULL DEFAULT '',
  `address` varchar(100)  NOT NULL,
  `teamaddress` varchar(100)  NULL DEFAULT NULL,
  `teamtime` varchar(30)  NULL DEFAULT NULL,
  `email` varchar(50)  NULL DEFAULT NULL,
  `tel` varchar(50)  NOT NULL,
  `joincompany` varchar(60)  NULL DEFAULT NULL,
  `content` text  NOT NULL,
  `createtime` bigint(16) DEFAULT NULL,
  `thumb` text  NOT NULL,
  `areaid` bigint(16) DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `sort` bigint(16) DEFAULT NULL,
  `uniacid` bigint(16) DEFAULT NULL,
  `isrecommand` tinyint(10) DEFAULT 0,
  `lng` decimal(10, 6) NULL DEFAULT 0.000000,
  `lat` decimal(10, 6) NULL DEFAULT 0.000000,
  `endtime` bigint(16) DEFAULT NULL,
  `star` tinyint(10) DEFAULT 0,
  `notenum` bigint(16) DEFAULT NULL,
  `cityid` bigint(16) DEFAULT NULL,
  `cardimg` text  NULL,
  `jobnum` bigint(16) DEFAULT NULL,
  `roleid` bigint(16) DEFAULT NULL,
  `weixin` varchar(30)  NULL DEFAULT NULL,
  `tid` bigint(16) DEFAULT NULL,
  `fxstatus` tinyint(10) DEFAULT 0,
  `companyimg` text  NULL,
  `uid` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  `shortname` varchar(100)  NULL DEFAULT NULL,
  `view` bigint(16) DEFAULT NULL,
  `chatlink` varchar(200)  NULL DEFAULT NULL,
  `schatlink` varchar(200)  NULL DEFAULT NULL,
  `business_license` varchar(255) DEFAULT NULL COMMENT '营业执照',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;


CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_companyaccount`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `name` varchar(60)  NULL DEFAULT NULL,
  `password` varchar(60)  NULL DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `logintime` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_companyrecord`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `mark` varchar(30)  NULL DEFAULT NULL,
  `totaljobnum` bigint(16) DEFAULT NULL,
  `totalnotenum` bigint(16) DEFAULT NULL,
  `jobnum` bigint(16) DEFAULT NULL,
  `notenum` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `topnum` bigint(16) DEFAULT NULL,
  `totaltopnum` bigint(16) DEFAULT NULL,
  `totalspreadnum` bigint(16) DEFAULT 0 COMMENT '加急扩散剩余次数',
  `type` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`totalnotenum`) USING BTREE,
  INDEX `indx_displayorder`(`totaljobnum`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_companyrole`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `title` varchar(30)  NULL DEFAULT NULL,
  `money` decimal(10, 2)  DEFAULT 0.00,
  `days` bigint(16) DEFAULT NULL,
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `jobnum` bigint(16) DEFAULT NULL,
  `notenum` bigint(16) DEFAULT NULL,
  `isinit` tinyint(10) DEFAULT 0,
  `topnum` bigint(16) NOT NULL DEFAULT 10,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_current`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(50)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `firstname` varchar(30)  NULL DEFAULT NULL,
  `ison` tinyint(10) DEFAULT 0,
  `ishot` tinyint(10) DEFAULT 0,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_edu`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `begindateschool` varchar(50)  NULL DEFAULT '',
  `enddateschool` varchar(50)  NULL DEFAULT NULL,
  `school` varchar(50)  NULL DEFAULT NULL,
  `vocation` varchar(50)  NULL DEFAULT NULL,
  `educationname` varchar(50)  NULL DEFAULT NULL,
  `uid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`school`) USING BTREE,
  INDEX `indx_displayorder`(`enddateschool`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_express`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `begindatejob` varchar(50)  NULL DEFAULT '',
  `enddatejob` varchar(50)  NULL DEFAULT NULL,
  `companyname` varchar(50)  NULL DEFAULT NULL,
  `jobtitle` varchar(50)  NULL DEFAULT NULL,
  `uid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`companyname`) USING BTREE,
  INDEX `indx_displayorder`(`enddatejob`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_fxrecord`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `createtime` bigint(16) DEFAULT NULL,
  `uid` bigint(16) DEFAULT NULL,
  `status` tinyint(10) NULL DEFAULT NULL,
  `money` decimal(10, 2)  DEFAULT 0.00,
  `orderid` bigint(16) DEFAULT NULL,
  `content` varchar(50)  NULL DEFAULT NULL,
  `type` tinyint(10) DEFAULT 0 COMMENT '0表示简历，1表示企业',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_helplab`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(50)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `firstname` varchar(30)  NULL DEFAULT NULL,
  `ison` tinyint(10) DEFAULT 0,
  `ishot` tinyint(10) DEFAULT 0,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_invaterecord`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `noteid` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `invatetime` bigint(16) DEFAULT NULL,
  `islook` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_job`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `jobtitle` varchar(50)  NULL DEFAULT NULL,
  `joblabel` tinyint(10) NULL DEFAULT 2,
  `worktype` bigint(16) DEFAULT NULL,
  `num` bigint(16) DEFAULT NULL,
  `sex` tinyint(10) DEFAULT 0,
  `age` varchar(20)  NULL DEFAULT NULL,
  `content` text  NULL,
  `vprice` decimal(20, 2)  DEFAULT 0.00,
  `companyid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL,
  `sort` bigint(16) DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `isrecommand` tinyint(10) DEFAULT 0,
  `money` varchar(50)  NULL DEFAULT NULL,
  `education` varchar(30)  NULL DEFAULT NULL,
  `express` varchar(30)  NULL DEFAULT NULL,
  `jobtype` tinyint(10) DEFAULT 0,
  `dmoney` decimal(10, 2)  DEFAULT 0.00,
  `hits` bigint(16) DEFAULT NULL,
  `endtime` bigint(16) DEFAULT NULL,
  `toptime` bigint(16) DEFAULT NULL,
  `spreadtime` bigint(16) DEFAULT NULL COMMENT '加急扩散到期时间',
  `noteprice` float(10, 2) NULL DEFAULT 0.00,
  `updatetime` bigint(16) DEFAULT NULL,
  `videourl` varchar(100)  NULL DEFAULT NULL,
  `managemoney` decimal(10, 2)  DEFAULT 0.00,
  `remoney` decimal(10, 2)  DEFAULT 0.00,
  `srate` float(10, 2) NOT NULL DEFAULT 0.00,
  `mreate` float(10, 2) NOT NULL DEFAULT 0.00,
  `intromoney` decimal(10, 2)  DEFAULT 0.00,
  `areaid` bigint(16) DEFAULT NULL,
  `companyname` varchar(100)  NULL DEFAULT NULL,
  `logo` text  NULL,
  `age1` bigint(16) DEFAULT NULL,
  `age2` bigint(16) DEFAULT NULL,
  `special` varchar(200)  NULL DEFAULT NULL,
  `jobpriceid` bigint(16) DEFAULT NULL,
  `type` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  `worktypeid` bigint(16) DEFAULT NULL,
  `ischeck` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_jobcate`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(50)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;


CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_jobpart`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `jobtitle` varchar(50)  NULL DEFAULT NULL,
  `joblabel` tinyint(10) NULL DEFAULT 2,
  `worktype` bigint(16) DEFAULT NULL,
  `num` bigint(16) DEFAULT NULL,
  `sex` tinyint(10) DEFAULT 0,
  `age` varchar(20)  NULL DEFAULT NULL,
  `content` text  NULL,
  `vprice` decimal(20, 2)  DEFAULT 0.00,
  `companyid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL,
  `sort` bigint(16) DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `isrecommand` tinyint(10) DEFAULT 0,
  `money` varchar(50)  NULL DEFAULT NULL,
  `education` varchar(30)  NULL DEFAULT NULL,
  `express` varchar(30)  NULL DEFAULT NULL,
  `jobtype` tinyint(10) DEFAULT 0,
  `dmoney` decimal(10, 2)  DEFAULT 0.00,
  `hits` bigint(16) DEFAULT NULL,
  `endtime` bigint(16) DEFAULT NULL,
  `toptime` bigint(16) DEFAULT NULL,
  `spreadtime` bigint(16) DEFAULT NULL COMMENT '加急扩散到期时间',
  `noteprice` decimal(10, 2)  DEFAULT 0.00,
  `updatetime` bigint(16) DEFAULT NULL,
  `videourl` varchar(100)  NULL DEFAULT NULL,
  `managemoney` decimal(10, 2)  DEFAULT 0.00,
  `remoney` decimal(10, 2)  DEFAULT 0.00,
  `srate` float(10, 2) NOT NULL DEFAULT 0.00,
  `mreate` float(10, 2) NOT NULL DEFAULT 0.00,
  `intromoney` decimal(10, 2)  DEFAULT 0.00,
  `areaid` bigint(16) DEFAULT NULL,
  `companyname` varchar(100)  NULL DEFAULT NULL,
  `logo` text  NULL,
  `age1` bigint(16) DEFAULT NULL,
  `age2` bigint(16) DEFAULT NULL,
  `special` varchar(200)  NULL DEFAULT NULL,
  `jobpriceid` bigint(16) DEFAULT NULL,
  `type` tinyint(10) DEFAULT 0,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_jobprice`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `uniacid` bigint(16) DEFAULT NULL,
  `name` varchar(50)  NULL DEFAULT '',
  `beginprice` bigint(16) DEFAULT NULL,
  `endprice` bigint(16) DEFAULT NULL,
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_weid`(`uniacid`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_jobrecord`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `jobid` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `invatetime` bigint(16) DEFAULT NULL,
  `islook` tinyint(10) DEFAULT 0,
  `shareid` bigint(16) DEFAULT NULL,
  `type` tinyint(10) DEFAULT 0,
  `ismsgtpl` tinyint(10) DEFAULT 0,
  `agentuid` bigint(16) DEFAULT NULL,
  `taskid` bigint(16) DEFAULT NULL,
  `signin_time` varchar(30) DEFAULT NULL COMMENT '签到时间',
  `signout_time` varchar(30) DEFAULT NULL COMMENT '签退时间',
  `signed_in` tinyint(4) DEFAULT 0 COMMENT '是否已签到 0否 1是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_jobsave`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `jobid` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_lookcompanyrecord`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `companyid` bigint(16) DEFAULT NULL,
  `noteid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_looknote`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `companyid` bigint(16) DEFAULT NULL,
  `noteid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_lookrecord`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `jobid` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `update_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_lookrole`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `title` varchar(30)  NULL DEFAULT NULL,
  `money` decimal(10, 2)  DEFAULT 0.00,
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `looknum` bigint(16) DEFAULT NULL,
  `isinit` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_lookrolerecord`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `looknum` bigint(16) DEFAULT NULL COMMENT '操作余额',
  `totallooknum` bigint(16) DEFAULT NULL COMMENT '可提结余',
  `create_time` bigint(16) NOT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `type` tinyint(10) DEFAULT 0,
  `mark` varchar(30)  NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_mygz`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_nav`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `advname` varchar(50)  NULL DEFAULT '',
  `link` varchar(255)  NULL DEFAULT '',
  `thumb` varchar(255)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `appid` varchar(60)  NULL DEFAULT NULL,
  `innerurl` varchar(255)  NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_news`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(200)  NULL DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `content` text  NOT NULL COMMENT '文章内容',
  `sort` bigint(16) DEFAULT NULL,
  `hits` bigint(16) DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `thumb` varchar(200)  NULL DEFAULT NULL,
  `cateid` bigint(16) DEFAULT NULL,
  `type` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_note`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `jobtitle` varchar(50)  NULL DEFAULT NULL,
  `name` varchar(200)  NULL DEFAULT NULL,
  `sex` tinyint(10) NULL DEFAULT 1,
  `tel` varchar(60)  NULL DEFAULT NULL,
  `birthday` varchar(30)  NULL DEFAULT NULL,
  `education` varchar(30)  NULL DEFAULT NULL,
  `express` varchar(30)  NULL DEFAULT NULL,
  `address` varchar(30)  NULL DEFAULT NULL,
  `email` varchar(30)  NULL DEFAULT NULL,
  `currentstatus` varchar(30)  NULL DEFAULT NULL,
  `worktype` varchar(30)  NULL DEFAULT NULL,
  `jobcateid` bigint(16) DEFAULT NULL,
  `money` varchar(30)  NULL DEFAULT NULL,
  `areaid` bigint(16) DEFAULT NULL,
  `content` text  NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `avatarUrl` varchar(200)  NULL DEFAULT NULL,
  `refreshtime` bigint(16) DEFAULT NULL,
  `cityid` bigint(16) DEFAULT NULL,
  `shareid` bigint(16) DEFAULT NULL,
  `tid` bigint(16) DEFAULT NULL,
  `send` tinyint(10) DEFAULT 0,
  `fxstatus` tinyint(10) DEFAULT 0,
  `service` varchar(100)  NULL DEFAULT NULL,
  `company` varchar(60)  NULL DEFAULT NULL,
  `star` tinyint(10) NOT NULL DEFAULT 3,
  `cateid` bigint(16) DEFAULT NULL,
  `skillname` varchar(200)  NULL DEFAULT NULL,
  `marray` tinyint(10) DEFAULT 0,
  `imgstr1` text  NULL,
  `imgstr2` text  NULL,
  `imgstr3` text  NULL,
  `imgstr4` text  NULL,
  `age` varchar(60)  NULL DEFAULT NULL,
  `money1` bigint(16) DEFAULT NULL,
  `money2` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  `currentid` bigint(16) DEFAULT NULL,
  `helplabid` bigint(16) DEFAULT NULL,
  `cardnum` varchar(60)  NULL DEFAULT NULL,
  `worktypeid` bigint(16) DEFAULT NULL,
  `jobpriceid` bigint(16) DEFAULT NULL,
  `ishidden` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_notice`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(200)  NULL DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `content` text  NOT NULL COMMENT '文章内容',
  `sort` bigint(16) DEFAULT NULL,
  `hits` bigint(16) DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;


CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_onlive`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(100)  NULL DEFAULT NULL,
  `cover_img` varchar(200)  NULL DEFAULT NULL,
  `start_time` bigint(16) DEFAULT NULL,
  `end_time` bigint(16) DEFAULT NULL,
  `anchor_name` varchar(30)  NULL DEFAULT NULL,
  `roomid` bigint(16) DEFAULT NULL,
  `goods` text  NULL,
  `live_status` bigint(16) DEFAULT NULL,
  `share_img` varchar(200)  NULL DEFAULT NULL,
  `live_type` tinyint(10) DEFAULT 0,
  `close_goods` tinyint(10) DEFAULT 0,
  `close_comment` tinyint(10) DEFAULT 0,
  `close_kf` tinyint(10) DEFAULT 0,
  `close_replay` tinyint(10) DEFAULT 0,
  `is_feeds_public` tinyint(10) DEFAULT 0,
  `creater_openid` varchar(60)  NULL DEFAULT NULL,
  `feeds_img` varchar(200)  NULL DEFAULT NULL,
  `close_like` tinyint(10) DEFAULT 0,
  `createtime` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_order`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20)  NOT NULL COMMENT '订单号',
  `user_id` bigint(16) NOT NULL COMMENT '外键，用户id，注意并不是openid',
  `delete_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  `total_price` decimal(6, 2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:未支付， 2：已支付，3：已发货 , 4: 已支付，但库存不足',
  `snap_img` varchar(255)  NULL DEFAULT NULL COMMENT '订单快照图片',
  `snap_name` varchar(80)  NULL DEFAULT NULL COMMENT '订单快照名称',
  `total_count` bigint(16) DEFAULT NULL,
  `update_time` bigint(16) DEFAULT NULL,
  `snap_items` text  NULL COMMENT '订单其他信息快照（json)',
  `snap_address` varchar(500)  NULL DEFAULT NULL COMMENT '地址快照',
  `prepay_id` varchar(100)  NULL DEFAULT NULL COMMENT '订单微信支付的预订单id（用于发送模板消息）',
  `type` varchar(30)  NULL DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_no`(`order_no`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_rect`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(50)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `thumb` varchar(200)  NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_sysinit`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30)  NULL DEFAULT NULL,
  `companyname` varchar(30)  NULL DEFAULT NULL COMMENT '创建时间',
  `address` varchar(60)  NULL DEFAULT NULL COMMENT '文章内容',
  `tel` varchar(30)  NULL DEFAULT NULL,
  `lat` decimal(10, 6) NULL DEFAULT 0.000000,
  `lng` decimal(10, 6) NULL DEFAULT 0.000000,
  `logo` varchar(200)  NULL DEFAULT NULL,
  `view` bigint(16) DEFAULT NULL,
  `content` text  NULL,
  `rate1` float(10, 2) NOT NULL DEFAULT 0.00,
  `rate2` float(10, 2) NOT NULL DEFAULT 0.00,
  `rate3` float(10, 2) NOT NULL DEFAULT 0.00,
  `appid` varchar(60)  NULL DEFAULT NULL,
  `appsecret` varchar(60)  NULL DEFAULT NULL,
  `couponid` varchar(30)  NULL DEFAULT NULL,
  `couponkey` varchar(60)  NULL DEFAULT NULL,
  `companycount` bigint(16) DEFAULT NULL,
  `jobcount` bigint(16) DEFAULT NULL,
  `notecount` bigint(16) DEFAULT NULL,
  `ischeck` tinyint(10) DEFAULT 0,
  `companymoney` float(10, 1) NOT NULL DEFAULT 0.0,
  `notemoney` float(10, 1) NOT NULL DEFAULT 0.0,
  `fxbg` varchar(200)  NULL DEFAULT NULL,
  `sharebg` varchar(200)  NULL DEFAULT NULL,
  `rulecontent` text  NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_sysmsg`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `content` varchar(200)  NULL DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `link` varchar(60)  NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_task`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(200)  NULL DEFAULT NULL,
  `jobid` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `num` bigint(16) DEFAULT NULL,
  `money` float(10, 1) NULL DEFAULT 0.0,
  `content` text  NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_taskrecord`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` bigint(16) DEFAULT NULL,
  `taskid` bigint(16) DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_user`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50)  NOT NULL,
  `nickname` varchar(50)  NULL DEFAULT NULL,
  `extend` varchar(255)  NULL DEFAULT NULL,
  `delete_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL COMMENT '注册时间',
  `update_time` bigint(16) DEFAULT NULL,
  `avatarUrl` varchar(200)  NULL DEFAULT NULL,
  `qrcode` varchar(200)  NULL DEFAULT NULL,
  `tid` bigint(16) DEFAULT NULL,
  `fxuid1` bigint(16) DEFAULT NULL,
  `fxuid2` bigint(16) DEFAULT NULL,
  `tel` varchar(60)  NULL DEFAULT NULL,
  `rectid` bigint(16) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL COMMENT '学校',
  `major` varchar(100) DEFAULT NULL COMMENT '专业',
  `experience_count` int(11) DEFAULT 0 COMMENT '兼职经历次数',
  `coin_balance` decimal(10,2) DEFAULT 0.00 COMMENT '章鱼币余额',
  `point_balance` int(11) DEFAULT 0 COMMENT '积分余额',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `openid`(`openid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_worktype`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `name` varchar(50)  NULL DEFAULT '',
  `sort` bigint(16) DEFAULT NULL,
  `enabled` bigint(16) DEFAULT NULL,
  `firstname` varchar(30)  NULL DEFAULT NULL,
  `ison` tinyint(10) DEFAULT 0,
  `ishot` tinyint(10) DEFAULT 0,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`enabled`) USING BTREE,
  INDEX `indx_displayorder`(`sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;


CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_money`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `type` tinyint(10) DEFAULT 0,
  `orderid` varchar(60) DEFAULT NULL,
  `money` float(10,2) NOT NULL DEFAULT 0.00,
  `content` varchar(30) DEFAULT NULL,
  `totalmoney` float(10,2) NOT NULL DEFAULT 0.00,
  `uid` bigint(16) DEFAULT NULL,
  `paytype` tinyint(10) DEFAULT 0,
  `createtime` bigint(16) DEFAULT NULL,
  `status` tinyint(10) DEFAULT 0,
  `cityid` bigint(16) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;


CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_projobrecord`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `gid` bigint(16) DEFAULT NULL,
  `title` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(10) DEFAULT 0,
  `type` tinyint(10) DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;



CREATE TABLE  IF NOT EXISTS `__PREFIX__zpwxsys_comment`  (
  `id` bigint(16) NOT NULL AUTO_INCREMENT,
  `content` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `uid` bigint(16) DEFAULT NULL,
  `companyid` bigint(16) DEFAULT NULL,
  `pid` bigint(16) DEFAULT NULL,
  `type` tinyint(10) DEFAULT 0,
  `update_time` bigint(16) DEFAULT NULL,
  `create_time` bigint(16) DEFAULT NULL,
  `score` bigint(16) DEFAULT NULL,
  `piclist` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '评论图片列表，逗号分隔',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `indx_enabled`(`companyid`) USING BTREE,
  INDEX `indx_displayorder`(`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 ;




-- 群聊系统表

CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_chat_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` int(11) NOT NULL COMMENT '岗位ID',
  `group_name` varchar(100) NOT NULL COMMENT '群名称',
  `notice` varchar(500) DEFAULT '' COMMENT '群公告',
  `companyid` int(11) NOT NULL COMMENT '企业ID（群主）',
  `owner_uid` int(11) NOT NULL COMMENT '群主用户ID',
  `max_member` int(11) DEFAULT 200 COMMENT '最大成员数',
  `member_count` int(11) DEFAULT 0 COMMENT '当前成员数',
  `status` tinyint(4) DEFAULT 1 COMMENT '状态 0=已解散 1=正常',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  `expire_time` int(11) DEFAULT NULL COMMENT '到期时间（岗位到期第二天）',
  PRIMARY KEY (`id`),
  INDEX `idx_jobid`(`jobid`),
  INDEX `idx_companyid`(`companyid`),
  INDEX `idx_status`(`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='群聊表';

CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_chat_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL COMMENT '群ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `role` tinyint(4) DEFAULT 0 COMMENT '角色 0=普通成员 1=管理员 2=群主',
  `is_muted` tinyint(4) DEFAULT 0 COMMENT '是否禁言 0=否 1=是',
  `agreed_rule` tinyint(4) DEFAULT 0 COMMENT '是否已同意公约 0=否 1=是',
  `jointime` int(11) DEFAULT NULL COMMENT '加入时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_group_user` (`groupid`, `uid`),
  INDEX `idx_uid`(`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='群成员表';

CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_chat_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL COMMENT '群ID',
  `uid` int(11) NOT NULL COMMENT '发送者ID',
  `content` varchar(500) NOT NULL COMMENT '消息内容',
  `msg_type` tinyint(4) DEFAULT 0 COMMENT '消息类型 0=文本 1=系统通知',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_groupid`(`groupid`),
  INDEX `idx_createtime`(`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='群消息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_chat_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT '' COMMENT '公约标题',
  `content` text COMMENT '公约内容',
  `status` tinyint(4) DEFAULT 1 COMMENT '是否启用 0=否 1=是',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='群聊公约配置表';

CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_sensitive_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(100) NOT NULL COMMENT '敏感词',
  `status` tinyint(4) DEFAULT 1 COMMENT '是否启用 0=否 1=是',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_word`(`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='敏感词库配置表';


CREATE TABLE IF NOT EXISTS `__PREFIX__zpwxsys_coin_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `type` tinyint(4) DEFAULT 1 COMMENT '类型 1=章鱼币 2=积分',
  `amount` decimal(10,2) DEFAULT 0.00 COMMENT '变动金额（正=收入，负=支出）',
  `balance` decimal(10,2) DEFAULT 0.00 COMMENT '变动后余额',
  `action` varchar(30) DEFAULT '' COMMENT '动作类型：recharge/consume/signin/referral/exchange/topjob/spreadjob/creategroup/tempcall',
  `remark` varchar(200) DEFAULT '' COMMENT '备注',
  `related_id` int(11) DEFAULT NULL COMMENT '关联业务ID',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid`(`uid`),
  INDEX `idx_type`(`type`),
  INDEX `idx_createtime`(`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='章鱼币/积分流水记录表';


-- 1.0.7 --

ALTER TABLE `__PREFIX__zpwxsys_sysinit` ADD COLUMN `fxbg` varchar(200) NULL DEFAULT ''  COMMENT '分销背景图' AFTER `notemoney`;
ALTER TABLE `__PREFIX__zpwxsys_sysinit` ADD COLUMN `sharebg` varchar(200) NULL DEFAULT ''  COMMENT '推荐分享背景图' AFTER `notemoney`;
ALTER TABLE `__PREFIX__zpwxsys_sysinit` ADD COLUMN `rulecontent` text NULL DEFAULT ''  COMMENT '协议内容' AFTER `notemoney`;

-- 1.0.12 --
ALTER TABLE `__PREFIX__zpwxsys_jobrecord` ADD COLUMN `agentuid` bigint(16) DEFAULT NULL  COMMENT '经纪人ID' AFTER `ismsgtpl`;
ALTER TABLE `__PREFIX__zpwxsys_jobrecord` ADD COLUMN `taskid` bigint(16) DEFAULT NULL  COMMENT '任务ID'  AFTER `ismsgtpl`;

-- 1.0.19 --
ALTER TABLE `__PREFIX__zpwxsys_user` ADD COLUMN `qrcode` varchar(200) NULL DEFAULT ''  COMMENT '审核状态' AFTER `avatarUrl`;
ALTER TABLE `__PREFIX__zpwxsys_sysinit` ADD COLUMN `ischeck` tinyint(10) DEFAULT 0  COMMENT '审核状态' AFTER `notemoney`;