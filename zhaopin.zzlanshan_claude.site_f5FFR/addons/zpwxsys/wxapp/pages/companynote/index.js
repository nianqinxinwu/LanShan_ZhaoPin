import { Company } from '../../model/company-model.js';

var company  = new Company();
Page({

  /**
   * 页面的初始数据
   */
  data: {
        page:1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;
    wx.setNavigationBarTitle({
      title: '简历管理',
    })


    that.initpage();

  },

  initpage:function()
  {
    var that = this;

    company.checkLogin(() => {
  
        

        var ctoken = wx.getStorageSync('ctoken');
        var params = {ctoken:ctoken,page:that.data.page};
        company.checkLogin((data) => {
  
   
          if(data.error == 0 )
          {
  
        company.companynote((data) => {
    
          that.setData({
            notelist:data.notelist
          
          });
                                    
            },params);
  
          }else{
  
           wx.reLaunch({
             url: '/pages/companylogin/index',
           })
  
  
          }
  
  
          wx.hideNavigationBarLoading(); //完成停止加载
          wx.stopPullDownRefresh();
  
  
  
          },params);
  
        
  
       
    });


  },


  toPay:function(e){

    var that = this;

    var pid = e.currentTarget.dataset.id;

    wx.navigateTo({
      url: "/pages/donenote/index?id=" + pid
    })

  },

  

  toWorkerDetail:function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/workerdetail/index?id=" + id
    })

  }
  ,

  toInvite:function(e){
    var that = this;
    var id = e.currentTarget.dataset.id;
    wx.showModal({
      title: '邀请面试',
      content: '确认邀请面试？',
      success: function (res) {
        if (res.confirm) {


  
        

      var ctoken =  wx.getStorageSync('ctoken');
      var params = {ctoken:ctoken,id:id};

      if(ctoken)
      {



      company.Invitenote((data) => {


        if(data.status == 2)
      {
        wx.reLaunch({
          url: '/pages/companylogin/index',
        })
      }else{
  
          if(data.status == 0)
          {

            wx.showModal({
              title: '提示',
              content: data.msg,
              showCancel: false,
              success:function(){

                that.onLoad();
              }
            })
            return

            
          }else{

            wx.showModal({
              title: '提示',
              content: data.msg,
              showCancel: false
            })
            return

          }
        }

                                  
          },params);


  
        }else{

          wx.reLaunch({
            url: '/pages/companylogin/index',
          })
        }





}}})

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
    wx.showNavigationBarLoading();
    this.initpage();
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  var that = this;
  
    this.data.page = this.data.page+1;
    that.initpage();
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    var that = this;
    return {
        title:'简历管理' ,
        path: '/pages/index/index'
    }
  }
})