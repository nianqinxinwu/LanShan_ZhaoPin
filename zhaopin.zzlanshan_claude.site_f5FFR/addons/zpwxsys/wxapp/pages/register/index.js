import { User } from '../../model/user-model.js';

var user  = new User();

import { Token } from '../../utils/token.js';

var token = new Token();

var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    flag: false,
    codeDis: false,
    phoneCode: "获取验证码",
    telephone: "",
    codePhone: "",
  }, 

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.setNavigationBarTitle({
      title: '绑定账号',
    })
   
    this.setData({
      loadmore: true

    })


  },
  toJobDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/weixinmao_zp/pages/zwjobdetail/index?id=" + id
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },


  getPhoneNumber: function (e) {
    var that = this;


  token.getTokenPhoneNumFromServer((data)=>{


    if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {
      wx.showModal({
        title: '提示',
        showCancel: false,
        content: '未授权',
        success: function (res) { }
      }) 
    } else {
    
      var params = {iv: e.detail.iv, encryptedData: e.detail.encryptedData};
      user.getPhone((data) => {

        that.data.istel= true;
        that.setData({
          istel:that.data.istel,
          tel:data.tel
        });

          },params);



    }



  }


  )


  
  },


  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

    var that = this;

    token.getTokenPhoneNumFromServer((data)=>{


    }
  
  
    )
    this.setData({
      loadmore: true

    })
  },
  toPerLogin:function(){

    wx.navigateTo({
      url: "/pages/login/index"
    })
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

  },
  bindSave: function (e) {
    var that = this;

    var tel = e.detail.value.tel;
  

    if (tel == "") {
      wx.showModal({
        title: '提示',
        content: '请输入手机号',
        showCancel: false
      })
      return
    }
 

    var params = {tel:tel};
      user.Updateusertel((data) => {

        wx.navigateBack({
          delta: 1, // 返回上一级页面。
          success: function() {
              console.log('成功！')
          }
        })
          },params);


  },


  
  phoneinput(e) {
    console.log(e)
    let value = e.detail.value
    console.log(value)
    this.setData({
      telephone: value
    })
  },

  changeCode() {
    var _this = this
    let telephone = this.data.telephone
    if (telephone.length != 11 || isNaN(telephone)) {


      wx.showModal({
        title: '提示',
        content: '请输入有效的手机号码',
        showCancel: false
      })
      return

    }
    this.setData({
      codeDis: true
    })


    app.util.request({
      'url': 'entry/wxapp/Sendsms',
      data: { phone: this.data.telephone },
      success: function (res) {
        if (!res.data.message.errno) {

          if(res.data.data.error == 1)
          {

       
            _this.setData({
              codeDis: false
            })

            wx.showModal({
              title: '提示',
              content: res.data.data.msg,
              showCancel: false
            })
            return



          }else{

          _this.setData({
            phoneCode: 60 ,
            code:res.data.data.code
          })
          let time = setInterval(() => {
            let phoneCode = _this.data.phoneCode
            phoneCode--
            _this.setData({
              phoneCode: phoneCode
            })
            if (phoneCode == 0) {
              clearInterval(time)
              _this.setData({
                phoneCode: "获取验证码",
                flag: true,
                codeDis: false
              })
            }
          }, 1000)


          }



        }
      }
    });







  },
  onShareAppMessage() {
    return {
      title: '信息综合查询-' + wx.getStorageSync('companyinfo').name,
      path: '/weixinmao_zp/pages/register/index'
    }
  }
})