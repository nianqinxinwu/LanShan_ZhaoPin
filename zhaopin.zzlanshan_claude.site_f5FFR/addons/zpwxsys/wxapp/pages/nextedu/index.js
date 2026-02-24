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
      title: '我的教育经历',
    })
    var that = this;
    that.initpage();



  },
  initpage:function(){

    var that = this;
    var params = {};
    note.getPubEduInit((data) => {

      var eduinfo = data.eduinfo;
      if(eduinfo)
      {
        that.setData({
          eduinfo:eduinfo,
          ln:data.ln,
          begindateschool:eduinfo.begindateschool,
          enddateschool:eduinfo.enddateschool,
          educationname:eduinfo.educationname,


          }); 

        if(data.ln >0)
        {

          that.setData({
          
            begindateschool2:eduinfo.begindateschool2,
            enddateschool2:eduinfo.enddateschool2,
            educationname2:eduinfo.educationname2,
  
  
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

  BeginDateSchool:function(e){

    var that = this;
    that.data.begindateschool = e.detail.value;
    this.setData({
      begindateschool:that.data.begindateschool
  })

  },

  EndDateSchool:function(e){

    var that = this;
    that.data.enddateschool = e.detail.value;
    this.setData({
      enddateschool:that.data.enddateschool
  })

  },

  BeginDateSchool2:function(e){

    var that = this;
    that.data.begindateschool2 = e.detail.value;
    this.setData({
      begindateschool2:that.data.begindateschool2
  })

  },

  EndDateSchool2:function(e){

    var that = this;
    that.data.enddateschool2 = e.detail.value;
    this.setData({
      enddateschool2:that.data.enddateschool2
  })

  },

  
  bindEducationChange: function (e) {

    var that= this;
    var education = that.data.education;
    
    if (education) {
      that.data.educationindex = e.detail.value;
      that.data.educationname = education[e.detail.value];
    }

    that.setData({
      education: education,
      educationname:that.data.educationname,
      educationindex: e.detail.value
    })
  }
  ,

  bindEducationChange2: function (e) {
    var that = this;
    var education = that.data.education;

    if (education) {
      that.data.educationindex2 = e.detail.value;
      that.data.educationname2 = education[e.detail.value];
    }

    this.setData({
      education: education,
      educationname2:that.data.educationname2,
      educationindex2: e.detail.value
    })
  }
  ,

  savepubinfo: function (e) {
    
    var that = this;
    var ln = that.data.ln;
    var content = '';
    var title = that.data.title;

   

    var begindateschool = that.data.begindateschool;
    var enddateschool = that.data.enddateschool;
    var school = e.detail.value.school;
    var educationname = that.data.educationname;
    var vocation = e.detail.value.vocation;


    if (begindateschool == "") {
      wx.showModal({
        title: '提示',
        content: '请选择入学时间',
        showCancel: false
      })
      return
    }

    if (enddateschool == "") {
      wx.showModal({
        title: '提示',
        content: '请选择毕业时间',
        showCancel: false
      })
      return
    }

    

    if (school == "") {
      wx.showModal({
        title: '提示',
        content: '请输入学校',
        showCancel: false
      })
      return
    }

    if (educationname == "") {
      wx.showModal({
        title: '提示',
        content: '请选择学历',
        showCancel: false
      })
      return
    }

  
    if (vocation == "") {
      wx.showModal({
        title: '提示',
        content:'请输入专业',
        showCancel: false
      })
      return
    }

    
  

    

    if(ln == 1)
    {
    var begindateschool2 = that.data.begindateschool2;
    var enddateschool2 = that.data.enddateschool2;
    var school2 = e.detail.value.school2;
    var educationname2 = that.data.educationname2;
    var vocation2 = e.detail.value.vocation2;


    if (begindateschool2 == "") {
      wx.showModal({
        title: '提示',
        content: '请选择入学时间',
        showCancel: false
      })
      return
    }

    if (enddateschool2 == "") {
      wx.showModal({
        title: '提示',
        content: '请选择毕业时间',
        showCancel: false
      })
      return
    }

    

    if (school2 == "") {
      wx.showModal({
        title: '提示',
        content: '请输入学校',
        showCancel: false
      })
      return
    }

    if (educationname2 == "") {
      wx.showModal({
        title: '提示',
        content: '请选择学历',
        showCancel: false
      })
      return
    }

  
    if (vocation2 == "") {
      wx.showModal({
        title: '提示',
        content:'请输入专业',
        showCancel: false
      })
      return
    }


  

    }



    if(ln ==0 )
    {

      var params = {
        begindateschool:begindateschool,
        enddateschool:enddateschool,
        school:school,
        educationname:educationname,
        vocation:vocation,
        ln:that.data.ln
  
      };
    }else{

      var params = {
        begindateschool:begindateschool,
        enddateschool:enddateschool,
        school:school,
        educationname:educationname,
        vocation:vocation,
        begindateschool2:begindateschool2,
        enddateschool2:enddateschool2,
        school2:school2,
        educationname2:educationname2,
        vocation2:vocation2,
        ln:that.data.ln
  
      };

    }
  
 

 


    console.log(params);

    note.Saveedu((data) => {

      wx.navigateTo({
        url: "/pages/nextexpress/index"
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
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

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
          title:'我的教育经历' ,
          path: '/pages/index/index'
      }
  }
})