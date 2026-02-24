
import {Base} from '../../utils/base.js'



class My extends Base{
    constructor(){
        super();
    }

    UserInit(callback,params){

        var that=this;
        var param={
            url: 'v1.Sysinit/getUserinit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };

        this.request(param);


    }


    getUserMoney(callback,params){

        var that=this;
        var param={
            url: 'v1.Sysinit/getUserMoney',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };

        this.request(param);


    }

    

    getMoneyRecord(callback,params){

        var that=this;
        var param={
            url: 'v1.Sysinit/getMoneyRecord',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };

        this.request(param);


    }

    getWxUserInfo(callback,params){

        var that=this;
        var param={
            url: 'v1.Sysinit/getWxUserInfo',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };

        this.request(param);


    }

    //得到用户信息
    getUserInfo(cb){
        var that=this;
        wx.login({
            success: function () {

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

                        console.log('mmmxxyyyy');


                        console.log(res);
                        typeof cb == "function" && cb(res.userInfo);

                        //将用户昵称 提交到服务器
                        if(!that.onPay) {
                            that._updateUserInfo(res.userInfo);
                        }

                    },
                    fail:function(res){
                        typeof cb == "function" && cb({
                            avatarUrl:'../../imgs/icon/user@default.png',
                            nickName:'零食小贩'
                        });
                    }
                });


            }}})

            }




            },

        })
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
        this.request(allParams);

    }
}



export {My}