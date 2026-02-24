import { Company } from '../../model/company-model.js';

var company  = new Company();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    page:1,
    status:-1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;
    wx.setNavigationBarTitle({
      title: '派遣订单',
    })

    that.initpage();
     

  },


  toEdu:function(e)
  {
    var that = this;
    var id = e.currentTarget.dataset.id;
    that.data.status = id;
    
    that.setData({

        status:that.data.status

    })
    that.initpage();

  },

 

  initpage:function(){

    var that = this;
    var params = {page:that.data.page,status:that.data.status};

    company.mysendorder((data) => {

      that.setData({
        notelist:data.notelist,
        totalcount:data.totalcount,
        totalcount_0:data.totalcount_0,
        totalcount_1:data.totalcount_1,
        totalcount_2:data.totalcount_2,
        totalcount_3:data.totalcount_3,
        totalcount_4:data.totalcount_4,
      
      });

      wx.hideNavigationBarLoading(); 
      wx.stopPullDownRefresh();
                                
        },params);

  },




  toGuestDetail:function(e){
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/sendorderdetail/index?id=" + id
    })


  },

  toWorkerDetail:function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/workerdetail/index?id=" + id
    })

  }
  ,

  toInvite:function(e){
    var that = this;
    var id = e.currentTarget.dataset.id;
    wx.showModal({
      title: '邀请面试',
      content: '确认邀请面试？',
      success: function (res) {
        if (res.confirm) {

    company.checkLogin(() => {
  
        

      var companyid =  wx.getStorageSync('companyid');
      var params = {companyid:companyid,id:id};

      company.Invitenote((data) => {
  
          if(data.status == 0)
          {

            wx.showModal({
              title: '提示',
              content: data.msg,
              showCancel: false,
              success:function(){

                that.onLoad();
              }
            })
            return

            
          }else{

            wx.showModal({
              title: '提示',
              content: data.msg,
              showCancel: false
            })
            return

          }


                                  
          },params);
  });


}}})

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
        title:'派遣订单' ,
        path: '/pages/index/index'
    }
  }
})