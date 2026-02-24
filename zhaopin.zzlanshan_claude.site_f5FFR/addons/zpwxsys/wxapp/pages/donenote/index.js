import { Company } from '../../model/company-model.js';

var company  = new Company();

import { Jobrecord } from '../../model/jobrecord-model.js';

var jobrecord  = new Jobrecord();



Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:0,
    status:-8,
    donestatus:'处理'
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


        
    var ctoken = wx.getStorageSync('ctoken');
    var params = {ctoken:ctoken, id:that.data.id};


    company.sendorderdetail((data) => {

      wx.setNavigationBarTitle({
        title: '求职进度处理',
      })

      that.setData({
        guestinfo:data.guestinfo,
        proguestlist:data.proguestlist,
        houseinfo:data.houseinfo
    });

    

    wx.hideNavigationBarLoading(); //完成停止加载
    wx.stopPullDownRefresh();



  },params);
            
      
            
      
 



    
  },

  toNotedetail:function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/workerdetail/index?id=" + id
    })

  }
  ,

  radioAgreeChange: function (e) {
    this.data.status = e.detail.value;
  },


  toSendorder:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var money = e.detail.value.money;
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = {ctoken:ctoken, id:that.data.id,status:5,content:content,money:money };

          jobrecord.doSendjobrecordorder((data) => {

            if(data.status == 0 )
            {

              that.onLoad();
            }

        },params);


}}})


  },

  toContact: function (e) {


      var that = this;
      console.log(that.data.id);
      wx.showModal({
        title: '提交',
        content: '确认提交？',
        success: function (res) {
          if (res.confirm) {


      var content = e.detail.value.content;
      var ctoken = wx.getStorageSync('ctoken');

      var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

      jobrecord.doJobrecordContact((data) => {

          if(data.status == 0 )
          {

              that.onLoad();
          }

  
    },params);


  }}})



  },
  toWork:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

          jobrecord.doWork((data) => {
            if(data.status ==0 )
            {
              that.onLoad();
            }

        },params);


}}})


  },


  toAgreeorder:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;

          var ctoken = wx.getStorageSync('ctoken');

          var params = { ctoken:ctoken,id:that.data.id,status:that.data.status,content:content };

          jobrecord.doAgree((data) => {
            if(data.status ==0 )
            {
              that.onLoad();
            }

        },params);


}}})


  },

  toPassorder:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

          jobrecord.doPass((data) => {
            if(data.status ==0 )
            {
              that.onLoad();
            }

        },params);


}}})


  },

  toTypeinorder:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

          jobrecord.doTypein((data) => {
            if(data.status ==0 )
            {
              that.onLoad();
            }

        },params);


}}})


  },
  toTryorder:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

          jobrecord.doTry((data) => {
            if(data.status ==0 )
            {
              that.onLoad();
            }

        },params);


}}})


  },

  toDoneorder:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = { ctoken:ctoken,id:that.data.id,status:that.data.status,content:content };

          jobrecord.doDone((data) => {
            if(data.status ==0 )
            {
              that.onLoad();
            }

        },params);


}}})


  },


  toCancleorder:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

          jobrecord.doCancle((data) => {
            if(data.status ==0 )
            {
              that.onLoad();
            }

        },params);


}}})


  },

  toUseWork:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

          jobrecord.doUseWork((data) => {

            if(data.status ==0 )
            {
              that.onLoad();
            }
        },params);


}}})


  },
  toInivateNote:function(e)
  {

    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
            var ctoken = wx.getStorageSync('ctoken');

          var content = e.detail.value.content;
          var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

          jobrecord.doInivateNote((data) => {

            if(data.status ==0 )
            {
              that.onLoad();
            }


        },params);


}}})


  },

  toDoneall:function(e){
    var that = this;
    console.log(that.data.id);
    wx.showModal({
      title: '提交',
      content: '确认提交？',
      success: function (res) {
        if (res.confirm) {
          var content = e.detail.value.content;
          var ctoken = wx.getStorageSync('ctoken');

          var params = {ctoken:ctoken, id:that.data.id,status:that.data.status,content:content };

          jobrecord.doDoneall((data) => {
            if(data.status ==0 )
            {
              wx.navigateBack({
                delta: 1,
              })
            }

        },params);


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