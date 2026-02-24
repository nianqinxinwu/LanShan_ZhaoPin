import { Agent } from '../../model/agent-model.js';

var agent  = new Agent();
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.setNavigationBarTitle({
      title: '经纪人入驻',
    })
  },

  savepubinfo: function (e) {

    var that = this;
 
    var name = e.detail.value.name;
    var tel = e.detail.value.tel;
    var weixin = e.detail.value.weixin;
    var email = e.detail.value.email;

   var tid =  wx.getStorageSync('tid');
   if(!tid)
    tid = 0 ;

    if (name == "") {
      wx.showModal({
        title: '提示',
        content: '请输入您的姓名',
        showCancel: false
      })
      return
    }
 
   
    if (tel == "") {
      wx.showModal({
        title: '提示',
        content: '请输入您的手机号',
        showCancel: false
      })
      return
    }

    if (weixin == "") {
      wx.showModal({
        title: '提示',
        content: '请输入您的微信号',
        showCancel: false
      })
      return
    }

    
    var params = {
      tid:tid,
      name: name,
      weixin: weixin,
      tel: tel
    };

    agent.Saveagent((data) => {

      if(data.status == 0 )
      {
        wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false,
          success:function(){

            wx.reLaunch({
              url: '/pages/user/index',
            })
          }
        })

      }else{

        wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false
        })
        return
        
      }
      
  },params);


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

  }
})