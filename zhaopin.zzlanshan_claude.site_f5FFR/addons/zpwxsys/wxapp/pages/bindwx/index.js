const defaultAvatarUrl2 = '../../imgs/icon/qpic.png'



import { User } from '../../model/user-model.js';

var user  = new User();

import { Company } from '../../model/company-model.js';

var company  = new Company();

import {My} from '../my/my-model.js';
var my=new My();

import { Token } from '../../utils/token.js';

var token = new Token();


Page({
  data: {
    avatarUrl: defaultAvatarUrl2,
    avatarUrlimg:'',
    role: '',
    from: '',
    tel: ''
  },
  onChooseAvatar(e) {
    var that = this;
    const { avatarUrl } = e.detail 
    that.data.avatarUrl = avatarUrl;
    that.uploadimg(that.data.avatarUrl,1);
    this.setData({
      avatarUrl,
    })
  },
  onShow:function()
  {
    var that = this;

    token.getTokenPhoneNumFromServer((data)=>{


    })
    
  },

  onLoad:function(options)
  {
    var that = this;
    that.data.role = options.role || '';
    that.data.from = options.from || '';
    wx.setNavigationBarTitle({
      title: '微信绑定资料',
    })
    var params = {};

    my.UserInit((data) => {
  
      that.data.avatarUrlimg = data.userinfo.avatarUrl;
      that.setData({
        userinfo:data.userinfo,
        tel:data.userinfo.tel,
        avatarUrl:data.userinfo.avatarUrl,
        defaultAvatarUrl2:defaultAvatarUrl2
      });

        wx.hideNavigationBarLoading(); //完成停止加载
        wx.stopPullDownRefresh();

    },params);

  },

  getPhoneNumber: function (e) {
    var that = this;

    console.log('getPhoneNumber e.detail:', JSON.stringify(e.detail));

    if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {
      wx.showModal({
        title: '提示',
        showCancel: false,
        content: '未授权',
        success: function (res) { }
      })
      return;
    }

    if (!e.detail.code) {
      console.log('getPhoneNumber: e.detail.code 为空');
      wx.showToast({
        title: '获取手机号code为空，请重试',
        icon: 'none'
      })
      return;
    }

    var params = {code: e.detail.code};
    console.log('getPhone 请求参数:', JSON.stringify(params));
    user.getPhone((data) => {
      console.log('getPhone 返回数据:', JSON.stringify(data));
      if (data.status == 0) {
        that.setData({
          tel: data.tel
        });
      } else {
        wx.showToast({
          title: data.msg || '获取手机号失败',
          icon: 'none'
        })
      }
    }, params);
  },
  savepubinfo: function (e) {
    var that = this;

    var nickname = e.detail.value.nickname;
    var avatarUrlimg = that.data.avatarUrlimg;
    var tel = that.data.tel || '';

    if( avatarUrlimg == '')
    {
      wx.showModal({
        title: '提示',
        content: '请上传头像',
        showCancel: false
      })
      return

    }
    if(nickname == '')
    {

      wx.showModal({
        title: '提示',
        content: '请输入昵称',
        showCancel: false
      })
      return



    }

    if(tel == '')
    {

      wx.showModal({
        title: '提示',
        content: '请获取手机号',
        showCancel: false
      })
      return



    }


    var params = {avatarUrl: avatarUrlimg,nickname:nickname,tel:tel};


    user.Updateuser((data) => {

        wx.showToast({
          title: '修改成功',
          icon: 'success',
          duration: 1000,
          mask:true
        })

        setTimeout(function() {
          if (that.data.from === 'login') {
            // 新用户首次登录：绑定完成后去选择身份
            wx.redirectTo({
              url: '/pages/loginrole/index'
            })
          } else if (that.data.role === 'publisher') {
            // 发布者流程：保存后跳转企业登录
            wx.redirectTo({
              url: '/pages/companylogin/index'
            })
          } else {
            wx.navigateBack({
              delta: 1,
            })
          }
        }, 1000)

                          },params);









  },
  uploadimg: function (path,id) {
    //var uploadurl = app.util.geturl({ 'url': 'entry/wxapp/upload' });
    // var id = id;
    wx.showToast({
      icon: "loading",
      title: "正在上传"
    });

    var that = this;

     var params ={

      path:path

     }
     company.uploadimg((data) => {

      console.log(data);

      if(id == 1)
      {


        that.data.avatarUrlimg = data.imgpath;

      }else if(id == 2){

        that.data.imagelist.push(data.imgpath);

      }



                            
                        },params);






  },



  
})