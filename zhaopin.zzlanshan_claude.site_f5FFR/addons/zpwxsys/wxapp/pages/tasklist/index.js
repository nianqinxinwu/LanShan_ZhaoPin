import { Company } from '../../model/company-model.js';

var company  = new Company();
import { Task } from '../../model/task-model.js';

var task  = new Task();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    page:1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;

    wx.setNavigationBarTitle({
      title: '任务管理',
    })
    
    that.initpage();

  },

  initpage:function(){

    var that = this;

    company.checkLogin(() => {
  
        

        var ctoken = wx.getStorageSync('ctoken');
        var params = {ctoken:ctoken,page:that.data.page};

      task.Getmytasklist((data) => {
  
        that.setData({
          list:data.tasklist
        
        });
                                  
          },params);


          wx.hideNavigationBarLoading(); //完成停止加载
          wx.stopPullDownRefresh();


  });

  },


  toAddtask:function(){

    wx.navigateTo({
      url: "/pages/addtask/index"
     })

  }, editTask:function(e){

    var id = e.currentTarget.dataset.id;

    wx.navigateTo({
      url: "/pages/edittask/index?id=" + id
    })
  },

  cancleTask:function(e){

    var that = this;

    var id = e.currentTarget.dataset.id;

    var ctoken = wx.getStorageSync('ctoken');

    var params = {ctoken:ctoken,id:id};
        wx.showModal({
          title: '下架',
          content: '确认下架？',
          success: function (res) {
            if (res.confirm) {

                company.cancleTask((data) => {

                  that.onLoad();
                                            
                    },params);


            }
          }

        })

   
  }, upTask: function (e) {

    var that = this;

    var id = e.currentTarget.dataset.id;

    var ctoken = wx.getStorageSync('ctoken');
    var params = {ctoken:ctoken,id:id};
        wx.showModal({
          title: '上架',
          content: '确认上架？',
          success: function (res) {
            if (res.confirm) {

                company.upTask((data) => {

                  that.onLoad();
                                            
                    },params);


            }
          }

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
        title:'任务管理' ,
        path: '/pages/index/index'
    }
  }
})