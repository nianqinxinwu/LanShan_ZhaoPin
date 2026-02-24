
import {Base} from '../utils/base.js';

class Company extends Base{
    constructor(){
        super();
    }

    cancleTask(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/cancleTask',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
        }
            
     upTask(callback,params){
            var that=this;
            var param={
                url: 'v1.Company/upTask',
                type:'post',
                data:params,
                sCallback:function(data){
                    data=data;
                    callback && callback(data);
                }
            };
            this.request(param);
            }


    delComment(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/delComment',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }
    
    getMyComment(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/getMyComment',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    mysendorder(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/mysendorder',
            type:'post',   
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    saveComment(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/saveComment',
            type:'post',   
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
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

    getCompanylist(callback,params){
        var that=this; 
        var param={
            url: 'v1.Company/getCompanylist',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getAgentCompanylist(callback,params){
        var that=this; 
        var param={
            url: 'v1.Company/getAgentCompanylist',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getSearchCompany(callback,params){
        var that=this; 
        var param={
            url: 'v1.Company/getSearchCompany',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    sendorderdetail(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/sendorderdetail',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    sendagentorderdetail(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/sendagentorderdetail',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }



    Savecompany(callback,params){
      var that=this;
      var param={
          url: 'v1.Company/saveCompany',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }

  autoLogin(callback, params) {
      var param = {
          url: 'v1.Company/autoCompanyLogin',
          type: 'post',
          data: params,
          sCallback: function(data) {
              callback && callback(data);
          }
      };
      this.request(param);
  }

  Login(callback,params){
    var that=this;
    var param={
        url: 'v1.Company/doLogin',
        type:'post',
        data:params,
        sCallback:function(data){
            data=data;
            callback && callback(data);
        }
    };
    this.request(param);
    }


    companycenter(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/companyCenter',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
        }


    companyjob(callback,params){
            var that=this;
            var param={
                url: 'v1.Company/companyJob',
                type:'post',
                data:params,
                sCallback:function(data){
                    data=data;
                    callback && callback(data);
                }
            };
            this.request(param);
            }

     companynote(callback,params){
                var that=this;
                var param={
                    url: 'v1.Company/companyNote',
                    type:'post',
                    data:params,
                    sCallback:function(data){
                        data=data;
                        callback && callback(data);
                    }
                };
                this.request(param);
                }


        Invitenote(callback,params){
            var that=this;
            var param={
                url: 'v1.Company/inviteNote',
                type:'post',
                data:params,
                sCallback:function(data){
                    data=data;
                    callback && callback(data);
                }
            };
            this.request(param);
            }


        cancleJob(callback,params){
            var that=this;
            var param={
                url: 'v1.Company/cancleJob',
                type:'post',
                data:params,
                sCallback:function(data){
                    data=data;
                    callback && callback(data);
                }
            };
            this.request(param);
            }
                
         upJob(callback,params){
                var that=this;
                var param={
                    url: 'v1.Company/upJob',
                    type:'post',
                    data:params,
                    sCallback:function(data){
                        data=data;
                        callback && callback(data);
                    }
                };
                this.request(param);
                }

        
         topJob(callback,params){
                    var that=this;
                    var param={
                        url: 'v1.Company/topJob',
                        type:'post',
                        data:params,
                        sCallback:function(data){
                            data=data;
                            callback && callback(data);
                        }
                    };
                    this.request(param);
                    }

         spreadJob(callback,params){
                    var param={
                        url: 'v1.Company/spreadJob',
                        type:'post',
                        data:params,
                        sCallback:function(data){
                            callback && callback(data);
                        }
                    };
                    this.request(param);
                    }


        doCompanyendtime(callback,params){
                    var param={
                        url: 'v1.Company/doCompanyendtime',
                        type:'post',
                        data:params,
                        sCallback:function(data){
                            callback && callback(data);
                        }
                    };
                    this.request(param);
                    }


        checkLogin(callback,params){
                        var that=this;
                        var ctoken = wx.getStorageSync('ctoken');

                        if (!ctoken) {
                            // 无ctoken，尝试自动登录
                            that.autoLogin(function(autoData) {
                                if (autoData && autoData.error == 0) {
                                    wx.setStorageSync('ctoken', autoData.ctoken);
                                    wx.setStorageSync('companyid', autoData.companyid);
                                    callback && callback({ error: 0, msg: '正常' });
                                } else {
                                    callback && callback({ error: 1, msg: 'Token异常' });
                                }
                            }, {});
                            return;
                        }

                        var param={
                            url: 'v1.Company/checkLogin',
                            type:'post',
                            data: params || { ctoken: ctoken },
                            sCallback:function(data){
                                if (data && data.error == 0) {
                                    callback && callback(data);
                                } else {
                                    // ctoken失效，尝试自动登录
                                    that.autoLogin(function(autoData) {
                                        if (autoData && autoData.error == 0) {
                                            wx.setStorageSync('ctoken', autoData.ctoken);
                                            wx.setStorageSync('companyid', autoData.companyid);
                                            callback && callback({ error: 0, msg: '正常' });
                                        } else {
                                            callback && callback(data);
                                        }
                                    }, {});
                                }
                            }
                        };
                        this.request(param);
                        }
    

/*
    checkLogin(callback){

        var that =this;

        if(!wx.getStorageSync('companyid'))
        {
          
            wx.navigateTo({
              url: '/pages/companylogin/index',
            })

            

        }else{
            callback && callback();
        }
    }
  */

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

    batchApplicant(callback,params){
        var param={
            url: 'v1.Company/batchApplicant',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getSigninList(callback,params){
        var param={
            url: 'v1.Company/getSigninList',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getSigninDetail(callback,params){
        var param={
            url: 'v1.Company/getSigninDetail',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

    batchSigninAction(callback,params){
        var param={
            url: 'v1.Company/batchSigninAction',
            type:'post',
            data:params,
            sCallback:function(data){
                callback && callback(data);
            }
        };
        this.request(param);
    }

};

export {Company};