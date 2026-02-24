import { Company } from '../../model/company-model.js';

var company = new Company();
Page({

  data: {
    jobid: '',
    jobinfo: {},
    page: 1,
    recordList: [],
    todayStr: ''
  },

  onLoad: function (options) {
    var that = this;
    if (options.jobid) {
      that.setData({ jobid: options.jobid });
    }
    var now = new Date();
    var y = now.getFullYear();
    var m = now.getMonth() + 1;
    var d = now.getDate();
    that.setData({ todayStr: y + '年' + (m < 10 ? '0' + m : m) + '月' + (d < 10 ? '0' + d : d) + '日' });
    that.initpage();
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
      jobid: that.data.jobid,
      page: that.data.page
    };

    company.getSigninDetail((data) => {
      that.setData({
        jobinfo: data.jobinfo || {},
        recordList: data.recordlist || []
      });

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, params);
  },

  doConfirmWork: function () {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认对所有待确认人员发起工作确认？',
      success: function (res) {
        if (res.confirm) {
          var ctoken = wx.getStorageSync('ctoken');
          company.batchSigninAction(function (data) {
            if (data.status == 1) {
              wx.showToast({ title: '操作成功', icon: 'success' });
              that.setData({ page: 1, recordList: [] });
              that.initpage();
            } else {
              wx.showToast({ title: data.msg || '操作失败', icon: 'none' });
            }
          }, { ctoken: ctoken, jobid: that.data.jobid, action: 'confirmWork' });
        }
      }
    });
  },

  doBatchSignIn: function () {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认对所有已确认人员发起批量签到？',
      success: function (res) {
        if (res.confirm) {
          var ctoken = wx.getStorageSync('ctoken');
          company.batchSigninAction(function (data) {
            if (data.status == 1) {
              wx.showToast({ title: '操作成功', icon: 'success' });
              that.setData({ page: 1, recordList: [] });
              that.initpage();
            } else {
              wx.showToast({ title: data.msg || '操作失败', icon: 'none' });
            }
          }, { ctoken: ctoken, jobid: that.data.jobid, action: 'signIn' });
        }
      }
    });
  },

  doBatchSignOut: function () {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确认对所有已签到人员发起批量签退？',
      success: function (res) {
        if (res.confirm) {
          var ctoken = wx.getStorageSync('ctoken');
          company.batchSigninAction(function (data) {
            if (data.status == 1) {
              wx.showToast({ title: '操作成功', icon: 'success' });
              that.setData({ page: 1, recordList: [] });
              that.initpage();
            } else {
              wx.showToast({ title: data.msg || '操作失败', icon: 'none' });
            }
          }, { ctoken: ctoken, jobid: that.data.jobid, action: 'signOut' });
        }
      }
    });
  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.setData({ page: 1, recordList: [] });
    this.initpage();
  },

  onReachBottom: function () {
    this.data.page = this.data.page + 1;
    this.initpage();
  },

  onShareAppMessage: function () {
    return {
      title: '签到详情',
      path: '/pages/index/index'
    }
  }
})
