import { Ploite } from '../../model/ploite-model.js';

var ploite  = new Ploite();
Page({

  /**
   * 页面的初始数据
   */
  data: {

    type:0,
    carid:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    wx.setNavigationBarTitle({
      title: '信息举报',
    })

    that.data.carid = options.id;
  },

  radioChange: function (e) {
    this.data.type = e.detail.value;
  },


  savePloitecar:function(e){
   
    var that = this;
    var content = e.detail.value.content;
    var type = that.data.type;
    var carid = that.data.carid;

    if (content == "") {
      wx.showModal({
        title: '提示',
        content: '请输入举报理由',
        showCancel: false
      })
      return
    }


    var params = {
      content:content,
      type: type,
      pid: carid
    };

    ploite.savePloite((data) => {

      if(data.status == 0 )
      {
        wx.showModal({
          title: '提示',
          content: '举报成功',
          showCancel: false,
          success:function(){

            wx.navigateBack({
              delta: 1,
            })
          }
        })

      }else{

        wx.showModal({
          title: '提示',
          content: '举报失败',
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