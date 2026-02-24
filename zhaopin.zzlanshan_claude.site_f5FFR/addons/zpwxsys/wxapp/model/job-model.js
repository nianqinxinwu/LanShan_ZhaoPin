import {Base} from '../utils/base.js';

class Job extends Base{
    constructor(){
        super();
    }

    getNavDetail(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/getNavDetail',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getPubJobInit(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/pubJobInit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    selectJobInit(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/selectJobInit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getJobIndexList(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/getJobIndexList',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getmatchjoblist(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/getmatchjoblist',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getJobListCount(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/getJobListCount',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    getSearchJobListData(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/getSearchJobList',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    Savejob(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/saveJob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }
    
    Saveaskjob(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/saveAskjob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    Updatejob(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/updateJob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    ZwSavejob(callback,params){
        var that=this;
        var param={
            url: 'v1.Job/zwSaveJob',
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

export {Job};