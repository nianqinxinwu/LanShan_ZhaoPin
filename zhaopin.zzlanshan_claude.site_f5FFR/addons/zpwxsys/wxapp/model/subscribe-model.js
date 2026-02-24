import {Base} from '../utils/base.js';

class Subscribe extends Base{
    constructor(){
        super();
    }


    saveSubscribe(callback,params){
      var that=this;


      var tmplId = params['tmplId'];

      var type = params['type'];
      wx.requestSubscribeMessage({
        tmplIds: [tmplId],
        success(res) {


    

          if (res[tmplId] == 'accept') {

            var params2 = {tmplId:tmplId,type:type}
            var param={
              url: 'saveSubscribe',
              type:'post',
              data:params2,
              sCallback:function(data){
                 
              }
          };
          that.request(param);
       
          }
    

          callback && callback();


        }
      })








  }

  };

  export {Subscribe};