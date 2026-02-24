import { Company } from '../../model/company-model.js';

var company  = new Company();
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
    wx.setNavigationBarTitle({
      title: '企业登录',
    })

    wx.hideNavigationBarLoading();
    wx.stopPullDownRefresh();

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },


  bindSave: function (e) {
    console.log(e.detail.formId);
    var that = this;
    var name = e.detail.value.name;
    var password = e.detail.value.password;

    if (name == "") {
      wx.showModal({
        title: '提示',
        content: '请填写企业登录账号',
        showCancel: false
      })
      return
    }
    if (password == "") {
      wx.showModal({
        title: '提示',
        content: '请填写企业登录密码',
        showCancel: false
      })
      return
    }

    var params = {name:name,password:password};
    
    company.Login((data) => {

      if(data.error == 0 )
      {

       // wx.setStorageSync('companyid', data.companyid);

       wx.setStorageSync('ctoken', data.ctoken);
       wx.setStorageSync('checkFlag', 1);

          wx.redirectTo({
            url: "/pages/companycenter/index"
          })

      }else{

        wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false
        })
        return



      }

                         },params);
  

  




    

  
  },

  goregister:function(e){
    wx.navigateTo({
      url: "/pages/companyregister/index"
    })

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
  onPullDownRefresh: function(){
    wx.showNavigationBarLoading();
    this.onLoad();
  },

  //分享效果
  onShareAppMessage: function () {
    var that = this;
      return {
          title:'企业登录' ,
          path: '/pages/companylogin/index'
      }
  }
})