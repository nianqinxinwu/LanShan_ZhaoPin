// 引用使用es6的module引入和定义
// 全局变量以g_开头
// 私有函数以_开头

import { Config } from 'config.js';

import { Base } from 'base.js';


class Token  {
    constructor() {
        this.verifyUrl = Config.restUrl + 'v1.token/verifyToken';
        this.tokenUrl = Config.restUrl + 'v1.token/getToken';
        this.updateUserUrl = Config.restUrl + 'v1.token/updateuser';
    }

    verify(cb) {
   
        var token = wx.getStorageSync('token');
        if (!token) {
            this.getTokenFromServer(cb);
        }
        else {
            this._veirfyFromServer(token,cb);
        } 
    }

    _veirfyFromServer(token,cb) {
        var that = this;
        wx.request({
            url: that.verifyUrl,
            method: 'POST',
            data: {
                token: token
            },
            success: function (res) {
                var valid = res.data.isValid;

                if(!valid){
                    that.getTokenFromServer();
                }else{

                    var userinfo = wx.getStorageSync('userInfo');

                    if (!userinfo) {
       
                        that.dologin(cb);
                  
                       }

                }

            }
        })
    }

    getTokenFromServer(cb) {
        var that  = this;
        wx.login({
            success: function (res) {
                wx.request({
                    url: that.tokenUrl,
                    method:'POST',
                    data:{
                        code:res.code
                    },
                    success:function(res){
                        if(res.data.token == false)
                        {

                            wx.showModal({
                                title: '提示',
                                content: '请到后台正确配置appid与appsecret',
                                showCancel: false
                              })
                              return

                        }else{

                        wx.setStorageSync('token', res.data.token);
                        }
                      //  callBack&&callBack(res.data.token);

                      that.dologin(cb);


                    }
                })
            }
        })
    }


    getTokenPhoneNumFromServer(cb) {
        var that  = this;

        console.log('tokenekenenenen');
        wx.login({
            success: function (res) {
                wx.request({
                    url: that.tokenUrl,
                    method:'POST',
                    data:{
                        code:res.code
                    },
                    success:function(res){
                        wx.setStorageSync('token', res.data.token);
                        typeof cb == "function" && cb();


                    }
                })
            }
        })
    }



    dologin(cb){

        var that = this;
        if(that.islogin == 1)
        {
          if (wx.canIUse('getUserProfile')) {


            wx.showModal({
                title: '获取用户信息',
                content: '请允许授权以便为您提供服务',
                success: function (res) {
                    if (res.confirm) {
            

          wx.getUserProfile({
            lang: 'zh_CN',
            desc: '用于登陆',
            success: function (res) {

                

                typeof cb == "function" && cb(res.userInfo);

                 //   that._updateUserInfo(res.userInfo);
                

            },
            fail:function(res){
                typeof cb == "function" && cb({
                    avatarUrl:'../../imgs/icon/user@default.png',
                    nickName:'会员用户'
                });
            }
        });


    }}})}

}

    }

   /*更新用户信息到服务器*/
   _updateUserInfo(res){
    var nickName=res.nickName;
    delete res.avatarUrl;  //将昵称去除
    delete res.nickName;  //将昵称去除
    var allParams = {
        url: 'user/wx_info',
        data:{nickname:nickName,extend:JSON.stringify(res)},
        type:'post',
        sCallback: function (data) {

           
        }
    };

    var req = new Base();
    req.request(allParams);

}



}

export {Token};