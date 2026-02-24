import { Home2 } from 'index-model.js';
var home = new Home2(); //实例化 首页 对象
var QQMapWX = require('../../utils/qqmap-wx-jssdk.min.js');
var qqmapsdk;
import { User } from '../../model/user-model.js';

var user  = new User();

import { Job } from '../../model/job-model.js';
var job  = new Job();
import { Token } from '../../utils/token.js';

var token = new Token();

import { Company } from '../../model/company-model.js';
var company  = new Company();
Page({
    data: {
        autoplay: true,
        interval: 3000,
        duration: 1000,

        autoplay: true,
        interval2: 3500,
        duration2: 2000,
        //是否采用衔接滑动  
        circular: true,
        //是否显示画板指示点  
        indicatorDots: false,
        //选中点的颜色  
        indicatorcolor: "#000",
        //是否竖直  
        vertical: false,
        //是否自动切换  
        //滑动动画时长毫秒  
        //所有图片的高度  
        imgheights: [],
        //图片宽度  
        imgwidth: 750,
        //默认  
        current: 0 ,
        swiperCurrent: 0,
        searchKeyword: '',
        loadingHidden: false,
        title:'',
        ischeck:0,
        userRole: 0,
        categoryList: [
          { name: '餐饮服务', icon: '../../imgs/category/1餐厅服务.png' },
          { name: '活动执行', icon: '../../imgs/category/2活动执行.png' },
          { name: '安保检票', icon: '../../imgs/category/3安保检票.png' },
          { name: '教育辅导', icon: '../../imgs/category/4教育辅导.png' },
          { name: '影视艺术', icon: '../../imgs/category/5群众演员.png' },
          { name: '临时促销', icon: '../../imgs/category/6临时促销.png' },
          { name: '电商推广', icon: '../../imgs/category/7电商推广.png' },
          { name: '问卷调查', icon: '../../imgs/category/8问卷调查.png' },
          { name: '物流配送', icon: '../../imgs/category/9物流配送.png' },
          { name: '装卸搬运', icon: '../../imgs/category/10装卸搬运.png' },
          { name: '家政护理', icon: '../../imgs/category/11家政服务.png' },
          { name: '流水线工', icon: '../../imgs/category/12流水线工.png' },
          { name: '手工制作', icon: '../../imgs/category/13手工制作.png' },
          { name: '内容创作', icon: '../../imgs/category/14内容创作.png' },
          { name: '校园专属', icon: '../../imgs/category/15校园专属.png' }
        ]
    },
    imageLoad: function (e) {
      var imgwidth = e.detail.width,
      imgheight = e.detail.height,
      //宽高比  
      ratio = imgwidth / imgheight;
      var viewHeight = 750 / ratio;
      var imgheight = viewHeight
      var imgheights = this.data.imgheights
      //把每一张图片的高度记录到数组里  
      imgheights.push(imgheight)
      this.setData({
        imgheights: imgheights,
      })
    }, bindchange: function (e) {
      console.log(e.detail.current)
      this.setData({ current: e.detail.current })
    },

    swiperChange: function (e) {

      this.setData({
        swiperCurrent: e.detail.current   //获取当前轮播图片的下标
      })
    },


  toIndex:function(){

      wx.redirectTo({
        url: '/pages/index/index',
      })
  },


  toFindjob: function (e) {

    var that = this;

    wx.redirectTo({
      url: "/pages/findjob/index"
    })

  },

  toCateFindjob: function (e) {
    var name = e.currentTarget.dataset.name;
    wx.navigateTo({
      url: "/pages/findjob/index?keyword=" + name
    })
  },

  toApplyJob: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/zwjobdetail/index?id=" + id
    })
  },

  toMyinvate: function (e) {

    var that = this;
  /*
    wx.redirectTo({
      url: "/pages/myinvate/index"
    })
*/

wx.redirectTo({
  url: "/pages/switchrole/index"
})
  
  },


  toSysmsg:function(){

    wx.redirectTo({
      url: '/pages/sysmsg/index',
    })
},


  toMyuser:function(){

    wx.redirectTo({
      url: '/pages/user/index',
    })
},


    onShow:function(){

      var that = this;

      
      var cityinfo = wx.getStorageSync('cityinfo');

            if (cityinfo) {

              wx.setStorageSync('city', cityinfo.name);

              that._loadData();

            }else{

                //获取信息
                qqmapsdk = new QQMapWX({
                  key: '5D3BZ-J55WF-SFPJJ-NI6PG-YN2ZO-M4BHX' // 必填
                });
                wx.getLocation({
                  type: 'gcj02', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
                  success: function (res) {
                    wx.setStorageSync('latitude', res.latitude);
                    wx.setStorageSync('longitude', res.longitude);
                    qqmapsdk.reverseGeocoder({
                      location: {
                        latitude: res.latitude,
                        longitude: res.longitude
                      },
                      success: function (addressRes) {
  
  
  
  
                        var address = addressRes.result.address_component.city;
  
                        var city = address.substr(0, address.length - 1);

                        console.log(city);
  
                        wx.setStorageSync('city', city);
  
                        that._loadData();
  
                      }
                    })
  
  
  
  
                  },
                  fail: function () {
                    // fail
  
                    that._loadData();
                  },
                  complete: function () {
                    // complete
                  }
                })
  


            }

    },


    selectjobtype:function(e){
      var that = this;
      var worktype = e.currentTarget.dataset.id;
      that.data.worktype = worktype;
      that.setData({
        worktype:that.data.worktype
      })

      that.getjoblist();
      


    },


    getjoblist:function(){
      var that = this;
      var cityid = wx.getStorageSync('cityinfo').id;
      var params = { cityid: cityid,worktype:that.data.worktype};
   
      job.getJobIndexList((data) => {
   

       that.setData({
           joblist: data.joblist
       });
   },params);


    },

    toIndex:function(){

        wx.redirectTo({
          url: '/pages/index/index',
        })
    },


    onLoad: function (options) {
     
      var that = this;
     // wx.setStorageSync('rectid',10);
      if(options)
      {
          if (options.hasOwnProperty("scene"))
          {
          var scene = decodeURIComponent(options.scene);
          var uid_array = scene.split('=');
          if(uid_array[0] == 'rectid')
          {
            var rectid = parseInt(uid_array[1]);
            wx.setStorageSync('rectid',rectid);

          }else{

          var uid = parseInt(uid_array[1]);
          wx.setStorageSync('tid',uid);
          }
          }
      }


      wx.showShareMenu({
        withShareTicket: true,
        menus: ['shareAppMessage', 'shareTimeline']
      })
    //  var cityinfo =[];
      //cityinfo.name = '盐城';
      //wx.setStorageSync('cityinfo',cityinfo);



       



    },


    rectbinduser:function()
    {
      
        if( wx.getStorageSync('rectid')>0)
        {

            var params = {rectid:wx.getStorageSync('rectid')};


            home.rectBinduser((data) => {

              
          },params);





        }

    },


    fxbinduser:function()
    {
      
        if( wx.getStorageSync('tid')>0)
        {


            var params = { tid:wx.getStorageSync('tid')};

              home.fxBinduser((data) => {
                        
          },params);

        }

    },

    /*加载所有数据*/
    _loadData:function(callback){
        var that = this;
        var city ;
       city =  wx.getStorageSync('city');
        home.getSysinit(city,(data) => {

        if(data.sysinfo)
        {
          wx.setNavigationBarTitle({
            title: data.sysinfo.name,
          })

          that.data.title = data.sysinfo.name;
          that.data.ischeck = data.sysinfo.ischeck;
          wx.setStorageSync('checkFlag', data.sysinfo.ischeck);

        }
        
          var currentCityinfo = wx.getStorageSync('cityinfo');
          if (!currentCityinfo || currentCityinfo.id != 0) {
            wx.setStorageSync('cityinfo', data.cityinfo);
          }

          var worktypelist = data.worktyelist;

          if(worktypelist.length>0)
                that.data.worktype = worktypelist[0]['id'];


          that.setData({
            ischeck:that.data.ischeck,
            userRole: parseInt(wx.getStorageSync('user_role') || 0),
            oldhouselist:data.oldhouselist,
              worktype:that.data.worktype,
              city:wx.getStorageSync('cityinfo').name,
              bannerlist: data.bannerlist,
              navlist:data.navlist,
              companylist:data.companylist,
              worktyelist:data.worktyelist,
              sysinfo:data.sysinfo,
              noticelist:data.noticelist
          });


          that.getjoblist();

     

          if(wx.getStorageSync('tid')>0)
          {

            setTimeout(()=> {

              that.fxbinduser();

            }, 2000)

         }

          if(wx.getStorageSync('rectid')>0)
          {

            that.rectbinduser();
          }
          

          wx.hideNavigationBarLoading(); //完成停止加载
          wx.stopPullDownRefresh();
      });

    
    },


    toNagivate: function (e) {
      var url = e.currentTarget.dataset.id;
      console.log(url);
      wx.navigateTo({
        url: url
      })
    },
  
    toSwitchtab: function (e) {
      var url = e.currentTarget.dataset.id;
      wx.switchTab({
        url: url
      })
    },
    toWxapp: function (e) {
      var url = e.detail.value.innerurl;
      var appid = e.detail.value.appid;
      console.log(url);
      console.log(appid);
      wx.navigateToMiniProgram({
        appId: appid,
        path: url,
        extraData: {
          foo: 'bar'
        },
        envVersion: 'develop',
        success(res) {
          // 打开成功
        }
      })
    },

    toInnerUrl: function (e) {


      var url = e.detail.value.innerurl;
      wx.navigateTo({
        url: url
      })
  
    },

    toPicInnerUrl: function (e) {


        var url = e.currentTarget.dataset.id;
        wx.navigateTo({
          url: url
        })
    
      },

    toWebview: function (e) {


      var id = e.detail.value.id;
      var url = "/pages/webview/index?id="+id;
      wx.navigateTo({
        url: url
      })
  
    },
  
    toMenuUrl: function (e) {
  
      var url = e.detail.value.innerurl;
      wx.switchTab({
        url: url
      })
  
    },

    toCompanylist:function(){

      var that = this;
    
      wx.navigateTo({
        url: "/pages/companylist/index"
      })

    },
    toSelectJob:function(){

      var that = this;

      wx.navigateTo({
        url: "/pages/selectjob/index"
      })

    },

    onSearchInput: function (e) {
      this.setData({ searchKeyword: e.detail.value });
    },

    doSearch: function () {
      var keyword = this.data.searchKeyword.trim();
      if (keyword) {
        wx.navigateTo({
          url: "/pages/findjob/index?keyword=" + keyword
        })
      } else {
        wx.redirectTo({
          url: "/pages/findjob/index"
        })
      }
    },

    toPerLogin:function(){

      var that = this;
  
        wx.navigateTo({
          url: "/pages/login/index"
        })

  
    },

    toPerRegister:function(){

      var that = this;
  
        wx.navigateTo({
          url: "/pages/register/index"
        })

  
    },

    toTaskjob:function(){




      var that = this;

      var params = {};

      token.verify(

      user.checkBind((data) => {

         if(data.isbind)
         {

          wx.navigateTo({
            url: "/pages/mynote/index"
          })
       

      }else{

        wx.navigateTo({
          url: "/pages/register/index"
        })

      }

         
  
          },params)

      )
  


      /*
  
        wx.navigateTo({
          url: "/pages/taskjob/index"
        })
          */
  
    },


    toJobDetail:function (e) {
      var id = e.currentTarget.dataset.id;

      wx.navigateTo({
       url: "/pages/zwjobdetail/index?id=" + id
      })
  
    }
    ,
    toFindworker: function (e) {


      var that = this;
      wx.switchTab({
        url: "/pages/findworker/index"
      })
  
    }
    ,
  
    toCompanydetial: function (e) {
      var id = e.currentTarget.dataset.id;
      wx.navigateTo({
        url: "/pages/companydetail/index?id=" + id
      })
    },
    

    toLogin: function (e) {


      var that = this;

      var params = {};

      token.verify(

      user.checkBind((data) => {

         if(data.isbind)
         {
          var ctoken = wx.getStorageSync('ctoken');

          if(ctoken)
          {
            var params = {ctoken:ctoken};

            company.checkLogin((data) => {

              if(data.error ==0 )
              {
                wx.navigateTo({
                  url: "/pages/companycenter/index"
                })

              }else{
                wx.navigateTo({
                  url: "/pages/companylogin/index"
                })

              }
        
                
            },params);




          }else{

               wx.navigateTo({
                  url: "/pages/companylogin/index"
                })

          }
          

        /*
        var companyid = wx.getStorageSync('companyid');
        if (companyid > 0) {
          wx.navigateTo({
            url: "/pages/companycenter/index"
          })



        } else {
  
          wx.navigateTo({
            url: "/pages/companylogin/index"
          })
        }

        */

      }else{

        wx.navigateTo({
          url: "/pages/register/index"
        })

      }

         
  
          },params)
  
      )
  
  

  
    },

    toArticle: function (e) {

        var that = this;

    
        wx.navigateTo({
          url: "/pages/article/index"
        })

      
    /*
     
    */
    }
    ,
    toLiveOn:function(e){
      var id = e.detail.value.innerurl;
      let roomId = [id] ;
      let customParams = encodeURIComponent(JSON.stringify({ path: 'pages/index/index', pid: 1 })) 
      wx.navigateTo({
          url: `plugin-private://APPID/pages/live-player-plugin?room_id=${roomId}&custom_params=${customParams}`
      })
    },

    toActive: function (e) {

      var that = this;
     
  
        wx.navigateTo({
          url: "/pages/active/index"
        })
  
      
  
  
    },

    toArticleDetail:function(e){

      var id = e.currentTarget.dataset.id;
      wx.navigateTo({
        url: "/pages/articledetail/index?id=" + id
      })
  
    },

    /*下拉刷新页面*/
    onPullDownRefresh: function(){
      wx.showNavigationBarLoading();
      this.onShow();
    },

    //分享效果
    onShareAppMessage: function () {
      var that = this;
        return {
            title:that.data.title ,
            path: '/pages/index/index'
        }
    }

})


