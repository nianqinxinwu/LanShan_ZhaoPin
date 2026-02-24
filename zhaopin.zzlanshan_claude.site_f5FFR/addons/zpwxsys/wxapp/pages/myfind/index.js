import { Findjob } from '../findjob/findjob-model.js';
var findjob = new Findjob(); //实例化 首页 对象
Page({

  /**
   * 页面的初始数据
   */
  data: {
    status:-1,
    currentTab: -1,
    page:1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var that = this;

    wx.setNavigationBarTitle({
      title: '我的报名',
    })

    if (e && e.id) {
      var initStatus = parseInt(e.id);
      that.setData({
        status: initStatus,
        currentTab: initStatus
      });
    }

    that.initpage();

  },

 initpage:function()
{
    var that = this;

    wx.setNavigationBarTitle({
      title: '我的报名',
    })


    var params = {status:that.data.status,page:that.data.page};

    findjob.myFindjob((data) => {

      that.setData({
        list: data.jobrecordlist || []
      });

      wx.hideNavigationBarLoading(); //完成停止加载
      wx.stopPullDownRefresh();


  },params);

},

  switchTab: function (e) {
    var that = this;
    var status = parseInt(e.currentTarget.dataset.status);
    that.setData({
      status: status,
      currentTab: status,
      page: 1,
      list: []
    });
    that.initpage();
  },


  toSetComment: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/setcomment/index?id=" + id
    })
  },

  doConfirmWork: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var idx = e.currentTarget.dataset.idx;
    var item = that.data.list[idx];
    // 检查是否可点击
    if (!item || item.signin_phase < 1 || item.status >= 4) return;
    wx.showModal({
      title: '工作确认',
      content: '确认开始工作？',
      success: function (res) {
        if (res.confirm) {
          var params = { id: id };
          findjob.confirmWork((data) => {
            if (data.status == 1) {
              wx.showToast({ title: data.msg || '确认成功', icon: 'none', duration: 2000 });
              that.setData({ page: 1, list: [] });
              that.initpage();
            } else {
              wx.showToast({ title: data.msg || '操作失败', icon: 'none', duration: 2000 });
            }
          }, params);
        }
      }
    })
  },

  doSignIn: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var idx = e.currentTarget.dataset.idx;
    var item = that.data.list[idx];
    // 检查是否可点击
    if (!item || item.signin_phase < 2 || item.status != 4 || item.signed_in == 1) return;
    wx.showModal({
      title: '签到',
      content: '确认签到？',
      success: function (res) {
        if (res.confirm) {
          var params = { id: id };
          findjob.doSignIn((data) => {
            if (data.status == 1) {
              wx.showToast({ title: data.msg || '签到成功', icon: 'none', duration: 2000 });
              that.setData({ page: 1, list: [] });
              that.initpage();
            } else {
              wx.showToast({ title: data.msg || '操作失败', icon: 'none', duration: 2000 });
            }
          }, params);
        }
      }
    })
  },

  doSignOut: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var idx = e.currentTarget.dataset.idx;
    var item = that.data.list[idx];
    // 检查是否可点击
    if (!item || item.signin_phase < 3 || item.status != 4 || item.signed_in != 1) return;
    wx.showModal({
      title: '签退',
      content: '确认签退？',
      success: function (res) {
        if (res.confirm) {
          var params = { id: id };
          findjob.doSignOut((data) => {
            if (data.status == 1) {
              wx.showToast({ title: data.msg || '签退成功', icon: 'none', duration: 2000 });
              that.setData({ page: 1, list: [] });
              that.initpage();
            } else {
              wx.showToast({ title: data.msg || '操作失败', icon: 'none', duration: 2000 });
            }
          }, params);
        }
      }
    })
  },

  toJob: function (e) {
    var pid = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/zwjobdetail/index?id=" + pid
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.initpage();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

    var that = this;
  
    this.data.page = this.data.page+1;
    that.initpage();


  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
 var that = this;
    return {
        title:'我的报名' ,
        path: '/pages/myfind/index'
    }
  }
})