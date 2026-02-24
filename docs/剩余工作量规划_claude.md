# 章鱼外快 — 剩余开发计划

**生成日期**：2026-02-19
**数据来源**：源码逐文件分析、开发计划文档、进度评估文档、需求文档、icon_source 资源清单

---

## 〇、核心原则：审核版本隔离

### 审核版本（ischeck == 1）的当前行为

后端 `zpwxsys_sysinit` 表的 `ischeck` 字段控制全局模式。当 `ischeck == 1` 时，首页（`index/index.wxml`）通过 `hidden="{{ischeck == 1 ? true : false}}"` 隐藏以下全部内容：

| 隐藏区域 | 代码位置 |
|---------|---------|
| 统计数据（入驻企业/职位/简历） | 第38行 |
| 公告滚动栏 | 第77行 |
| 名企招聘横向滚动 | 第107-129行 |
| 创建简历/发布职位按钮 | 第141-176行 |
| 工作类型分类选择 | 第186行 |
| 招聘岗位列表（ejoblist 模板） | 第197行 |
| "查看全部职位信息"链接 | 第210行 |
| 底部信息（隐私/指南/须知） | 第213行 |
| 自定义 TabBar（首页/找工作/发布/消息/我的） | 第222行 |

审核版本**仅显示**：轮播图 + 导航按钮（navlist）+ 旧房屋列表（oldhouselist 模板，第204行 `hidden="{{ischeck == 0 ? true : false}}"`）。

审核员看到的是一个简洁的房产信息浏览小程序，不会接触到招聘/外快功能。

### 隔离开发准则

**所有章鱼外快新功能的开发必须遵守：**

1. **后端无条件兼容** — 新增的 Controller/Model/Service 仅在前端调用时才触发，不影响审核版本的数据返回
2. **前端入口隔离** — 所有章鱼外快新增入口必须加 `hidden="{{ischeck == 1 ? true : false}}"` 或读取 `checkFlag` 判断
3. **新页面不可自动触发** — 新页面（签到/群聊/章鱼币等）通过用户主动导航进入，审核版本中入口被隐藏，自然不会触及
4. **数据表向下兼容** — ALTER 增加字段使用 DEFAULT 值，新建表不影响已有表查询
5. **app.json 页面注册安全** — 在 app.json 中注册新页面不会影响审核，因为审核员无法看到导航入口

### checkFlag 写入 Storage（首要任务）

当前 `ischeck` 仅存在于首页 page data 中，其他页面无法获取。需要在首页加载时写入全局 Storage：

**改动文件**：`pages/index/index.js` 的 `_loadData` 方法（第316行回调内）

```javascript
// 在 that.data.ischeck = data.sysinfo.ischeck; 之后添加：
wx.setStorageSync('checkFlag', data.sysinfo.ischeck);
```

**各页面读取模式**（在 `onShow` 或 `onLoad` 中）：

```javascript
var checkFlag = wx.getStorageSync('checkFlag');
```

- 审核版本 `checkFlag == 1`：保持原有行为不变
- 上线版本 `checkFlag == 0` 或 `checkFlag == ''`（未设置）：显示章鱼外快完整功能

---

## 一、当前进度概览

| 维度 | 完成度 | 说明 |
|------|--------|------|
| 总体 | **~40%** | 阶段1-3基本完成，阶段4-10大部分待开发 |
| 服务端 | ~45% | 核心API已实现，高级功能（签到/群聊/章鱼币）未开始 |
| 前端 | ~55% | 82个注册页面中52个完整，11个空壳 |
| 数据库 | ~60% | 现有表完整，6张新表 + 3张表字段变更未执行 |

**里程碑状态**：

```
M1（浏览和报名）  ████████████████████  ✅ 基本达成
M2（发布和管理）  ██████████░░░░░░░░░░  ⚠️ ~50%
M3（签到/评价）   ░░░░░░░░░░░░░░░░░░░░  ❌ 未开始
M4（群聊上线）    ░░░░░░░░░░░░░░░░░░░░  ❌ 未开始
```

---

## 二、图标资源部署方案

