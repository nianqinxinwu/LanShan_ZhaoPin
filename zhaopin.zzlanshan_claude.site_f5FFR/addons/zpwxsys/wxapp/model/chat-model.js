
import {Base} from '../utils/base.js';

class Chat extends Base{
    constructor(){
        super();
    }

    getRule(callback, params){
        var param = {
            url: 'v1.Chat/getRule',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    agreeRule(callback, params){
        var param = {
            url: 'v1.Chat/agreeRule',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    createGroup(callback, params){
        var param = {
            url: 'v1.Chat/createGroup',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    chatList(callback, params){
        var param = {
            url: 'v1.Chat/chatList',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    groupDetail(callback, params){
        var param = {
            url: 'v1.Chat/groupDetail',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    messages(callback, params){
        var param = {
            url: 'v1.Chat/messages',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    send(callback, params){
        var param = {
            url: 'v1.Chat/send',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    editNotice(callback, params){
        var param = {
            url: 'v1.Chat/editNotice',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    muteMember(callback, params){
        var param = {
            url: 'v1.Chat/muteMember',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    kickMember(callback, params){
        var param = {
            url: 'v1.Chat/kickMember',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    addMember(callback, params){
        var param = {
            url: 'v1.Chat/addMember',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    memberList(callback, params){
        var param = {
            url: 'v1.Chat/memberList',
            type: 'post',
            data: params,
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    uploadImage(callback, filePath){
        var param = {
            url: 'v1.Chat/uploadImage',
            data: { path: filePath },
            sCallback: function(data){
                callback && callback(data);
            }
        };
        this.requestuploadimg(param);
    }
}

export { Chat };
