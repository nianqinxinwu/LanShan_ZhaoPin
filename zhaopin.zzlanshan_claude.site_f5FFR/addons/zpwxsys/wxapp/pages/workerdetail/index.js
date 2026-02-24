import { Note } from '../findworker/note-model.js';
var note = new Note(); //实例化 首页 对象
import { Lookrolerecord } from '../../model/lookrolerecord-model.js';

var lookrolerecord  = new Lookrolerecord();

import { Token } from '../../utils/token.js';

var token = new Token();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:0,
    isLook:true,
    isGetLook:true
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

    var companyid = 0 ;

    if(wx.getStorageSync('ctoken'))
    {
       var ctoken = wx.getStorageSync('ctoken');
       var params = { id:that.data.id,ctoken:ctoken};

    }else{
        var ctoken = wx.getStorageSync('ctoken');
        var params = { id:that.data.id,ctoken:''};

    }

   

  
    note.getNoteDetailData((data) => {

      that.data.isLook = data.isLook;

      wx.setNavigationBarTitle({
        title: data.noteinfo.name,
      })
      
      that.data.title = data.noteinfo.name;
     var express = data.expresslist;

     var companyid = 0 ;
    
     if( wx.getStorageSync('companyid')>0)
     {
        companyid  = wx.getStorageSync('companyid');
     }
   
     that.setData({
      isLook:that.data.isLook,
         data:data.noteinfo,
         expresslist:data.expresslist,
         edulist:data.edulist,
        // companyid:companyid,
         current:data.current,
         worktype:data.worktype,
         helplab:data.helplab
     });

     wx.hideNavigationBarLoading(); //完成停止加载
     wx.stopPullDownRefresh();

 },params);


 

  },
  toSelectlook:function(){

    var that = this;
    that.data.isGetLook = false;

    that.setData({
      isGetLook:that.data.isGetLook
    })

  },

  toLookUser:function(){

      var that = this;

      
      var params = { noteid:that.data.id };
      lookrolerecord.dealLookroleRecord((data) => {

      if(data.error ==0)
      {
        that.data.isGetLook = true;
        that.setData({
          isGetLook:that.data.isGetLook
        })
          that.onLoad();
    }else if(data.error == 2)
    {

        wx.removeStorageSync('ctoken');
        wx.navigateTo({
            url: '/pages/companylogin/index',
          })
  
    
      }else{

        wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false,
          success:function(){

            wx.navigateTo({
              url: '/pages/lookrole/index',
            })

          }
        })

      }


      },params);



  }, 

  toLookCompany:function(){

    var that = this;
    var ctoken = wx.getStorageSync('ctoken');
    if(ctoken)
    {

  
      var params = { noteid:that.data.id,ctoken:ctoken };
      lookrolerecord.dealLookRecord((data) => {

      if(data.error ==0)
      {
        that.data.isGetLook = true;
        that.setData({
          isGetLook:that.data.isGetLook
        })
         //that.onLoad();
         //开始打电话

         var tel = data.tel;
         wx.makePhoneCall({
           phoneNumber: tel, //此号码并非真实电话号码，仅用于测试
           success: function () {
             console.log("拨打电话成功！")
           },
           fail: function () {
             console.log("拨打电话失败！")
           }
         })
        }else if(data.error ==2){



        

      }else{

        wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false,
          success:function(){

            wx.navigateTo({
              url: '/pages/lookrole/index',
            })

          }
        })

      }


      },params);
      

      
    }else{

        wx.navigateTo({
          url: '/pages/companylogin/index',
        })

    }
  },

  
  doCall: function (e) {
    console.log(e.currentTarget);
    var tel = e.currentTarget.dataset.tel;
    wx.makePhoneCall({
      phoneNumber: tel,
      success: function () {
        console.log("拨打电话成功！")
      },
      fail: function () {
        console.log("拨打电话失败！")
      }
    })

  },

  toApplicantCard: function () {
    var that = this;
    var noteinfo = that.data.data;
    if (noteinfo && noteinfo.uid) {
      wx.navigateTo({
        url: '/pages/usercard/index?type=applicant&uid=' + noteinfo.uid
      })
    }
  },

  doCheckCall:function(e){

    var that = this;
    var ctoken = wx.getStorageSync('ctoken');
    if(ctoken)
    {

      if(that.data.isLook == true)
      {

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


      }else{



        that.data.isGetLook = false;
        that.setData({
          isGetLook:that.data.isGetLook
        })



      }

      

      
    }else{

        wx.navigateTo({
          url: '/pages/companylogin/index',
        })

    }

  },


  doSendmsg:function(){
    var that = this;
    var ctoken = wx.getStorageSync('ctoken');

    if(ctoken)
    {

      wx.showModal({
        title: '邀请面试',
        content: '确认邀请面试？',
        success: function (res) {

          if (res.confirm) {

          var params = { noteid:that.data.id,ctoken:ctoken};

          token.verify(
            note.sendinvatejob((data) => {

              if(data.status == 3 )
              {

                wx.navigateTo({
                  url: '/pages/companylogin/index',
                })

            
              }else{

                wx.showToast({
                  title: data.msg,
                  icon: 'none',
                  duration: 2000
                 })
              }

            },params)

          )

          }

          }})

      
    }else{

        wx.navigateTo({
          url: '/pages/companylogin/index',
        })

    }



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


    var that = this;
    return {
        title:that.data.title ,
        path: '/pages/workerdetail/index?id='+that.data.id
    }




  }
})