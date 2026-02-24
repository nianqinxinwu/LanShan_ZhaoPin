
import {Base} from '../../utils/base.js';

class Note extends Base{
    constructor(){
        super();
    }

    getPubNoteInit(callback,params){
        var that=this;
        var param={
            url: 'pubnoteinit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }



    uploadimg(callback,params){
        var that=this;
    
        console.log(params);
        var param={
            url: 'uploadimg',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.requestuploadimg(param);
     }

     

    getPubEduInit(callback,params){
        var that=this;
        var param={
            url: 'pubeduinit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }
   
    getPubExpressInit(callback,params){
        var that=this;
        var param={
            url: 'pubexpressinit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    Savenote(callback,params){
        var that=this;
        var param={
            url: 'savenote',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    Saveedu(callback,params){
        var that=this;
        var param={
            url: 'saveedu',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    Saveexpress(callback,params){
        var that=this;
        var param={
            url: 'saveexpress',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    Savecontent(callback,params){
        var that=this;
        var param={
            url: 'savecontent',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    getMatchNoteListData(callback,params){
        var that=this;
        var param={
            url: 'matchnotelist',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    getNoteListData(callback,params){
        var that=this;
        var param={
            url: 'v1.Note/getNotelist',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getNoteListCount(callback,params){
        var that=this;
        var param={
            url: 'getNoteListCount',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    NoteRefresh(callback,params){
        var that=this;
        var param={
            url: 'noterefresh',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    getAgentNoteListData(callback,params){
        var that=this;
        var param={
            url: 'agentnotelist',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    

    getNoteDetailData(callback,params){
        var that=this;
        var param={
            url: 'notedetail',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    sendinvatejob(callback,params){
        var that =this;
        var param={
            url: 'sendinvatejob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        that.request(param);
    }




};

export {Note};