`icon_source/` 目录下共 44 个 PNG 文件，分 5 个类别，**均未部署到 wxapp**。需重命名为英文后复制到 `wxapp/imgs/icon/` 目录。

### 2.1 首页图标（15个工作类型 + 1个发布按钮）

> 注：15个工作类型图标已在阶段1改造中通过后端 navlist 的 thumb URL 引用。此处的 `发布.png` 尚未使用。

| 源文件 | 部署路径 | 使用位置 |
|--------|---------|---------|
| `首页图标/发布.png` | `imgs/icon/publish.png` | 首页自定义TabBar发布按钮 |

### 2.2 报名者"我的"页面图标（12个）

| 源文件 | 部署路径 | 使用位置 |
|--------|---------|---------|
| `报名者-我的页面图标/小红花.png` | `imgs/icon/flower.png` | 评价展示（满分） |
| `报名者-我的页面图标/小红花（空）.png` | `imgs/icon/flower_empty.png` | 评价展示（空） |
| `报名者-我的页面图标/小红花（半）.png` | `imgs/icon/flower_half.png` | 评价展示（半星） |
| `报名者-我的页面图标/小红花0.2.png` | `imgs/icon/flower_02.png` | 评价展示（低分） |
| `报名者-我的页面图标/小红花0.8.png` | `imgs/icon/flower_08.png` | 评价展示（高分） |
| `报名者-我的页面图标/收藏 - 未收藏.png` | `imgs/icon/fav_empty.png` | 收藏按钮（未收藏态） |
| `报名者-我的页面图标/举手.png` | `imgs/icon/signup.png` | 我的报名入口 |
| `报名者-我的页面图标/客服.png` | `imgs/icon/service.png` | 联系客服入口 |
| `报名者-我的页面图标/代签协议.png` | `imgs/icon/agreement.png` | 用户协议入口 |
| `报名者-我的页面图标/14红包.png` | `imgs/icon/redpack.png` | 推荐红包入口 |
| `报名者-我的页面图标/M_隐私.png` | `imgs/icon/privacy.png` | 隐私条款入口 |
| `报名者-我的页面图标/36合作、关系-线性.png` | `imgs/icon/cooperate.png` | 找我合作入口 |

### 2.3 发布者"我的"页面图标（11个）

| 源文件 | 部署路径 | 使用位置 |
|--------|---------|---------|
| `发布者-我的页面/01_已发布.png` | `imgs/icon/published.png` | 我的发布入口 |
| `发布者-我的页面/登录签到表.png` | `imgs/icon/signin.png` | 签到表入口（阶段5） |
| `发布者-我的页面/数译_群聊.png` | `imgs/icon/groupchat.png` | 群聊入口（阶段6） |
| `发布者-我的页面/扩散.png` | `imgs/icon/spread.png` | 加急扩散入口（阶段8） |
| `发布者-我的页面/置顶.png` | `imgs/icon/pin_top.png` | 置顶服务入口（阶段8） |
| `发布者-我的页面/评价.png` | `imgs/icon/rating.png` | 评价入口（阶段5） |
| `发布者-我的页面/钱币-彩.png` | `imgs/icon/coin.png` | 章鱼币入口（阶段9） |
| `发布者-我的页面/使用说明.png` | `imgs/icon/guide.png` | 使用说明入口 |
| `发布者-我的页面/代签协议.png` | `imgs/icon/agreement_pub.png` | 发布者协议入口 |
| `发布者-我的页面/客服.png` | `imgs/icon/service_pub.png` | 发布者客服入口 |
| `发布者-我的页面/36合作、关系-线性.png` | `imgs/icon/cooperate_pub.png` | 发布者合作入口 |

### 2.4 打电话/收藏图标（3个）

| 源文件 | 部署路径 | 使用位置 |
|--------|---------|---------|
| `打电话:收藏图标/电话.png` | `imgs/icon/phone_call.png` | 名片临时通话（阶段7） |
| `打电话:收藏图标/收藏.png` | `imgs/icon/fav.png` | 收藏按钮 |
| `打电话:收藏图标/收藏 -已收藏-copy.png` | `imgs/icon/fav_active.png` | 收藏按钮（已收藏态） |

