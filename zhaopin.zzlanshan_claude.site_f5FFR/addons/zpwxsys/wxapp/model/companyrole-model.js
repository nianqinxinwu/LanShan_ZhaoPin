import {Base} from '../utils/base.js';

class Companyrole extends Base{
    constructor(){
        super();
    }





    GetCompanyrolelist(callback,params){
        var that=this;
        var param={
            url: 'v1.Companyrole/getCompanyRoleList',
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

export {Companyrole};