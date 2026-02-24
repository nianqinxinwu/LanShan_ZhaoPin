import { Chat } from '../../model/chat-model.js';
var chat = new Chat();

Page({
  data: {
    rule: { title: '', content: '' },
    canAgree: false,
    groupid: 0
  },

  onLoad: function (e) {
    var that = this;
    that.data.groupid = e.groupid;
    wx.setNavigationBarTitle({ title: '群聊公约' });
    chat.getRule(function (data) {
      that.setData({ rule: data.rule });
      // After content renders, check if it fits in one screen
      setTimeout(function () {
        that.checkContentFit();
      }, 300);
    }, {});
  },

  checkContentFit: function () {
    var that = this;
    if (that.data.canAgree) return;
    var sysInfo = wx.getSystemInfoSync();
    var windowHeight = sysInfo.windowHeight;
    var query = wx.createSelectorQuery();
    query.select('.agree-section').boundingClientRect();
    query.exec(function (res) {
      // If the agree section is already visible on screen, content is short enough
      if (res[0] && res[0].top < windowHeight) {
        that.setData({ canAgree: true });
      }
    });
  },

  onReachBottom: function () {
    // User scrolled to the bottom of the page — they've seen all content
    if (!this.data.canAgree) {
      this.setData({ canAgree: true });
    }
  },

  onPageScroll: function () {
    if (!this.data.canAgree) {
      var that = this;
      var query = wx.createSelectorQuery();
      query.select('.agree-section').boundingClientRect();
      query.exec(function (res) {
        if (res[0] && res[0].top < wx.getSystemInfoSync().windowHeight) {
          that.setData({ canAgree: true });
        }
      });
    }
  },

  onAgree: function () {
    var that = this;
    var params = { groupid: that.data.groupid };
    chat.agreeRule(function (data) {
      if (data.status == 1) {
        wx.showToast({ title: '已同意公约', icon: 'success' });
        setTimeout(function () {
          wx.navigateBack({ delta: 1 });
        }, 1000);
      }
    }, params);
  }
});
