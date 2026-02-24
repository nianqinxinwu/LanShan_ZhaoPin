// pages/user/index.js
import { Token } from '../../utils/token.js';
import { Config } from '../../utils/config.js';

import {My} from '../my/my-model.js';
var my=new My();

import { Agent } from '../../model/agent-model.js';

var agent  = new Agent();

import { Note } from '../findworker/note-model.js';
var note = new Note(); //实例化 首页 对象

import { User } from '../../model/user-model.js';

var user  = new User();

import { Sysmsg } from '../../model/sysmsg-model.js';

var sysmsg  = new Sysmsg();

import { Company } from '../../model/company-model.js';

var company  = new Company();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    isuser:true,
    checkFlag: 0,
    userRole: 0,
    isPublisher: false,
    coinBalance: 0,
    pointBalance: 0,
    rating: 0,
    loginDays: 1,
    isVerified: false,
    ratingStars: [],
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
    url: "/pages/switchrole/index"
  })


},


toSysmsg:function(){

  wx.redirectTo({
    url: '/pages/sysmsg/index',
  })
},


toMyuser:function(){

  wx.redirectTo({
    url: '/pages/user/index',
  })
},

toMymoney:function(){

  wx.navigateTo({
    url: '/pages/mymoney/index',
  })
},
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;

    var checkFlag = wx.getStorageSync('checkFlag') || 0;
    var ctoken = wx.getStorageSync('ctoken');
    var userRole = wx.getStorageSync('user_role') || 0;
    that.setData({
      checkFlag: checkFlag,
      userRole: parseInt(userRole),
      isPublisher: !!ctoken
    });

    wx.setNavigationBarTitle({
      title: userRole == 2 ? '会员中心' : '我的',
    })

    var appuser = wx.getStorageSync('userInfo');

    console.log('user page loaded');


      var params = {};

      my.UserInit((data) => {

              // 头像URL补全：相对路径加上服务器地址
              var baseUrl = Config.restUrl.replace(/\/addons\/zpwxsys\/$/, '');
              if (data.userinfo && data.userinfo.avatarUrl && data.userinfo.avatarUrl.indexOf('/') === 0) {
                data.userinfo.avatarUrl = baseUrl + data.userinfo.avatarUrl;
              }

              that.setData({
                sendnotecount:data.sendnotecount,
                noticenotecount:data.noticenotecount,
                totallooknum:data.totallooknum,
                sysmsgcount:data.sysmsgcount,
                isuser:data.isuser,
                userinfo:data.userinfo,
                coinBalance: data.coin_balance || 0,
                pointBalance: data.point_balance || 0,
                rating: data.rating || 0,
                loginDays: data.login_days || 1,
                isVerified: !!data.is_verified,
              });

              // 同步 user_role 到本地存储和页面数据
              if (data.user_role) {
                wx.setStorageSync('user_role', data.user_role);
                that.setData({ userRole: parseInt(data.user_role) });
              }

              // 计算小红花评分图标
              that.computeRatingStars(data.rating || 0);

              wx.hideNavigationBarLoading(); //完成停止加载
              wx.stopPullDownRefresh();
          },params);


      that.setData({
        userinfo:appuser
      });
   //  }

     that.setData({
      isuser:that.data.isuser
    });

 },

 /**
  * 将0-5分转为5个小红花图标路径数组
  */
 computeRatingStars: function(score) {
   var stars = [];
   score = parseFloat(score) || 0;
   for (var i = 1; i <= 5; i++) {
     if (score >= i) {
       stars.push('../../imgs/icon/flower.png');
     } else if (score >= i - 0.5) {
       stars.push('../../imgs/icon/flower_half.png');
     } else {
       stars.push('../../imgs/icon/flower_empty.png');
     }
   }
   this.setData({ ratingStars: stars });
 },

 toLogin: function (e) {


  var that = this;

  var ctoken = wx.getStorageSync('ctoken');

  if(ctoken)
  {



  var params = {ctoken:ctoken};

  user.checkBind((data) => {

     if(data.isbind)
     {
          company.checkLogin((data) => {

            if(data.error ==0)
            {
              wx.navigateTo({
                url: "/pages/companycenter/index"
              })

            }else{
              wx.navigateTo({
                url: "/pages/companylogin/index"
              })

            }



              },params)




  }else{

    wx.navigateTo({
      url: "/pages/register/index"
    })

  }



      },params);


    }else{



      wx.navigateTo({
        url: "/pages/companylogin/index"
      })
    }



},



 toMatchNote:function(){

  var that = this;

var params = {};

    note.CheckNote((data) => {

      if(data.status == 1)
      {

        wx.navigateTo({
          url: '/pages/matchjob/index',
        })


      }else if(data.status == 2){

        wx.navigateTo({
          url: '/pages/mynote/index',
        })

      }else{

        wx.showToast({
          title: data.msg,
          icon: 'none',
          duration: 2000
        })

      }

        console.log(data);

        },params);

},

