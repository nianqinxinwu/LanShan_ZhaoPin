import { Note } from '../findworker/note-model.js';

var note  = new Note();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    ln:0 ,
    education: ['初中', '高中', '中技', '中专', '大专', '本科', '硕士', '博士', '博后'],
    educationindex: -1,
    educationname: '',
    educationindex2: -1,
    educationname2: '',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    wx.setNavigationBarTitle({
      title: '我的工作经历',
    })
    var that = this;
    that.initpage();



  },
  initpage:function(){

    var that = this;
    var params = {};
    note.getPubExpressInit((data) => {

      var expressinfo = data.expressinfo;
      if(expressinfo)
      {
        that.setData({
          expressinfo:expressinfo,
          ln:data.ln,
          begindatejob:expressinfo.begindatejob,
          enddatejob:expressinfo.enddatejob,


          }); 

        if(data.ln >0)
        {

          that.setData({
          
            begindatejob2:expressinfo.begindatejob2,
            enddatejob2:expressinfo.enddatejob2,
  
  
            }); 

        }
      }
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    
  },params);

  },

  addedu:function(){
    if(this.data.ln == 1){
      wx.showToast({
        title: '最多添加2条信息',
        icon:'none'
      })
    }else{
      this.setData({
        ln:this.data.ln + 1
      })
    }
  },

  BeginDateJob:function(e){

    var that = this;
    that.data.begindatejob = e.detail.value;
    this.setData({
      begindatejob:that.data.begindatejob
  })

  },

  EndDateJob:function(e){

    var that = this;
    that.data.enddatejob = e.detail.value;
    this.setData({
      enddatejob:that.data.enddatejob
  })

  },

  BeginDateJob2:function(e){

    var that = this;
    that.data.begindatejob2 = e.detail.value;
    this.setData({
      begindatejob2:that.data.begindatejob2
  })

  },

  EndDateJob2:function(e){

    var that = this;
    that.data.enddatejob2 = e.detail.value;
    this.setData({
      enddatejob2:that.data.enddatejob2
  })

  },

  




  savepubinfo: function (e) {
    
    var that = this;
    var ln = that.data.ln;
    var content = '';
    var title = that.data.title;



    var begindatejob = that.data.begindatejob;
    var enddatejob = that.data.enddatejob;
    var companyname = e.detail.value.companyname;
    var jobtitle = e.detail.value.jobtitle;




  
    if (begindatejob == "") {
      wx.showModal({
        title: '提示',
        content: '请选择入职时间',
        showCancel: false
      })
      return
    }

    if (enddatejob == "") {
      wx.showModal({
        title: '提示',
        content: '请选择离职时间',
        showCancel: false
      })
      return
    }

    

    if (companyname == "") {
      wx.showModal({
        title: '提示',
        content: '请输入公司',
        showCancel: false
      })
      return
    }

    if (jobtitle == "") {
      wx.showModal({
        title: '提示',
        content: '请选择职位',
        showCancel: false
      })
      return
    }

  if(ln ==1)
  {


    var begindatejob2 = that.data.begindatejob2;
    var enddatejob2 = that.data.enddatejob;
    var companyname2 = e.detail.value.companyname2;
    var jobtitle2 = e.detail.value.jobtitle2;


    if (begindatejob2 == "") {
        wx.showModal({
          title: '提示',
          content: '请选择入职时间',
          showCancel: false
        })
        return
      }
  
      if (enddatejob2 == "") {
        wx.showModal({
          title: '提示',
          content: '请选择离职时间',
          showCancel: false
        })
        return
      }

    if (begindatejob2 == "") {
      wx.showModal({
        title: '提示',
        content: '请选择入职时间',
        showCancel: false
      })
      return
    }

    if (enddatejob2 == "") {
      wx.showModal({
        title: '提示',
        content: '请选择离职时间',
        showCancel: false
      })
      return
    }

    

    if (companyname2 == "") {
      wx.showModal({
        title: '提示',
        content: '请输入公司',
        showCancel: false
      })
      return
    }

    if (jobtitle2 == "") {
      wx.showModal({
        title: '提示',
        content: '请选择职位',
        showCancel: false
      })
      return
    }


  }
 

  if(ln == 0 )
  {
    
    var params = {
      begindatejob:begindatejob,
      enddatejob:enddatejob,
      companyname:companyname,
      jobtitle:jobtitle,
      ln:that.data.ln

    };

  }else{

    var params = {
      begindatejob:begindatejob,
      enddatejob:enddatejob,
      companyname:companyname,
      jobtitle:jobtitle,

      begindatejob2:begindatejob2,
      enddatejob2:enddatejob2,
      companyname2:companyname2,
      jobtitle2:jobtitle2,
      ln:that.data.ln
    };

  }
  


    note.Saveexpress((data) => {

 

      wx.navigateTo({
        url: "/pages/nextdone/index"
      })
      
  },params);

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
   * 页面上拉触底事件的处理函数
   */
  onPullDownRefresh: function(){
    wx.showNavigationBarLoading();
    this.initpage();
  },

  //分享效果
  onShareAppMessage: function () {
    var that = this;
      return {
          title:'我的工作经历' ,
          path: '/pages/index/index'
      }
  }
})