import { Company } from '../../model/company-model.js';

var company  = new Company();
Page({

  data: {
    page:1,
    currentTab: -1,
    commentType: -1,
    commentlist: [],
    pendinglist: []
  },

  onLoad: function (options) {
    var that = this;

    wx.setNavigationBarTitle({
      title: '我的评价',
    })

    that.initpage();

  },

  initpage:function()
  {
    var that = this;

    var params = {page:that.data.page, commentType:that.data.commentType};

    company.getMyComment((data) => {

      that.setData({
        commentlist: data.commentlist || [],
        pendinglist: data.pendinglist || []
      });

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();

  },params);

  },

  switchTab: function (e) {
    var that = this;
    var type = parseInt(e.currentTarget.dataset.type);
    that.setData({
      commentType: type,
      currentTab: type,
      page: 1,
      commentlist: [],
      pendinglist: []
    });
    that.initpage();
  },

  delComment:function(e)
  {

    var that = this;

    var id = e.currentTarget.dataset.id;
    var params = {id:id};
    wx.showModal({
      title: '提示',
      content: '确定删除该条评论？',
      success: function (res) {
        if (res.confirm) {

            company.delComment((data) => {

              if(data.status == 0 )
              {
                wx.showModal({
                  title: '提示',
                  content: data.msg,
                  showCancel: false,
                  success:function(){

                    that.onLoad();
                  }
                })



              }else{

                wx.showToast({
                  title: data.msg,
                  icon: 'none',
                  duration: 2000
                })

              }

                },params);


        }
      }

    })

  },

  toSetComment: function (e) {
    var id = e.currentTarget.dataset.id;

      wx.navigateTo({
        url: "/pages/setcomment/index?id=" + id
      })


  },

  toJob: function (e) {
    var pid = e.currentTarget.dataset.id;

      wx.navigateTo({
        url: "/pages/zwjobdetail/index?id=" + pid
      })


  },

  onReady: function () {

  },

  onShow: function () {

  },

  onHide: function () {

  },

  onUnload: function () {

  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.initpage();
  },

  onReachBottom: function () {
    var that = this;

    this.data.page = this.data.page+1;
    that.initpage();

  },

  onShareAppMessage: function () {
    var that = this;
    return {
        title:'我的评价' ,
        path: '/pages/mycomment/index'
    }
  }
})
