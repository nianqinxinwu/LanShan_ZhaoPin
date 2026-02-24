import {Base} from '../utils/base.js';

class User extends Base{
    constructor(){
        super();
    }

  


    checkBind(callback,params){
        var that=this;
        var param={
            url: 'v1.Login/checkBind',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    Updateuser(callback,params){
        var that=this;
        var param={
            url: 'v1.Sysinit/updateUser',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }
 

    getPhone(callback,params){
        var that=this;
        var param={
            url: 'v1.Sysinit/getPhone',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    Updateusertel(callback,params){
        var that=this;
        var param={
            url: 'v1.Sysinit/updateUsertel',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    myLookNote(callback,params){
        var that=this;
        var param={
            url: 'v1.Note/myLookNote',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    userLogin(callback,params){
        var that=this;
        var param={
            url: 'v1.Login/userLogin',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    userRegister(callback,params){
        var that=this;
        var param={
            url: 'v1.Login/userRegister',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    userSysinit(callback,params){
        var that=this;
        var param={
            url: 'v1.Login/userSysinit',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    setUserRole(callback,params){
        var that=this;
        var param={
            url: 'v1.Login/setUserRole',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getUserRole(callback,params){
        var that=this;
        var param={
            url: 'v1.Login/getUserRole',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }


    
   



};

export {User};