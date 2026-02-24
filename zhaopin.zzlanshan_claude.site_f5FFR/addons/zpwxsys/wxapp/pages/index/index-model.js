import {Base} from '../../utils/base.js';

class Home2 extends Base{
    constructor(){
        super();
    }

    getSysinit(params, callback){
        var that=this;
        var param={
            url: 'v1.Sysinit/getSysinit',
             type:'post',
             data:{city:params},
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);

    }

    fxBinduser(callback,params){
        var that=this;
        var param={
            url: 'v1.Fxsys/fxBinduser',
             type:'post',
             data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    
    }

rectBinduser(callback,params){
    var that=this;
    var param={
        url: 'v1.Fxsys/rectBinduser',
         type:'post',
         data:params,
        sCallback:function(data){
            data=data;
            callback && callback(data);
        }
    };
    this.request(param);

}

    
/*

    getBannerData(callback){
        var that=this;
        var param={
            url: 'banner/1',

            sCallback:function(data){
                data=data.items;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getThemeData(callback){
        var param={
            url: 'theme?ids=1,2,3',
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }


    getProductorData(callback){
        var param={
            url: 'product/recent',
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

*/

};

export {Home2};