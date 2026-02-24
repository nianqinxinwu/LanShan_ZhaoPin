import { Findjob } from 'findjob-model.js';
var findjob = new Findjob(); //实例化 首页 对象
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
    typetitle:'',
    type:0,
    typeid:0,
    title:''
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
  toSelectType:function(){
    var that = this;
    that.data.scrollTop = 146;
    that.data.isPrice = true;
    that.data.isType  = false;
    that.data.isArea = true;
    that.data.isCate = true;

    that.setData({
      isType:that.data.isType,
      isPrice:that.data.isPrice,
      isArea:that.data.isArea,
      isCate:that.data.isCate,
      scrollTop: that.data.scrollTop
    })
  },

  toSelectPrice:function(){
    var that = this;
    that.data.scrollTop = 146;
    that.data.isPrice = false;
    that.data.isArea = true;
    that.data.isCate = true;
    that.data.isType = true;

    that.setData({
      isPrice:that.data.isPrice,
      isArea:that.data.isArea,
      isCate:that.data.isCate,
      isType:that.data.isType,
      scrollTop: that.data.scrollTop
    })
  },


  toSelectCate:function(){
    var that = this;
    that.data.scrollTop = 146;
    that.data.isCate = false;
    that.data.isPrice = true;
    that.data.isArea = true;
    that.data.isType = true;
    that.setData({
      isPrice:that.data.isPrice,
      isArea:that.data.isArea,
      isCate:that.data.isCate,
      isType:that.data.isType,
      scrollTop: that.data.scrollTop
    })
  },

  toSelectArea:function(){
    var that = this;
    that.data.scrollTop = 146;
    that.data.isArea = false;
    that.data.isCate = true;
    that.data.isPrice = true;
    that.data.isType = true;
    that.setData({
      isPrice:that.data.isPrice,
      isArea:that.data.isArea,
      isCate:that.data.isCate,
      isType:that.data.isType,
      scrollTop: that.data.scrollTop
    })
  },


  SelectAreaItem:function(e){
    var that = this;
    var areaid = e.currentTarget.dataset.id;
    that.data.areatitle = e.currentTarget.dataset.title;
    that.data.areaid = areaid;
    that.data.isArea = true;
    that.setData({
      areaid:that.data.areaid,
      isArea:that.data.isArea,
      areatitle:that.data.areatitle
    
    })
    that.getjoblist();
  },

  SelectPriceItem:function(e){
    var that = this;
    var priceid = e.currentTarget.dataset.id;
    that.data.pricetitle = e.currentTarget.dataset.title;
    that.data.priceid = priceid;
    that.data.isPrice = true;
    that.setData({
      priceid:that.data.priceid,
      isPrice:that.data.isPrice,
      pricetitle:that.data.pricetitle
    
    })
    that.getjoblist();
  },

  SelectCateItem:function(e){
    var that = this;
    var cateid = e.currentTarget.dataset.id;
    that.data.catetitle = e.currentTarget.dataset.title;
    that.data.cateid = cateid;
    that.data.isCate = true;
    that.setData({
      cateid:that.data.cateid,
      isCate:that.data.isCate,
      catetitle:that.data.catetitle
    
    })

    that.getjoblist();
  },


  SelectTypeItem:function(e){
    var that = this;
    that.data.scrollTop = 16;
    var typeid = e.currentTarget.dataset.id;
    that.data.typetitle = e.currentTarget.dataset.title;
    that.data.typeid = typeid;
    that.data.isType = true;
    that.setData({
      typeid:that.data.typeid,
      isType:that.data.isType,
      typetitle:that.data.typetitle,
      scrollTop:that.data.scrollTop
    })
    that.getjoblist();
  },


  toSelectJob:function(){

    var that = this;
  
    wx.navigateTo({
      url: "/pages/selectjob/index"
    })

  },
  onLoad: function (e) {

    var that = this;
    var title ;

    that.data.type = e.id;

    if (that.data.type > 0) {
        var type = that.data.type;
      } else {
        var type = e.id;
        that.data.type = e.id;
      }

  
  },
  
  
  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {

     var that = this;

 
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

    var latitude =   wx.getStorageSync('latitude');
    var longitude = wx.getStorageSync('longitude');

    var params = { cityid: cityid, page: that.data.page, houseareaid: that.data.areaid, priceid: that.data.priceid, jobcateid: that.data.cateid, latitude: latitude, longitude: longitude ,type:that.data.type};

    findjob.getJobListData((data) => {


 wx.setNavigationBarTitle({
      title: data.worktype.name,
    })

    that.data.title = data.worktype.name;
      that.setData({
          joblist: data.joblist,
          arealist:data.arealist,
          jobcatelist:data.jobcatelist,
          worktypelist:data.worktypelist
      });


      wx.hideNavigationBarLoading(); //完成停止加载
      wx.stopPullDownRefresh();
      
  },params);

  },

  getjoblist:function(){

    var that = this;

    var cityid = wx.getStorageSync('cityinfo').id;

    var latitude =   wx.getStorageSync('latitude');
    var longitude = wx.getStorageSync('longitude');

    var params = { cityid: cityid, page: that.data.page, areaid: that.data.areaid, priceid: that.data.priceid, jobcateid: that.data.cateid, latitude: latitude, longitude: longitude,type:that.data.typeid };

    findjob.getJobListData((data) => {

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
    wx.showNavigationBarLoading();
      this.onShow();
    },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

    var that = this;
    that.data.page = this.data.page+1;
    that.onShow();
    
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    var that = this;
    return {
      title: that.data.title,
      path: '/pages/typejoblist/index?id='+that.data.type
  }

  }
})