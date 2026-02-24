import {Base} from '../utils/base.js';

class News extends Base{
    constructor(){
        super();
    }





    GetNewsList(callback,params){
        var that=this;
        var param={
            url: 'v1.News/getNewslist',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    GetNewsListByCateId(callback,params){
        var that=this;
        var param={
            url: 'v1.News/getnewslistByCateid',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    
    GetNewsdetail(callback,params){
        var that=this;
        var param={
            url: 'v1.News/getNewsdetail',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    getFxRule(callback,params){
        var that=this;
        var param={
            url: 'v1.News/getFxRule',
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

export {News};