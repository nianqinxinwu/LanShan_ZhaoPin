import {Base} from '../utils/base.js';

class Taskrecord extends Base{
    constructor(){
        super();
    }

  

    GetTaskRecordList(callback,params){
        var that=this;
        var param={
            url: 'v1.Taskrecord/getTaskRecordList',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

 
    SaveTaskRecord(callback,params){
        var that=this;
        var param={
            url: 'v1.Taskrecord/saveTaskRecord',
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

export {Taskrecord};