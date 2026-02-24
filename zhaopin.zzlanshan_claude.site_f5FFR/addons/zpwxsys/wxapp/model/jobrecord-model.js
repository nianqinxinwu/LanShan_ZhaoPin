import {Base} from '../utils/base.js';

class Jobrecord extends Base{
    constructor(){
        super();
    }


  //面试成功|失败
  doInvatePass(callback,params){
    var that=this;
    var param={
        url: 'v1.Company/doInvatePass',
        type:'post',
        data:params,
        sCallback:function(data){
            data=data;
            callback && callback(data);
        }
    };
    this.request(param);
}



  //试用成功|失败
  doInvateTypein(callback,params){
    var that=this;
    var param={
        url: 'v1.Company/doInvateTypein',
        type:'post',
        data:params,
        sCallback:function(data){
            data=data;
            callback && callback(data);
        }
    };
    this.request(param);
}


  //试用成功|失败
  doInvateTry(callback,params){
    var that=this;
    var param={
        url: 'v1.Company/doInvateTry',
        type:'post',
        data:params,
        sCallback:function(data){
            data=data;
            callback && callback(data);
        }
    };
    this.request(param);
}


  //订单完成|失败
  doInvateDone(callback,params){
    var that=this;
    var param={
        url: 'v1.Company/doInvateDone',
        type:'post',
        data:params,
        sCallback:function(data){
            data=data;
            callback && callback(data);
        }
    };
    this.request(param);
}

//同意|拒绝面试
    doAgree(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/doAgree',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    //面试成功|失败
    doPass(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/doPass',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }



      //试用成功|失败
      doTypein(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/doTypein',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


      //试用成功|失败
      doTry(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/doTry',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


      //订单完成|失败
      doDone(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/doDone',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    doInivateNote(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/doInivateNote',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    doWork(callback,params){
        var that=this;
        var param={
            url: 'v1.Company/doWork',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    doUseWork(callback,params){
      var that=this;
      var param={
          url: 'v1.Company/doUseWork',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }




doDoneorder(callback,params){
    var that=this;
    var param={
        url: 'v1.Company/doDoneorder',
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

export {Jobrecord};