import { User } from '../../model/user-model.js';

var user  = new User();

import { Token } from '../../utils/token.js';

var token = new Token();

import { Company } from '../../model/company-model.js';
var company  = new Company();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    checkFlag: 0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    var checkFlag = wx.getStorageSync('checkFlag') || 0;
    that.setData({ checkFlag: checkFlag });
    wx.setNavigationBarTitle({
      title: '发布',
    })
    wx.hideNavigationBarLoading();
    wx.stopPullDownRefresh();
  },

  toIndex:function(){

    wx.redirectTo({
      url: '/pages/index/index',
    })
},


toFindjob: function (e) {

  var that = this;

  wx.redirectTo({
    url: "/pages/findjob/index"
  })




},




toSysmsg:function(){

  var that = this;

  wx.navigateTo({
    url: "/pages/sysmsg/index"
  })
},

toMyuser:function(){

  wx.redirectTo({
    url: '/pages/user/index',
  })
},


  toPubOld:function(){

    wx.navigateTo({
      url: '/pages/pubold/index',
    })
  },


  toPubLet:function(){

    wx.navigateTo({
      url: '/pages/publet/index',
    })
  },


  toTaskjob:function(){




    var that = this;

    var params = {};

    token.verify(

    user.checkBind((data) => {

       if(data.isbind)
       {

        wx.navigateTo({
          url: "/pages/mynote/index"
        })
     

    }else{

      wx.navigateTo({
        url: "/pages/register/index"
      })

    }

       

        },params)

    )



    /*

      wx.navigateTo({
        url: "/pages/taskjob/index"
      })
        */

  },

  toLogin: function (e) {


    var that = this;

    var params = {};

    token.verify(

    user.checkBind((data) => {

       if(data.isbind)
       {
          company.checkLogin((data) => {

            if(data && data.error ==0 )
            {
              wx.navigateTo({
                url: "/pages/companycenter/index"
              })

            }else{
              wx.navigateTo({
                url: "/pages/companylogin/index"
              })

            }


          });

        }else{

      wx.navigateTo({
        url: "/pages/register/index"
      })

    }

       

        },params)

    )




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

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  onPullDownRefresh: function(){
    wx.showNavigationBarLoading();
    this.onLoad();
  },

  //分享效果
  onShareAppMessage: function () {
    var that = this;
      return {
          title:'发布' ,
          path: '/pages/switchrole/index'
      }
  },




})