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

  },

  goHousexy:function(){

    wx.navigateTo({
      url: "/pages/fxrule/index"
     })

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {

    var that = this;

    if (that.data.id > 0) {
      var id = that.data.id;
    } else {
      var id = e.id;
      that.data.id = e.id;
    }

    var params = { id:that.data.id};

    wx.setNavigationBarColor({
      frontColor: '#ffffff',
      backgroundColor: '#ff6732',
      animation: {
        duration: 400,
        timingFunc: 'easeIn'
      }
    })

    task.GetTaskdetail((data) => {

      console.log(data);
      that.setData({
        taskinfo:data,
        jobinfo:data.jobinfo,
        companyinfo:data.companyinfo
      });
  },params);
    



  },


  toAgentcenter:function()
  {

    var that = this;

  

    var params = {taskid:that.data.id};
 
    agent.Checkagent((data) => {

      if(data.status == 0 )
      {
        wx.navigateTo({
          url: "/pages/regagent/index"
        })

      }else if(data.status == 1){
       
        taskrecord.SaveTaskRecord((rdata) => {


          if(rdata.status == 0 )
          {

          
    
                wx.showModal({
                  title: '提示',
                  content: '领取成功',
                  showCancel: false,
                  success:function(){

                        wx.navigateTo({
                          url: '/pages/mytasklist/index',
                        })

                  }
                })
           
          }else{


            wx.showToast({
              title: rdata.msg,
              icon: 'none',
              duration: 2000
             })
            

          }




        },params);

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