import { Company } from '../../model/company-model.js';

var company  = new Company();

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
    priceid:-1,
    cateid:'不限',
    isPrice:true,
    isCate:true,
    areatitle:'',
    pricetitle:'',
    catetitle:'',
    ln:0
  },


  onPageScroll:function(t){
    console.log(t.scrollTop);//会随着用户下滑页面值变大
    var that = this;

    
    if(t.scrollTop <=145)
    {
       that.data.isArea = true;

        that.data.isPrice = true;
        that.data.isCate = true;

        that.setData({isArea: that.data.isArea,isPrice:that.data.isPrice,isCate:that.data.isCate});
    }
      
    that.setData({scrollTop:t.scrollTop});
  },
  toSelectPrice:function(){
    var that = this;
    that.data.scrollTop = 146;
    that.data.isPrice = false;
    that.data.isArea = true;
    that.data.isCate = true;

    that.setData({
      isPrice:that.data.isPrice,
      isArea:that.data.isArea,
      isCate:that.data.isCate,
      scrollTop: that.data.scrollTop
    })
  },

  toSelectCate:function(){
    var that = this;
    that.data.scrollTop = 146;
    that.data.isCate = false;
    that.data.isPrice = true;
    that.data.isArea = true;
    that.setData({
      isPrice:that.data.isPrice,
      isArea:that.data.isArea,
      isCate:that.data.isCate,
      scrollTop: that.data.scrollTop
    })
  },

  toSelectArea:function(){
    var that = this;
    that.data.scrollTop = 146;
    that.data.isArea = false;
    that.data.isCate = true;
    that.data.isPrice = true;
    that.setData({
      isPrice:that.data.isPrice,
      isArea:that.data.isArea,
      isCate:that.data.isCate,
      scrollTop: that.data.scrollTop
    })
  },

  SelectAreaItem:function(e){
    var that = this;
    var areaid = e.currentTarget.dataset.id;
    that.data.areatitle = e.currentTarget.dataset.title;
    that.data.areaid = areaid;
    that.data.isArea = true;
    that.data.scrollTop = 85;
    that.setData({
      areaid:that.data.areaid,
      isArea:that.data.isArea,
      areatitle:that.data.areatitle,
      scrollTop:that.data.scrollTop
    
    })
    that.initpage();
  },

  SelectPriceItem:function(e){
    var that = this;
    var priceid = e.currentTarget.dataset.id;
    that.data.pricetitle = e.currentTarget.dataset.title;
    that.data.priceid = priceid;
    that.data.isPrice = true;
    that.data.scrollTop = 85;
    that.setData({
      priceid:that.data.priceid,
      isPrice:that.data.isPrice,
      pricetitle:that.data.pricetitle,
      scrollTop:that.data.scrollTop
    
    })
    that.initpage();
  },

  SelectCateItem:function(e){
    var that = this;
    var cateid = e.currentTarget.dataset.id;
    that.data.catetitle = e.currentTarget.dataset.title;
    that.data.cateid = cateid;
    that.data.isCate = true;
    that.data.scrollTop = 85;
    that.setData({
      cateid:that.data.cateid,
      isCate:that.data.isCate,
      catetitle:that.data.catetitle,
      scrollTop:that.data.scrollTop
    
    })

    that.initpage();
  },

  toSelectJob:function(){

    var that = this;
  
    wx.navigateTo({
      url: "/pages/searchcompany/index"
    })

  },
  
  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {

     var that = this;

     wx.setNavigationBarTitle({
      title: '企业库',
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
    var cityid = wx.getStorageSync('cityinfo').id;


    var params = { cityid: cityid,areaid:that.data.areaid,priceid:that.data.priceid,cateid:that.data.cateid, page: that.data.page};

    company.getCompanylist((data) => {

      console.log(data); 
      that.setData({
          companylist: data.companylist,
          arealist:data.arealist,
          ln:data.companylist.length,

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

  onReachBottom(params) {
    var that = this;
 
   that.data.page = this.data.page+1;
   that.initpage();
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