refreshNotice:function(){

    var that = this;

    var params = {};

    user.checkBind((data) => {

        if(data.isbind)
        {

        note.NoteRefresh((data) => {

            if(data.status == 1)
            {

            wx.showToast({
                title: data.msg,
                icon: 'none',
                duration: 2000
            })

            }else if(data.status == 2){

            wx.navigateTo({
                url: '/pages/mynote/index',
            })

            }else{

            wx.showToast({
                title: data.msg,
                icon: 'none',
                duration: 2000
            })

            }

            console.log(data);

            },params);


    }else{

    wx.navigateTo({
        url: "/pages/register/index"
    })

    }



        },params);



  },

  bindGetUserInfo:function(){

    var that=this;

    if (!that.data.isuser) {
      // 未登录用户，先去微信绑定资料，绑定完成后会跳转身份选择
      wx.navigateTo({
        url: "/pages/bindwx/index?from=login"
      })
    } else {
      // 已登录但未选择身份，跳转身份选择页面
      var userRole = wx.getStorageSync('user_role') || 0;
      if (userRole == 0) {
        wx.navigateTo({
          url: "/pages/loginrole/index"
        })
      } else if (userRole == 2 && !wx.getStorageSync('ctoken')) {
        // 发布者未绑定企业，弹窗引导完善企业信息
        wx.showModal({
          title: '完善企业信息',
          content: '您还未绑定企业信息，绑定后才能发布岗位。是否现在去完善？',
          confirmText: '去完善',
          cancelText: '稍后再说',
          success: function (res) {
            if (res.confirm) {
              wx.navigateTo({
                url: '/pages/companylogin/index'
              })
            }
          }
        });
      }
    }

  },

  updateuserinfo:function(){

    var that = this;
    var userinfo = wx.getStorageSync('userInfo');
    var params = {nickname:userinfo.nickName,avatarUrl:userinfo.avatarUrl};

    user.Updateuser((data) => {


      that.data.istel= data.istel;
      that.setData({

        istel:that.data.istel
     });

        },params);

  },
  toAgentcenter:function()
  {

    var that = this;

    var params = {};

    agent.Checkagent((data) => {

      if(data.status == 0 )
      {
        wx.navigateTo({
          url: "/pages/regagent/index"
        })

      }else if(data.status == 1){
        wx.navigateTo({
          url: "/pages/agentcenter/index"
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


  },
  toMydone:function()
  {
    var that = this;
    wx.navigateTo({
      url: "/pages/mydone/index"
    })

  },
  toHelp:function()
  {
    var that = this;
    wx.navigateTo({
      url: "/pages/article/index?id=846"
    })

  },

  toMyComment:function()
  {
    var that = this;
    wx.navigateTo({
      url: "/pages/mycomment/index"
    })

  },

  toMylooknote:function()
  {
    var that = this;
    wx.navigateTo({
      url: "/pages/mylooknote/index"
    })

  },
  toMyNote: function () {
    var that = this;

    var that = this;

    var params = {};



    user.checkBind((data) => {

       if(data.isbind)
       {

        wx.navigateTo({
          url: "/pages/notecenter/index"
        })


    }else{

      wx.navigateTo({
        url: "/pages/register/index"
      })

    }



        },params);

  },
  toMyFind:function(e){
    var that = this;
      var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/myfind/index?id="+id
    })

  },
  toLookrole:function(){

    var that = this;
    wx.navigateTo({
      url: "/pages/lookrole/index"
    })
  },


  toMymoneyrecord:function(){
    var that = this;
    wx.navigateTo({
      url: "/pages/mymoneyrecord/index"
    })

  },


  toMySave: function (e) {

    var that = this;

    wx.navigateTo({
      url: "/pages/mysave/index"
    })

  },

  toMyGz: function (e) {

    var that = this;

    wx.navigateTo({
      url: "/pages/mygz/index"
    })

  },

  toMyPublished: function () {
    wx.navigateTo({
      url: "/pages/companyjob/index"
    })
  },

  toSigninList: function () {
    wx.navigateTo({
      url: "/pages/signinlist/index"
    })
  },

  toChatList: function () {
    wx.navigateTo({
      url: "/pages/mychatlist/index"
    })
  },

  toMyCard: function () {
    var ctoken = wx.getStorageSync('ctoken');
    if (ctoken) {
      wx.navigateTo({
        url: "/pages/usercard/index?type=publisher"
      })
    } else {
      wx.navigateTo({
        url: "/pages/usercard/index?type=applicant"
      })
    }
  },

  toAgreement: function () {
    wx.navigateTo({
      url: "/pages/article/index?id=847"
    })
  },

  toPrivacy: function () {
    wx.navigateTo({
      url: "/pages/article/index?id=848"
    })
  },

  toCooperate: function () {
    wx.navigateTo({
      url: "/pages/article/index?id=849"
    })
  },

  toRecharge: function () {
    wx.navigateTo({
      url: '/pages/mymoney/index'
    })
  },

  toExchange: function () {
    wx.navigateTo({
      url: '/pages/mypoint/index'
    })
  },

  toRecommend: function () {
    wx.navigateTo({
      url: '/pages/switchrole/index'
    })
  },

  toAccountSecurity: function () {
    wx.navigateTo({
      url: '/pages/bindwx/index'
    })
  },

  toPinTop: function () {
    wx.navigateTo({
      url: '/pages/companycenter/index'
    })
  },

  toSpread: function () {
    wx.navigateTo({
      url: '/pages/companycenter/index'
    })
  },

  toGuide: function () {
    wx.navigateTo({
      url: '/pages/article/index?id=846'
    })
  },

  logout: function () {
    wx.showModal({
      title: '提示',
      content: '确定退出登录吗？',
      success: function (res) {
        if (res.confirm) {
          wx.clearStorageSync();
          wx.redirectTo({
            url: '/pages/index/index'
          })
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
  onShow: function () {
      var that = this;
      that.onLoad();
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
  onPullDownRefresh: function(){
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
          title: wx.getStorageSync('user_role') == 2 ? '会员中心' : '我的' ,
          path: '/pages/user/index'
      }

  }
})
