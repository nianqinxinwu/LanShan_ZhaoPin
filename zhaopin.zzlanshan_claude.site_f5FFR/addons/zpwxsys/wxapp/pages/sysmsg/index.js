import { Sysmsg } from '../../model/sysmsg-model.js';
var sysmsg = new Sysmsg();

var categoryIconMap = {
  '餐饮服务': '../../imgs/category/1餐厅服务.png',
  '餐厅服务': '../../imgs/category/1餐厅服务.png',
  '活动执行': '../../imgs/category/2活动执行.png',
  '安保检票': '../../imgs/category/3安保检票.png',
  '教育辅导': '../../imgs/category/4教育辅导.png',
  '群众演员': '../../imgs/category/5群众演员.png',
  '临时促销': '../../imgs/category/6临时促销.png',
  '电商推广': '../../imgs/category/7电商推广.png',
  '问卷调查': '../../imgs/category/8问卷调查.png',
  '物流配送': '../../imgs/category/9物流配送.png',
  '装卸搬运': '../../imgs/category/10装卸搬运.png',
  '家政服务': '../../imgs/category/11家政服务.png',
  '流水线工': '../../imgs/category/12流水线工.png',
  '手工制作': '../../imgs/category/13手工制作.png',
  '内容创作': '../../imgs/category/14内容创作.png',
  '校园专属': '../../imgs/category/15校园专属.png'
};

var defaultIcon = '../../imgs/category/1餐厅服务.png';

function getIconPath(jobcatename) {
  if (jobcatename && categoryIconMap[jobcatename]) {
    return categoryIconMap[jobcatename];
  }
  return defaultIcon;
}

Page({

  data: {
    page: 1,
    checkFlag: 0,
    notifyHidden: true,
    activeTab: 'all',
    sysmsglist: [],
    unreadCount: 0
  },

  onLoad: function (options) {
    var that = this;
    var checkFlag = wx.getStorageSync('checkFlag') || 0;
    var notifyHidden = wx.getStorageSync('notifyBannerClosed') || false;

    that.setData({
      checkFlag: checkFlag,
      notifyHidden: notifyHidden
    });

    wx.setNavigationBarTitle({
      title: checkFlag == 1 ? '消息中心' : '消息',
    });

    that.initpage();
  },

  initpage: function () {
    var that = this;
    var params = { page: that.data.page };

    if (that.data.activeTab === 'unread') {
      params.readstatus = 0;
    }

    sysmsg.GetsysmsgList(function (data) {
      var sysmsglist = data.sysmsglist || [];

      // 为每条消息匹配分类图标
      for (var i = 0; i < sysmsglist.length; i++) {
        sysmsglist[i].iconPath = getIconPath(sysmsglist[i].jobcatename);
      }

      that.setData({
        sysmsglist: sysmsglist,
        unreadCount: data.unreadCount || 0
      });

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, params);
  },

  enableNotify: function () {
    var that = this;
    wx.requestSubscribeMessage({
      tmplIds: [],
      success: function () {
        that.setData({ notifyHidden: true });
        wx.setStorageSync('notifyBannerClosed', true);
      },
      fail: function () {
        that.setData({ notifyHidden: true });
        wx.setStorageSync('notifyBannerClosed', true);
      }
    });
  },

  closeNotify: function () {
    this.setData({ notifyHidden: true });
    wx.setStorageSync('notifyBannerClosed', true);
  },

  switchTab: function (e) {
    var tab = e.currentTarget.dataset.tab;
    if (tab === this.data.activeTab) return;

    this.setData({
      activeTab: tab,
      page: 1,
      sysmsglist: []
    });

    this.initpage();
  },

  tapMsg: function (e) {
    var item = e.currentTarget.dataset.item;

    // 标记已读
    if (item.status == 0 && item.id) {
      sysmsg.MarkAsRead(function () { }, { id: item.id });

      // 本地更新状态
      var sysmsglist = this.data.sysmsglist;
      for (var i = 0; i < sysmsglist.length; i++) {
        if (sysmsglist[i].id === item.id) {
          sysmsglist[i].status = 1;
          break;
        }
      }
      var unreadCount = this.data.unreadCount > 0 ? this.data.unreadCount - 1 : 0;
      this.setData({
        sysmsglist: sysmsglist,
        unreadCount: unreadCount
      });
    }

    // 如果有链接，跳转
    if (item.link && item.link !== '') {
      wx.navigateTo({
        url: item.link,
      });
    }
  },

  toIndex: function () {
    wx.redirectTo({
      url: '/pages/index/index',
    });
  },

  toFindjob: function () {
    wx.redirectTo({
      url: '/pages/findjob/index'
    });
  },

  toMyinvate: function () {
    wx.redirectTo({
      url: '/pages/switchrole/index'
    });
  },

  toSysmsg: function () {
    wx.redirectTo({
      url: '/pages/sysmsg/index',
    });
  },

  toMyuser: function () {
    wx.redirectTo({
      url: '/pages/user/index',
    });
  },

  onReady: function () { },

  onShow: function () { },

  onHide: function () { },

  onUnload: function () { },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.setData({ page: 1 });
    this.initpage();
  },

  onReachBottom: function () {
    this.data.page = this.data.page + 1;
    this.initpage();
  },

  onShareAppMessage: function () {
    return {
      title: '消息中心',
      path: '/pages/sysmsg/index'
    };
  },

});
