import {Base} from '../utils/base.js';

class Usercard extends Base{
    constructor(){
        super();
    }

    getApplicantCard(callback, params){
        var param={
            url: 'v1.Usercard/getApplicantCard',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getPublisherCard(callback, params){
        var param={
            url: 'v1.Usercard/getPublisherCard',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    saveApplicantCard(callback, params){
        var param={
            url: 'v1.Usercard/saveApplicantCard',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    savePublisherCard(callback, params){
        var param={
            url: 'v1.Usercard/savePublisherCard',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    unlockPhone(callback, params){
        var param={
            url: 'v1.Usercard/unlockPhone',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

};

export {Usercard};
