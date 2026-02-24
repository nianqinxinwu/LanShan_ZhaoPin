import { Findjob } from 'findjob-model.js';
var findjob = new Findjob(); 

import { Task } from '../../model/task-model.js';
var task  = new Task();
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
    list: [],
    house_list: [],
    housetypelist:[],
    houseareaid:0,
    housepriceid:0,
    housetype:0,
    page:1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {

     var that = this;

     wx.setNavigationBarTitle({
      title: '任务中心',
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
  
  toTaskDetail:function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/taskdetail/index?id=" + id
    })

  }
  ,

  initpage:function()
  {
    var that = this;
    var cityid = wx.getStorageSync('cityinfo').id;

    var params = { cityid: cityid, page: that.data.page,houseareaid:that.data.houseareaid,housetype:that.data.housetype };

    task.GetTaskList((data) => {

      console.log(data);
      that.setData({
          tasklist: data.tasklist,
          arealist:data.arealist,
          jobcatelist:data.jobcatelist
      });
  },params);


  },


  selectcarsitem: function (e) {
    var carid = e.currentTarget.id;
    var title = e.currentTarget.dataset.title;

    this.setData({ carid: carid, isCars: true,title:title });
    this.data.houseareaid = carid;
    this.initpage();

  }
  ,
  selectpriceitem: function (e) {
    var priceid = e.currentTarget.id;
    var title = e.currentTarget.dataset.title;
    this.setData({ priceid: priceid, isPrice: true, price: title });
    this.data.housepriceid = priceid;
    this.gethouselist();
  }
  ,
  selecttypeitem: function (e) {
    var typeid = e.currentTarget.id;
    var title = e.currentTarget.dataset.title;
    this.setData({ typeid: typeid, isType: true, typetitle: title });
    this.data.housetype = typeid;
    this.initpage();
  }
  ,

  selectCars: function (e) {
    var that = this;
    that.setData({
      isSort: true,
      isPrice: true,
      isType:true,
      isCars: (!that.data.isCars)
    })
  },
  selectPrice: function () {
    var that = this;
    that.setData({
      isSort: true,
      isCars: true,
      isType: true,
      isPrice: (!that.data.isPrice)
    })
  },
  selectType: function () {
    var that = this;
    that.setData({
      isSort: true,
      isCars: true,
      isPrice: true,
      isType: (!that.data.isType)
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