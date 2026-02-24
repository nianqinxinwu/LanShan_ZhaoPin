import { Task } from '../../model/task-model.js';
var task  = new Task();

import { Taskrecord } from '../../model/taskrecord-model.js';
var taskrecord  = new Taskrecord();

import { Agent } from '../../model/agent-model.js';
var agent  = new Agent();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    page:1
  },

  toSendtask:function(e)
  {
    var that = this;
    var id = e.currentTarget.dataset.id;

    wx.navigateTo({
        url: "/pages/sendtask/index?id="+id
      })



 
  },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {

    var that = this;

    wx.setNavigationBarTitle({
      title: '我的任务',
    })

    that.initpage();


  },

  initpage:function(){

    var that = this;

    var params = {};
 
    agent.Checkagent((data) => {

      if(data.status == 0 )
      {
        wx.navigateTo({
          url: "/pages/regagent/index"
        })

      }else if(data.status == 1){
       


        var params = {page:that.data.page};

        taskrecord.GetTaskRecordList((rdata) => {
    
       
          that.setData({
    
            tasklist:rdata.taskrecordlist
          });



      },params);
    





      }else{

        wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false
        })
        return

      }


      wx.hideNavigationBarLoading(); 
      wx.stopPullDownRefresh();

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
        title:'我的任务' ,
        path: '/pages/index/index'
    }
  }
})