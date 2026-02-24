 import { News } from '../../model/news-model.js';
var news  = new News();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    cateid:0,
    page:1,
    cateid:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var that = this;
    wx.setNavigationBarTitle({
      title: '职业资讯',
    })

   
    var cateid = 0 ;

    /*
    if (options.hasOwnProperty("id")) {
      var cateid = options.id;
    }
*/





    if (that.data.cateid > 0) {
        var cateid = that.data.id;
      } else {
        var cateid = e.id;
        that.data.cateid = e.id;
      }

      
      console.log(cateid);


    var params = {page:that.data.page};

    news.GetNewsList((data) => {

      var catelist = data.catelist;

      if(cateid ==0 || !cateid )
      {

          cateid = catelist[0]['id'];
      }
      that.setData({

       // newslist:data.newslist,
        catelist:catelist,
        activeCategoryId:cateid
      });

      that.data.cateid = catelist[0]['id'];
      that.getnewslistdata();


      wx.hideNavigationBarLoading(); //完成停止加载
      wx.stopPullDownRefresh();
  },params);
  },

  tabClick: function(e){


    var pid = e.currentTarget.id;
    var that = this;
    that.data.cateid = pid;
    that.setData({
      activeCategoryId: that.data.cateid
    })

    that.getnewslistdata();
  
  },

  getnewslistdata:function()
  {
    var that = this;

    var params = {cateid:that.data.cateid,page:that.data.page};

    news.GetNewsListByCateId((data) => {

     // var catelist = data.catelist;
      that.setData({

        newslist:data.newslist,
      //  catelist:catelist,
      //  activeCategoryId:catelist[0]['id']
      });
  },params);


  },

toNewsDetail:function(e){

    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/articledetail/index?id=" + id
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
  onPullDownRefresh: function(){
    wx.showNavigationBarLoading();
    this.onLoad();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    var that = this;
    that.data.page = this.data.page+1;
    that.onLoad();
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

   
    var that = this;
    return {
        title:'职业资讯' ,
        path: '/pages/article/index'
    }
  }
})