import { Company } from '../companydetail/company-model.js';
var company = new Company(); //实例化 首页 对象
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
      title: '我的关注',
    })

    that.initpage();

  },

 initpage:function(){

    var that = this;

    var params = {page:that.data.page};

    company.myGzCompany((data) => {


      that.setData({
        list:data.mygzlist || []
      });

      wx.hideNavigationBarLoading(); //完成停止加载
      wx.stopPullDownRefresh();

  },params);
 },

  cancelGz: function (e) {
    var that = this;
    var companyid = e.currentTarget.dataset.companyid;
    wx.showModal({
      title: '取消关注',
      content: '确认取消关注该企业？',
      success: function (res) {
        if (res.confirm) {
          var params = { companyid: companyid };
          company.gzCompany((data) => {
            wx.showToast({ title: '已取消关注', icon: 'none', duration: 1500 });
            that.setData({ page: 1 });
            that.initpage();
          }, params);
        }
      }
    });
  },


  toCompanyDetial:function(e){
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/companydetail/index?id=" + id
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
        title:'我的关注' ,
        path: '/pages/mygz/index'
    }
  }
})