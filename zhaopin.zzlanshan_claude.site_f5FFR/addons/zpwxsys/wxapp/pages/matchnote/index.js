import { Note } from '../findworker/note-model.js';
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
    jobid:0
  },

 


 


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {

    var that = this;

    that.data.jobid = e.id;
    wx.setNavigationBarTitle({
      title: '匹配简历',
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
   
   var params = { cityid: cityid,jobid:that.data.jobid};

   console.log(params);

   note.getMatchNoteListData((data) => {

    console.log(data);
    that.setData({
        notelist: data.notelist,
        notecount:data.notecount,
        jobinfo:data.jobinfo
    });
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