import {Base} from '../utils/base.js';

class Lookrole extends Base{
    constructor(){
        super();
    }





    GetLookroleList(callback,params){
        var that=this;
        var param={
            url: 'v1.Lookrole/getlookrolelist',
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

export {Lookrole};