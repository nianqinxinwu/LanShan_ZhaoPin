INSERT INTO `__PREFIX__zpwxsys_sysinit` (`id`, `name`, `companyname`, `address`, `tel`, `lat`, `lng`, `logo`, `view`, `content`, `rate1`, `rate2`, `rate3`, `appid`, `appsecret`, `couponid`, `couponkey`, `companycount`, `jobcount`, `notecount`, `ischeck`, `companymoney`, `notemoney`, `fxbg`, `sharebg`, `rulecontent`) VALUES
(55, '招聘小程序', NULL, NULL, NULL, '0.000000', '0.000000', NULL, 184, '招聘小程序', 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 12, 11, 21, 0, 0.0, 0.0, '/uploads/20250411/d5a49719f8a7dd04502d600a086c60ab.jpg', '/uploads/20250411/d5a49719f8a7dd04502d600a086c60ab.jpg', '招聘小程序');

INSERT INTO `__PREFIX__zpwxsys_city` VALUES (1, '盐城', 2, 1, 'Y', 1, 0, 1657962577, 0);
INSERT INTO `__PREFIX__zpwxsys_city` VALUES (2, '扬州', 1, 1, 'Y', 0, 0, 1657962602, 1657962602);

INSERT INTO `__PREFIX__zpwxsys_area` VALUES (3187, '亭湖区', 0, 1, 1, 0, 0);
INSERT INTO `__PREFIX__zpwxsys_area` VALUES (3188, '开发区', 0, 1, 1, 0, 0);
INSERT INTO `__PREFIX__zpwxsys_area` VALUES (3189, '盐都区', 0, 1, 1, 0, 0);
INSERT INTO `__PREFIX__zpwxsys_area` VALUES (3190, '大丰区', 0, 1, 1, 0, 0);
INSERT INTO `__PREFIX__zpwxsys_area` VALUES (3207, '维扬区', 1, 1, 2, 1657962916, 1657962916);
INSERT INTO `__PREFIX__zpwxsys_area` VALUES (3208, '江都区', 2, 1, 2, 1657962928, 1657962928);
INSERT INTO `__PREFIX__zpwxsys_area` VALUES (3209, '邗江区', 3, 1, 2, 1657962940, 1657962940);
INSERT INTO `__PREFIX__zpwxsys_area` VALUES (3210, '广陵区', 4, 1, 2, 1657962953, 1657962953);


INSERT INTO `__PREFIX__zpwxsys_adv` VALUES (1, '幻灯片', '', '/assets/addons/zpwxsys/640x400.png', 0, 1, NULL, NULL, 1);


INSERT INTO `__PREFIX__zpwxsys_nav` (`id`, `advname`, `link`, `thumb`, `sort`, `enabled`, `appid`, `innerurl`) VALUES
(1, '找人才', 'toInnerUrl', '/assets/addons/zpwxsys/100x100.png', 0, 1, '1', '/pages/findworker/index'),
(2, '招聘会', 'toInnerUrl', '/assets/addons/zpwxsys/100x100.png', 2, 1, '1', '/pages/active/index'),
(3, '找企业', 'toInnerUrl', '/assets/addons/zpwxsys/100x100.png', 13, 1, '1', '/pages/companylist/index'),
(4, '会员中心', 'toInnerUrl', '/assets/addons/zpwxsys/100x100.png', 0, 1, '1', '/pages/user/index'),
(5, '找工作', 'toInnerUrl', '/assets/addons/zpwxsys/100x100.png', 15, 1, '1', '/pages/nearjoblist/index'),
(6, '知政策', 'toInnerUrl', '/assets/addons/zpwxsys/100x100.png', 11, 1, '1', '/pages/article/index'),
(7, '附近职位', 'toInnerUrl', '/assets/addons/zpwxsys/100x100.png', 0, 1, '4', '/pages/nearjoblist/index'),
(8, '悬赏职位', 'toInnerUrl', '/assets/addons/zpwxsys/100x100.png', 0, 1, '1', '/pages/taskjob/index');



