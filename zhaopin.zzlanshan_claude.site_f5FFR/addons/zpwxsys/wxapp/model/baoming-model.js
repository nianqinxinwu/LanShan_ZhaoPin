
import {Base} from '../utils/base.js';

class Baoming extends Base{
    constructor(){
        super();
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



    Savebaoming(callback,params){
      var that=this;
      var param={
          url: 'v1.Baoming/saveBaoming',
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

export {Baoming};