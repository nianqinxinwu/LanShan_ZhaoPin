import { Company } from '../../model/company-model.js';

var company  = new Company();

var QQMapWX = require('../../utils/qqmap-wx-jssdk.min.js');
var qqmapsdk;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    logoimglist:[],
    imagelist:[],
    cardimglist:[],
    companyimglist:[],
    arealist:[],
    areaidindex:-1,
    areaid:0,
    lat:0,
    lng:0,
    companyworker: ['1-5人', '5-10人', '10-20人', '20-50人', '50人以上'],
    companyworkerindex: -1,
    companyworkername: '',
    companytype: ['私营', '国有', '政府机关', '事业单位', '股份制', '上市公司', '中外合资/合作', '外商独资/办事处','非盈利机构'],
    companytypeindex: -1,
    companytypename: '',
    isagree:0,
    show1:'none',
    show2:'none',
    show3:'none',

    categoryList: [
      { name: '餐饮服务' },
      { name: '活动执行' },
      { name: '安保检票' },
      { name: '教育辅导' },
      { name: '影视艺术' },
      { name: '临时促销' },
      { name: '电商推广' },
      { name: '问卷调查' },
      { name: '物流配送' },
      { name: '装卸搬运' },
      { name: '家政护理' },
      { name: '流水线工' },
      { name: '手工制作' },
      { name: '内容创作' },
      { name: '校园专属' }
    ],
    jobcateindex: -1,
  },


  toArticleDetail:function(e){

    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/articledetail/index?id=" + id
    })

  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;
    that.data.id =options.id;
    wx.setNavigationBarTitle({
      title: '企业注册' ,
    })
    var companyid = wx.getStorageSync('companyid');
    //初始化导航数据


    var cityinfo = wx.getStorageSync('cityinfo');

    console.log(cityinfo);
    if (cityinfo) {

      wx.setStorageSync('city', cityinfo.name);
      that.initpage();

    } else {

      //获取信息
      qqmapsdk = new QQMapWX({
        key: '5D3BZ-J55WF-SFPJJ-NI6PG-YN2ZO-M4BHX' // 必填
      });
      wx.getLocation({
        type: 'gcj02', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
        success: function (res) {

          qqmapsdk.reverseGeocoder({
            location: {
              latitude: res.latitude,
              longitude: res.longitude
            },
            success: function (addressRes) {




              var address = addressRes.result.address_component.city;

              var city = address.substr(0, address.length - 1);
            //  console.log(city);
              wx.setStorageSync('city', city);

              that.initpage();

            }
          })




        },
        fail: function () {
          // fail

          that.initpage();
        },
        complete: function () {
          // complete
        }
      })

    }      

  },

  initpage:function(){

    var that = this;
    var city ;
    city =  wx.getStorageSync('city');


    var params = {city:city};

    console.log(params);


    company.getCompanyRegisterInit((data) => {

      // If no city was set locally, use backend-provided cityinfo
      if (!city && data.cityinfo) {
        city = data.cityinfo.name;
        wx.setStorageSync('city', city);
        wx.setStorageSync('cityinfo', data.cityinfo);
      }

      that.setData({
        arealist:data.arealist,
        city:city

      })

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();



      },params);

  },


  bindJobcateChange: function (e) {
    var categoryList = this.data.categoryList;

    if (categoryList) {
      this.data.jobcateindex = e.detail.value;
      this.data.companycate = categoryList[e.detail.value].name;
    }
    this.setData({
      jobcateindex: e.detail.value
    })
  }
  ,
  bindAreaChange: function (e) {
    var arealist = this.data.arealist;

    if (arealist) {
      this.data.areaid = arealist[e.detail.value].id;
      this.data.areaidindex = e.detail.value;
    }
    this.setData({
      arealist: arealist,
      areaidindex: e.detail.value
    })
  }
  ,
  
  bindCompanytypeChange: function (e) {
    var companytype = this.data.companytype;

    if (companytype) {
      this.data.companytypeindex = e.detail.value;
      this.data.companytypename = companytype[e.detail.value];
    }
    this.setData({
      companytype: companytype,
      companytypeindex: e.detail.value
    })
  }
  ,
  getpostion:function(){
    var that = this;
    wx.chooseLocation({
      success: function (res) {
        that.data.lat = res.latitude;
        that.data.lng = res.longitude;
        that.setData({
          address: res.name
        })
      },
      fail: function (res) {
        // fail
        console.log(res);
      },
      complete: function () {
        // complete
      }
    })

  },
  bindCompanyworkerChange: function (e) {
    var companyworker = this.data.companyworker;

    if (companyworker) {
      this.data.companyworkerindex = e.detail.value;
      this.data.companyworkername = companyworker[e.detail.value];
    }
    this.setData({
      companyworker: companyworker,
      companyworkerindex: e.detail.value
    })
  }
  ,


  savepubinfo:function(e){

     var that = this;

     /*
   var form_id = e.detail.formId;
     var userinfo = wx.getStorageSync('userInfo');
 
     if(!userinfo)
     {
         that.data.isuser = false;
         that.setData({
           isuser: that.data.isuser
         })
         return
     }
     
 */






   if (that.data.isagree == 0) {
     wx.showModal({
       title: '提示',
       content: '请先同意企业入驻协议',
       showCancel: false,
       success: function (res) {
 
 
       }
     })
     return
 
   }


 
 
     var companyname = e.detail.value.companyname;
     var companycate = that.data.companycate;
     var companytype = that.data.companytypename;
     var companyworker = that.data.companyworkername;
     var mastername = e.detail.value.mastername;
     var tel = e.detail.value.tel;
     var address = e.detail.value.address;
     var account = e.detail.value.account;
     var password = e.detail.value.password;
     var password2 = e.detail.value.password2;
     var content = e.detail.value.content;
     var id = that.data.id;
     var areaid = that.data.areaid;
    
     if(areaid == 0 )
       {
       wx.showModal({
         title: '提示',
         content: '请选择区域',
         showCancel: false
       })
       return
 
       }
     if (companyname == "") {
       wx.showModal({
         title: '提示',
         content: '请输入企业名称',
         showCancel: false
       })
       return
     }
 
    
 
   
     if (mastername == "") {
     wx.showModal({
       title: '提示',
       content: '请输入负责人',
       showCancel: false
     })
     return
   }
 
   if (tel == "") {
     wx.showModal({
       title: '提示',
       content: '请填写手机号',
       showCancel: false
     })
     return
   } else {
 
 
     if (!(/^1(3|4|5|6|7|8|9)\d{9}$/.test(tel))) {
 
       wx.showModal({
         title: '提示',
         content: '手机号有误,请重新填写',
         showCancel: false
       })
       return
     }
   }
 
  
     if (account == "") {
       wx.showModal({
         title: '提示',
         content: '请输入登录账号',
         showCancel: false
       })
       return
     }else{
 
       var re = /^[a-zA-z]\w{3,15}$/;
       if (!re.test(account)) {
         wx.showModal({
           title: '提示',
           content: '登录账号以字母开头与数字组合',
           showCancel: false
         })
         return
       } 
 
 
     }
 
     if (password == "") {
       wx.showModal({
         title: '提示',
         content: '请输入登录密码',
         showCancel: false
       })
       return
     }else{
       var re = /^(\w){6,20}$/;
       if (!re.test(password))
       {
         wx.showModal({
           title: '提示',
           content: '登录密码以6-20个字母、数字、下划线 ',
           showCancel: false
         })
         return
 
       }
 
     }
     if (password2 == "") {
       wx.showModal({
         title: '提示',
         content: '请输入确认密码',
         showCancel: false
       })
       return
     }
     if (password != password2) {
       wx.showModal({
         title: '提示',
         content: '两次密码不一致',
         showCancel: false
       })
       return
     }
    
   
 
   var tid = 0;
   if (wx.getStorageSync('tid')) {
     tid = wx.getStorageSync('tid');
   }
   


   if(that.data.logoimglist.length ==0)
   {



     wx.showModal({
      title: '提示',
      content: '请上传企业LOGO',
      showCancel: false
    })
    return
   
   }

   if(that.data.cardimglist.length ==0)
   {



     wx.showModal({
      title: '提示',
      content: '请上传企业执照',
      showCancel: false
    })
    return
   
   }



   if(that.data.companyimglist.length ==0)
   {
     wx.showModal({
      title: '提示',
      content: '请上传企业证件或相关证件',
      showCancel: false
    })
    return
   
   }



    var cardimgstr = that.data.cardimglist.join(',');
    var companyimgstr = that.data.companyimglist.join(',');
    var logoimglist = that.data.logoimglist;
 
    var cityinfo = wx.getStorageSync('cityinfo');
     
     var params = {
       areaid:areaid,
       cityid: cityinfo.id,
                 companyname: companyname,
                 companycate: companycate, 
                 companytype: companytype,
                 companyworker: companyworker,
                 mastername: mastername,
                 tel: tel,
                 address: address,
                 account:account,
                 password:password,
                 content: content,
                 lat:that.data.lat,
                 lng:that.data.lng,
                 cardimg:cardimgstr,
                 companyimg:companyimgstr,
                 thumb: logoimglist[0],
                 tid:tid
                             };




         // console.log(params);

        //  return;

 
       company.Savecompany((data) => {

                 if(data.status ==  0 )

                 {
                      wx.showModal({
                        title: '提示',
                        content: '注册成功,请等待审核',
                        showCancel: false,
                        success:function(){

      
                          wx.reLaunch({
                            url: '/pages/companylogin/index',
                          })

                        }
                      }) 

                    }else{

                      wx.showModal({
                        title: '提示',
                        content: data.msg,
                        showCancel: false
                      }) 
                          
                    }


                              
                          },params);

                      
 
 
 
   },


   doagree: function (e) {
    var isagree = e.detail.value;



    if (isagree.length > 0) {
      this.data.isagree = isagree[0];
    } else {

      this.data.isagree = 0;
    }

    //


    console.log(this.data.isagree);

  },

  deleteImg1:function(e){
    var that = this;
    var index = e.currentTarget.dataset.index;

    that.data.logoimglist = [];

    that.setData({
      imgs1: [],
      show1: 'none'
    });


    console.log(index);

  },



  deleteImg2:function(e){

    var that = this;
    var index = e.currentTarget.dataset.index;

    var cardimglist = [];
    var imgs2 = [];





    for(var i = 0 ; i < that.data.cardimglist.length ; i++ )
    {

      if(i != index)
      {
        cardimglist.push(that.data.cardimglist[i]);
          imgs2.push(that.data.imgs2[i]);

      }

    }

    that.data.cardimglist = cardimglist;
    that.data.imgs2 = imgs2;
    that.setData({
      imgs2: imgs2,
     // show2: 'none'
    });

    if(that.data.cardimglist.length == 0)
    {

      that.setData({
        show2: 'none'
      });

    }



  },



  deleteImg3:function(e){

    var that = this;
    var index = e.currentTarget.dataset.index;

    var companyimglist = [];
    var imgs3 = [];





    for(var i = 0 ; i < that.data.companyimglist.length ; i++ )
    {

      if(i != index)
      {
        companyimglist.push(that.data.companyimglist[i]);
        imgs3.push(that.data.imgs3[i]);

      }

    }

    that.data.companyimglist = companyimglist;
    that.data.imgs3 = imgs3;
    that.setData({
      imgs3: imgs3,
     // show2: 'none'
    });

    if(that.data.companyimglist.length == 0)
    {

      that.setData({
        show3: 'none'
      });

    }



  },





  chooseImg: function (e) {
    var that = this;
   
    var count = 9;
    var id = parseInt(e.currentTarget.dataset.id);
    if(id ==1 )
    {
        count = 1;

    }


    wx.chooseImage({
      count: count, // 默认9
      sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        


        var tempFilePaths = res.tempFilePaths;

        var imgs = [];
  
         
        if(id == 1)
        {
          that.data.logoimglist = [];
          that.setData({
            imgs1: tempFilePaths,
            show1: 'block'
          });
        }else if(id == 2){
          that.data.cardimglist = [];
          that.data.imgs2 = tempFilePaths;
          that.setData({
            imgs2: tempFilePaths,
            show2: 'block'
          });
        }else if(id == 3){
          that.data.companyimglist = [];
          that.data.imgs3 = tempFilePaths;
          that.setData({
            imgs3: tempFilePaths,
            show3: 'block'
          });
        }
  
  
       // var tempFilePaths = that.data.imgs
  
        for (var s = 0; s < tempFilePaths.length; s++) {
  
          console.log(tempFilePaths[s]);
  
          that.uploadimg(tempFilePaths[s],id);
        }




      },
      fail: function (res) {
      },
      complete: function (res) {
      }
    });
  },

  uploadimg: function (path,id) {
    //var uploadurl = app.util.geturl({ 'url': 'entry/wxapp/upload' });
    // var id = id;
    wx.showToast({
      icon: "loading",
      title: "正在上传"
    });

    var that = this;

     var params ={

      path:path

     }
    company.uploadimg((data) => {

      console.log(data);

      if(id == 1)
      {

        console.log(data.imgpath);
        that.data.logoimglist.push(data.imgpath);
        console.log( that.data.logoimglist);

      }else if(id == 2){

        that.data.cardimglist.push(data.imgpath);

      }else if(id == 3){

        that.data.companyimglist.push(data.imgpath);

        
      }



                            
                        },params);






  },



  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

    var that = this;
    var cityinfo = wx.getStorageSync('cityinfo');
    var cityName = cityinfo ? cityinfo.name : '';

    // If city changed, refresh arealist and reset area selection
    if (cityName && cityName !== that.data.city) {
      wx.setStorageSync('city', cityName);
      that.setData({
        city: cityName,
        areaidindex: -1,
        areaid: 0
      });
      that.initpage();
    } else if (cityName) {
      that.setData({ city: cityName });
    }

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

 onPullDownRefresh: function(){
    wx.showNavigationBarLoading();
    this.initpage();
  },

  //分享效果
  onShareAppMessage: function () {
    var that = this;
      return {
          title:'企业注册' ,
          path: '/pages/companyregister/index'
      }
  }
})

