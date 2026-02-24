import { Chat } from '../../model/chat-model.js';
var chat = new Chat();

Page({
  data: {
    grouplist: [],
    page: 1
  },

  onShow: function () {
    var that = this;
    wx.setNavigationBarTitle({ title: '我的群聊' });
    that.setData({ page: 1, grouplist: [] });
    that.loadList();
  },

  loadList: function () {
    var that = this;
    var params = { page: that.data.page };
    chat.chatList(function (data) {
      that.setData({ grouplist: data.grouplist || [] });
    }, params);
  },

  toChat: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: '/pages/chatroom/index?groupid=' + id
    });
  },

  toSelectNote: function (e) {
    var jobid = e.currentTarget.dataset.jobid;
    wx.navigateTo({
      url: '/pages/selectnote/index?jobid=' + jobid
    });
  },

  onPullDownRefresh: function () {
    this.setData({ page: 1 });
    this.loadList();
    wx.stopPullDownRefresh();
  },

  onReachBottom: function () {
    this.data.page++;
    this.loadList();
  }
});
