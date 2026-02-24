import { Company } from '../../model/company-model.js';

var company = new Company();
Page({

  data: {
    page: 1,
    currentTab: -1,
    filterType: -1,
    list: []
  },

  onLoad: function () {
    this.initpage();
  },

  initpage: function () {
    var that = this;
    var ctoken = wx.getStorageSync('ctoken');

    if (!ctoken) {
      wx.navigateTo({ url: "/pages/companylogin/index" });
      return;
    }

    var params = {
      ctoken: ctoken,
      page: that.data.page,
      filterType: that.data.filterType
    };

    company.getSigninList((data) => {
      that.setData({
        list: data.joblist || []
      });

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, params);
  },

  switchTab: function (e) {
    var type = parseInt(e.currentTarget.dataset.type);
    this.setData({
      filterType: type,
      currentTab: type,
      page: 1,
      list: []
    });
    this.initpage();
  },

  toDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/signindetail/index?jobid=" + id
    });
  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.setData({ page: 1, list: [] });
    this.initpage();
  },

  onReachBottom: function () {
    this.data.page = this.data.page + 1;
    this.initpage();
  },

  onShareAppMessage: function () {
    return {
      title: '签到管理',
      path: '/pages/index/index'
    }
  }
})
