import { Active } from '../../model/active-model.js';
var active  = new Active();
import { Order } from '../../model/order-model.js';

var order  = new Order();

import { Pay } from '../../model/pay-model.js';

var pay  = new Pay();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var that = this;
    if (that.data.id > 0) {
      var id = that.data.id;
    } else {
      var id = e.id;
      that.data.id = e.id;
    }


    wx.setNavigationBarTitle({
      title: '报名招聘会',
    })


    var params = { id:that.data.id};

    wx.setNavigationBarColor({
      frontColor: '#ffffff',
      backgroundColor: '#f0412c',
      animation: {
        duration: 400,
        timingFunc: 'easeIn'
      }
    })


 
    active.GetActivedetail((data) => {
 
     
     
     that.setData({

      activeinfo:data.activeinfo
       
     });
 },params);
  },

  toPayactive:function(){
    var that = this;
    var id = that.data.id;

    wx.navigateTo({
      url: "/pages/payactive/index?id="+ id
    })
  },

  pay: function (e) {
    var that = this;
   
        var pid = that.data.id;
        var companyid =  wx.getStorageSync('companyid');  
       if(pid > 0 && companyid > 0)
       {


        var params = {pid: pid,type:'companyactive',companyid:companyid};

        order.Activeorder((data) => {
  
            pay.execPay(data.order_id,(res) => {
    

              wx.navigateBack();

                                      
              })
              
            },params);








       }else{

         wx.showModal({
           title: '提示',
           content: '数据错误',
           showCancel: false
         })
         return

       }



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
}),wx.makeBluetoothPair({
  deviceId: 'deviceId',
  pin: 'pin',
  timeout: 0,
  success: (res) => {},
  fail: (res) => {},
  complete: (res) => {},
})