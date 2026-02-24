import {Base} from '../utils/base.js';

class Notice extends Base{
    constructor(){
        super();
    }


    
    GetNoticedetail(callback,params){
        var that=this;
        var param={
            url: 'v1.Notice/getNoticeDetail',
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

export {Notice};