### 2.5 身份选择图标（2个）

| 源文件 | 部署路径 | 使用位置 |
|--------|---------|---------|
| `身份选择页面图标/男老师.png` | `imgs/icon/role_publisher.png` | 身份选择页发布者 |
| `身份选择页面图标/男学生.png` | `imgs/icon/role_applicant.png` | 身份选择页报名者 |

**合计部署**：44 个文件 → `wxapp/imgs/icon/`

---

## 三、阶段性开发计划

### 阶段 A：收尾 + 基础设施（阶段1-3遗留 + 阶段4前置）

#### A1. checkFlag 全局写入

| 改动 | 文件 | 说明 |
|------|------|------|
| 写入 Storage | `pages/index/index.js:325` | `wx.setStorageSync('checkFlag', data.sysinfo.ischeck);` |
| 标题切换 | `pages/findjob/index.js:254,408` | `checkFlag==1 → '找工作'`，否则 `→ '找活'` |
| 标题切换 | `pages/searchjob/index.js:163` | 同上 |
| 标题切换 | `pages/partjob/index.js:304` | 同上 |

审核隔离：这些页面在审核版本中通过首页 tabbar 隐藏无法进入，标题切换是双重保险。

#### A2. Login 控制器补全

**后端**（`addons/zpwxsys/controller/v1/Login.php`）：

| 方法 | 当前状态 | 需实现 |
|------|---------|--------|
| `userLogin()` | 空壳（第48-64行） | 验证手机号+UID → 查询用户 → 返回 Token/错误 |
| `userRegister()` | 空壳（第66-77行） | 验证参数 → 创建用户 → 绑定 openid → 返回 Token |
| `userSysinit()` | 空壳（第80-84行） | 返回用户初始化数据（余额、未读消息等） |

**前端**（`pages/login/index.js`，当前74行桩代码）：
- 完整重写：手机号输入 → 微信手机号快速验证（`getPhoneNumber`）→ 调用 API → Token 存储 → 跳转

审核隔离：login 页面通过导航进入，审核版本中 tabbar 隐藏，审核员不会触发此流程。

#### A3. 代码清理

| 问题 | 位置 | 处理 |
|------|------|------|
| `Log::record()` 调试日志残留 | `Pay.php` | 删除或改为条件日志 |
| 文件末尾 20+ 行空白 | `Order.php` | 删除 |
| `$god` 变量连续赋值两次 | `Job.php` | 删除冗余赋值 |
| `debug: true` | `wxapp/app.json` | 上线前改 false |

> 注意：`app.json` 中 `navigationBarBackgroundColor: "#2577f5"` 不在此阶段修改——审核版本当前使用此颜色，保持不变。章鱼外快的深蓝色 `#182078` 通过页面级 `wx.setNavigationBarColor` 在上线模式下覆盖。

#### A4. 图标资源批量部署

将 icon_source/ 全部 44 个文件按照第二章映射表复制到 `wxapp/imgs/icon/`。

审核隔离：图标文件存在于目录中不会被加载，仅当 wxml 引用时才生效。

---

### 阶段 B：我的页面双角色改造（阶段4）— 当前 ~40%

#### B1. "我的"页面改造（`pages/user/index`）

当前 `user/index.wxml` 是旧招聘系统的布局（经纪人中心、企业中心、我的简历等），且**没有 ischeck 条件控制**——审核版本中用户可以通过其他途径进入此页面。

**改造策略**：在现有页面上增加章鱼外快功能区，用 `checkFlag` 条件包裹：

