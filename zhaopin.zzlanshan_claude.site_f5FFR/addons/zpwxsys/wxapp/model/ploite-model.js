import {Base} from '../utils/base.js';

class Ploite extends Base{
    constructor(){
        super();
    }

    savePloite(callback,params){
      var that=this;
      var param={
          url: 'v1.Ploite/savePloite',
          type:'post',
          data:params,
          sCallback:function(data){
              data=data;
              callback && callback(data);
          }
      };
      this.request(param);
  }

  }

  export {Ploite};