import { Company } from '../../model/company-model.js';

var company = new Company();
Page({

  data: {
    page: 1,
    list: []
  },

  onLoad: function (options) {
    var that = this;

    wx.setNavigationBarTitle({
      title: '任务管理',
    })

    that.initpage();
  },

  initpage: function () {
    var that = this;

    company.checkLogin(() => {

      var ctoken = wx.getStorageSync('ctoken');

      if (ctoken) {
        var params = { ctoken: ctoken, page: that.data.page, type: 'task' };

        company.companyjob((data) => {

          that.setData({
            list: data.joblist
          });

        }, params);

      } else {

        wx.navigateTo({
          url: "/pages/companylogin/index"
        })
      }

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();

    });

  },

  addcompanyjob: function () {
    wx.navigateTo({
      url: "/pages/addcompanyjob/index?type=task"
    })
  },

  editCompanyjob: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.redirectTo({
      url: "/pages/editcompanyjob/index?id=" + id
    })
  },

  toMatchnote: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/matchnote/index?id=" + id
    })
  },

  toSharejob: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/sharejob/index?id=" + id
    })
  },

  doCompanyendtime: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var ctoken = wx.getStorageSync('ctoken');
    var params = { ctoken: ctoken, id: id };

    company.doCompanyendtime((data) => {
      wx.showToast({
        title: data.msg || '同步成功',
        icon: 'none',
        duration: 2000
      })
      that.initpage();
    }, params);
  },

  topPaytopjob: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var companyid = wx.getStorageSync('companyid');
    var params = { companyid: companyid, id: id };

    wx.showModal({
      title: '提示',
      content: '您的操作将会消耗置顶1次？',
      success: function (res) {
        if (res.confirm) {
          company.topJob((data) => {
            if (data.status == 0) {
              wx.showModal({
                title: '提示',
                content: data.msg,
                showCancel: false,
                success: function () {
                  that.initpage();
                }
              })
            } else {
              wx.showToast({
                title: data.msg,
                icon: 'none',
                duration: 2000
              })
            }
          }, params);
        }
      }
    })
  },

  cancleJob: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var ctoken = wx.getStorageSync('ctoken');
    var params = { ctoken: ctoken, id: id };

    wx.showModal({
      title: '下架',
      content: '确认下架？',
      success: function (res) {
        if (res.confirm) {
          company.cancleJob((data) => {
            that.initpage();
          }, params);
        }
      }
    })
  },

  upJob: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var ctoken = wx.getStorageSync('ctoken');
    var params = { ctoken: ctoken, id: id };

    wx.showModal({
      title: '上架',
      content: '确认上架？',
      success: function (res) {
        if (res.confirm) {
          company.upJob((data) => {
            that.initpage();
          }, params);
        }
      }
    })
  },

  onReady: function () {},

  onShow: function () {},

  onHide: function () {},

  onUnload: function () {},

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.initpage();
  },

  onReachBottom: function () {
    this.data.page = this.data.page + 1;
    this.initpage();
  },

  onShareAppMessage: function () {
    return {
      title: '任务管理',
      path: '/pages/index/index'
    }
  }
})
