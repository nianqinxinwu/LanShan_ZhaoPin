# 章鱼外快 — PHP 服务器接口文档

> 自动生成于 2026-02-22，基于 `addons/zpwxsys/` 源码分析

---

## 目录

- [1. 架构概述](#1-架构概述)
- [2. 认证机制](#2-认证机制)
- [3. 响应格式](#3-响应格式)
- [4. 接口清单](#4-接口清单)
  - [4.1 Token — 登录认证](#41-token--登录认证)
  - [4.2 Login — 用户登录/注册](#42-login--用户登录注册)
  - [4.3 Sysinit — 系统初始化](#43-sysinit--系统初始化)
  - [4.4 Banner — 广告轮播](#44-banner--广告轮播)
  - [4.5 City — 城市列表](#45-city--城市列表)
  - [4.6 Job — 岗位管理](#46-job--岗位管理)
  - [4.7 Jobpart — 兼职岗位](#47-jobpart--兼职岗位)
  - [4.8 Note — 简历管理](#48-note--简历管理)
  - [4.9 Company — 企业管理](#49-company--企业管理)
  - [4.10 Task — 悬赏任务](#410-task--悬赏任务)
  - [4.11 Taskrecord — 任务记录](#411-taskrecord--任务记录)
  - [4.12 Chat — 群聊系统](#412-chat--群聊系统)
  - [4.13 Order — 订单管理](#413-order--订单管理)
  - [4.14 Pay — 支付处理](#414-pay--支付处理)
  - [4.15 Coin — 章鱼币/积分](#415-coin--章鱼币积分)
  - [4.16 Usercard — 名片系统](#416-usercard--名片系统)
  - [4.17 Agent — 代理/经纪人](#417-agent--代理经纪人)
  - [4.18 Fxsys — 分销系统](#418-fxsys--分销系统)
  - [4.19 News — 资讯文章](#419-news--资讯文章)
  - [4.20 Notice — 公告通知](#420-notice--公告通知)
  - [4.21 Sysmsg — 系统消息](#421-sysmsg--系统消息)
  - [4.22 Active — 招聘活动](#422-active--招聘活动)
  - [4.23 Baoming — 报名](#423-baoming--报名)
  - [4.24 Lookrole — 查看简历套餐](#424-lookrole--查看简历套餐)
  - [4.25 Lookrolerecord — 查看简历记录](#425-lookrolerecord--查看简历记录)
  - [4.26 Companyrole — 企业会员套餐](#426-companyrole--企业会员套餐)
  - [4.27 Companyrecord — 企业资源记录](#427-companyrecord--企业资源记录)
  - [4.28 Ploite — 平台通知/投诉](#428-ploite--平台通知投诉)
- [5. 数据库表结构](#5-数据库表结构)
  - [5.1 用户与企业表](#51-用户与企业表)
  - [5.2 岗位与简历表](#52-岗位与简历表)
  - [5.3 记录与交易表](#53-记录与交易表)
  - [5.4 财务表](#54-财务表)
  - [5.5 任务表](#55-任务表)
  - [5.6 配置与内容表](#56-配置与内容表)
  - [5.7 群聊表](#57-群聊表)
  - [5.8 其他表](#58-其他表)
- [6. 服务层架构](#6-服务层架构)
- [7. 接口与数据表关联矩阵](#7-接口与数据表关联矩阵)

---

## 1. 架构概述

```
请求流: HTTP → public/index.php → ThinkPHP Router → Controller/Action → Model → JSON Response
```

- **后端框架**: ThinkPHP 5 + FastAdmin
- **业务插件路径**: `addons/zpwxsys/`
- **API 控制器**: `addons/zpwxsys/controller/v1/` (28 个控制器, 272+ 方法)
- **Model 层**: `addons/zpwxsys/model/` (54+ 模型)
- **Service 层**: `addons/zpwxsys/service/` (15 个服务类)
- **数据库前缀**: `fa_` (实际表名如 `fa_zpwxsys_job`)
- **接口基础路径**: `{domain}/addons/zpwxsys/v1.{Controller}/{method}`

---

## 2. 认证机制

### 2.1 用户 Token (User Token)

| 项目 | 说明 |
|------|------|
| 获取方式 | `v1.Token/getToken` 传入微信 `code` |
| 传递方式 | HTTP Header: `token: {token_value}` |
| 有效期 | 7200 秒 (2 小时) |
| 缓存结构 | `{"openid":"...", "session_key":"...", "uid":123, "scope":1}` |

### 2.2 企业 Token (Company Token)

| 项目 | 说明 |
|------|------|
| 获取方式 | `v1.Company/doLogin` 传入企业账号密码 |
| 传递方式 | POST 参数: `ctoken` |
| 缓存结构 | `{"cscope":1, "companyid":456}` |

### 2.3 权限范围 (Scope)

| 级别 | 值 | 说明 |
|------|----|------|
| ExclusiveScope | 1 | 普通用户 — 仅操作自身数据 |
| PrimaryScope | ≥1 | 企业用户 — 可操作企业范围数据 |
| SuperScope | 3 | 超级管理员 — 可通过 GET `uid` 参数代理操作 |

### 2.4 Controller 鉴权方法

```php
$this->checkExclusiveScope()  // 验证 scope == User
$this->checkPrimaryScope()    // 验证 scope >= User
$this->checkSuperScope()      // 验证 scope == Super
```

---

## 3. 响应格式

所有接口统一返回 JSON:

```json
{
  "status": 1,          // 1=成功, 0=失败, 3=特殊提示
  "msg": "请求数据正常",  // 消息文本
  "data": {}            // 业务数据（可选）
}
```

异常响应:
- `401` — Token 缺失或失效 (`TokenException`)
- `403` — 权限不足 (`ForbiddenException`)
- `400` — 参数验证失败 (`ParameterException`)

---

## 4. 接口清单

### 4.1 Token — 登录认证

**控制器**: `controller/v1/Token.php`（不继承 BaseController）

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getToken` | POST | `code` (必填) | 微信登录, 换取用户 token | `zpwxsys_user`, `zpwxsys_lookrole` |
| `getAppToken` | GET | `ac`, `se` (URL参数) | 第三方应用 Token | `zpwxsys_thirdapp` |
| `verifyToken` | POST | `token` (必填) | 验证 token 有效性 | — |

---

### 4.2 Login — 用户登录/注册

**控制器**: `controller/v1/Login.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `checkBind` | GET/POST | token(header) | 检查用户是否已绑定手机 | `zpwxsys_user` |
| `userLogin` | POST | `tel`, token | 手机号登录 | `zpwxsys_user` |
| `userRegister` | POST | `tel`, `nickname`, token | 新用户注册 | `zpwxsys_user` |
| `userSysinit` | GET/POST | token | 获取用户初始化信息 | `zpwxsys_user` |

---

### 4.3 Sysinit — 系统初始化

**控制器**: `controller/v1/Sysinit.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getSysinit` | POST | `city` | 首页数据: 轮播/导航/岗位/企业/公告 | `zpwxsys_city`, `zpwxsys_adv`, `zpwxsys_nav`, `zpwxsys_job`, `zpwxsys_company`, `zpwxsys_sysinit`, `zpwxsys_notice`, `zpwxsys_worktype`, `zpwxsys_oldhouse` |
| `getUserinit` | GET/POST | token | 用户中心数据 | `zpwxsys_user`, `zpwxsys_jobrecord`, `zpwxsys_sysmsg`, `zpwxsys_money`, `zpwxsys_coin_record` |
| `getMoneyRecord` | GET/POST | token | 用户提现/佣金记录 | `zpwxsys_money` |
| `getUserMoney` | GET/POST | token | 用户余额/提现 | `zpwxsys_money` |
| `getPhone` | POST | `encryptedData`, `iv` | 解密微信手机号 | — |
| `updateUsertel` | POST | `tel`, token | 更新用户手机 | `zpwxsys_user` |
| `getWxUserInfo` | GET/POST | token | 获取用户信息(含小程序码) | `zpwxsys_user`, `zpwxsys_sysinit` |
| `updateUser` | POST | `nickname`, `avatarUrl`, `tel` | 更新用户资料 | `zpwxsys_user` |

---

### 4.4 Banner — 广告轮播

**控制器**: `controller/v1/Banner.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getBanner` | GET/POST | — | 获取广告轮播列表 | `zpwxsys_adv` |

---

### 4.5 City — 城市列表

**控制器**: `controller/v1/City.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getCityList` | GET/POST | — | 获取热门城市 + 全部城市 | `zpwxsys_city` |

---

### 4.6 Job — 岗位管理

**控制器**: `controller/v1/Job.php`（1400+ 行, 核心控制器）

#### 岗位列表/搜索

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getJoblist` | POST | `cityid`, `areaid`, `jobcateid`, `priceid`, `type`, `settle_type`, `sex`, `keyword`, `sortby`, `page` | 岗位列表(支持筛选排序) | `zpwxsys_job`, `zpwxsys_area`, `zpwxsys_jobcate`, `zpwxsys_jobprice`, `zpwxsys_worktype` |
| `getInitJob` | POST | `cityid` | 获取筛选条件初始化数据 | `zpwxsys_area`, `zpwxsys_jobcate`, `zpwxsys_worktype` |
| `getJobIndexList` | POST | `cityid`, `worktype` | 首页岗位列表(按工种) | `zpwxsys_job` |
| `getSearchJobList` | POST | `cityid`, `cateid`, `priceid`, `edu`, `express`, `sex`, `special` | 搜索岗位列表 | `zpwxsys_job` |
| `getJobListCount` | POST | 同上 | 搜索结果计数 | `zpwxsys_job` |
| `getmatchjoblist` | POST | `city`, `cityid`, `page`, token | 匹配推荐岗位 | `zpwxsys_note`, `zpwxsys_city`, `zpwxsys_jobcate`, `zpwxsys_job` |
| `getNearjoblist` | POST | `latitude`, `longitude`, `cityid`, `areaid`, `page` | 附近岗位(LBS) | `zpwxsys_job`, `zpwxsys_city`, `zpwxsys_area`, `zpwxsys_worktype` |

#### 岗位详情

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getJobdetail` | GET | `id` (必填) | 旧版岗位详情 | `zpwxsys_job`, `zpwxsys_jobsave` |
| `getZwJobdetail` | GET | `id` (必填) | **新版岗位详情**(含发布者信息/报名人数) | `zpwxsys_job`, `zpwxsys_jobsave`, `zpwxsys_jobrecord`, `zpwxsys_company` |
| `getNavDetail` | POST | `id` (必填) | 导航详情 | `zpwxsys_nav` |
| `getqrcodejob` | GET | `id` (必填) | 获取岗位小程序码 | `zpwxsys_job`, `zpwxsys_sysinit` |

#### 岗位操作(求职者)

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `sendJob` | POST | `jobid`, `companyid`, token | 报名/投递简历 | `zpwxsys_note`, `zpwxsys_job`, `zpwxsys_jobrecord`, `zpwxsys_company`, `zpwxsys_jobcate`, `zpwxsys_sysmsg` |
| `sendCompanyJob` | POST | `taskid`, `noteid`, token | 企业任务投递 | `zpwxsys_task`, `zpwxsys_note`, `zpwxsys_jobrecord`, `zpwxsys_job` |
| `jobSave` | POST | `jobid`, `companyid`, token | 收藏/取消收藏岗位 | `zpwxsys_jobsave` |
| `saveAskjob` | POST | `jobid`, `content`, token | 岗位咨询提问 | `zpwxsys_job`, `zpwxsys_askjob` |
| `confirmWork` | POST | `id` (jobrecord), token | 确认上岗 | `zpwxsys_jobrecord` |
| `doSignIn` | POST | `id` (jobrecord), token | 签到打卡 | `zpwxsys_jobrecord`, `zpwxsys_coin_record` |
| `doSignOut` | POST | `id` (jobrecord), token | 签退 | `zpwxsys_jobrecord` |

#### 求职者记录

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `myFindjob` | POST | `status`, `page`, token | 我的投递记录 | `zpwxsys_jobrecord` |
| `mySaveJob` | POST | `status`, `page`, token | 我的收藏 | `zpwxsys_jobsave` |
| `cancleSave` | POST | `id`, token | 取消收藏 | `zpwxsys_jobsave` |

#### 岗位发布/管理(企业)

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `pubJobInit` | POST | `city`, token | 发布岗位初始化(获取分类/区域) | `zpwxsys_jobcate`, `zpwxsys_city`, `zpwxsys_area`, `zpwxsys_jobprice`, `zpwxsys_worktype` |
| `selectJobInit` | POST | `city`, token | 筛选初始化数据 | `zpwxsys_jobcate`, `zpwxsys_jobprice` |
| `saveJob` | POST | 岗位字段 + `ctoken` | 发布岗位(旧版) | `zpwxsys_job`, `zpwxsys_sensitive_word`, `zpwxsys_companyrecord` |
| `zwSaveJob` | POST | 岗位字段 + `ctoken` | **发布岗位(新版, 含新字段)** | `zpwxsys_job`, `zpwxsys_sensitive_word`, `zpwxsys_companyrecord` |
| `updateJob` | POST | 更新字段 + `ctoken` | 更新岗位信息 | `zpwxsys_job`, `zpwxsys_sensitive_word` |
| `auditJob` | POST | `id`, `action`, SuperScope | 审核岗位(管理员) | `zpwxsys_job` |

---

### 4.7 Jobpart — 兼职岗位

**控制器**: `controller/v1/Jobpart.php`

> 与 Job 控制器功能类似，针对兼职岗位的独立接口

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getJoblist` | POST | `city`, `areaid`, `jobcateid`, `priceid`, `type`, `cityid`, `page` | 兼职岗位列表 | `zpwxsys_jobpart`, `zpwxsys_city`, `zpwxsys_area`, `zpwxsys_jobcate` |
| `getSearchJobList` | POST | 筛选参数 | 搜索兼职 | `zpwxsys_jobpart` |
| `getJobListCount` | POST | 筛选参数 | 搜索计数 | `zpwxsys_jobpart` |
| `getJobdetail` | GET | `id` | 兼职详情 | `zpwxsys_jobpart`, `zpwxsys_jobsave` |
| `sendJob` | POST | `jobid`, `companyid`, token | 兼职报名 | `zpwxsys_note`, `zpwxsys_jobrecord` |
| `jobSave` | POST | `jobid`, `companyid`, token | 收藏兼职 | `zpwxsys_jobsave` |
| `saveAskjob` | POST | `jobid`, `content`, token | 兼职咨询 | `zpwxsys_jobpart`, `zpwxsys_askjob` |
| `myFindjob` | POST | `status`, token | 我的兼职记录 | `zpwxsys_jobrecord` |
| `mySaveJob` | POST | token | 我的兼职收藏 | `zpwxsys_jobsave` |
| `cancleSave` | POST | `id`, token | 取消兼职收藏 | `zpwxsys_jobsave` |
| `pubJobInit` | POST | `city`, token | 兼职发布初始化 | `zpwxsys_jobcate`, `zpwxsys_city`, `zpwxsys_area`, `zpwxsys_jobprice` |
| `selectJobInit` | POST | `city`, token | 兼职筛选初始化 | `zpwxsys_jobcate`, `zpwxsys_jobprice` |
| `saveJob` | POST | 岗位字段 + token | 发布兼职 | `zpwxsys_jobpart` |
| `updateJob` | POST | 更新字段 + `ctoken` | 更新兼职 | `zpwxsys_jobpart` |

---

### 4.8 Note — 简历管理

**控制器**: `controller/v1/Note.php`（779 行）

#### 简历列表/搜索

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getNotelist` | GET | `cityid`, `areaid`, `eduid`, `cateid`, `express`, `sex`, `money`, `page` | 简历列表 | `zpwxsys_note`, `zpwxsys_area`, `zpwxsys_jobcate` |
| `getMatchNotelist` | POST | `city`, `jobid` | 匹配某岗位的简历 | `zpwxsys_job`, `zpwxsys_city`, `zpwxsys_note` |
| `getNoteListCount` | POST | 筛选参数 | 简历搜索计数 | `zpwxsys_note` |
| `getAgentNotelist` | POST | `page`, token | 代理的简历列表 | `zpwxsys_note` |

#### 简历详情

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getNotedetail` | GET | `id`, `ctoken`(可选) | 简历详情(含查看权限检查) | `zpwxsys_note`, `zpwxsys_looknote`, `zpwxsys_lookcompanyrecord`, `zpwxsys_area`, `zpwxsys_edu`, `zpwxsys_express`, `zpwxsys_worktype`, `zpwxsys_helplab`, `zpwxsys_current` |

#### 简历发布/编辑

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getPubnoteinit` | POST | `city`, token | 发布简历初始化数据 | `zpwxsys_jobcate`, `zpwxsys_city`, `zpwxsys_area`, `zpwxsys_current`, `zpwxsys_worktype`, `zpwxsys_helplab`, `zpwxsys_jobprice`, `zpwxsys_note`, `zpwxsys_edu`, `zpwxsys_express` |
| `getPubeduinit` | GET/POST | token | 获取学历列表 | `zpwxsys_edu` |
| `getPubexpressinit` | GET/POST | token | 获取工作经历列表 | `zpwxsys_express` |
| `saveNote` | POST | 简历字段 + token | 保存简历基本信息 | `zpwxsys_note`, `zpwxsys_user` |
| `saveContent` | POST | 内容字段 + token | 保存简历详细内容 | `zpwxsys_note` |
| `saveEdu` | POST | 学历数据 + token | 保存教育经历 | `zpwxsys_edu` |
| `saveExpress` | POST | 经验数据 + token | 保存工作经历 | `zpwxsys_express` |
| `checkNote` | POST | token | 检查是否已有简历 | `zpwxsys_note` |
| `noteRefresh` | POST | token | 刷新简历(提高排名) | `zpwxsys_note` |
| `uploadImg` | POST | `file`(multipart) | 上传简历图片 | — |

#### 简历操作

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `myLookNote` | GET/POST | token | 我查看过的简历 | `zpwxsys_looknote` |
| `sendInvatejob` | POST | `noteid`, `ctoken` | 邀请面试 | `zpwxsys_invaterecord`, `zpwxsys_note`, `zpwxsys_company`, `zpwxsys_sysmsg` |

---

### 4.9 Company — 企业管理

**控制器**: `controller/v1/Company.php`（2600+ 行, 最大控制器）

#### 企业列表/详情

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getCompanylist` | POST | `cityid`, `areaid`, `jobcateid`, `priceid`, `type`, `page` | 企业列表 | `zpwxsys_company`, `zpwxsys_city`, `zpwxsys_area`, `zpwxsys_jobcate`, `zpwxsys_job` |
| `getSearchCompany` | POST | 搜索筛选条件 | 搜索企业 | `zpwxsys_company` |
| `getCompanydetail` | POST | `id` (必填) | 企业详情(含岗位/评论) | `zpwxsys_company`, `zpwxsys_job`, `zpwxsys_comment` |
| `getCompanyuserdetail` | POST | `uid` | 企业用户详情 | `zpwxsys_user`, `zpwxsys_note` |
| `getAgentCompanylist` | POST | `page`, token | 代理关联企业列表 | `zpwxsys_company` |

#### 企业注册/管理

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getCompanyregisterinit` | GET/POST | — | 企业注册初始化 | `zpwxsys_city`, `zpwxsys_jobcate` |
| `saveCompany` | POST | 企业字段 + 上传 | 新增企业 | `zpwxsys_company`, `zpwxsys_companyaccount` |
| `updateCompany` | POST | 企业更新字段 + 上传 | 更新企业信息 | `zpwxsys_company` |
| `checkLogin` | POST | — | 检查企业登录状态 | — |
| `isLogin` | POST | `ctoken` | 验证企业token | — |
| `doLogin` | POST | 企业账号密码 | 企业登录 | `zpwxsys_companyaccount` |

#### 企业中心

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `companyCenter` | POST | `ctoken` | 企业工作台首页数据 | `zpwxsys_job`, `zpwxsys_jobrecord`, `zpwxsys_comment`, `zpwxsys_companyrecord`, `zpwxsys_coin_record`, `zpwxsys_companyrole` |
| `companyJob` | POST | `ctoken`, `type`, `page` | 企业已发布岗位 | `zpwxsys_job`, `zpwxsys_jobrecord` |
| `companyNote` | POST | `ctoken`, `page`, `type` | 企业收到的简历 | `zpwxsys_note`, `zpwxsys_job`, `zpwxsys_comment` |
| `inviteNote` | POST | `ctoken`, `noteid` | 查看邀约简历详情 | `zpwxsys_note` |
| `batchApplicant` | POST | `ctoken`, `type` | 批量查看应聘统计 | `zpwxsys_jobrecord` |

#### 岗位管理(企业)

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `cancleJob` | POST | `id`, `ctoken` | 取消/下架岗位 | `zpwxsys_job` |
| `topJob` | POST | `id`, `ctoken` | 置顶岗位 | `zpwxsys_job`, `zpwxsys_companyrecord` |
| `spreadJob` | POST | `id`, `ctoken` | 急聘推广岗位 | `zpwxsys_job`, `zpwxsys_companyrecord` |
| `doCompanyendtime` | POST | `id`, `time`, `ctoken` | 延长岗位有效期 | `zpwxsys_job` |
| `upJob` | POST | 更新字段 + `ctoken` | 更新岗位 | `zpwxsys_job` |

#### 招聘流程管理

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `doAgree` | POST | `id`(jobrecord), `ctoken` | 同意报名 | `zpwxsys_jobrecord`, `zpwxsys_sysmsg` |
| `doPass` | POST | `id`(jobrecord), `ctoken` | 通过初审 | `zpwxsys_jobrecord`, `zpwxsys_sysmsg` |
| `doTypein` | POST | `id`(jobrecord), `ctoken` | 录用 | `zpwxsys_jobrecord`, `zpwxsys_sysmsg` |
| `doTry` | POST | `id`(jobrecord), `ctoken` | 试岗 | `zpwxsys_jobrecord`, `zpwxsys_sysmsg` |
| `doDone` | POST | `id`(jobrecord), `ctoken`, token | 完成(评价) | `zpwxsys_jobrecord`, `zpwxsys_comment`, `zpwxsys_sysmsg` |
| `doInvatePass` | POST | `id`(jobrecord), `ctoken` | 邀约-通过 | `zpwxsys_jobrecord`, `zpwxsys_sysmsg` |
| `doInvateTypein` | POST | `id`(jobrecord), `ctoken` | 邀约-录用 | `zpwxsys_jobrecord`, `zpwxsys_sysmsg` |
| `doInvateTry` | POST | `id`(jobrecord), `ctoken` | 邀约-试岗 | `zpwxsys_jobrecord`, `zpwxsys_sysmsg` |
| `doInvateDone` | POST | `id`(jobrecord), `ctoken` | 邀约-完成 | `zpwxsys_jobrecord`, `zpwxsys_comment`, `zpwxsys_sysmsg` |

#### 评论/关注

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `saveComment` | POST | 评论字段 + `ctoken` | 发表评论 | `zpwxsys_comment` |
| `delComment` | POST | `id`(comment), `ctoken` | 删除评论 | `zpwxsys_comment` |
| `getMycomment` | POST | `ctoken`, `page` | 我的评论列表 | `zpwxsys_comment` |
| `gzCompany` | POST | `companyid`, token | 关注企业 | `zpwxsys_mygz` |
| `myGzCompany` | GET/POST | token | 我关注的企业 | `zpwxsys_mygz` |

#### 签到管理

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getSigninList` | POST | `ctoken`, `jobid`, `page` | 签到人员列表 | `zpwxsys_jobrecord` |
| `getSigninDetail` | POST | `ctoken`, `jobid`, `noteid` | 签到详情 | `zpwxsys_jobrecord` |

#### 其他

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `mysendorder` | POST | `type`, `pid`, `num`, `ctoken` | 购买企业服务 | `zpwxsys_order` |
| `sendorderdetail` | POST | `id`, `ctoken` | 服务订单详情 | `zpwxsys_order` |
| `sendagentorderdetail` | POST | `id` | 代理订单详情 | `zpwxsys_order` |
| `cancleTask` | POST | `id`, `ctoken` | 取消悬赏任务 | `zpwxsys_task` |
| `upTask` | POST | `id`, `ctoken` | 上架悬赏任务 | `zpwxsys_task` |
| `uploadImg` | POST | `file`(multipart) | 上传企业图片 | — |

---

### 4.10 Task — 悬赏任务

**控制器**: `controller/v1/Task.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getTasklist` | POST | `city`, `cityid`, `houseareaid`, `housetype` | 悬赏任务列表 | `zpwxsys_task`, `zpwxsys_city`, `zpwxsys_area`, `zpwxsys_jobcate` |
| `getTaskdetail` | POST | `id` (必填) | 任务详情(含计算总奖金) | `zpwxsys_task` |
| `getMyTasklist` | POST | `ctoken`, token | 我的任务列表 | `zpwxsys_task`, `zpwxsys_taskrecord` |
| `pubTaskInit` | POST | `ctoken`, token | 发布任务初始化 | `zpwxsys_job` |
| `saveTask` | POST | 任务字段 + `ctoken` | 发布任务 | `zpwxsys_task` |
| `updateTask` | POST | 更新字段 + `ctoken` | 更新任务 | `zpwxsys_task` |
| `sendJob` | POST | `jobid`, `companyid`, token | 任务报名 | `zpwxsys_jobrecord` |
| `jobSave` | POST | `jobid`, `companyid`, token | 收藏任务 | `zpwxsys_jobsave` |
| `myFindjob` | POST | token | 我的任务参与记录 | `zpwxsys_jobrecord` |
| `mySaveJob` | POST | token | 我收藏的任务 | `zpwxsys_jobsave` |

---

### 4.11 Taskrecord — 任务记录

**控制器**: `controller/v1/Taskrecord.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getTaskRecordList` | POST | `page`, token | 任务参与记录列表 | `zpwxsys_taskrecord` |
| `saveTaskRecord` | POST | 任务参数 + token | 报名参与任务 | `zpwxsys_taskrecord` |

---

### 4.12 Chat — 群聊系统

**控制器**: `controller/v1/Chat.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getRule` | GET/POST | — | 获取群聊公约 | `zpwxsys_chat_rule` |
| `agreeRule` | POST | `groupid` | 同意群聊公约 | `zpwxsys_chat_member` |
| `createGroup` | POST | `jobid`, `max_member`, `ctoken` | 创建岗位群聊 | `zpwxsys_job`, `zpwxsys_chat_group`, `zpwxsys_chat_member`, `zpwxsys_chat_message` |
| `chatList` | POST | `page` | 群聊列表 | `zpwxsys_chat_group` |
| `groupDetail` | POST | `groupid` | 群聊详情 | `zpwxsys_chat_group`, `zpwxsys_chat_member` |
| `messages` | POST | `groupid`, `lastId` | 获取群聊消息 | `zpwxsys_chat_member`, `zpwxsys_chat_message` |
| `send` | POST | `groupid`, `content` | 发送消息(含敏感词过滤) | `zpwxsys_chat_member`, `zpwxsys_sensitive_word`, `zpwxsys_chat_message`, `zpwxsys_chat_group` |
| `editNotice` | POST | `groupid`, `notice` | 编辑群公告 | `zpwxsys_chat_member`, `zpwxsys_chat_group`, `zpwxsys_chat_message` |
| `muteMember` | POST | `groupid`, `targetUid`, `mute` | 禁言/解禁成员 | `zpwxsys_chat_member` |
| `kickMember` | POST | `groupid`, `targetUid` | 踢出成员 | `zpwxsys_chat_member` |
| `addMember` | POST | `groupid`, `targetUid` | 添加成员 | `zpwxsys_chat_member`, `zpwxsys_chat_group` |
| `memberList` | POST | `groupid` | 群成员列表 | `zpwxsys_chat_member` |

---

### 4.13 Order — 订单管理

**控制器**: `controller/v1/Order.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `placeOrder` | POST | `products/a`(数组), token | 下单(商品订单) | `zpwxsys_order` |
| `lookRoleOrder` | POST | `type`, `pid` | 简历包订单 | `zpwxsys_lookrole`, `zpwxsys_order` |
| `companyroleOrder` | POST | `type`, `pid`, `ctoken` | 企业会员订单 | `zpwxsys_companyrole`, `zpwxsys_order` |
| `activeOrder` | POST | `type`, `pid`, `companyid` | 活动订单 | `zpwxsys_active`, `zpwxsys_order` |
| `getDetail` | GET | `id`(URL), token | 订单详情 | `zpwxsys_order` |
| `delivery` | POST | `id`(URL), token | 确认发货 | `zpwxsys_order` |

---

### 4.14 Pay — 支付处理

**控制器**: `controller/v1/Pay.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getPreOrder` | POST | `id` (必填), token | 获取微信支付预支付信息 | `zpwxsys_order` |
| `getPreCompanyroleOrder` | POST | `id`(URL) | 企业会员支付预支付信息 | `zpwxsys_order` |
| `redirectNotify` | POST | — | 微信支付回调(跳转) | `zpwxsys_order` |
| `notifyConcurrency` | POST | — | 微信支付并发回调 | `zpwxsys_order` |
| `receiveNotify` | POST | — | 微信支付通知接收 | `zpwxsys_order` |

---

### 4.15 Coin — 章鱼币/积分

**控制器**: `controller/v1/Coin.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getBalance` | GET/POST | token | 获取章鱼币/积分余额 | `zpwxsys_coin_record` |
| `recharge` | POST | `amount` (必填) | 章鱼币充值 | `zpwxsys_coin_record` |
| `getCoinRecords` | GET/POST | `page` | 章鱼币流水记录 | `zpwxsys_coin_record` |
| `getPointRecords` | GET/POST | `page` | 积分流水记录 | `zpwxsys_coin_record` |
| `consume` | POST | `amount`, `action`, `related_id`, `remark` | 消费章鱼币 | `zpwxsys_coin_record` |
| `exchangePoints` | POST | `points` (必填) | 积分兑换章鱼币 | `zpwxsys_coin_record` |
| `getRechargeOptions` | GET/POST | — | 获取充值档位选项 | — |

---

### 4.16 Usercard — 名片系统

**控制器**: `controller/v1/Usercard.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getApplicantCard` | POST | `uid`(可选), token | 求职者名片 | `zpwxsys_user`, `zpwxsys_note`, `zpwxsys_jobrecord`, `zpwxsys_comment` |
| `getPublisherCard` | POST | `companyid`, `ctoken` | 发布者名片 | `zpwxsys_company`, `zpwxsys_user`, `zpwxsys_comment`, `zpwxsys_job` |
| `saveApplicantCard` | POST | `school`, `major` | 保存求职者名片信息 | `zpwxsys_user` |
| `savePublisherCard` | POST | `business_license`, `companyname`, `ctoken` | 保存发布者名片 | `zpwxsys_company` |
| `unlockPhone` | POST | `target_uid`, `target_type`, `companyid` | 解锁电话号码(消耗章鱼币) | `zpwxsys_coin_record`, `zpwxsys_company`, `zpwxsys_note`, `zpwxsys_user` |

---

### 4.17 Agent — 代理/经纪人

**控制器**: `controller/v1/Agent.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `myAgentTeam` | GET/POST | token | 我的代理团队 | `zpwxsys_user` |
| `agentInit` | GET/POST | token | 代理中心初始化 | `zpwxsys_agent`, `zpwxsys_money` |
| `checkAgent` | GET/POST | token | 检查代理身份 | `zpwxsys_agent` |
| `Getfxrecord` | POST | token | 分销佣金记录 | `zpwxsys_fxrecord`, `zpwxsys_note`, `zpwxsys_company` |
| `agentFxrecord` | POST | `page`, token | 代理推荐用户列表 | `zpwxsys_agent`, `zpwxsys_user`, `zpwxsys_note` |
| `saveAgent` | POST | 代理参数 + token | 申请成为代理 | `zpwxsys_agent`, `zpwxsys_user` |

---

### 4.18 Fxsys — 分销系统

**控制器**: `controller/v1/Fxsys.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `fxBinduser` | POST | `tid`, token | 绑定分销上级 | `zpwxsys_user` |
| `rectBinduser` | POST | `rectid`, token | 绑定推荐人 | `zpwxsys_user` |

---

### 4.19 News — 资讯文章

**控制器**: `controller/v1/News.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getNewslist` | POST | `page` | 资讯列表 | `zpwxsys_news`, `zpwxsys_cate` |
| `getnewslistByCateid` | POST | `cateid`, `page` | 按分类获取资讯 | `zpwxsys_news` |
| `getNewsdetail` | POST | `id` (必填) | 资讯详情 | `zpwxsys_news` |
| `getFxRule` | GET/POST | — | 获取分销规则 | `zpwxsys_sysinit` |

---

### 4.20 Notice — 公告通知

**控制器**: `controller/v1/Notice.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getNoticedetail` | POST | `id` (必填) | 公告详情 | `zpwxsys_notice` |

---

### 4.21 Sysmsg — 系统消息

**控制器**: `controller/v1/Sysmsg.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getSysmsgList` | POST | `page`, `readstatus` | 系统消息列表 | `zpwxsys_sysmsg` |
| `updateSysmsg` | POST | token | 批量标记已读 | `zpwxsys_sysmsg` |
| `markAsRead` | POST | `id` (必填) | 单条标记已读 | `zpwxsys_sysmsg` |

---

### 4.22 Active — 招聘活动

**控制器**: `controller/v1/Active.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getActivelist` | POST | `page` | 活动列表 | `zpwxsys_active` |
| `getActivedetail` | POST | `id` (必填) | 活动详情 | `zpwxsys_active`, `zpwxsys_activerecord` |
| `checkActiveRecord` | POST | `aid`, `ctoken` | 检查/创建活动参与记录 | `zpwxsys_active`, `zpwxsys_activerecord` |

---

### 4.23 Baoming — 报名

**控制器**: `controller/v1/Baoming.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `saveBaoming` | POST | `jobid`, token | 提交报名 | `zpwxsys_baoming` |

---

### 4.24 Lookrole — 查看简历套餐

**控制器**: `controller/v1/Lookrole.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getLookRoleList` | GET/POST | token | 简历查看套餐列表 + 购买记录 | `zpwxsys_lookrole`, `zpwxsys_lookrolerecord` |

---

### 4.25 Lookrolerecord — 查看简历记录

**控制器**: `controller/v1/Lookrolerecord.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `dealLookroleRecord` | POST | `noteid`, token | 消耗查看额度查看简历 | `zpwxsys_lookrecord`, `zpwxsys_lookrolerecord` |

---

### 4.26 Companyrole — 企业会员套餐

**控制器**: `controller/v1/Companyrole.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `getCompanyRoleList` | POST | `ctoken` | 企业会员套餐列表 + 资源记录 | `zpwxsys_companyrole`, `zpwxsys_companyrecord` |

---

### 4.27 Companyrecord — 企业资源记录

**控制器**: `controller/v1/Companyrecord.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `isLogin` | POST | `ctoken` | 验证企业登录 | — |
| `dealLookRecord` | POST | `noteid`, `ctoken` | 消耗企业额度查看简历 | `zpwxsys_note`, `zpwxsys_lookcompanyrecord`, `zpwxsys_companyrecord` |

---

### 4.28 Ploite — 平台通知/投诉

**控制器**: `controller/v1/Ploite.php`

| 方法 | HTTP | 参数 | 说明 | 关联表 |
|------|------|------|------|--------|
| `savePloite` | POST | 投诉参数 + token | 提交投诉/反馈 | `zpwxsys_ploite` |

---

## 5. 数据库表结构

> 所有表名带 `fa_` 前缀（如 `fa_zpwxsys_job`），使用 InnoDB 引擎 + utf8mb4 字符集

### 5.1 用户与企业表

#### zpwxsys_user

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 用户ID |
| `openid` | varchar(128) | 微信openid |
| `nickname` | varchar(200) | 昵称 |
| `tel` | varchar(60) | 手机号 |
| `avatarUrl` | varchar(200) | 头像URL |
| `sex` | tinyint(10) | 性别 |
| `score` | bigint(16) | 评分 |
| `createtime` | bigint(16) | 注册时间 |
| `status` | tinyint(10) | 状态 |
| `tid` | bigint(16) | 分销上级ID |
| `fxuid1` | bigint(16) | 分销一级 |
| `fxuid2` | bigint(16) | 分销二级 |
| `rectid` | bigint(16) | 推荐人ID |
| `cityid` | bigint(16) | 城市ID |
| `is_verified` | tinyint(4) | 实名认证 |
| `school` | varchar(100) | 学校 |
| `major` | varchar(100) | 专业 |

#### zpwxsys_company

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 企业ID |
| `companyname` | varchar(100) | 企业名称 |
| `companycate` | varchar(100) | 行业分类 |
| `companytype` | varchar(50) | 企业类型 |
| `mastername` | varchar(100) | 负责人 |
| `tel` | varchar(30) | 联系电话 |
| `address` | varchar(100) | 地址 |
| `lat`/`lng` | decimal(10,6) | 经纬度 |
| `thumb` | text | 企业图片 |
| `status` | tinyint(10) | 审核状态 |
| `uid` | bigint(16) | 创建者用户ID |
| `business_license` | varchar(255) | 营业执照 |

#### zpwxsys_companyaccount

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 账户ID |
| `uid` | bigint(16) | 用户ID |
| `name` | varchar(60) | 账号 |
| `password` | varchar(60) | 密码哈希 |
| `companyid` | bigint(16) | 企业ID |
| `logintime` | bigint(16) | 最后登录 |

---

### 5.2 岗位与简历表

#### zpwxsys_job

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 岗位ID |
| `jobtitle` | varchar(50) | 岗位名称 |
| `content` | text | 工作内容 |
| `money` | varchar(50) | 薪资范围 |
| `num` | bigint(16) | 招聘人数 |
| `sex` | tinyint(10) | 性别要求 (0=不限,1=男,2=女) |
| `age` | varchar(20) | 年龄要求 |
| `education` | varchar(30) | 学历要求 |
| `express` | varchar(30) | 经验要求 |
| `companyid` | bigint(16) | 所属企业 |
| `areaid` | bigint(16) | 区域ID |
| `createtime` | bigint(16) | 发布时间(时间戳) |
| `endtime` | bigint(16) | 截止时间(时间戳) |
| `status` | tinyint(10) | 状态 (0=待审,1=上架) |
| `toptime` | bigint(16) | 置顶到期时间 |
| `spreadtime` | bigint(16) | 急聘到期时间 |
| `special` | varchar(200) | 特殊福利标签 |
| `worktype` | bigint(16) | 工种ID |
| `ischeck` | tinyint(10) | 审核模式标记 |
| **新增字段** | | |
| `job_address` | varchar(200) | 岗位独立地址 |
| `job_lat`/`job_lng` | decimal(10,6) | 岗位经纬度 |
| `work_start_date` | varchar(20) | 工作开始日期 |
| `work_end_date` | varchar(20) | 工作结束日期 |
| `work_start_time` | varchar(10) | 每日开始时间 |
| `work_end_time` | varchar(10) | 每日结束时间 |
| `requirements` | text | 工作要求 |
| `tips` | text | 温馨提示 |
| `benefit_tag1` | varchar(50) | 福利标签1 |
| `benefit_tag2` | varchar(50) | 福利标签2 |
| `hourly_rate` | decimal(10,2) | 时薪(元/小时) |

> 关联: `hasOne Company` (通过 `companyid`)

#### zpwxsys_jobpart

> 结构与 `zpwxsys_job` 基本相同，专用于兼职岗位

#### zpwxsys_note (简历)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 简历ID |
| `uid` | bigint(16) | 用户ID |
| `jobtitle` | varchar(50) | 求职意向 |
| `name` | varchar(200) | 姓名 |
| `sex` | tinyint(10) | 性别 |
| `tel` | varchar(60) | 手机号 |
| `birthday` | varchar(30) | 生日 |
| `education` | varchar(30) | 学历 |
| `express` | varchar(30) | 经验 |
| `content` | text | 简历描述 |
| `jobcateid` | bigint(16) | 求职分类 |
| `areaid` | bigint(16) | 期望区域 |
| `cityid` | bigint(16) | 期望城市 |
| `money` | varchar(30) | 期望薪资 |
| `avatarUrl` | varchar(200) | 头像 |
| `star` | tinyint(10) | 评级 |
| `skillname` | varchar(200) | 技能 |
| `imgstr1`~`imgstr4` | text | 证书/作品图 |
| `ishidden` | tinyint(10) | 是否隐藏 |

---

### 5.3 记录与交易表

#### zpwxsys_jobrecord (投递/报名记录)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 记录ID |
| `uid` | bigint(16) | 求职者ID |
| `jobid` | bigint(16) | 岗位ID |
| `companyid` | bigint(16) | 企业ID |
| `status` | tinyint(10) | 流程状态 (0=已报名, 1=通过, 2=录用, 3=试岗, 4=完成) |
| `createtime` | bigint(16) | 报名时间 |
| `type` | tinyint(10) | 类型 (0=主动, 1=邀约) |
| `signin_time` | varchar(30) | 签到时间 |
| `signout_time` | varchar(30) | 签退时间 |
| `signed_in` | tinyint(4) | 是否已签到 |

#### zpwxsys_jobsave (收藏)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 收藏ID |
| `uid` | bigint(16) | 用户ID |
| `jobid` | bigint(16) | 岗位ID |
| `companyid` | bigint(16) | 企业ID |

#### zpwxsys_invaterecord (面试邀约)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 邀约ID |
| `noteid` | bigint(16) | 简历ID |
| `companyid` | bigint(16) | 企业ID |

#### zpwxsys_looknote (简历查看记录)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 记录ID |
| `uid` | bigint(16) | 查看者ID |
| `companyid` | bigint(16) | 企业ID |
| `noteid` | bigint(16) | 简历ID |

#### zpwxsys_lookcompanyrecord (企业查看简历记录)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 记录ID |
| `companyid` | bigint(16) | 企业ID |
| `noteid` | bigint(16) | 简历ID |

#### zpwxsys_baoming (活动报名)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 报名ID |
| `uid` | bigint(16) | 用户ID |
| `jobid` | bigint(16) | 岗位ID |
| `name`/`tel` | varchar | 姓名/手机 |

#### zpwxsys_mygz (关注企业)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 关注ID |
| `uid` | bigint(16) | 用户ID |
| `companyid` | bigint(16) | 企业ID |

#### zpwxsys_askjob (岗位咨询)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 咨询ID |
| `uid` | bigint(16) | 用户ID |
| `jobid` | bigint(16) | 岗位ID |
| `content` | varchar(200) | 咨询内容 |

#### zpwxsys_comment (评论)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 评论ID |
| `uid` | bigint(16) | 用户ID |
| `companyid` | bigint(16) | 企业ID |
| `content` | varchar(50) | 评论内容 |
| `score` | bigint(16) | 评分 |
| `piclist` | varchar(500) | 评论图片 |

---

### 5.4 财务表

#### zpwxsys_order

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 订单ID |
| `order_no` | varchar(20) UNIQUE | 订单号 |
| `user_id` | bigint(16) | 用户ID |
| `total_price` | decimal(6,2) | 总价 |
| `status` | tinyint(4) | 状态 (1=未支付,2=已支付,3=已发货) |
| `prepay_id` | varchar(100) | 微信预支付ID |
| `type` | varchar(30) | 订单类型 |
| `companyid` | bigint(16) | 企业ID |

#### zpwxsys_money (佣金/提现记录)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 记录ID |
| `uid` | bigint(16) | 用户ID |
| `money` | float(10,2) | 金额 |
| `totalmoney` | float(10,2) | 累计余额 |
| `type` | tinyint(10) | 交易类型 |
| `content` | varchar(30) | 描述 |

#### zpwxsys_coin_record (章鱼币/积分流水)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | int(11) PK | 记录ID |
| `uid` | int(11) | 用户ID |
| `type` | tinyint(4) | 类型 (1=章鱼币, 2=积分) |
| `amount` | decimal(10,2) | 变动金额 (+收入/-支出) |
| `balance` | decimal(10,2) | 变动后余额 |
| `action` | varchar(30) | 动作类型 (recharge/consume/signin/referral/exchange/topjob/spreadjob/creategroup/tempcall/signinservice) |
| `remark` | varchar(200) | 备注 |
| `related_id` | int(11) | 关联业务ID |

#### zpwxsys_fxrecord (分销佣金记录)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 记录ID |
| `uid` | bigint(16) | 用户ID |
| `money` | decimal(10,2) | 佣金金额 |
| `orderid` | bigint(16) | 关联订单 |
| `type` | tinyint(10) | 类型 (0=简历, 1=企业) |

---

### 5.5 任务表

#### zpwxsys_task (悬赏任务)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 任务ID |
| `title` | varchar(200) | 标题 |
| `jobid` | bigint(16) | 关联岗位 |
| `companyid` | bigint(16) | 企业ID |
| `num` | bigint(16) | 需要人数 |
| `money` | float(10,1) | 单人奖金 |
| `content` | text | 任务描述 |

#### zpwxsys_taskrecord

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 记录ID |
| `uid` | bigint(16) | 用户ID |
| `taskid` | bigint(16) | 任务ID |

---

### 5.6 配置与内容表

#### zpwxsys_city (城市)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 城市ID |
| `name` | varchar(50) | 城市名 |
| `firstname` | varchar(30) | 首字母 |
| `ishot` | tinyint(10) | 是否热门 |

#### zpwxsys_area (区域)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 区域ID |
| `name` | varchar(50) | 区域名 |
| `cityid` | bigint(16) | 所属城市 |

#### zpwxsys_worktype (工种类型)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 工种ID |
| `name` | varchar(50) | 工种名 (全职/兼职/零工/妈妈班/实习) |

#### zpwxsys_jobcate (岗位分类)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 分类ID |
| `name` | varchar(50) | 分类名 |

#### zpwxsys_jobprice (薪资范围)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 范围ID |
| `name` | varchar(50) | 显示名 (如 2000-2500元) |
| `beginprice`/`endprice` | bigint(16) | 最低/最高薪资 |

#### zpwxsys_sysinit (系统配置)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | — |
| `name` | varchar(30) | 平台名称 |
| `appid`/`appsecret` | varchar(60) | 微信配置 |
| `rate1`/`rate2`/`rate3` | float(10,2) | 佣金比例 |
| `sharebg`/`fxbg` | varchar(200) | 分享/分销背景图 |
| `rulecontent` | text | 平台规则 |
| `ischeck` | tinyint(10) | 审核模式 |

#### zpwxsys_adv (广告Banner)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 广告ID |
| `advname` | varchar(60) | 广告名 |
| `thumb` | varchar(255) | 图片URL |
| `link` | varchar(255) | 跳转链接 |
| `appid` | varchar(50) | 跳转小程序appid |
| `cityid` | bigint(16) | 城市ID |

#### zpwxsys_nav (导航菜单)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 导航ID |
| `advname` | varchar(50) | 菜单名 |
| `thumb` | varchar(255) | 图标 |
| `link`/`innerurl` | varchar(255) | 链接 |

#### zpwxsys_news (资讯文章)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 文章ID |
| `title` | varchar(200) | 标题 |
| `content` | text | 内容 |
| `cateid` | bigint(16) | 分类ID |

#### zpwxsys_notice (公告)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 公告ID |
| `title` | varchar(200) | 标题 |
| `content` | text | 内容 |

#### zpwxsys_sysmsg (系统消息)

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | bigint(16) PK | 消息ID |
| `uid` | bigint(16) | 接收者ID |
| `content` | varchar(200) | 消息内容 |
| `status` | tinyint(10) | 已读状态 |
| `link` | varchar(60) | 操作链接 |

#### 其他配置表

| 表名 | 说明 |
|------|------|
| `zpwxsys_cate` | 文章分类 |
| `zpwxsys_current` | 就业状态选项 |
| `zpwxsys_helplab` | 就业帮扶标签 |
| `zpwxsys_rect` | 推荐位 |
| `zpwxsys_edu` | 用户教育经历 |
| `zpwxsys_express` | 用户工作经历 |
| `zpwxsys_oldhouse` | 租房列表 |

---

### 5.7 群聊表

#### zpwxsys_chat_group

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | int(11) PK | 群ID |
| `jobid` | int(11) | 关联岗位 |
| `group_name` | varchar(100) | 群名 |
| `notice` | varchar(500) | 群公告 |
| `companyid` | int(11) | 群主企业 |
| `owner_uid` | int(11) | 群主用户ID |
| `max_member` | int(11) | 最大人数 (默认200) |
| `member_count` | int(11) | 当前人数 |
| `expire_time` | int(11) | 过期时间 |

#### zpwxsys_chat_member

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | int(11) PK | — |
| `groupid` | int(11) | 群ID |
| `uid` | int(11) | 用户ID |
| `role` | tinyint(4) | 角色 (0=成员, 1=管理员, 2=群主) |
| `is_muted` | tinyint(4) | 是否禁言 |
| `agreed_rule` | tinyint(4) | 已同意公约 |

#### zpwxsys_chat_message

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | int(11) PK | 消息ID |
| `groupid` | int(11) | 群ID |
| `uid` | int(11) | 发送者ID |
| `content` | varchar(500) | 消息内容 |
| `msg_type` | tinyint(4) | 类型 (0=文本, 1=系统通知) |

#### zpwxsys_chat_rule

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | int(11) PK | — |
| `title` | varchar(100) | 公约标题 |
| `content` | text | 公约内容 |

#### zpwxsys_sensitive_word

| 字段 | 类型 | 说明 |
|------|------|------|
| `id` | int(11) PK | — |
| `word` | varchar(100) | 敏感词 |
| `status` | tinyint(4) | 是否启用 |

---

### 5.8 其他表

| 表名 | 说明 |
|------|------|
| `zpwxsys_companyrecord` | 企业服务资源使用记录 (岗位发布额度/简历查看额度/置顶额度) |
| `zpwxsys_companyrole` | 企业会员套餐配置 (普通/月卡/年卡) |
| `zpwxsys_lookrole` | 简历查看套餐配置 |
| `zpwxsys_lookrolerecord` | 用户简历查看额度记录 |
| `zpwxsys_lookrecord` | 岗位查看记录 |
| `zpwxsys_activerecord` | 活动参与记录 |
| `zpwxsys_active` | 招聘活动 |
| `zpwxsys_agent` | 代理/经纪人申请 |
| `zpwxsys_ploite` | 投诉/反馈 |
| `zpwxsys_projobrecord` | 项目工作记录 |

---

## 6. 服务层架构

### Service 文件列表

| 服务类 | 文件 | 职责 |
|--------|------|------|
| `Token` | service/Token.php | Token 验证、Scope 检查、缓存读写 |
| `UserToken` | service/UserToken.php | 微信用户登录、自动注册、Token 发放 |
| `CompanyToken` | service/CompanyToken.php | 企业账号登录、Token 发放 |
| `AppToken` | service/AppToken.php | 第三方应用认证 |
| `Order` | service/Order.php | 订单创建、库存检查、快照生成 |
| `Pay` | service/Pay.php | 微信支付统一下单、签名生成 |
| `WxNotify` | service/WxNotify.php | 支付回调处理(事务+行锁) |
| `WxMessage` | service/WxMessage.php | 模板消息基类 |
| `DeliveryMessage` | service/DeliveryMessage.php | 发货通知模板消息 |
| `AccessToken` | service/AccessToken.php | 微信平台 Access Token、小程序码生成 |
| `LookRoleRecord` | service/LookRoleRecord.php | 简历查看额度记录 |
| `CompanyRecord` | service/CompanyRecord.php | 企业资源使用记录 |
| `Lookrole` | service/Lookrole.php | 查看权限验证 |
| `Curl` | service/Curl.php | HTTP 请求工具 |
| `ErrorCode` | service/ErrorCode.php | AES 加密错误码 |

### 数据流示意

```
用户登录:
  微信code → UserToken.get() → jscode2session → 创建/获取用户 → 发Token → 缓存7200s

岗位报名:
  Token验证 → checkBind → sendJob() → 检查简历 → 创建jobrecord → 发系统消息

微信支付:
  下单 → Order.place() → Pay.pay() → 微信统一下单 → 返回签名
  支付成功 → WxNotify → 行锁订单 → 更新状态 → 发放权益 → 提交事务
```

---

## 7. 接口与数据表关联矩阵

| 控制器 | 主要关联表 |
|--------|-----------|
| Token | user, lookrole |
| Login | user |
| Sysinit | user, city, adv, nav, job, company, sysinit, notice, worktype, oldhouse, jobrecord, sysmsg, money, coin_record |
| Banner | adv |
| City | city |
| Job | job, jobsave, jobrecord, company, note, area, jobcate, jobprice, worktype, askjob, sysmsg, sensitive_word, companyrecord, coin_record, nav |
| Jobpart | jobpart, jobsave, jobrecord, area, jobcate, jobprice, askjob, note, city |
| Note | note, area, jobcate, edu, express, worktype, helplab, current, jobprice, city, looknote, lookcompanyrecord, lookrecord, user, invaterecord, company, sysmsg, job |
| Company | company, companyaccount, job, jobrecord, comment, note, area, jobcate, city, companyrecord, companyrole, order, task, mygz, sysmsg, user, coin_record |
| Task | task, taskrecord, job, city, area, jobcate, jobrecord, jobsave |
| Taskrecord | taskrecord |
| Chat | chat_group, chat_member, chat_message, chat_rule, sensitive_word, job |
| Order | order, lookrole, companyrole, active |
| Pay | order |
| Coin | coin_record |
| Usercard | user, note, jobrecord, comment, company, coin_record |
| Agent | agent, user, money, fxrecord, note, company |
| Fxsys | user |
| News | news, cate, sysinit |
| Notice | notice |
| Sysmsg | sysmsg |
| Active | active, activerecord |
| Baoming | baoming |
| Lookrole | lookrole, lookrolerecord |
| Lookrolerecord | lookrecord, lookrolerecord |
| Companyrole | companyrole, companyrecord |
| Companyrecord | note, lookcompanyrecord, companyrecord |
| Ploite | ploite |

---

## 统计摘要

| 指标 | 数量 |
|------|------|
| API 控制器 | 28 |
| API 方法总数 | 272+ |
| 数据库表 | 54 |
| Service 服务类 | 15 |
| 验证器 | 9 |
