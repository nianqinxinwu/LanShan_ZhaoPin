import {Base} from '../utils/base.js';

class Sysmsg extends Base{
    constructor(){
        super();
    }


    GetsysmsgList(callback,params){
        var that=this;
        var param={
            url: 'v1.Sysmsg/getSysmsgList',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    Updatesysmsg(callback,params){
        var that=this;
        var param={
            url: 'v1.Sysmsg/updateSysmsg',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    MarkAsRead(callback,params){
        var that=this;
        var param={
            url: 'v1.Sysmsg/markAsRead',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    




};

export {Sysmsg};