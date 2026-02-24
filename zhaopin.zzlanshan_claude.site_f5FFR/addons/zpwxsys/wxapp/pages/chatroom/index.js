import { Chat } from '../../model/chat-model.js';
var chat = new Chat();

Page({
  data: {
    groupid: 0,
    group: {},
    member: {},
    msglist: [],
    inputValue: '',
    scrollToMsg: '',
    loading: false,
    lastMsgId: 0,
    pollingTimer: null,
    socketConnected: false
  },

  onLoad: function (e) {
    var that = this;
    that.setData({ groupid: e.groupid });
    that.loadGroupDetail();
    that.loadMessages();
  },

  onShow: function () {
    var that = this;
    if (that.data.groupid) {
      that.loadGroupDetail();
      // Immediately fetch latest messages on re-entry
      if (that.data.lastMsgId > 0) {
        that.pollNewMessages();
      } else {
        that.loadMessages();
      }
      that.startPolling();
    }
  },

  onHide: function () {
    this.stopPolling();
    this.closeSocket();
  },

  onUnload: function () {
    this.stopPolling();
    this.closeSocket();
  },

  loadGroupDetail: function () {
    var that = this;
    var params = { groupid: that.data.groupid };
    chat.groupDetail(function (data) {
      if (data.status == 1) {
        that.setData({
          group: data.group,
          member: data.member
        });
        wx.setNavigationBarTitle({ title: data.group.group_name });
        that.connectSocket();
      } else {
        wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false,
          success: function () {
            wx.navigateBack({ delta: 1 });
          }
        });
      }
    }, params);
  },

  loadMessages: function () {
    var that = this;
    // Only show loading indicator when there are no messages yet
    if (that.data.msglist.length == 0) {
      that.setData({ loading: true });
    }
    var params = { groupid: that.data.groupid, lastId: 0 };
    chat.messages(function (data) {
      that.setData({ loading: false });
      if (data.status == 1 && data.msglist) {
        var list = data.msglist;
        var lastId = 0;
        if (list.length > 0) {
          lastId = list[list.length - 1].id;
        }
        that.setData({
          msglist: list,
          lastMsgId: lastId
        });
        that.scrollToBottom();
      }
    }, params);
    // Safety timeout: clear loading after 5 seconds to prevent permanent display
    setTimeout(function () {
      if (that.data.loading) {
        that.setData({ loading: false });
      }
    }, 5000);
  },

  pollNewMessages: function () {
    var that = this;
    // If no messages loaded yet, do a full load instead
    if (that.data.lastMsgId == 0) {
      that.loadMessages();
      return;
    }
    var params = { groupid: that.data.groupid, lastId: that.data.lastMsgId };
    chat.messages(function (data) {
      if (data.status == 1 && data.msglist && data.msglist.length > 0) {
        var oldList = that.data.msglist;
        var newList = oldList.concat(data.msglist);
        var lastId = data.msglist[data.msglist.length - 1].id;
        that.setData({
          msglist: newList,
          lastMsgId: lastId
        });
        that.scrollToBottom();
      }
    }, params);
  },

  // WebSocket 连接
  connectSocket: function () {
    var that = this;
    var wsUrl = this.getWsUrl();
    if (!wsUrl) {
      // WebSocket 不可用时用轮询
      that.startPolling();
      return;
    }

    wx.connectSocket({
      url: wsUrl,
      success: function () {
        console.log('WebSocket connecting...');
      }
    });

    wx.onSocketOpen(function () {
      that.setData({ socketConnected: true });
      that.stopPolling();
      // 发送认证消息
      wx.sendSocketMessage({
        data: JSON.stringify({
          type: 'auth',
          groupid: that.data.groupid,
          token: wx.getStorageSync('token')
        })
      });
    });

    wx.onSocketMessage(function (res) {
      var msg = JSON.parse(res.data);
      if (msg.type == 'message') {
        var oldList = that.data.msglist;
        oldList.push(msg.data);
        that.setData({
          msglist: oldList,
          lastMsgId: msg.data.id
        });
        that.scrollToBottom();
      }
    });

    wx.onSocketClose(function () {
      that.setData({ socketConnected: false });
      that.startPolling();
    });

    wx.onSocketError(function () {
      that.setData({ socketConnected: false });
      that.startPolling();
    });
  },

  closeSocket: function () {
    if (this.data.socketConnected) {
      wx.closeSocket();
      this.setData({ socketConnected: false });
    }
  },

  getWsUrl: function () {
    // 从配置获取 WebSocket 地址
    var wsUrl = wx.getStorageSync('wsUrl');
    return wsUrl || '';
  },

  // 轮询回退方案
  startPolling: function () {
    var that = this;
    that.stopPolling();
    if (!that.data.socketConnected) {
      that.data.pollingTimer = setInterval(function () {
        that.pollNewMessages();
      }, 3000);
    }
  },

  stopPolling: function () {
    if (this.data.pollingTimer) {
      clearInterval(this.data.pollingTimer);
      this.data.pollingTimer = null;
    }
  },

  onInput: function (e) {
    this.setData({ inputValue: e.detail.value });
  },

  doSend: function () {
    var that = this;
    var content = that.data.inputValue.trim();
    if (!content) return;

    var params = { groupid: that.data.groupid, content: content };
    chat.send(function (data) {
      if (data.status == 1) {
        that.setData({ inputValue: '' });
        // 直接将消息追加到列表展示
        if (data.msgItem) {
          var oldList = that.data.msglist;
          oldList.push(data.msgItem);
          that.setData({
            msglist: oldList,
            lastMsgId: data.msgItem.id
          });
          that.scrollToBottom();
        } else if (!that.data.socketConnected) {
          that.pollNewMessages();
        }
      } else {
        wx.showToast({ title: data.msg, icon: 'none' });
      }
    }, params);
  },

  scrollToBottom: function () {
    var that = this;
    var list = that.data.msglist;
    if (list.length > 0) {
      that.setData({ scrollToMsg: 'msg-' + list[list.length - 1].id });
    }
  },

  showNotice: function () {
    wx.showModal({
      title: '群公告',
      content: this.data.group.notice,
      showCancel: false
    });
  },

  toRule: function () {
    wx.navigateTo({
      url: '/pages/chatrule/index?groupid=' + this.data.groupid
    });
  },

  toManage: function () {
    wx.navigateTo({
      url: '/pages/chatmanage/index?groupid=' + this.data.groupid
    });
  },

  toSelectNote: function () {
    var jobid = this.data.group.jobid;
    if (jobid) {
      wx.navigateTo({
        url: '/pages/selectnote/index?jobid=' + jobid
      });
    }
  },

  chooseImage: function () {
    var that = this;
    wx.chooseImage({
      count: 1,
      sizeType: ['compressed'],
      sourceType: ['album', 'camera'],
      success: function (res) {
        var tempFilePath = res.tempFilePaths[0];
        that.uploadAndSendImage(tempFilePath);
      }
    });
  },

  uploadAndSendImage: function (filePath) {
    var that = this;
    wx.showLoading({ title: '发送中...' });
    chat.uploadImage(function (data) {
      wx.hideLoading();
      if (data.status == 1 && data.img_url) {
        var params = {
          groupid: that.data.groupid,
          content: '[图片]',
          msg_type: 2,
          img_url: data.img_url
        };
        chat.send(function (sendData) {
          if (sendData.status == 1) {
            if (sendData.msgItem) {
              var oldList = that.data.msglist;
              oldList.push(sendData.msgItem);
              that.setData({
                msglist: oldList,
                lastMsgId: sendData.msgItem.id
              });
              that.scrollToBottom();
            } else if (!that.data.socketConnected) {
              that.pollNewMessages();
            }
          } else {
            wx.showToast({ title: sendData.msg, icon: 'none' });
          }
        }, params);
      } else {
        wx.showToast({ title: data.msg || '上传失败', icon: 'none' });
      }
    }, filePath);
  },

  previewImage: function (e) {
    var url = e.currentTarget.dataset.url;
    var urls = [];
    var msglist = this.data.msglist;
    for (var i = 0; i < msglist.length; i++) {
      if (msglist[i].msg_type == 2 && msglist[i].img_url) {
        urls.push(msglist[i].img_url);
      }
    }
    if (urls.length === 0) {
      urls.push(url);
    }
    wx.previewImage({
      current: url,
      urls: urls
    });
  }
});
