
import {Base} from '../utils/base.js';

class Agent extends Base{
    constructor(){
        super();
    }

    getUserMoney(callback,params){
        var that=this;
        var param={
            url: 'v1.Sysinit/getUserMoney',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }

    agentInit(callback,params){
        var that=this;
        var param={
            url: 'v1.Agent/agentInit',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    myAgentTeam(callback,params){
        var that=this;
        var param={
            url: 'v1.Agent/myAgentTeam',
            type:'post',
            data:params,
            sCallback:function(data){
                data=data;
                callback && callback(data);
            }
        };
        this.request(param);
    }


    Checkagent(callback,params){
      var that=this;
      var param={
          url: 'v1.Agent/checkAgent',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }

  Agentfxrecord(callback,params){
    var that=this;
    var param={
        url: 'v1.Agent/agentFxrecord',
        type:'post',
        data:params,
        sCallback:function(data){
            data=data;
            callback && callback(data);
        }
    };
    this.request(param);
}


Getfxrecord(callback,params){
    var that=this;
    var param={
        url: 'v1.Agent/Getfxrecord',
        type:'post',
        data:params,
        sCallback:function(data){
            data=data;
            callback && callback(data);
        }
    };
    this.request(param);
}



    Saveagent(callback,params){
      var that=this;
      var param={
          url: 'v1.Agent/saveAgent',
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

export {Agent};