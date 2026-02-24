import { User } from '../../model/user-model.js';

var user  = new User();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    city: wx.getStorageSync('companyinfo').city,
    isCars: true,	// 选择车源开关
    isSort: true,	// 选择排序开关
    isPrice: true,	// 选择价格开关
    isType:true,
    loadMore: '',

    page:1,
    isArea:true,
    areaid:0,
    priceid:0,
    cateid:0,
    isPrice:true,
    isCate:true,
    areatitle:'',
    pricetitle:'',
    catetitle:'',
    ln:0
  },


  
  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {

     var that = this;

     wx.setNavigationBarTitle({
      title: '对我感兴趣',
    })

     wx.showShareMenu({
      withShareTicket: true,
      menus: ['shareAppMessage', 'shareTimeline']
    })

    var cityinfo = wx.getStorageSync('cityinfo');

    console.log(cityinfo);
    if (cityinfo) {

      wx.setStorageSync('city', cityinfo.name);
      that.setData({
        city: wx.getStorageSync('cityinfo').name

      })

    } else {

 
    }

    that.initpage();

 

  },

  toCompanyDetial: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/companydetail/index?id=" + id
    })
  },

  initpage:function(){

    var that = this;

    var params = {page: that.data.page};

    user.myLookNote((data) => {

      console.log(data); 
      that.setData({
          looknotelist: data.looknotelist
      });

      wx.hideNavigationBarLoading(); //完成停止加载
      wx.stopPullDownRefresh();


  },params);





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

    return {
      title: '企业库',
      path: '/pages/companylist/index'
  }

  }
})