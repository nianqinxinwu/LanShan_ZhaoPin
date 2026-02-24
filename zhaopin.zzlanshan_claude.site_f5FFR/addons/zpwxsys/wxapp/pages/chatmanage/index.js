import { Chat } from '../../model/chat-model.js';
var chat = new Chat();

Page({
  data: {
    groupid: 0,
    notice: '',
    members: [],
    myRole: 0
  },

  onLoad: function (e) {
    var that = this;
    that.setData({ groupid: e.groupid });
    wx.setNavigationBarTitle({ title: '群管理' });
    that.loadMembers();
    that.loadDetail();
  },

  loadDetail: function () {
    var that = this;
    chat.groupDetail(function (data) {
      if (data.status == 1) {
        that.setData({ notice: data.group.notice });
      }
    }, { groupid: that.data.groupid });
  },

  loadMembers: function () {
    var that = this;
    chat.memberList(function (data) {
      if (data.status == 1) {
        that.setData({
          members: data.members || [],
          myRole: data.myRole
        });
      }
    }, { groupid: that.data.groupid });
  },

  onNoticeInput: function (e) {
    this.setData({ notice: e.detail.value });
  },

  saveNotice: function () {
    var that = this;
    chat.editNotice(function (data) {
      wx.showToast({ title: data.msg, icon: data.status == 1 ? 'success' : 'none' });
    }, { groupid: that.data.groupid, notice: that.data.notice });
  },

  doMute: function (e) {
    var that = this;
    var uid = e.currentTarget.dataset.uid;
    var mute = e.currentTarget.dataset.mute;
    chat.muteMember(function (data) {
      wx.showToast({ title: data.msg, icon: data.status == 1 ? 'success' : 'none' });
      if (data.status == 1) that.loadMembers();
    }, { groupid: that.data.groupid, targetUid: uid, mute: mute });
  },

  doKick: function (e) {
    var that = this;
    var uid = e.currentTarget.dataset.uid;
    wx.showModal({
      title: '提示',
      content: '确认移出该成员？',
      success: function (res) {
        if (res.confirm) {
          chat.kickMember(function (data) {
            wx.showToast({ title: data.msg, icon: data.status == 1 ? 'success' : 'none' });
            if (data.status == 1) that.loadMembers();
          }, { groupid: that.data.groupid, targetUid: uid });
        }
      }
    });
  }
});
