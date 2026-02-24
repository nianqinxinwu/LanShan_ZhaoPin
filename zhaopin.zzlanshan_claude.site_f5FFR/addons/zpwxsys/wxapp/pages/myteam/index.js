import { Agent } from '../../model/agent-model.js';
var agent  = new Agent();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    ordertype:1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;

    wx.setNavigationBarTitle({
      title: '我的邀请',
    })

    that.initPage();

  },

  initPage:function(){

    var that = this;

    var params = {};

    agent.myAgentTeam((data) => {

        
        that.setData({
          agentlevel_one:data.agentlevel_one,
          agentlevel_two:data.agentlevel_two,
          ordertype:that.data.ordertype
        });
        wx.hideNavigationBarLoading(); //完成停止加载
        wx.stopPullDownRefresh();

    },params);
  },


  tabClick: function (e) {

    var that = this;
    var ordertype = e.currentTarget.id;
    that.data.ordertype = ordertype;
    that.setData({
      ordertype: ordertype
    })

    that.initPage();



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
    this.onLoad();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    var that = this;
    return {
        title:'我的邀请' ,
        path: '/pages/index/index'
    }
  }
})