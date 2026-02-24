
import {Base} from '../../utils/base.js';

class Findjob extends Base{
    constructor(){
        super();
    }

    /*banner图片信息*/
    getJobListData(callback,params){
        var that=this;
        var param={
            url: 'joblist',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }
    getJobDetailData(callback,params){
        var that=this;
        var param={
            url: 'jobdetail',
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
            url: 'sendjob',
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
            url: 'jobsave',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    myFindjob(callback,params){
        var that=this;
        var param={
            url: 'myfindjob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    mySavejob(callback,params){
        var that=this;
        var param={
            url: 'mysavejob',
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

export {Findjob};