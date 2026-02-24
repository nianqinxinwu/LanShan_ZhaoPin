import {Base} from '../utils/base.js';

class Pay extends Base{
    constructor(){
        super();
    }


    execPay(orderNumber,callback){
      var allParams = {
          url: 'v1.Pay/getPreOrder',
          type:'post',
          data:{id:orderNumber},
          sCallback: function (data) {
              var timeStamp= data.timeStamp;
              if(timeStamp) { //可以支付
                  wx.requestPayment({
                      'timeStamp': timeStamp.toString(),
                      'nonceStr': data.nonceStr,
                      'package': data.package,
                      'signType': data.signType,
                      'paySign': data.paySign,
                      success: function () {
                          callback && callback(2);
                      },
                      fail: function () {
                          callback && callback(1);
                      }
                  });
              }else{
                  callback && callback(0);
              }
          }
      };
      this.request(allParams);
  }




};

export {Pay};