```xml
<!-- 审核版本保持原有布局不变 -->

<!-- 章鱼外快功能区 — 仅上线版本显示 -->
<view hidden="{{checkFlag == 1}}">
  <!-- 报名者功能矩阵 -->
  <view wx:if="{{role == 'applicant'}}">
    <view bindtap="toMyfind"><image src="../../imgs/icon/signup.png"/>我的报名</view>
    <view bindtap="toMysave"><image src="../../imgs/icon/fav.png"/>收藏</view>
    <view bindtap="toSignin"><image src="../../imgs/icon/signin.png"/>签到</view>
    <view bindtap="toMychatlist"><image src="../../imgs/icon/groupchat.png"/>我的群聊</view>
    <view bindtap="toMyinvate"><image src="../../imgs/icon/redpack.png"/>推荐</view>
  </view>

  <!-- 发布者功能矩阵 -->
  <view wx:if="{{role == 'publisher'}}">
    <view bindtap="toMynote"><image src="../../imgs/icon/published.png"/>我的发布</view>
    <view bindtap="toSigninlist"><image src="../../imgs/icon/signin.png"/>签到表</view>
    <view bindtap="toMychatlist"><image src="../../imgs/icon/groupchat.png"/>我的群聊</view>
    <view bindtap="toMycomment"><image src="../../imgs/icon/rating.png"/>评价</view>
    <view bindtap="toMymoney"><image src="../../imgs/icon/coin.png"/>章鱼币</view>
    <view bindtap="toArticle"><image src="../../imgs/icon/guide.png"/>使用说明</view>
  </view>

  <!-- 公共菜单 -->
  <view bindtap="toAgreement"><image src="../../imgs/icon/agreement.png"/>用户协议</view>
  <view bindtap="toPrivacy"><image src="../../imgs/icon/privacy.png"/>隐私条款</view>
  <view bindtap="toCooperate"><image src="../../imgs/icon/cooperate.png"/>找我合作</view>
  <view><button open-type="contact"><image src="../../imgs/icon/service.png"/>联系客服</button></view>
</view>
```

**JS 中读取 checkFlag**：

```javascript
onShow: function() {
    this.setData({ checkFlag: wx.getStorageSync('checkFlag') || 0 });
}
```

审核隔离：审核版本中 `checkFlag == 1`，整个章鱼外快功能区不渲染，页面保持旧布局。

#### B2. 报名者子页面增强

| 页面 | 当前状态 | 改造内容 | 图标引用 |
|------|---------|---------|---------|
| `myfind/index` | 140行基础 | 增加筛选Tab（全部/已选中/发布中/已过期）| — |
| `mysave/index` | 139行基础 | 增加筛选Tab（全部/发布中/已过期）+ 取消收藏 | `fav.png` / `fav_active.png` / `fav_empty.png` |
| `mygz/index` | 109行基础 | 关注/订阅功能完善 | — |
| `mymoneyrecord/index` | 95行基础 | 消费/充值记录流水展示 | — |

#### B3. 发布者管理增强

| 页面 | 改造内容 | 图标引用 |
|------|---------|---------|
| `mynote/index` | 增加筛选Tab（全部/审核中/已发布/不通过） | `published.png` |
| `mycomment/index` | 增加筛选Tab + 小花评分图标 | `flower.png` / `flower_empty.png` / `flower_half.png` |

#### B4. 空壳页面填充

| 页面 | 行数 | 需填充内容 |
|------|------|-----------|
| `login/index` | 74 | 完整认证流程（与 A2 联动） |
| `mytaskjob/index` | 65 | 任务岗位列表 |
| `myteam/index` | 115 | 团队列表 |
| `agentfxrecord/index` | 105 | 代理佣金记录 |
| `ploiteinfo/index` | 132 | 平台信息 |
| `fxrule/index` | 92 | 分销规则展示 |

#### B5. 后端配合

| 文件 | 改造 |
|------|------|
| `controller/v1/Baoming.php` | 报名列表增加状态筛选参数 |
| `controller/v1/Job.php` | 收藏列表增加状态筛选参数 |

---

### 阶段 C：签到与评价系统（阶段5）— 当前 0%

#### C1. 新建数据表

