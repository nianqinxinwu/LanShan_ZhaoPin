import {Base} from '../utils/base.js';

class Partjob extends Base{
    constructor(){
        super();
    }

    getPubJobInit(callback,params){
        var that=this;
        var param={
            url: 'v1.Jobpart/pubJobInit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    selectJobInit(callback,params){
        var that=this;
        var param={
            url: 'v1.Jobpart/selectJobInit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    getJobListCount(callback,params){
        var that=this;
        var param={
            url: 'v1.Jobpart/getJobListCount',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    getSearchJobListData(callback,params){
        var that=this;
        var param={
            url: 'v1.Jobpart/getSearchJobList',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    Savejob(callback,params){
        var that=this;
        var param={
            url: 'v1.Jobpart/saveJob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }
    
    Saveaskjob(callback,params){
        var that=this;
        var param={
            url: 'v1.Jobpart/saveAskjob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    Updatejob(callback,params){
        var that=this;
        var param={
            url: 'v1.Jobpart/updateJob',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


       
    getJobListData(callback,params){
      var that=this;
      var param={
          url: 'v1.Jobpart/getJoblist',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }
  getJobDetailData(callback,params){
      var that=this;
      var param={
          url: 'v1.Jobpart/getJobdetail',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }

  sendJob(callback,params){
      var that=this;
      var param={
          url: 'v1.Jobpart/sendJob',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }

  jobSave(callback,params){
      var that=this;
      var param={
          url: 'v1.Jobpart/jobSave',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }

  cancleSave(callback,params){
      var that=this;
      var param={
          url: 'v1.Jobpart/canleSave',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }

  myFindjob(callback,params){
      var that=this;
      var param={
          url: 'v1.Jobpart/myFindJob',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }

  mySavejob(callback,params){
      var that=this;
      var param={
          url: 'v1.Jobpart/mySaveJob',
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

export {Partjob};