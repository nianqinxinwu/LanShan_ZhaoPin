
import {Base} from '../../utils/base.js';

class Note extends Base{
    constructor(){
        super();
    }

    getPubNoteInit(callback,params){
        var that=this;
        var param={
            url: 'v1.Note/getPubnoteinit',
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
            url: 'v1.Note/uploadImg',
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
            url: 'v1.Note/getPubeduinit',
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
            url: 'v1.Note/getPubexpressinit',
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
            url: 'v1.Note/saveNote',
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
            url: 'v1.Note/saveEdu',
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
            url: 'v1.Note/saveExpress',
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
            url: 'v1.Note/saveContent',
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
            url: 'v1.Note/getMatchNotelist',
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
            url: 'v1.Note/getNoteListCount',
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
            url: 'v1.Note/noteRefresh',
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
            url: 'v1.Note/getAgentNotelist',
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
            url: 'v1.Note/getNotedetail',
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
            url: 'v1.Note/sendInvatejob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        that.request(param);
    }



    CheckNote(callback,params){
        var that=this;
        var param={
            url: 'v1.Note/checkNote',
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

export {Note};