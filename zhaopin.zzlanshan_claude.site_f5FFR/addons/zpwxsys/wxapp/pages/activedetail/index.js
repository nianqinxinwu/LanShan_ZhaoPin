import { Active } from '../../model/active-model.js';
var active  = new Active();
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

    wx.showShareMenu({
      withShareTicket: true,
      menus: ['shareAppMessage', 'shareTimeline']
    })
    var params = { id:that.data.id};




 
    active.GetActivedetail((data) => {

        if(data.status == 0 )
        {
 
      wx.setNavigationBarTitle({
        title: data.activeinfo.title,
      })
      that.data.title = data.activeinfo.title;
     
     that.setData({

      activeinfo:data.activeinfo ,  
      companycount:data.companycount,
      activerecordlist:data.activerecordlist   
     });

    }else{

        wx.showToast({
            title: data.msg,
            icon: 'none',
            duration: 5000
          })
    }

     wx.hideNavigationBarLoading(); //完成停止加载
     wx.stopPullDownRefresh();

     
 },params);
  },

  toPayactive:function(){
    var that = this;
    var aid = that.data.id;

    var ctoken =  wx.getStorageSync('ctoken');

    if(ctoken)
    {
    var params = {ctoken:ctoken,aid:aid};


 
    active.CheckActiveRecord((data) => {
 
      if(data.status == 0 )
      {
      
       wx.showToast({
        title: data.msg,
        icon: 'none',
        duration: 2000
      })
     }else if(data.status == 2){
      wx.navigateTo({
        url: "/pages/companylogin/index"
      })
    

      }else{

        wx.showToast({
          title: data.msg,
          icon: 'none',
          duration: 2000
        })
        
      }
   


 },params);

}else{

  wx.navigateTo({
    url: "/pages/companylogin/index"
  })

}

   
  },
  doSendjob:function(e){

    var that = this;
    var id = e.currentTarget.dataset.companyid;
    wx.navigateTo({
      url: "/pages/companydetail/index?id="+ id
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
        path: '/pages/activedetail/index?id='+that.data.id
    }
  }
})