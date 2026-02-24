
import {Base} from '../../utils/base.js';

class Findjob extends Base{
    constructor(){
        super();
    }



    sendCompanyJob(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/sendCompanyJob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    /*banner图片信息*/
    getJobListData(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/getJoblist',
            type:'post',
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
            url: 'v1.Job/getJobdetail',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getqrcodejob(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/getqrcodejob',
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

    cancleSave(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/cancleSave',
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
            url: 'v1.Job/myFindJob',
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
            url: 'v1.Job/mySaveJob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    confirmWork(callback,params){
        var param={
            url: 'v1.Job/confirmWork',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    doSignIn(callback,params){
        var param={
            url: 'v1.Job/doSignIn',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    doSignOut(callback,params){
        var param={
            url: 'v1.Job/doSignOut',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

};

export {Findjob};