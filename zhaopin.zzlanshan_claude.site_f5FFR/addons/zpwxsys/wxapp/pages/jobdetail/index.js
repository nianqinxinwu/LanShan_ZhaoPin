import { Findjob } from '../findjob/findjob-model.js';
var findjob = new Findjob(); 
import { User } from '../../model/user-model.js';

import { Token } from '../../utils/token.js';

var token = new Token();


var user  = new User();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:0,
    savestatus:0,
    title:'',
  
        /* 生成二维码参数 */
        img:"../../imgs/icon/gobg.jpg",
        wechat:"../../imgs/icon/wechat.png",
        quan:"../../imgs/icon/quan.png",
        code:"E7AI98",
        inputValue:"",
        maskHidden: false,
        name:"",
        touxiang:"",
        code: "E7A93C",
        qrcode:''
        /* 生成二维码参数 */
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
    if(e)
    {
        if (e.hasOwnProperty("scene"))
        {
        var scene = decodeURIComponent(e.scene);
        var uid_array = scene.split('=');
        that.data.id = parseInt(uid_array[1]);

        }else{


          if (that.data.id > 0) {
            var id = that.data.id;
          } else {
            var id = e.id;
            that.data.id = e.id;  
          }

        }
    }



    var params = { id:that.data.id};



 
    findjob.getJobDetailData((data) => {


        if(data.status ==0)
        {

    
 
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

  baocun2:function(){
    var that = this
    wx.saveImageToPhotosAlbum({
      filePath: that.data.imagePath,
      success(res) {
        wx.showModal({
          content: '图片已保存到相册，赶紧晒一下吧~',
          showCancel: false,
          confirmText: '好的',
          confirmColor: '#333',
          success: function (res) {
            if (res.confirm) {
              console.log('用户点击确定');
              /* 该隐藏的隐藏 */
              that.setData({
                maskHidden: false
              })
            }
          },fail:function(res){
            console.log(11111)
          }
        })
      }
    })
  },


  
  toPloitejob:function(e){

    var id = e.currentTarget.dataset.id;

    wx.navigateTo({
      url: "/pages/ploiteinfo/index?id=" + id
    })
  },
  toContact:function(){
    var companyid = this.data.companyinfo.id;
    wx.navigateTo({
      url: '/pages/wechat/index?id='+companyid
    })
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


  baocun2:function(){
    var that = this
    wx.saveImageToPhotosAlbum({
      filePath: that.data.imagePath,
      success(res) {
        wx.showModal({
          content: '图片已保存到相册，赶紧晒一下吧~',
          showCancel: false,
          confirmText: '好的',
          confirmColor: '#333',
          success: function (res) {
            if (res.confirm) {
              console.log('用户点击确定');
              /* 该隐藏的隐藏 */
              that.setData({
                maskHidden: false
              })
            }
          },fail:function(res){
            console.log(11111)
          }
        })
      }
    })
  },

  doSendjob: function (e) {
    var that = this;




    var companyid =  e.currentTarget.dataset.id;
    var params = { jobid:that.data.id,companyid:companyid};

    var params2 = {};


    token.verify(
    user.checkBind((data) => {

      if(data.isbind)
      {

        findjob.sendJob((data) => {

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
    

   }else{

     wx.navigateTo({
       url: "/pages/register/index"
     })

   }

      

       },params2)

    );



    




  },

  doSavejob: function (e) {
    var that = this;
    var companyid =  e.currentTarget.dataset.id;
    var params = { jobid:that.data.id,companyid:companyid};
    token.verify(
    findjob.jobSave((data) => {
     
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
 },params)
    )



  },

  formSubmit: function (e) {
    var that = this;
    this.setData({
      maskHidden: false
    });
    wx.showToast({
      title: '正在生成中...',
      icon: 'loading',
      duration: 1000
    });



    var params = { id:that.data.id};


 
    findjob.getqrcodejob((data) => {
 
 
      that.data.qrcode2 = data.qrcode;

      wx.downloadFile({
        url: data.sharebg,
        success: function (res2) {

          that.data.gobg = res2.tempFilePath;

      wx.downloadFile({
        url: that.data.qrcode2,
        success: function (res) {
          console.log(res.tempFilePath);
          that.data.myqrcodefile = res.tempFilePath;
  
  
          that.createNewImg();
  
  
  
        }})
  
    }})


 },params);

    







   
  
  },

  createNewImg: function () {
    var that = this;
    var context = wx.createCanvasContext('mycanvas');
    context.setFillStyle("#ffe200")
    context.fillRect(0, 0, 375, 667)
   // var path = "../../imgs/icon/gobg.jpg";
   var path = that.data.gobg;
    //将模板图片绘制到canvas,在开发工具中drawImage()函数有问题，不显示图片
    //不知道是什么原因，手机环境能正常显示
    context.drawImage(path, 0, 0, 375, 667);
    var path1 = that.data.touxiang;
    console.log(path1,"path1")
    //将模板图片绘制到canvas,在开发工具中drawImage()函数有问题，不显示图片
    //var path2 = "../../imgs/icon/txquan.png";
    //var path3 = "../../imgs/icon/heise.png";

    var path4 = "../../imgs/icon/wenziBg.png";
    var path5 = "../../imgs/icon/wenxin.png";
    
   // context.drawImage(path2, 126, 186, 120, 120);
 
    

    var name = that.data.name;
    //绘制名字
    context.setFontSize(24);
    context.setFillStyle('#333333');
    context.setTextAlign('center');
    context.fillText(name, 185, 340);
    context.stroke();


    //绘制左下角文字背景图
    context.drawImage(path4, 25, 520, 184, 82);
    context.setFontSize(12);
    context.setFillStyle('#333');
    context.setTextAlign('left');
    context.fillText("进入小程序查看信息", 35, 540);
    context.stroke();
    context.setFontSize(12);
    context.setFillStyle('#333');
    context.setTextAlign('left');
    context.fillText(",分享赚钱正在等着", 35, 560);
    context.stroke();
    context.setFontSize(12);
    context.setFillStyle('#333');
    context.setTextAlign('left');
    context.fillText("你来哦~", 35, 580);
    context.stroke();
    //绘制右下角扫码提示语

    context.drawImage(that.data.myqrcodefile, 243, 495, 100, 100);

    context.drawImage(path5, 248, 598, 90, 25);
    //绘制头像
    context.arc(186, 246, 50, 0, 2 * Math.PI) //画出圆
    context.strokeStyle = "#ffe200";
    context.clip(); //裁剪上面的圆形
    context.drawImage(path1, 136, 196, 100, 100); // 在刚刚裁剪的园上画图
    context.draw();
    //将生成好的图片保存到本地，需要延迟一会，绘制期间耗时

    setTimeout(function () {
      wx.hideToast()
     
      that.drawAfter();
      that.setData({
        maskHidden: true
      });
    }, 200)

    
  },


  
  drawAfter: function () {
    var that = this;
    
    wx.canvasToTempFilePath({
      canvasId: 'mycanvas',
      success: function (res) {
        var tempFilePath = res.tempFilePath;

        
        that.setData({
          imagePath: tempFilePath,
          canvasHidden:true
        });
      },
      fail: function (res) {
        console.log(res);
      }
    });

   
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
     url: "/pages/jobdetail/index?id=" + id
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
        path: '/pages/jobdetail/index?id='+that.data.id
    }

  }
})