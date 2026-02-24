// pages/mymoney/index.js
import { Token } from '../../utils/token.js';

import {My} from '../my/my-model.js';
var my=new My();

import { Coin } from '../../model/coin-model.js';
var coin = new Coin();

Page({

  data: {
    isuser:true,
    tel:'',
    usermoney:0,
    checkFlag: 0,
    coinBalance: 0,
    rechargeOptions: [],
    selectedAmount: 0
  },

  onLoad: function (options) {
    var that = this;
    var checkFlag = wx.getStorageSync('checkFlag') || 0;

    that.setData({ checkFlag: checkFlag });

    if (checkFlag == 1) {
      // 旧系统：佣金模式
      wx.setNavigationBarTitle({ title: '我的钱包' });

      var appuser = wx.getStorageSync('userInfo');
      var params = {};
      my.UserInit((data) => {
        that.data.usermoney = data.usermoney;
        that.setData({
          totalmoney: data.totalmoney
        });
        wx.hideNavigationBarLoading();
        wx.stopPullDownRefresh();
      }, params);

      that.setData({ userinfo: appuser });
    } else {
      // 章鱼外快：章鱼币模式
      wx.setNavigationBarTitle({ title: '我的钱包' });
      that.loadCoinBalance();
      that.loadRechargeOptions();
    }
  },

  loadCoinBalance: function () {
    var that = this;
    coin.getBalance(function (data) {
      if (data.status == 0) {
        that.setData({
          coinBalance: data.coin_balance
        });
      }
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, {});
  },

  loadRechargeOptions: function () {
    var that = this;
    coin.getRechargeOptions(function (data) {
      if (data.status == 0) {
        that.setData({ rechargeOptions: data.options });
      }
    }, {});
  },

  // 选择充值档位
  selectAmount: function (e) {
    var amount = e.currentTarget.dataset.amount;
    this.setData({ selectedAmount: amount });
  },

  // 充值
  doRecharge: function () {
    var that = this;
    if (that.data.selectedAmount <= 0) {
      wx.showToast({ title: '请选择充值档位', icon: 'none' });
      return;
    }

    wx.showModal({
      title: '充值确认',
      content: '确认充值 ' + that.data.selectedAmount + ' 章鱼币？',
      success: function (res) {
        if (res.confirm) {
          coin.recharge(function (data) {
            if (data.status == 0) {
              wx.showToast({ title: '充值成功', icon: 'success' });
              that.loadCoinBalance();
              that.setData({ selectedAmount: 0 });
            } else {
              wx.showToast({ title: data.msg, icon: 'none' });
            }
          }, { amount: that.data.selectedAmount });
        }
      }
    });
  },

  // 查看章鱼币明细
  toCoinRecords: function () {
    wx.navigateTo({
      url: "/pages/mymoneyrecord/index?type=coin"
    });
  },

  // 旧系统：提现
  toGetmoney: function () {
    var that = this;

    if (that.data.usermoney <= 0) {
      wx.showModal({
        title: '提示',
        content: '余额不足,无法提现',
        showCancel: false
      });
      return;
    } else {
      wx.showModal({
        title: '提示',
        content: '确认提现？',
        success: function (res) {
          if (res.confirm) {
            var params = {};
            my.getUserMoney((data) => {
              if (data.error == 0) {
                wx.showModal({
                  title: '提示',
                  content: '提现成功',
                  showCancel: false,
                  success: function () {
                    wx.navigateTo({
                      url: "/pages/mymoneyrecord/index"
                    });
                  }
                });
              } else {
                wx.showModal({
                  title: '提示',
                  content: data.msg,
                  showCancel: false
                });
              }
            }, params);
          }
        }
      });
    }
  },

  toMymoneyrecord: function () {
    wx.navigateTo({
      url: "/pages/mymoneyrecord/index"
    });
  },

  onShow: function () {
    this.onLoad();
  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.onLoad();
  },

  onShareAppMessage: function () {
    return {
      title: '我的钱包',
      path: '/pages/user/index'
    };
  }
})
