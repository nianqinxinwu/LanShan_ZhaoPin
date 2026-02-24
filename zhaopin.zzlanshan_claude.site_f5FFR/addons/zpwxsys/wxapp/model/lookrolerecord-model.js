import {Base} from '../utils/base.js';

class Lookrolerecord extends Base{
    constructor(){
        super();
    }





    dealLookroleRecord(callback,params){
        var that=this;
        var param={
            url: 'v1.Lookrolerecord/dealLookroleRecord',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    dealLookRecord(callback,params){
        var that=this;
        var param={
            url: 'v1.Companyrecord/dealLookRecord',
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

export {Lookrolerecord};