import { Company } from '../../model/company-model.js';

var company  = new Company();
Page({

  /**
   * 页面的初始数据
   */
  data: {
 page:1,
 currentTab: -1,
 filterStatus: -1,
 /* 生成二维码参数 */
 img:"../../imgs/icon/gobg.png",
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


  createNewImg: function () {
    var that = this;
    var context = wx.createCanvasContext('mycanvas');
    context.setFillStyle("#ffe200")
    context.fillRect(0, 0, 375, 667)
    var path = "../../imgs/icon/gobg.png";
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

  /**
   * 生命周期函数--监听页面加载
   */
  onShow: function (options) {

    var that = this;

    wx.setNavigationBarTitle({
      title: '我的发布',
    })

     that.initpage();

  },


  initpage:function()
  {
    var that = this;

    company.checkLogin((data) => {
      var ctoken = wx.getStorageSync('ctoken');

      if (ctoken && data && data.error == 0) {
        var params = { ctoken: ctoken, page: that.data.page, filterStatus: that.data.filterStatus };

        company.companyjob((jobData) => {
          var joblist = jobData.joblist || [];
          var settleTypeMap = { 0: '日结', 1: '周结', 2: '月结', 3: '完工结' };
          var sexMap = { 0: '男女不限', 1: '仅限男', 2: '仅限女' };
          for (var i = 0; i < joblist.length; i++) {
            var st = parseInt(joblist[i].settle_type);
            joblist[i].settle_type_text = settleTypeMap[st] !== undefined ? settleTypeMap[st] : '面议';
            var sx = parseInt(joblist[i].sex);
            joblist[i].sex_text = sexMap[sx] !== undefined ? sexMap[sx] : '男女不限';
          }
          that.setData({
            list: joblist
          });
        }, params);
      } else {
        wx.navigateTo({
          url: "/pages/companylogin/index"
        })
      }

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    });
  },

  switchTab: function (e) {
    var that = this;
    var status = parseInt(e.currentTarget.dataset.status);
    that.setData({
      filterStatus: status,
      currentTab: status,
      page: 1,
      list: []
    });
    that.initpage();
  },

  addcompanyjob:function(){
    var checkFlag = wx.getStorageSync('checkFlag') || 0;
    wx.navigateTo({
      url: checkFlag == 1 ? "/pages/addcompanyjob/index" : "/pages/zwaddjob/index"
    })
  }, editCompanyjob:function(e){

    var id = e.currentTarget.dataset.id;
 

    wx.redirectTo({
      url: "/pages/editcompanyjob/index?id=" + id
    })
  },

  toMatchnote:function(e){

    var id = e.currentTarget.dataset.id;

    var ischeck = e.currentTarget.dataset.ischeck;

    if(ischeck == 0)
    {
      wx.showModal({
        title: '提示',
        content: '该职位未审核通过',
        showCancel: false
      })
      return

    }else{

    wx.navigateTo({
      url: "/pages/matchnote/index?id=" + id
    })
  }
  },

  toSelectNote: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/selectnote/index?jobid=" + id
    })
  },

  toBuyService: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/buyservice/index?jobid=" + id
    })
  },

  toSigninDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/signindetail/index?jobid=" + id
    })
  },

  toSpreadJob: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var companyid = wx.getStorageSync('companyid');
    var params = { companyid: companyid, id: id };

    wx.showModal({
      title: '提示',
      content: '您的操作将会消耗加急扩散1次？',
      success: function (res) {
        if (res.confirm) {
          company.spreadJob((data) => {
            if (data.status == 0) {
              wx.showModal({
                title: '提示',
                content: data.msg,
                showCancel: false,
                success: function () {
                  that.onShow();
                }
              })
            } else {
              wx.showToast({
                title: data.msg,
                icon: 'none',
                duration: 2000
              })
            }
          }, params);
        }
      }
    })
  },

  topPaytopjob:function(e)
  {

    var that = this;

    var id = e.currentTarget.dataset.id;

    var companyid =  wx.getStorageSync('companyid');
    var params = {companyid:companyid,id:id};
        wx.showModal({
          title: '提示',
          content: '您的操作将会消耗置顶1次？',
          success: function (res) {
            if (res.confirm) {

                company.topJob((data) => {

                  if(data.status == 0 )
                  {
                    wx.showModal({
                      title: '提示',
                      content: data.msg,
                      showCancel: false,
                      success:function(){

                        that.onShow();
                      }
                    })

                

                  }else{

                    wx.showToast({
                      title: data.msg,
                      icon: 'none',
                      duration: 2000
                    })

                  }
                                            
                    },params);


            }
          }

        })

  },

  cancleJob:function(e){

    var that = this;

    var id = e.currentTarget.dataset.id;

    var ctoken = wx.getStorageSync('ctoken');
    var params = {ctoken:ctoken,id:id};

        wx.showModal({
          title: '下架',
          content: '确认下架？',
          success: function (res) {
            if (res.confirm) {

                company.cancleJob((data) => {

                  that.onShow();
                                            
                    },params);


            }
          }

        })

   
  }, upJob: function (e) {

    var that = this;

    var id = e.currentTarget.dataset.id;

    var ctoken = wx.getStorageSync('ctoken');
    var params = {ctoken:ctoken,id:id};
        wx.showModal({
          title: '上架',
          content: '确认上架？',
          success: function (res) {
            if (res.confirm) {

                company.upJob((data) => {

                  that.onShow();
                                            
                    },params);


            }
          }

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
        title:'我的发布' ,
        path: '/pages/index/index'
    }
  }
  
})