```sql
CREATE TABLE `zpwxsys_signin_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` int(11) NOT NULL COMMENT '岗位ID',
  `uid` int(11) NOT NULL COMMENT '报名者ID',
  `companyid` int(11) NOT NULL COMMENT '发布者ID',
  `date` date NOT NULL COMMENT '签到日期',
  `confirm_status` tinyint(1) DEFAULT 0 COMMENT '工作确认 0未确认 1已确认',
  `signin_time` int(11) DEFAULT NULL COMMENT '签到时间',
  `signout_time` int(11) DEFAULT NULL COMMENT '签退时间',
  `rating` tinyint(1) DEFAULT 1 COMMENT '评价 1好评 0差评',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_jobid` (`jobid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

审核隔离：新建表不影响已有查询。

#### C2. 新建后端

| 文件 | 类型 | 内容 |
|------|------|------|
| `controller/v1/Signin.php` | 控制器 | 6个方法：list, detail, confirm, signin, signout, rating |
| `model/Signinrecord.php` | 模型 | 签到记录 CRUD |
| `service/Signin.php` | 服务 | 签到状态机、微信消息推送触发 |
| `validate/SigninValidate.php` | 验证器 | 签到参数校验 |

审核隔离：控制器仅在前端主动调用时响应，审核版本无入口可触发。

#### C3. 新建前端页面

| 页面 | 功能 | 图标引用 |
|------|------|---------|
| `pages/signinlist/index` | 发布者签到表列表（仅已付费岗位） | `signin.png` |
| `pages/signin/index` | 岗位签到详情（日期选择/确认/签到/签退/评价） | `flower.png` 系列 |

**前端 API 模型**：新建 `model/signin-model.js`

#### C4. 改造现有页面

| 页面 | 改造 | 审核隔离方式 |
|------|------|-------------|
| `myfind/index` | 底部增加"工作确认/签到/签退"按钮 | `hidden="{{checkFlag == 1}}"` |
| `mycomment/index` | 小花图标评分展示 | 评价功能不影响审核版本 |

#### C5. 新增 API 接口

| 接口 | 说明 |
|------|------|
| `POST /v1/signin/list` | 签到表列表 |
| `POST /v1/signin/detail` | 岗位签到详情 |
| `POST /v1/signin/confirm` | 发起工作确认 |
| `POST /v1/signin/in` | 签到 |
| `POST /v1/signin/out` | 签退 |
| `POST /v1/signin/rating` | 评价 |

#### C6. 微信订阅消息

| 触发场景 | 通知对象 | 消息内容 |
|---------|---------|---------|
| 发布者"发起工作确认" | 报名者 | 岗位名称、日期 |
| 发布者"发起签到" | 报名者 | 签到时间 |
| 发布者"发起签退" | 报名者 | 签退时间 |

前端在操作前调用 `wx.requestSubscribeMessage()`，后端复用已有 `WxMessage.php` + `AccessToken.php` 推送。

**依赖**：阶段 D（服务购买）— 签到表需付费开通；但签到功能本身可先开发，付费校验后补。

---

### 阶段 D：名片 + 发布者管理 + 服务购买（阶段7-8）

#### D1. 名片系统

**数据表变更**：

```sql
ALTER TABLE `zpwxsys_user` ADD COLUMN `school` varchar(100) DEFAULT NULL COMMENT '学校';
ALTER TABLE `zpwxsys_user` ADD COLUMN `major` varchar(100) DEFAULT NULL COMMENT '专业';
ALTER TABLE `zpwxsys_user` ADD COLUMN `experience_count` int(11) DEFAULT 0 COMMENT '兼职经历次数';
ALTER TABLE `zpwxsys_company` ADD COLUMN `business_license` varchar(255) DEFAULT NULL COMMENT '营业执照';
```

**新建前端页面**：

| 页面 | 功能 | 图标引用 |
|------|------|---------|
| `pages/usercard/index` | 报名者名片（头像/脱敏手机/学校/专业/经历次数/小花评分） | `flower.png` 系列, `phone_call.png` |
| `pages/companycard/index` | 发布者名片（头像/用户名/手机/公司/执照/Logo） | `phone_call.png` |

**后端改造**：`controller/v1/User.php` 增加名片获取/编辑接口；`model/User.php` + `model/Company.php` 增加新字段映射。

**校验规则**：手机号脱敏 `178****4107`；填写公司全称→执照和Logo必填。

**身份选择页图标更新**：`pages/switchrole/index.wxml` 中使用 `role_publisher.png`（男老师）和 `role_applicant.png`（男学生）。

#### D2. 发布者管理端增强

**服务订单表**：

```sql
CREATE TABLE `zpwxsys_service_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '购买者ID',
  `jobid` int(11) NOT NULL COMMENT '关联岗位ID',
  `service_type` varchar(50) NOT NULL COMMENT 'top/urgent/group/call/signin/custom',
  `amount` decimal(10,2) NOT NULL COMMENT '消费章鱼币',
  `status` tinyint(1) DEFAULT 1 COMMENT '1有效 0过期',
  `expire_time` int(11) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uid_job` (`uid`, `jobid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Job 表增加字段**：

```sql
ALTER TABLE `zpwxsys_job` ADD COLUMN `settle_type` tinyint(1) DEFAULT 0 COMMENT '结算方式 0日结 1周结 2月结';
ALTER TABLE `zpwxsys_job` ADD COLUMN `is_top` tinyint(1) DEFAULT 0 COMMENT '是否置顶';
ALTER TABLE `zpwxsys_job` ADD COLUMN `top_expire` int(11) DEFAULT NULL COMMENT '置顶到期时间';
```

**新建前端页面**：

| 页面 | 功能 | 图标引用 |
|------|------|---------|
| `pages/selectbaoming/index` | 筛选报名者（列表+小花排序+多选确认） | `flower.png` 系列 |
| `pages/buyservice/index` | 功能服务购买（置顶/加急/群聊/通话/签到/自定义） | `pin_top.png`, `spread.png`, `groupchat.png`, `phone_call.png`, `signin.png` |

**改造 `mynote/index`**：每张岗位卡增加置顶(`pin_top.png`)、加急(`spread.png`)、筛选报名者、功能服务按钮。

**新建后端**：

| 文件 | 内容 |
|------|------|
| `controller/v1/Service.php` | 服务列表、购买、校验余额 |
| `model/Serviceorder.php` | 服务订单模型 |
| `service/Service.php` | 服务开通/过期逻辑 |

**前端 API 模型**：新建 `model/service-model.js`

---

### 阶段 E：群聊系统（阶段6）

#### E1. 新建数据表

```sql
CREATE TABLE `zpwxsys_chat_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobid` int(11) NOT NULL COMMENT '岗位ID',
  `group_name` varchar(100) NOT NULL COMMENT '群名称=岗位名+通知群',
  `owner_id` int(11) NOT NULL COMMENT '群主ID（发布者）',
  `max_members` int(11) DEFAULT 0 COMMENT '最大成员数',
  `notice` text COMMENT '群公告',
  `notice_status` tinyint(1) DEFAULT 0 COMMENT '公告审核 0待审 1通过',
  `is_muted` tinyint(1) DEFAULT 0 COMMENT '全员禁言',
  `status` tinyint(1) DEFAULT 1 COMMENT '1正常 0已解散',
  `expire_time` int(11) DEFAULT NULL COMMENT '到期时间',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_jobid` (`jobid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `zpwxsys_chat_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `is_muted` tinyint(1) DEFAULT 0 COMMENT '个人禁言',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_group_user` (`groupid`, `uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `zpwxsys_chat_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(20) DEFAULT 'text' COMMENT 'text/system',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_groupid_time` (`groupid`, `createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### E2. 新建前端页面

| 页面 | 功能 | 图标引用 |
|------|------|---------|
| `pages/chatrule/index` | 群聊公约（阅读后同意方可进群） | — |
| `pages/mychatlist/index` | 我加入的群聊列表 | `groupchat.png` |
| `pages/chatroom/index` | 群聊天（消息列表+发送文本） | — |
| `pages/chatmanage/index` | 群管理（公告/禁言/踢人/加人） | — |

审核隔离：入口在 user 页面的 `hidden="{{checkFlag == 1}}"` 区域内。

#### E3. 新建后端

| 文件 | 内容 |
|------|------|
| `controller/v1/Chat.php` | list, create, messages, send, notice, mute, kick, addmember |
| `model/Chatgroup.php` | 群聊模型 |
| `model/Chatmember.php` | 成员模型 |
| `model/Chatmessage.php` | 消息模型 |
| `service/Chat.php` | 消息处理、敏感词过滤、群自动解散 |
| `validate/ChatValidate.php` | 消息校验（敏感词+数字限制） |

**前端 API 模型**：新建 `model/chat-model.js`

#### E4. 业务规则

- 群名 = 岗位名称 + "通知群"
- 仅文本（禁图片）
- 连续数字不超过2位（防泄露手机号）
- 岗位到期次日自动解散（后端定时任务）
- 记录保留一个月

#### E5. 通讯方案：HTTP 轮询

```
前端 setInterval(5000) → POST /v1/chat/messages { groupid, since: last_msg_id } → 增量返回新消息
```

- `onShow` 启动轮询，`onHide` 停止
- 本地缓存已加载消息，仅追加新内容
- MySQL 索引 `idx_groupid_time` 保障查询性能

#### E6. 敏感词过滤

使用微信官方 `msgSecCheck` 接口（`POST https://api.weixin.qq.com/wxa/msg_sec_check`），复用已有 `AccessToken` 和 `Curl` 服务。

---

### 阶段 F：章鱼币/积分系统（阶段9）

#### F1. 新建数据表

```sql
CREATE TABLE `zpwxsys_coin_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1章鱼币 2积分',
  `amount` decimal(10,2) NOT NULL COMMENT '变动金额',
  `balance` decimal(10,2) NOT NULL COMMENT '变动后余额',
  `action` varchar(50) NOT NULL COMMENT 'recharge/consume/signin/recommend',
  `related_id` int(11) DEFAULT NULL COMMENT '关联业务ID',
  `remark` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_type_action` (`type`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zpwxsys_user` ADD COLUMN `coin_balance` decimal(10,2) DEFAULT 0.00 COMMENT '章鱼币余额';
ALTER TABLE `zpwxsys_user` ADD COLUMN `point_balance` int(11) DEFAULT 0 COMMENT '积分余额';
```

#### F2. 新建后端

| 文件 | 内容 |
|------|------|
| `controller/v1/Coin.php` | balance, recharge, records |
| `model/Coinrecord.php` | 流水记录模型 |
| `service/Coin.php` | 余额查询、充值（微信支付）、扣费（事务+悲观锁）、积分发放 |
| `validate/CoinValidate.php` | 参数校验 |

**前端 API 模型**：新建 `model/coin-model.js`

#### F3. 改造前端页面

| 页面 | 改造 | 图标引用 |
|------|------|---------|
| `mymoney/index` | 改造为章鱼币/积分钱包（余额+充值入口+消费记录入口） | `coin.png` |
| `mymoneyrecord/index` | 改造为通用流水（充值/消费/签到奖励/推荐奖励） | — |

#### F4. 事务安全

所有余额变动使用数据库事务 + `lock(true)` 悲观锁：

```php
Db::startTrans();
try {
    $user = Db::name('zpwxsys_user')->where('id', $uid)->lock(true)->find();
    if ($user['coin_balance'] < $amount) { Db::rollback(); return ...; }
    // 扣减 + 写流水
    Db::commit();
} catch (\Exception $e) {
    Db::rollback();
}
```

#### F5. 业务规则

- **章鱼币**（发布者）：充值（微信支付）→ 购买服务消费。档位待确认。
- **积分**（报名者）：签到获得 + 推荐获得，满10分可兑换。规则待确认。

---

### 阶段 G：我的报名与收藏完善（阶段10）

| 页面 | 改造 | 图标引用 |
|------|------|---------|
| `myfind/index` | 筛选Tab（全部/已选中/发布中/已过期）+ 底部签到联动按钮 | `signup.png` |
| `mysave/index` | 筛选Tab（全部/发布中/已过期）+ 取消收藏 | `fav.png` / `fav_active.png` / `fav_empty.png` |

后端：`Baoming.php` + `Job.php` 增加状态筛选参数。

---

## 四、里程碑总览

### M2：发布和管理正常运行

```
阶段 A（收尾）+ 阶段 B（我的页面）
├── checkFlag 全局写入 + 标题切换
├── Login 前后端补全
├── "我的"页面双角色改造（使用报名者/发布者图标资源）
├── 空壳页面填充
├── 图标资源全量部署（44个文件）
└── 代码清理
```

### M3：核心业务闭环

```
阶段 C（签到评价）+ 阶段 D（名片+发布管理+服务购买）
├── 签到表 + 签到详情页（使用 signin.png, flower 系列）
├── 微信订阅消息集成
├── 名片系统（使用 phone_call.png, role_*.png）
├── 发布管理增强（使用 pin_top.png, spread.png）
├── 功能服务购买页（使用多个发布者图标）
├── 筛选报名者页（使用 flower 系列）
├── 我的报名/收藏增强（使用 fav 系列, signup.png）
└── 新建数据表 2 张 + ALTER 3 张表
```

### M4：完整功能上线

```
阶段 E（群聊）+ 阶段 F（章鱼币）
├── 群聊 4 页面（使用 groupchat.png）
├── HTTP 轮询通讯 + 敏感词过滤
├── 章鱼币/积分系统（使用 coin.png）
├── 充值对接微信支付
├── 名片临时通话（使用 phone_call.png + 章鱼币扣费）
├── 群自动解散定时任务
└── 新建数据表 4 张 + ALTER user 表 2 字段
```

---

## 五、全量新建文件清单

### 前端新页面（9个 = 36个文件，注册到 app.json）

| 页面 | 阶段 |
|------|------|
| `pages/signinlist/` | C |
| `pages/signin/` | C |
| `pages/usercard/` | D |
| `pages/companycard/` | D |
| `pages/selectbaoming/` | D |
| `pages/buyservice/` | D |
| `pages/chatrule/` | E |
| `pages/mychatlist/` | E |
| `pages/chatroom/` | E |
| `pages/chatmanage/` | E |

### 前端 API 模型（4个）

`signin-model.js`, `chat-model.js`, `coin-model.js`, `service-model.js`

### 后端新文件（17个）

| 文件 | 类型 | 阶段 |
|------|------|------|
| `controller/v1/Signin.php` | 控制器 | C |
| `controller/v1/Chat.php` | 控制器 | E |
| `controller/v1/Coin.php` | 控制器 | F |
| `controller/v1/Service.php` | 控制器 | D |
| `model/Signinrecord.php` | 模型 | C |
| `model/Chatgroup.php` | 模型 | E |
| `model/Chatmember.php` | 模型 | E |
| `model/Chatmessage.php` | 模型 | E |
| `model/Coinrecord.php` | 模型 | F |
| `model/Serviceorder.php` | 模型 | D |
| `service/Signin.php` | 服务 | C |
| `service/Chat.php` | 服务 | E |
| `service/Coin.php` | 服务 | F |
| `service/Service.php` | 服务 | D |
| `validate/SigninValidate.php` | 验证器 | C |
| `validate/ChatValidate.php` | 验证器 | E |
| `validate/CoinValidate.php` | 验证器 | F |

### 数据库变更

| 操作 | 表 | 阶段 |
|------|---|------|
| CREATE | zpwxsys_signin_record | C |
| CREATE | zpwxsys_service_order | D |
| CREATE | zpwxsys_chat_group | E |
| CREATE | zpwxsys_chat_member | E |
| CREATE | zpwxsys_chat_message | E |
| CREATE | zpwxsys_coin_record | F |
| ALTER | zpwxsys_user + school, major, experience_count | D |
| ALTER | zpwxsys_user + coin_balance, point_balance | F |
| ALTER | zpwxsys_company + business_license | D |
| ALTER | zpwxsys_job + settle_type, is_top, top_expire | D |

---

## 六、待确认业务事项

| 序号 | 问题 | 阻塞阶段 |
|------|------|---------|
| 1 | 章鱼币充值档位和价格 | F（高） |
| 2 | 功能服务定价（置顶/加急/群聊/通话/签到） | D（高） |
| 3 | 积分兑换规则 | F（中） |
| 4 | 群聊公约文案 | E（低） |
| 5 | 客服联系方式 | B（低） |
| 6 | 用户协议/隐私条款 URL | B（低） |

---

**文档版本**：v2.0
**生成方式**：基于源码逐文件分析 + 审核版本隔离架构分析
**生成日期**：2026-02-19