INSERT INTO `__PREFIX__zpwxsys_cate` VALUES (1, '创业培训', 0, 1);
INSERT INTO `__PREFIX__zpwxsys_cate` VALUES (2, '职业培训', 1, 1);
INSERT INTO `__PREFIX__zpwxsys_cate` VALUES (3, '政策通知', 12, 1);
INSERT INTO `__PREFIX__zpwxsys_cate` VALUES (4, '帮助中心', 0, 1);



INSERT INTO `__PREFIX__zpwxsys_companyrole` VALUES (1, '普通会员', 0.00, 365, 1, 1, 2, 10, 1, 10, 1657962971, 0);
INSERT INTO `__PREFIX__zpwxsys_companyrole` VALUES (2, '月卡会员', 0.01, 30, 2, 1, 5, 20, 0, 10, 1657788266, 0);
INSERT INTO `__PREFIX__zpwxsys_companyrole` VALUES (3, '季卡会员', 300.00, 120, 3, 1, 20, 200, 0, 10, 1657788267, 0);
INSERT INTO `__PREFIX__zpwxsys_companyrole` VALUES (4, '年卡会员', 1000.00, 365, 5, 1, 50, 1000, 0, 10, 1657930858, 0);


INSERT INTO `__PREFIX__zpwxsys_current` VALUES (1, '我目前已离职,可快速到岗', 5, 1, NULL, 0, 0, 1657177408, 1653373201);
INSERT INTO `__PREFIX__zpwxsys_current` VALUES (2, '我目前在职，但考虑换个新环境', 4, 1, NULL, 0, 0, 1653373225, 1653373225);
INSERT INTO `__PREFIX__zpwxsys_current` VALUES (3, '观望有好的机会再考虑', 3, 1, NULL, 0, 0, 1653373236, 1653373236);
INSERT INTO `__PREFIX__zpwxsys_current` VALUES (4, '目前暂无跳槽打算', 2, 1, NULL, 0, 0, 1653373244, 1653373244);
INSERT INTO `__PREFIX__zpwxsys_current` VALUES (5, '应届毕业生', 1, 1, NULL, 0, 0, 1653373252, 1653373252);


INSERT INTO `__PREFIX__zpwxsys_helplab` VALUES (1, '就业困难人员', 7, 1, NULL, 0, 0, 1653387226, 1653373347);
INSERT INTO `__PREFIX__zpwxsys_helplab` VALUES (2, '退役军人', 6, 1, NULL, 0, 0, 1653373357, 1653373357);
INSERT INTO `__PREFIX__zpwxsys_helplab` VALUES (3, '残疾人', 5, 1, NULL, 0, 0, 1653373370, 1653373370);
INSERT INTO `__PREFIX__zpwxsys_helplab` VALUES (4, '建档立卡贫困户', 4, 1, NULL, 0, 0, 1653373383, 1653373383);
INSERT INTO `__PREFIX__zpwxsys_helplab` VALUES (5, '失业人员', 3, 1, NULL, 0, 0, 1653373390, 1653373390);
INSERT INTO `__PREFIX__zpwxsys_helplab` VALUES (6, '应届毕业生', 2, 1, NULL, 0, 0, 1653373401, 1653373401);
INSERT INTO `__PREFIX__zpwxsys_helplab` VALUES (7, '退捕渔民', 1, 1, NULL, 0, 0, 1653373408, 1653373408);



INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (1, '咨询/法律', 15, 1, 1652884672, 1652884672);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (2, '家政服务/保姆', 16, 1, 1652884617, 1652884617);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (3, '服务业', 17, 1, 1652884603, 1652884603);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (4, '销售/客服', 18, 1, 1652884594, 1652884594);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (5, '人事/行政', 19, 1, 1652884585, 1652884585);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (6, '农林牧渔', 22, 1, 1657093449, 1652884570);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (7, '会计/金融', 14, 1, 1652884687, 1652884687);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (8, '生产/营运', 13, 1, 1652884701, 1652884701);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (9, '软件工程师/程序员', 12, 1, 1652884716, 1652884716);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (10, '市场/营销', 11, 1, 1652884784, 1652884784);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (11, '医疗/护理', 10, 1, 1652884792, 1652884792);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (12, '餐饮/娱乐', 9, 1, 1652884801, 1652884801);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (13, '物业维修/设施管理', 8, 1, 1652884811, 1652884811);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (14, '酒店前厅/客房/礼宾', 7, 1, 1652884823, 1652884823);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (15, '厨师', 6, 1, 1652884833, 1652884833);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (16, '公路运输/司机', 5, 1, 1652884838, 1652884838);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (17, '清洁服务人员', 4, 1, 1652884844, 1652884844);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (18, '店员/营业员', 3, 1, 1652884854, 1652884854);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (19, '计算机/互联网', 2, 1, 1652884861, 1652884861);
INSERT INTO `__PREFIX__zpwxsys_jobcate` VALUES (20, '其他', 1, 1, 1652884867, 1652884867);



INSERT INTO `__PREFIX__zpwxsys_jobprice` VALUES (1, 0, '2000-2500元', 2000, 2500, 0, 1, 0, 0);
INSERT INTO `__PREFIX__zpwxsys_jobprice` VALUES (2, 0, '2500-3000元', 2500, 3000, 1, 1, 0, 0);
INSERT INTO `__PREFIX__zpwxsys_jobprice` VALUES (3, 0, '3000-5000元', 3000, 5000, 2, 1, 0, 0);
INSERT INTO `__PREFIX__zpwxsys_jobprice` VALUES (4, 0, '5000-8000元', 5000, 8000, 3, 1, 0, 0);
INSERT INTO `__PREFIX__zpwxsys_jobprice` VALUES (5, 0, '8000-10000元', 8000, 10000, 4, 1, 1657099236, 0);
INSERT INTO `__PREFIX__zpwxsys_jobprice` VALUES (6, 0, '10000以上', 10000, 10000000, 6, 1, 1657098512, 0);



INSERT INTO `__PREFIX__zpwxsys_lookrole` VALUES (1, '简历包', 0.01, 1, 1, 100, 1);
INSERT INTO `__PREFIX__zpwxsys_lookrole` VALUES (2, '简历包', 0.01, 0, 1, 200, 0);
INSERT INTO `__PREFIX__zpwxsys_lookrole` VALUES (3, '简历包', 0.03, 0, 1, 300, 0);


INSERT INTO `__PREFIX__zpwxsys_news` VALUES (53, '隐私政策', 1640007890,'隐私政策', 0, 0, 1, '', 847, 1);
INSERT INTO `__PREFIX__zpwxsys_news` VALUES (54, '入驻指南', 1640007914, '入驻指南', 0, 0, 1, '', 847, 1);
INSERT INTO `__PREFIX__zpwxsys_news` VALUES (60, '企业入驻协议', 1654134149,'企业入驻协议',0, 0, 1, '', 847, 1);
INSERT INTO `__PREFIX__zpwxsys_news` VALUES (55, '用户须知（服务协议）', 1640007958,'服务协议', 0, 0, 1, '', 847, 1);



INSERT INTO `__PREFIX__zpwxsys_worktype` VALUES (1, '全职', 6, 1, NULL, 0, 0, 1657160305, 1653373005);
INSERT INTO `__PREFIX__zpwxsys_worktype` VALUES (2, '兼职', 4, 1, NULL, 0, 0, 1653373018, 1653373018);
INSERT INTO `__PREFIX__zpwxsys_worktype` VALUES (3, '零工', 3, 1, NULL, 0, 0, 1653373026, 1653373026);
INSERT INTO `__PREFIX__zpwxsys_worktype` VALUES (4, '妈妈班', 2, 1, NULL, 0, 0, 1653373034, 1653373034);
INSERT INTO `__PREFIX__zpwxsys_worktype` VALUES (5, '实习', 1, 1, NULL, 0, 0, 1653373042, 1653373042);


