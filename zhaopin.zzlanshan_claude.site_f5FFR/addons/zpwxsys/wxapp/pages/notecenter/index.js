import { Note } from '../findworker/note-model.js';

var note  = new Note();

Page({

  /**
   * 页面的初始数据
   */
  data: {

    

  },



  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {
    
    wx.setNavigationBarTitle({
      title: '简历中心',
    })
   
    var that = this;



    that.initpage();

  },



  initpage:function(){

    var that = this;

    var params = {};

    note.getPubNoteInit((data) => {


      var noteinfo = data.noteinfo;
      if(noteinfo)
      {

        that.setData({
            noteinfo: noteinfo,
            edulist:data.edulist,
            expresslist:data.expresslist,
          })
      }


      wx.hideNavigationBarLoading(); //完成停止加载
      wx.stopPullDownRefresh();



  },params);

  },


  toNextExpress:function(){

    wx.navigateTo({
      url: '/pages/nextexpress/index',
    })

  },

  toNextEdu:function(){

    wx.navigateTo({
      url: '/pages/nextedu/index',
    })

  },


  toMynote:function(){

    wx.navigateTo({
        url: '/pages/mynote/index',
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
    this.onShow();
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
        title:'简历中心' ,
        path: '/pages/notecenter/index'
    }



  }
})


