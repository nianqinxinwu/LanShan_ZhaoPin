// pages/mypoint/index.js
import { Coin } from '../../model/coin-model.js';
var coin = new Coin();

Page({

  data: {
    pointBalance: 0,
    exchangePoints: 0,
    exchangeCoins: 0
  },

  onLoad: function () {
    wx.setNavigationBarTitle({ title: '我的积分' });
    this.loadPointBalance();
  },

  loadPointBalance: function () {
    var that = this;
    coin.getBalance(function (data) {
      if (data.status == 0) {
        var pb = data.point_balance || 0;
        var ep = Math.floor(pb / 10) * 10;
        that.setData({
          pointBalance: pb,
          exchangePoints: pb >= 10 ? ep : 0,
          exchangeCoins: pb >= 10 ? Math.floor(pb / 10) : 0
        });
      }
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, {});
  },

  // 积分兑换
  doExchange: function () {
    var that = this;

    if (that.data.pointBalance < 10) {
      wx.showToast({ title: '最少需要10积分才能兑换', icon: 'none' });
      return;
    }

    var points = Math.floor(that.data.pointBalance / 10) * 10;
    var coins = points / 10;

    wx.showModal({
      title: '积分兑换',
      content: '将 ' + points + ' 积分兑换为 ' + coins + ' 章鱼币？',
      success: function (res) {
        if (res.confirm) {
          coin.exchangePoints(function (data) {
            if (data.status == 0) {
              wx.showToast({ title: '兑换成功', icon: 'success' });
              that.loadPointBalance();
            } else {
              wx.showToast({ title: data.msg, icon: 'none' });
            }
          }, { points: points });
        }
      }
    });
  },

  // 查看积分明细
  toPointRecords: function () {
    wx.navigateTo({
      url: "/pages/mymoneyrecord/index?type=point"
    });
  },

  onShow: function () {
    this.loadPointBalance();
  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.loadPointBalance();
  },

  onShareAppMessage: function () {
    return {
      title: '我的积分',
      path: '/pages/user/index'
    };
  }
})
