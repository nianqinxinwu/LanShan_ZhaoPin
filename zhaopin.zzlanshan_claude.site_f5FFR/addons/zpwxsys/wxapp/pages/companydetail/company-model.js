
import {Base} from '../../utils/base.js';

class Company extends Base{
    constructor(){
        super();
    }

    getCompanyRegisterInit(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/getCompanyregisterinit',
            type:'post',   
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getCompanyDetailData(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/getCompanydetail',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getCompanyUserDetailData(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/getCompanyuserdetail',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    

    gzCompany(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/gzCompany',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    
    Updatecompany(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/updateCompany',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }
    
    myGzCompany(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/myGzCompany',
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
            url: 'v1.Company/uploadImg',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.requestuploadimg(param);
     }

};

export {Company};