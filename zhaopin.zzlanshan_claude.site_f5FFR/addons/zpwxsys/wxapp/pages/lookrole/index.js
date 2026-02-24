//recharge.js
import { Lookrole } from '../../model/lookrole-model.js';
var lookrole  = new Lookrole();
import { Order } from '../../model/order-model.js';

var order  = new Order();

import { Pay } from '../../model/pay-model.js';

var pay  = new Pay();

var color, sucmoney
var money = 0
var b = 0
var yajinid = 0
Page({
  data: {
    id:0,
    mymoney: 0,
    disabled: false,
    curNav: 1,
    curIndex: 0,
    cart: [],
    cartTotal: 0,
    lockhidden: true,
    yajinhidden: true,
    sucmoney: 424,
    color: "limegreen",
    nocancel: false,
    tajinmodaltitle: "押金充值",
    yajinmodaltxt: "去充值",
    yajinmoney: 0,
    yajintxt: "您是否确定充值押金299元？押金充值后可以在摩拜单车App全额退款",
 
  },
  //充值金额分类渲染模块
  selectNav(event) {
    var that = this;
    let id = event.currentTarget.dataset.id;

   // console.log(id);
    that.data.id = id;
    var index = parseInt(event.currentTarget.dataset.index);
    var b = parseInt(event.currentTarget.dataset.money);
    self = this;
    this.setData({
      curNav: id,
      curIndex: index,
    })
  },
  //页面加载模块
  onLoad: function () {
   
    wx.setNavigationBarTitle({
      title: '购买简历包',
    })
  var that = this;
   
  var params = {};

  lookrole.GetLookroleList((data) => {

 
    that.setData({

      lookrolelist:data.lookrolelist,
      lookrolerecordlist:data.lookrolerecordlist
    });
},params);
  



  },
  buttonEventHandle: function (event) {
  },

 pay: function (e) {
    var that = this;
   
        var pid = that.data.id;
    
       if(pid > 0)
       {


        var params = {pid: pid,type:'lookrole'};

        order.LookRoleorder((data) => {
  
            pay.execPay(data.order_id,(res) => {
    
                                      
              })
              
            },params);





      /*
        var userinfo = wx.getStorageSync('userInfo');
        var ordertype = 'paycard';
        wx.showModal({
          title: '确认支付',
          content: '确认支付金额？',
          success: function (res) {
            if (res.confirm) {
              app.util.request({
                'url': 'entry/wxapp/paycard',
                data: { ordertype: ordertype, pid: pid, sessionid: userinfo.sessionid, uid: userinfo.memberInfo.uid },
                success: function (res) {
                  console.log(res);
                  if (res.data && res.data.data) {
                    wx.requestPayment({
                      'timeStamp': res.data.data.timeStamp,
                      'nonceStr': res.data.data.nonceStr,
                      'package': res.data.data.package,
                      'signType': 'MD5',
                      'paySign': res.data.data.paySign,
                      'success': function (res) {
                        //支付成功后，系统将会调用payResult() 方法，此处不做支付成功验证，只负责提示用户
                        console.log(res);

                        that.setData({
                          ispay: 1
                        })
                  

                        setTimeout(function () {

                          //go to next
                          wx.navigateBack();

                        }, 1000);

                      },
                      'fail': function (res) {
                        //支付失败后，
                      }
                    })
                  }

                },
                fail: function (res) {
                  console.log(res);
                }

              })



            }
          }





   


    });



*/




       }else{

         wx.showModal({
           title: '提示',
           content: '请选择充值选项',
           showCancel: false
         })
         return

       }



  },



  //去充值功能模块
  goblance: function (event) {
    money += b;
    this.setData({
      lockhidden: false,
      mymoney: money,
      sucmoney: b,
    })
  },
  confirm: function () {
    this.setData({
      lockhidden: true
    });
  },
  //押金功能模块
  yajin: function (event) {
    this.setData({
      yajinhidden: false
    });
  },
  yajincancel: function (event) {
    this.setData({
      yajinhidden: true
    });
  },
  yajinconfirm: function (event) {
    if (yajinid == 0) {
      yajinid = 1;
      this.setData({
        nocancel: true,
        yajintxt: "您已成功充值押金299元",
        tajinmodaltitle: "充值成功",
        yajinmodaltxt: "完成"
      });
    } else {
      yajinid = 0;
      this.setData({
        nocancel: false,
        yajinhidden: true,
        yajinmoney: 299
      });
    }
    this.setData({
      nocancel: true,
    });
  }
})
