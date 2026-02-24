import { Note } from '../findworker/note-model.js';

var note  = new Note();

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
      title: '我的人才库',
    })

    that.initpage();


  },


  initpage:function(){
    var that = this;

    var params = {page:that.data.page};

    note.getAgentNoteListData((data) => {

      console.log(data);
      that.setData({
          notelist: data.notelist
      });


      wx.hideNavigationBarLoading(); 
      wx.stopPullDownRefresh();

  },params);

  },


  toWorkerDetail:function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/workerdetail/index?id=" + id
    })

  }
  ,

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
        title:'我的人才库' ,
        path: '/pages/index/index'
    }
  }
})