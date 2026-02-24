//app.js
import { Token } from '/utils/token.js';
App({
  onLaunch: function () {
      var token = new Token();
      token.islogin = 0 ;
      

      token.verify();
  },

  onShow:function(){
  
	},
	
	

	onHide: function () {
	//	console.log(getCurrentPages())
	},
	onError: function (msg) {
		//console.log(msg)
	},

	tabBar: {
		"color": "#123",
		"selectedColor": "#1ba9ba",
		"borderStyle": "#1ba9ba",
		"backgroundColor": "#fff",
		"list": [
			
		]
	},
 
	globalData: {
		userInfo: null,
    isuser:false
  }

})