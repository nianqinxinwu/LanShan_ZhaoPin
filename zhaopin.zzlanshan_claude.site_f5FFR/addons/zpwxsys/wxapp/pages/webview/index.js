import { Job } from '../../model/job-model.js';
var job  = new Job();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id: 0,
    title: '',
    tel:'',
    pid:1,
    isgz:0,
    title:''
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


 
    job.getNavDetail((data) => {

     that.setData({
         data:data.navinfo,
      
     });

     wx.hideNavigationBarLoading(); //完成停止加载
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
        title:that.data.title ,
        path: '/pages/companydetail/index?id='+that.data.id
    }

  }
})