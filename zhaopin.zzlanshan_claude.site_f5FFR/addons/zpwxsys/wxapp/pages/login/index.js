import {User} from '../../model/user-model.js';

var user = new User();

Page({

  data: {
    tel: ''
  },

  onLoad: function (options) {
    wx.setNavigationBarTitle({
      title: '用户登录',
    })
  },

  onTelInput: function(e) {
    this.setData({ tel: e.detail.value });
  },

  onGetPhoneNumber: function(e) {
    var that = this;
    if (e.detail.errMsg == 'getPhoneNumber:ok') {
      var params = {
        encryptedData: e.detail.encryptedData,
        iv: e.detail.iv
      };
      user.getPhone(function(data) {
        if (data && data.tel) {
          that.setData({ tel: data.tel });
        }
      }, params);
    }
  },

  doLogin: function() {
    var that = this;
    var tel = that.data.tel;

    if (!tel || tel.length != 11) {
      wx.showToast({ title: '请输入正确手机号', icon: 'none' });
      return;
    }

    var params = { tel: tel };
    user.userLogin(function(data) {
      if (data.status == 1) {
        wx.showToast({ title: '登录成功', icon: 'success' });
        setTimeout(function() {
          wx.navigateBack();
        }, 1000);
      } else {
        wx.showToast({ title: data.msg || '登录失败', icon: 'none' });
      }
    }, params);
  },

  toPerRegister: function() {
    wx.navigateTo({
      url: "/pages/register/index"
    })
  },

  onReady: function () {},
  onShow: function () {},
  onHide: function () {},
  onUnload: function () {},
  onPullDownRefresh: function () {},
  onReachBottom: function () {},
  onShareAppMessage: function () {}
})