INSERT INTO `__PREFIX__zpwxsys_company` (`id`, `companyname`, `companycate`, `companytype`, `companyworker`, `mastername`, `address`, `teamaddress`, `teamtime`, `email`, `tel`, `joincompany`, `content`, `createtime`, `thumb`, `areaid`, `status`, `sort`, `uniacid`, `isrecommand`, `lng`, `lat`, `endtime`, `star`, `notenum`, `cityid`, `cardimg`, `jobnum`, `roleid`, `weixin`, `tid`, `fxstatus`, `companyimg`, `uid`, `update_time`, `create_time`, `shortname`, `view`, `chatlink`, `schatlink`) VALUES
(1, '测试企业', 'IT行业', '私营', '1-5人', '李先生', '人民路22号', NULL, NULL, NULL, '13888888888', NULL, '测试企业', 1744764012, '/assets/addons/zpwxsys/100x100.png', 3187, 1, 1, NULL, 1, '116.456270', '39.919990', NULL, 0, NULL, 1, '/assets/addons/zpwxsys/100x100.png', NULL, NULL, '13888888888', NULL, 0, '/assets/addons/zpwxsys/100x100.png', NULL, 1744764834, 1744764012, '测试企业', NULL, NULL, NULL);


INSERT INTO `__PREFIX__zpwxsys_companyaccount` (`id`, `uid`, `name`, `password`, `createtime`, `status`, `logintime`, `companyid`, `update_time`, `create_time`) VALUES
(1, NULL, 'test', 'e10adc3949ba59abbe56e057f20f883e', 1744764071, 1, NULL, 1, 1744764071, 1744764071);

INSERT INTO `__PREFIX__zpwxsys_companyrecord` (`id`, `mark`, `totaljobnum`, `totalnotenum`, `jobnum`, `notenum`, `createtime`, `companyid`, `create_time`, `update_time`, `topnum`, `totaltopnum`, `type`) VALUES
(1, '【后台充值】年卡会员', 50, 1000, 50, 1000, 1744764031, 1, NULL, NULL, 10, 10, 0);


INSERT INTO `__PREFIX__zpwxsys_task` (`id`, `title`, `jobid`, `companyid`, `createtime`, `status`, `num`, `money`, `content`) VALUES
(1, '测试任务', 1, 1, 1744765512, 1, 1, 10.0, '<p>测试任务</p>');


INSERT INTO `__PREFIX__zpwxsys_note` (`id`, `uid`, `jobtitle`, `name`, `sex`, `tel`, `birthday`, `education`, `express`, `address`, `email`, `currentstatus`, `worktype`, `jobcateid`, `money`, `areaid`, `content`, `createtime`, `status`, `avatarUrl`, `refreshtime`, `cityid`, `shareid`, `tid`, `send`, `fxstatus`, `service`, `company`, `star`, `cateid`, `skillname`, `marray`, `imgstr1`, `imgstr2`, `imgstr3`, `imgstr4`, `age`, `money1`, `money2`, `update_time`, `create_time`, `currentid`, `helplabid`, `cardnum`, `worktypeid`, `jobpriceid`, `ishidden`) VALUES
(1, 1744764180, '技术员', '李先生', 1, '13888888888', '1980', '本科以上', '3-5年', '盐城', NULL, '我目前已离职,可快速到岗', NULL, 19, '8000-10000元', 3187, '工作认真', 1744764180, 1, '', 1744764180, 1, NULL, NULL, 0, 0, NULL, NULL, 3, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1744764180, 1744764180, NULL, NULL, NULL, 1, 5, 0);

INSERT INTO `__PREFIX__zpwxsys_active` (`id`, `title`, `createtime`, `content`, `sort`, `hits`, `status`, `thumb`, `begintime`, `endtime`, `mainwork`, `money`, `companyid`) VALUES
(1, '春季招聘开始啦', NULL, '春季招聘开始啦', 1, NULL, 1, '/assets/addons/zpwxsys/640x400.png', '2025-04-01', ' 2035-10-28', '测试人力', '0.00', NULL);


