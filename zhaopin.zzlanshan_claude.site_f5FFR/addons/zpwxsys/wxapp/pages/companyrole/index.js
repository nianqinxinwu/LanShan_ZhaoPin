//recharge.js
import { Companyrole } from '../../model/companyrole-model.js';
var companyrole  = new Companyrole();
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
      title: '购买企业套餐',
    })
  var that = this;


  var ctoken = wx.getStorageSync('ctoken');
    var params = {ctoken:ctoken};

  companyrole.GetCompanyrolelist((data) => {

 
    that.setData({

      companyrolelist:data.companyrolelist,
      companyrecordlist:data.companyrecordlist
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

        var ctoken =  wx.getStorageSync('ctoken');
        var params = {pid: pid,type:'companyrole',ctoken:ctoken};

        order.Roleorder((data) => {
  
            pay.execPay(data.order_id,(res) => {

              that.onLoad();
              
              /*
              wx.navigateTo({
                url: '/pages/companycenter/index',
              })
              */
 

                                      
              })
              
            },params);








       }else{

         wx.showModal({
           title: '提示',
           content: '请选择企业套餐',
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
