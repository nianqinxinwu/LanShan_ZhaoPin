import {Base} from '../utils/base.js';

class Order extends Base{
    constructor(){
        super();
    }


    Activeorder(callback,params){
        var that=this;
        var param={
            url: 'v1.Order/activeOrder',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


  

    Roleorder(callback,params){
        var that=this;
        var param={
            url: 'v1.Order/companyroleOrder',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    LookRoleorder(callback,params){
        var that=this;
        var param={
            url: 'v1.Order/lookRoleOrder',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }




};

export {Order};