import { Note } from 'note-model.js';
var note = new Note(); //实例化 首页 对象
Page({

  /**
   * 页面的初始数据
   */
  data: {
    city: wx.getStorageSync('companyinfo').city,
    isCars: true,	// 选择车源开关
    isSort: true,	// 选择排序开关
    isArea:true,
    isCate:true,
    isEdu: true,	// 选择价格开关
    isType: true,
    isSelect: true,
    loadMore: '',
    list: [],
    house_list: [],
    housetypelist: [],
    houseareaid: 0,
    housepriceid: 0,
    housetype: 0,
    letway: 0,
    page:1,
    title:'',
    noteln:0,
    cateid:0,
    eduid:'',
    areaid:0,
  },

  onPageScroll:function(t){
    console.log(t.scrollTop);//会随着用户下滑页面值变大
    var that = this;

    
    if(t.scrollTop <=145)
    {
       that.data.isArea = true;

        that.data.isEdu = true;
        that.data.isCate = true;

        that.setData({isArea: that.data.isArea,isEdu:that.data.isEdu,isCate:that.data.isCate});
    }
      
    that.setData({scrollTop:t.scrollTop});
  },


  


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {

    var that = this;

    wx.setNavigationBarTitle({
      title: '简历筛选',
    })

    wx.showShareMenu({
     withShareTicket: true,
     menus: ['shareAppMessage', 'shareTimeline']
   })

   var housetypelist = [
    { 'name': '初中', 'id': 1 },
    { 'name': '高中', 'id': 2 },
    { 'name': '中技', 'id': 3 },
    { 'name': '中专', 'id': 4 },
    { 'name': '大专', 'id': 5 },
    { 'name': '本科', 'id': 6 },
    { 'name': '硕士', 'id': 7 },
    { 'name': '博士', 'id': 8 },
    { 'name': '博后', 'id': 9 }];

  var housewaylist = [
    { 'name': '全职', 'id': 1 },
    { 'name': '兼职', 'id': 2 }
  ];



  that.data.cateid = e.cateid;
  that.data.eduid = e.edu;

  that.data.express = e.express;
  that.data.sex = e.sex;

  that.data.money = e.priceid;
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

  initpage:function(){
    var that = this;

    var cityid = wx.getStorageSync('cityinfo').id;
   
    var params = { cityid: cityid, page: that.data.page, cateid: that.data.cateid, eduid: that.data.eduid,express:that.data.express, sex:that.data.sex,money:that.data.money};
 
    console.log(params);
 
    note.getNoteListData((data) => {
 
     console.log(data);
     that.setData({
         notelist: data.notelist,
         noteln:data.notelist.length,
         arealist:data.arealist,
         jobcatelist:data.jobcatelist
           });
 
     wx.hideNavigationBarLoading(); //完成停止加载
     wx.stopPullDownRefresh();
     
 },params);
  },


  toWorkerDetail:function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/workerdetail/index?id=" + id
    })

  }
  ,

  selectCars: function (e) {
    var that = this;
    that.setData({
      isSort: true,
      isPrice: true,
      isType: true,
      isSelect:true,
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
  selectSort: function () {
    var that = this;
    that.setData({
      isCars: true,
      isPrice: true,
      isType: true,
      isSort: (!that.data.isSort)
    })
  },

  selectWay: function () {
    var that = this;
    that.setData({
      isSort: true,
      isCars: true,
      isPrice: true,
      isType: true,
      isSelect: (!that.data.isSelect)
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

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


  onPullDownRefresh: function(){
    wx.showNavigationBarLoading();
    this.onShow();
  },
  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom(params) {
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
          title:'招人才' ,
          path: '/pages/findworker/index'
      }
  }
})