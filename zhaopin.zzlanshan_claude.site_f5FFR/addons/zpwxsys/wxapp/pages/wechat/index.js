import { Company } from 'company-model.js';
var company = new Company(); //实例化 首页 对象
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

    wx.showShareMenu({
      withShareTicket: true,
      menus: ['shareAppMessage', 'shareTimeline']
    })

    that.setData({
      activeCategoryId: that.data.pid
    })


    var params = { id:that.data.id};


 
    company.getCompanyDetailData((data) => {
 
      wx.setNavigationBarTitle({
        title: data.companyinfo.companyname,
      })
      that.data.title =  data.companyinfo.companyname;
      that.data.isgz = data.companyinfo.isgz;
     that.setData({
         data:data.companyinfo,
         isgz:that.data.isgz,
         joblist:data.joblist,
         companyimglist:data.companyinfo.companyimg
     });

     wx.hideNavigationBarLoading(); //完成停止加载
              wx.stopPullDownRefresh();
 },params);


  },

  tabClick: function (e) {


    var pid = e.currentTarget.id;
    
    var that = this;
    that.data.pid = pid;
    that.setData({
      activeCategoryId: pid
    })



  },

  toJobDetail:function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/zwjobdetail/index?id=" + id
    })

  }
  ,


  toGz: function (e) {
    var that = this;
    var companyid =  that.data.id;
    var params = { companyid:that.data.id};

    company.gzCompany((data) => {
     
      if(data.status == 0 )
      {
        that.data.isgz = 1;
          that.setData({
            isgz:1
        });

        wx.showToast({
          title: data.msg,
          icon: 'success',
          duration: 2000
         })
    
  }else{
     
    that.data.isgz =0;
    that.setData({
      isgz:0
  });
    wx.showToast({
      title: data.msg,
      icon: 'success',
      duration: 2000
     })
    
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