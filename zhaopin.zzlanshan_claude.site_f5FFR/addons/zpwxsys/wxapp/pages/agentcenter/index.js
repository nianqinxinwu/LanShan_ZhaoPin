// pages/user/index.js
import { Token } from '../../utils/token.js';

import {My} from '../my/my-model.js';
var my=new My();

import { Agent } from '../../model/agent-model.js';

var agent  = new Agent();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    isuser:true,
    /* 生成二维码参数 */
    img:"../../imgs/icon/gobg.jpg",
    wechat:"../../imgs/icon/wechat.png",
    quan:"../../imgs/icon/quan.png",
    code:"E7AI98",
    inputValue:"",
    maskHidden: false,
    name:"",
    touxiang:"",
    code: "E7A93C"
    /* 生成二维码参数 */
  },


  toGetmoney:function(){

    var that = this;

    if(that.data.usermoney<=0)
    {
      
      wx.showModal({
        title: '提示',
        content: '余额不足,无法提现',
        showCancel: false
      })
      return


    }else{


      wx.showModal({
        title: '提示',
        content: '确认提现？',
        success: function (res) {
          if (res.confirm) {

            var params = {};

            agent.getUserMoney((data) => {

              if(data.error == 0 )
              {
              wx.showModal({
                title: '提示',
                content: '提现成功',
                showCancel: false,
                success:function(){
                  wx.navigateTo({
                    url: "/pages/mymoneyrecord/index"
                  })

                }
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

          }}

        })





    }

  },


  createNewImg: function () {
    var that = this;
    var context = wx.createCanvasContext('mycanvas');
    context.setFillStyle("#ffe200")
    context.fillRect(0, 0, 375, 667)
    var path = "../../imgs/icon/gobg.jpg";
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
    context.fillText("进入小程序查看海量职位", 35, 540);
    context.stroke();
    context.setFontSize(12);
    context.setFillStyle('#333');
    context.setTextAlign('left');
    context.fillText(",高端职位正在等着", 35, 560);
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
  //点击保存到相册
  baocun:function(){
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
  //点击生成
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

   // that.data.myqrcode = 'https://dev.site100.cn/uploads/images/1640250895qrcode.jpg';
    wx.downloadFile({
      url: that.data.myqrcode,
      success: function (res) {
        console.log(res.tempFilePath);
        that.data.myqrcodefile = res.tempFilePath;


        that.createNewImg();



      }})

   
  
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


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;

    wx.setNavigationBarTitle({
      title: '经纪人中心',
    })

    var appuser = wx.getStorageSync('userInfo');

    agent.agentInit((data)=>{

   
      that.data.myqrcode = data.agentinfo.qrcode;


       that.data.usermoney = data.usermoney;

      that.setData({
        totalmoney:data.totalmoney,
    
      });


      
    wx.hideNavigationBarLoading(); //完成停止加载
    wx.stopPullDownRefresh();
  
     }
     
     );
  

    if (!appuser) {
       
      that.data.isuser = true;

     }else{
      that.data.isuser = false;

      var params = {};

      


      that.setData({
        userinfo:appuser
      });
     }

     that.setData({
      isuser:that.data.isuser
    });
     
  },

  bindGetUserInfo:function(){

    var that=this;
    var token = new Token();
    token.islogin = 1;
    token.verify((data)=>{

      console.log(data);
      wx.setStorageSync('userInfo',data);
      that.data.isuser = false;

      
        
         that.setData({
        
            isuser:that.data.isuser,
             userinfo:data
         });
  
     });


/*
   my.getUserInfo((data)=>{

    console.log(data);

    wx.setStorageSync('userInfo',data);
    that.data.isuser = false;
       that.setData({
      
          isuser:that.data.isuser,
           userinfo:data
       });

   }
   
   );

   */


    
  },
  toSendorder:function(){

    var that = this;
    wx.navigateTo({
      url: "/pages/sendorder/index"
    })

  },
  toMyNote: function () {
    var that = this;
    wx.navigateTo({
      url: "/pages/mynote/index"
    })
  },

  toMyagentqrcode: function () {
    var that = this;
    wx.navigateTo({
      url: "/pages/myagentqrcode/index"
    })
  },
  toMyFind:function(){
    var that = this;
    wx.navigateTo({
      url: "/pages/myfind/index"
    })

  },

  toAgentnotelist:function(){
    var that = this;
    wx.navigateTo({
      url: "/pages/agentnotelist/index"
    })

  },

  toAgentcompanylist:function(){
    var that = this;
    wx.navigateTo({
      url: "/pages/agentcompanylist/index"
    })

  },

  toAgentFxrecord:function(){
    var that = this;
    wx.navigateTo({
      url: "/pages/agentfxrecord/index"
    })

  },

  toMytasklist:function(){
    var that = this;
    wx.navigateTo({
      url: "/pages/mytasklist/index"
    })

  },

  toMySave: function (e) {

    var that = this;

    wx.navigateTo({
      url: "/pages/mysave/index"
    })

  },

  toMyteam: function (e) {

    var that = this;

    wx.navigateTo({
      url: "/pages/myteam/index"
    })

  },
  toMymoneyrecord:function(){

    var that = this;

    wx.navigateTo({
      url: "/pages/mymoneyrecord/index"
    })
  },
  
  toMyGz: function (e) {

    var that = this;

    wx.navigateTo({
      url: "/pages/mygz/index"
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
        title:'经纪人中心' ,
        path: '/pages/index/index'
    }
  }
})