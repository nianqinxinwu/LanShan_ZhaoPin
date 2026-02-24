import { Token } from '../../utils/token.js';

import {My} from '../my/my-model.js';
var my=new My();

import { Coin } from '../../model/coin-model.js';
var coin = new Coin();

Page({
  data: {
    checkFlag: 0,
    recordType: '', // coin, point, or empty (legacy)
    recordlist: [],
    balance: 0
  },

  onLoad: function (options) {
    var that = this;
    var checkFlag = wx.getStorageSync('checkFlag') || 0;
    var recordType = options.type || '';

    that.setData({
      checkFlag: checkFlag,
      recordType: recordType
    });

    if (recordType == 'coin') {
      wx.setNavigationBarTitle({ title: '章鱼币明细' });
      that.loadCoinRecords();
    } else if (recordType == 'point') {
      wx.setNavigationBarTitle({ title: '积分明细' });
      that.loadPointRecords();
    } else {
      // 旧系统佣金明细
      wx.setNavigationBarTitle({ title: '佣金明细' });
      var params = {};
      my.getMoneyRecord((data) => {
        that.setData({ moneylist: data.moneylist });
        wx.hideNavigationBarLoading();
        wx.stopPullDownRefresh();
      }, params);
    }
  },

  loadCoinRecords: function () {
    var that = this;
    coin.getCoinRecords(function (data) {
      if (data.status == 0) {
        that.setData({
          recordlist: data.recordlist,
          balance: data.balance
        });
      }
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, {});
  },

  loadPointRecords: function () {
    var that = this;
    coin.getPointRecords(function (data) {
      if (data.status == 0) {
        that.setData({
          recordlist: data.recordlist,
          balance: data.balance
        });
      }
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, {});
  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.onLoad({ type: this.data.recordType });
  },

  onShareAppMessage: function () {
    return {
      title: '消费明细',
      path: '/pages/index/index'
    };
  }
})