INSERT INTO `__PREFIX__zpwxsys_job` (`id`, `jobtitle`, `joblabel`, `worktype`, `num`, `sex`, `age`, `content`, `vprice`, `companyid`, `createtime`, `sort`, `status`, `isrecommand`, `money`, `education`, `express`, `jobtype`, `dmoney`, `hits`, `endtime`, `toptime`, `noteprice`, `updatetime`, `videourl`, `managemoney`, `remoney`, `srate`, `mreate`, `intromoney`, `areaid`, `companyname`, `logo`, `age1`, `age2`, `special`, `jobpriceid`, `type`, `update_time`, `create_time`, `worktypeid`, `ischeck`) VALUES
(1, '测试职位', 2, 19, 1, 0, '不限', '测试职位', '0.00', 1, 1744764116, 1, 1, 1, '8000-10000元', '本科以上', '1-3年', 0, '0.00', NULL, NULL, 0, 0.00, 1744764116, NULL, '0.00', '0.00', 0.00, 0.00, '0.00', NULL, NULL, NULL, NULL, NULL, '五险,五险一金,补充医疗保险', 5, 1, 1744764116, 1744764116, 1, 1);


-- ============================================================
-- 职位 ID=11 及配套测试数据（报名者筛选页面验证用）
-- ============================================================

-- 职位 11：活动执行助理（status=1 已上架/审核通过, ischeck=1）
INSERT INTO `__PREFIX__zpwxsys_job` (`id`, `jobtitle`, `joblabel`, `worktype`, `num`, `sex`, `age`, `content`, `vprice`, `companyid`, `createtime`, `sort`, `status`, `isrecommand`, `money`, `education`, `express`, `jobtype`, `dmoney`, `hits`, `endtime`, `toptime`, `noteprice`, `updatetime`, `videourl`, `managemoney`, `remoney`, `srate`, `mreate`, `intromoney`, `areaid`, `companyname`, `logo`, `age1`, `age2`, `special`, `jobpriceid`, `type`, `update_time`, `create_time`, `worktypeid`, `ischeck`) VALUES
(11, '活动执行助理', 2, 3, 5, 0, '18-35', '<p>负责线下活动执行、物料搬运与现场协调。要求有责任心，能吃苦耐劳。</p>', '0.00', 1, 1769904000, 1, 1, 0, '150-200元/天', '大专', '无经验', 0, 180.00, 26, 1772582400, 0, 0.00, 1769904000, NULL, '0.00', '0.00', 0.00, 0.00, '0.00', 3187, '测试企业', NULL, 18, 35, '包午餐,交通补贴', 1, 2, 1769904000, 1769904000, 2, 1);

-- 补充 work_start_date 等扩展字段（ensureZwFields 动态添加的列）
-- 如果列已存在则 UPDATE，如果是全新库可在上面 INSERT 后执行
UPDATE `__PREFIX__zpwxsys_job` SET
  `work_start_date` = '2026/01/01',
  `work_end_date`   = '2026/02/28',
  `work_start_time`  = '8:00',
  `work_end_time`    = '17:00',
  `job_address`      = '盐城市亭湖区人民路22号',
  `requirements`     = '有责任心，能吃苦耐劳，服从现场安排',
  `benefit_tag1`     = '包午餐',
  `benefit_tag2`     = '交通补贴',
  `hourly_rate`      = 22.50
WHERE `id` = 11;


-- 测试用户（求职者 uid 101-103）
INSERT INTO `__PREFIX__zpwxsys_user` (`id`, `openid`, `nickname`, `create_time`, `update_time`, `avatarUrl`, `tel`, `coin_balance`, `point_balance`) VALUES
(101, 'oTest_zhangsan_001', '张三', 1769904000, 1769904000, NULL, '13800000101', 0.00, 0),
(102, 'oTest_lisi_002',     '李四', 1769904000, 1769904000, NULL, '13800000102', 0.00, 0),
(103, 'oTest_wangwu_003',   '王五', 1769904000, 1769904000, NULL, '13800000103', 0.00, 0);


