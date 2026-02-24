import {Base} from '../utils/base.js';

class Active extends Base{
    constructor(){
        super();
    }


    CheckActiveRecord(callback,params){
        var that=this;
        var param={
            url: 'v1.Active/checkActiveRecord',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    GetActiveList(callback,params){
        var that=this;
        var param={
            url: 'v1.Active/getActivelist',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    
    GetActivedetail(callback,params){
        var that=this;
        var param={
            url: 'v1.Active/getActivedetail',
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

export {Active};