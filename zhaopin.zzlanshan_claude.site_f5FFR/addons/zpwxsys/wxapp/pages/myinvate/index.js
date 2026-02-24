import { Agent } from '../../model/agent-model.js';
var agent  = new Agent();
import {My} from '../my/my-model.js';
var my=new My();

Page({

  /**
   * 页面的初始数据
   */
  data: {
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
    qrcode:'',
    gobg:''
    /* 生成二维码参数 */
  },

  toIndex:function(){

    wx.redirectTo({
      url: '/pages/index/index',
    })
},


toFindjob: function (e) {

  var that = this;

  wx.redirectTo({
    url: "/pages/findjob/index"
  })




},

toMyinvate: function (e) {

  var that = this;

  wx.redirectTo({
    url: "/pages/myinvate/index"
  })




},


toSysmsg:function(){

  var that = this;

  wx.navigateTo({
    url: "/pages/sysmsg/index"
  })
},

toMyuser:function(){

  wx.redirectTo({
    url: '/pages/user/index',
  })
},

toMyteam:function(){

  wx.redirectTo({
    url: '/pages/myteam/index',
  })
},
toFxRule:function(e){

    wx.navigateTo({
      url: "/pages/fxrule/index"
    })

  },

  createNewImg: function () {
    var that = this;
    var context = wx.createCanvasContext('mycanvas');
    context.setFillStyle("#ffe200")
    context.fillRect(0, 0, 375, 667)
    var path = that.data.gobg;

    context.drawImage(that.data.gobgfile, 0, 0, 375, 667);
    var path1 = that.data.touxiang;



    var path4 = "../../imgs/icon/wenziBg.png";
    var path5 = "../../imgs/icon/wenxin.png";


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

 
    wx.downloadFile({
      url: that.data.qrcode,
      success: function (res) {
        console.log(res.tempFilePath);
        that.data.myqrcodefile = res.tempFilePath;

        console.log(that.data.myqrcodefile);

        wx.downloadFile({
          url: that.data.gobg,
          success: function (res2) {

            that.data.gobgfile = res2.tempFilePath;
            that.createNewImg();
          }})



  



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
    var title = '邀请好友';
 

      wx.setNavigationBarTitle({
        title:title,
      })


      var params = {};

      my.getWxUserInfo((data) => {
  
            that.data.qrcode = data.userinfo.qrcode;
            that.data.gobg = data.userinfo.sharebg;
              that.setData({
                userinfo:data.userinfo,
              
              });

              wx.hideNavigationBarLoading(); //完成停止加载
              wx.stopPullDownRefresh();
          },params);

  },

  toMyfxinvate:function()
  {

    wx.navigateTo({
      url: '/pages/myfxinvate/index',
    })

  },
  toAgentsort:function(){

    wx.navigateTo({
      url: '/pages/agentsort/index',
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