-- 求职者简历（note 表，uid 对应上面的用户）
INSERT INTO `__PREFIX__zpwxsys_note` (`id`, `uid`, `jobtitle`, `name`, `sex`, `tel`, `birthday`, `education`, `express`, `address`, `email`, `currentstatus`, `worktype`, `jobcateid`, `money`, `areaid`, `content`, `createtime`, `status`, `avatarUrl`, `refreshtime`, `cityid`, `shareid`, `tid`, `send`, `fxstatus`, `service`, `company`, `star`, `cateid`, `skillname`, `marray`, `imgstr1`, `imgstr2`, `imgstr3`, `imgstr4`, `age`, `money1`, `money2`, `update_time`, `create_time`, `currentid`, `helplabid`, `cardnum`, `worktypeid`, `jobpriceid`, `ishidden`) VALUES
(101, 101, '活动策划', '张三', 1, '13800000101', '2001-05-15', '本科',   '1-3年',  '盐城', NULL, '我目前已离职,可快速到岗', NULL, 3, '3000-5000元', 3187, '擅长活动策划与执行', 1769904000, 1, NULL, 1769904000, 1, NULL, NULL, 0, 0, NULL, NULL, 4, NULL, '策划,执行,沟通', 0, NULL, NULL, NULL, NULL, '25', NULL, NULL, 1769904000, 1769904000, NULL, NULL, NULL, 2, 3, 0),
(102, 102, '服务员',   '李四', 2, '13800000102', '2004-03-22', '大专',   '无经验', '盐城', NULL, '应届毕业生',               NULL, 12, '2000-2500元', 3187, '在校期间多次参与志愿者活动', 1769904000, 1, NULL, 1769904000, 1, NULL, NULL, 0, 0, NULL, NULL, 3, NULL, NULL, 0, NULL, NULL, NULL, NULL, '22', NULL, NULL, 1769904000, 1769904000, NULL, NULL, NULL, 2, 1, 0),
(103, 103, '搬运工',   '王五', 1, '13800000103', '1998-11-08', '高中',   '3-5年',  '盐城', NULL, '我目前已离职,可快速到岗', NULL, 20, '5000-8000元', 3187, '有5年物流搬运经验', 1769904000, 1, NULL, 1769904000, 1, NULL, NULL, 0, 0, NULL, NULL, 2, NULL, '搬运,体力好', 0, NULL, NULL, NULL, NULL, '28', NULL, NULL, 1769904000, 1769904000, NULL, NULL, NULL, 3, 4, 0);


-- 报名记录（3人报名职位11，status=0 待处理）
INSERT INTO `__PREFIX__zpwxsys_jobrecord` (`id`, `uid`, `jobid`, `companyid`, `createtime`, `status`, `invatetime`, `islook`, `shareid`, `type`, `ismsgtpl`, `agentuid`, `taskid`, `signed_in`) VALUES
(1101, 101, 11, 1, 1770076800, 0, NULL, 0, NULL, 0, 0, NULL, NULL, 0),
(1102, 102, 11, 1, 1770163200, 0, NULL, 0, NULL, 0, 0, NULL, NULL, 0),
(1103, 103, 11, 1, 1770249600, 0, NULL, 0, NULL, 0, 0, NULL, NULL, 0);


-- 评分数据（用于小红花显示）
INSERT INTO `__PREFIX__zpwxsys_comment` (`id`, `content`, `uid`, `companyid`, `pid`, `type`, `update_time`, `create_time`, `score`, `piclist`) VALUES
(1001, '工作认真负责', 101, 1, NULL, 0, 1769990400, 1769990400, 4, ''),
(1002, '沟通能力强',   101, 1, NULL, 0, 1770076800, 1770076800, 4, ''),
(1003, '态度端正',     102, 1, NULL, 0, 1769990400, 1769990400, 3, ''),
(1004, '需要多加练习', 103, 1, NULL, 0, 1769990400, 1769990400, 2, '');
