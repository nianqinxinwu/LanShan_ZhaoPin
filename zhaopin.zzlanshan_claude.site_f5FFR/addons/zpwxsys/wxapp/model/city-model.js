import {Base} from '../utils/base.js';

class City extends Base{
    constructor(){
        super();
    }





    GetCityList(callback,params){
        var that=this;
        var param={
            url: 'v1.City/getCityList',
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

export {City};