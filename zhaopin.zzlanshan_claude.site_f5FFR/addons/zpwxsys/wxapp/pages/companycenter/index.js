import { Company } from '../../model/company-model.js';

var company  = new Company();

import { Order } from '../../model/order-model.js';

var order  = new Order();

import { Pay } from '../../model/pay-model.js';

var pay  = new Pay();

Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;

    wx.setNavigationBarTitle({
      title: '企业中心',
    })

    company.checkLogin((data) => {

        var ctoken = wx.getStorageSync('ctoken');
        var params = {ctoken:ctoken};

      company.companycenter((data) => {

        that.setData({
          companyinfo:data.companyinfo,
          totaljobnum:data.totaljobnum,
          totalnotenum:data.totalnotenum,
          totaltopnum:data.totaltopnum,
          totalspreadnum:data.totalspreadnum || 0,
          coinBalance: data.coin_balance || 0,
          companyrole:data.companyrole
        });

          },params);


  });

  },

  /*
  toCompanyrole:function(){

    var that = this;

    var params = {};

    order.Roleorder((data) => {

   
        pay.execPay(data.order_id,(res) => {

  
                                  
          })
      


                                
        },params);

  },

  */

 toEditcompany:function(){

  wx.navigateTo({
    url: "/pages/editcompany/index"
  })
},
toCompanychat:function(){

  wx.navigateTo({
    url: "/pages/companychat/index"
  })
},
loginout:function(){

wx.clearStorageSync('companyid');

wx.reLaunch({
  url: "/pages/companylogin/index"
})

},

 toCompanyrole:function(){

  wx.navigateTo({
    url: "/pages/companyrole/index"
  })
},

toRecharge:function(){
  wx.navigateTo({
    url: "/pages/mymoney/index"
  })
},

toActive:function()
{

  wx.navigateTo({
    url: "/pages/active/index"
  })
},



  toMyjoblist:function(){

    wx.navigateTo({
      url: "/pages/companyjob/index"
    })
  },

  toCompanynote:function(){
    wx.navigateTo({
      url: "/pages/companynote/index"
    })

  },
  toMytasklist:function(){

    wx.navigateTo({
      url: "/pages/tasklist/index"
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