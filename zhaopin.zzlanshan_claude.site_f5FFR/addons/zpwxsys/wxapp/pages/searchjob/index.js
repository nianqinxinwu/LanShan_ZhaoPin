import { Job } from '../../model/job-model.js';
var job  = new Job();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    city: wx.getStorageSync('companyinfo').city,
    page:1,
    cateid:0,
    priceid:0,
    edu:'',
    express:'',
    sex:-1,
    special:''
  },


  
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {

     var that = this;
    that.data.cateid = e.cateid;
    that.data.priceid = e.priceid;
    that.data.edu = e.edu;
    that.data.express = e.express;
    that.data.sex = e.sex;
    that.data.special = e.special;

     wx.setNavigationBarTitle({
      title: '职位筛选',
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

    var cityid = wx.getStorageSync('cityinfo').id;

    that.data.cateid = e.cateid;
    that.data.priceid = e.priceid;
    that.data.edu = e.edu;
    that.data.express = e.express;
    that.data.sex = e.sex;
    that.data.special = e.special;




    var params = { cityid: cityid, page: that.data.page, priceid: that.data.priceid, cateid: that.data.cateid, edu:that.data.edu,express:that.data.express,sex:that.data.sex,special:that.data.special,type:0 };

    job.getSearchJobListData((data) => {

      console.log(data);
      that.setData({
          joblist: data.joblist
      });
  },params);

  },

  getjoblist:function(){

    var that = this;

    var cityid = wx.getStorageSync('cityinfo').id;

    var latitude =   wx.getStorageSync('latitude');
    var longitude = wx.getStorageSync('longitude');

    var params = { cityid: cityid, page: that.data.page, areaid: that.data.areaid, priceid: that.data.priceid, jobcateid: that.data.cateid, latitude: latitude, longitude: longitude };

    job.getSearchJobListData((data) => {

      console.log(data);
      that.setData({
          joblist: data.joblist
      });
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
    this.setData({ page: 1, joblist: [] });
    this.onLoad(this.data);
    wx.stopPullDownRefresh();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    this.data.page = this.data.page + 1;
    var that = this;
    var cityid = wx.getStorageSync('cityinfo').id;
    var params = { cityid: cityid, page: that.data.page, priceid: that.data.priceid, cateid: that.data.cateid, edu:that.data.edu,express:that.data.express,sex:that.data.sex,special:that.data.special,type:0 };
    job.getSearchJobListData((data) => {
      var oldList = that.data.joblist || [];
      var newList = oldList.concat(data.joblist || []);
      that.setData({ joblist: newList });
    },params);
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

    return {
      title: wx.getStorageSync('checkFlag') == 1 ? '找工作' : '找活',
      path: '/pages/findjob/index'
  }

  }
})