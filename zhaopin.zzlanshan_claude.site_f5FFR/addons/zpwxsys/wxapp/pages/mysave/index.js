import { Findjob } from '../findjob/findjob-model.js';
var findjob = new Findjob(); //实例化 首页 对象
Page({

  /**
   * 页面的初始数据
   */
  data: {
    page:1,
    status: -1,
    currentTab: -1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;

    wx.setNavigationBarTitle({
      title: '我的收藏',
    })
    
    that.initpage();

  },

  initpage:function(){
    var that = this;
    var params = {page:that.data.page, status:that.data.status};

    findjob.mySavejob((data) => {
      that.setData({
        list: data.jobsavelist || []
      });
      wx.hideNavigationBarLoading();
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




  cancleSave:function(e){

      var that = this;
      var id = e.currentTarget.dataset.id;
      wx.showModal({
        title: '取消收藏',
        content: '确认取消收藏？',
        success: function (res) {
          if (res.confirm) {


            var params = {id:id};

            findjob.cancleSave((data) => {
              if (data.status == 0) {
                wx.showToast({ title: '已取消收藏', icon: 'none', duration: 1500 });
                that.setData({ page: 1 });
                that.initpage();
              } else {
                wx.showToast({ title: data.msg || '操作失败', icon: 'none' });
              }
            },params);




          }}})



  },



  toJobDetail:function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/zwjobdetail/index?id=" + id
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
        title:'我的收藏' ,
        path: '/pages/mysave/index'
    }
  }
})