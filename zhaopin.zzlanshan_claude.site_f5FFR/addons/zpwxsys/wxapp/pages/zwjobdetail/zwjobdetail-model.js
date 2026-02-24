import {Base} from '../../utils/base.js';

class ZwJobdetail extends Base{
    constructor(){
        super();
    }

    getZwJobDetailData(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/getZwJobdetail',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    sendJob(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/sendJob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    jobSave(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/jobSave',
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

export {ZwJobdetail};
