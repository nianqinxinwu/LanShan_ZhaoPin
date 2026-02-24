import {Base} from '../utils/base.js';

class Task extends Base{
    constructor(){
        super();
    }

    getPubTaskInit(callback,params){
        var that=this;
        var param={
            url: 'v1.Task/pubTaskInit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    Getmytasklist(callback,params){
        var that=this;
        var param={
            url: 'v1.Task/getMyTasklist',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    GetTaskList(callback,params){
        var that=this;
        var param={
            url: 'v1.Task/getTasklist',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    
    GetTaskdetail(callback,params){
        var that=this;
        var param={
            url: 'v1.Task/getTaskdetail',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    

 
    Savetask(callback,params){
        var that=this;
        var param={
            url: 'v1.Task/saveTask',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    Updatetask(callback,params){
        var that=this;
        var param={
            url: 'v1.Task/updateTask',
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

export {Task};