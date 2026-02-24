
import {Base} from '../../utils/base.js';

class Company extends Base{
    constructor(){
        super();
    }

    getCompanyRegisterInit(callback,params){
        var that=this;
        var param={
            url: 'companyregisterinit',
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
            url: 'companydetail',
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
            url: 'gzcompany',
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
            url: 'updatecompany',
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
            url: 'mygzcompany',
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

};

export {Company};