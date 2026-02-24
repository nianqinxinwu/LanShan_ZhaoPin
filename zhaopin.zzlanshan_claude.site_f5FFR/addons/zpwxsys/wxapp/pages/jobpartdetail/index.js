import { Partjob } from '../../model/partjob-model.js';
var partjob  = new Partjob();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:0,
    savestatus:0,
    title:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var that = this;

    wx.showShareMenu({
      withShareTicket: true,
      menus: ['shareAppMessage', 'shareTimeline']
    })
    if (that.data.id > 0) {
      var id = that.data.id;
    } else {
      var id = e.id;
      that.data.id = e.id;
    }

    var params = { id:that.data.id};


 
    partjob.getJobDetailData((data) => {
 
      wx.setNavigationBarTitle({
        title: data.jobinfo.jobtitle,
      })
      that.data.title = data.jobinfo.title;
      that.data.savestatus = data.jobinfo.savestatus;
        that.data.companyinfo = data.jobinfo.companyinfo;
        
      console.log(data.joblist);
     that.setData({
         data:data.jobinfo,
         savestatus:that.data.savestatus,
         companyinfo:data.jobinfo.companyinfo,
         joblist:data.joblist
     });


     wx.hideNavigationBarLoading(); //完成停止加载
     wx.stopPullDownRefresh();
 },params);




  },


  goMap: function (e) {
    var that = this;
    wx.openLocation({
      latitude: Number(that.data.companyinfo.lat),
      longitude: Number(that.data.companyinfo.lng),
      scale: 18,
      name: that.data.companyinfo.companyname,
      address: that.data.companyinfo.address
    })
  },


  doSendjob: function (e) {
    var that = this;

    wx.navigateTo({
      url: '/pages/baoming/index?id='+that.data.id
    })

    /*
    var companyid =  e.currentTarget.dataset.id;
    var params = { jobid:that.data.id,companyid:companyid};
    

    partjob.sendJob((data) => {

      if(data.status == 3)
      {

       

         wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false,
          success:function(){

            wx.navigateTo({
              url: '/pages/mynote/index',
            })

          }
        })
        return

      }else{

      wx.showToast({
        title: data.msg,
        icon: 'none',
        duration: 2000
       })

      }
 },params);
*/


  },

  doSavejob: function (e) {
    var that = this;
    var companyid =  e.currentTarget.dataset.id;
    var params = { jobid:that.data.id,companyid:companyid};

    partjob.jobSave((data) => {
     
      if(data.status == 0 )
      {
        that.data.savestatus = 1;
          that.setData({
            savestatus:1
        });
/*
        wx.showToast({
          title: data.msg,
          icon: 'success',
          duration: 2000
         })
  */  
  }else{
     
    that.data.savestatus =0;
    that.setData({
      savestatus:0
  });

  /*
    wx.showToast({
      title: data.msg,
      icon: 'success',
      duration: 2000
     })

     */
    
  }
 },params);



  },

  toAskjob:function(){
    var that = this;
    var id = that.data.id;
    wx.navigateTo({
      url: "/pages/askjob/index?id=" + id
    })
  },

  toCompanyDetail: function (e) {

    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/companydetail/index?id=" + id
    })
  },

  toJobDetail:function (e) {
    var id = e.currentTarget.dataset.id;

    wx.navigateTo({
     url: "/pages/zwjobdetail/index?id=" + id
    })

  }
  ,

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


  doCall: function (e) {
    console.log(e.currentTarget);
    var tel = e.currentTarget.dataset.tel;
    wx.makePhoneCall({
      phoneNumber: tel, //此号码并非真实电话号码，仅用于测试
      success: function () {
        console.log("拨打电话成功！")
      },
      fail: function () {
        console.log("拨打电话失败！")
      }
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
    wx.showNavigationBarLoading();
    this.onLoad();
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

    var that = this;
    return {
        title:that.data.title ,
        path: '/pages/zwjobdetail/index?id='+that.data.id
    }

  }
})