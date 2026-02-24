import { User } from '../../model/user-model.js';
var user = new User();

import { Company } from '../../model/company-model.js';
var company = new Company();

Page({
  data: {},

  onLoad: function () {
    wx.setNavigationBarTitle({
      title: '身份选择',
    })
  },

  /**
   * 选择报名者身份
   */
  selectApplicant: function () {
    var params = { user_role: 1 };

    user.setUserRole(function (data) {
      if (data.status == 1) {
        wx.setStorageSync('user_role', 1);
        // 报名者流程完成，返回"我的"页面
        wx.redirectTo({
          url: '/pages/user/index'
        })
      } else {
        wx.showToast({
          title: data.msg || '设置失败',
          icon: 'none'
        })
      }
    }, params);
  },

  /**
   * 选择发布者身份
   */
  selectPublisher: function () {
    var params = { user_role: 2 };

    user.setUserRole(function (data) {
      if (data.status == 1) {
        wx.setStorageSync('user_role', 2);
        wx.setStorageSync('checkFlag', 1);

        // 发布者：尝试自动登录企业（checkLogin内含自动登录）
        company.checkLogin(function (loginData) {
          if (loginData && loginData.error == 0) {
            wx.redirectTo({
              url: '/pages/companycenter/index'
            })
          } else {
            wx.redirectTo({
              url: '/pages/companylogin/index'
            })
          }
        });
      } else {
        wx.showToast({
          title: data.msg || '设置失败',
          icon: 'none'
        })
      }
    }, params);
  },

  onReady: function () {},
  onShow: function () {},
  onHide: function () {},
  onUnload: function () {},

  onShareAppMessage: function () {
    return {
      title: '身份选择',
      path: '/pages/loginrole/index'
    }
  }
})
