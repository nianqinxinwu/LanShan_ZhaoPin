import { Company } from '../companydetail/company-model.js';

var company  = new Company();



var QQMapWX = require('../../utils/qqmap-wx-jssdk.min.js');
var qqmapsdk;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    formats: {},
    readOnly: false,
    placeholder: '开始输入...',
    editorHeight: 300,
    keyboardHeight: 0,
    isIOS: false,


    logoimglist:[],
    imagelist:[],
    cardimglist:[],
    imgs1:[],
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
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;
    that.data.id =options.id;
    wx.setNavigationBarTitle({
      title: '编辑企业信息' ,
    })


    const platform = wx.getSystemInfoSync().platform;
    const isIOS = platform === 'ios';
    this.setData({ isIOS });
    this.updatePosition(0);
    let keyboardHeight = 0;

    wx.onKeyboardHeightChange(res => {
      if (res.height === keyboardHeight) return
      const duration = res.height > 0 ? res.duration * 1000 : 0
      keyboardHeight = res.height
      setTimeout(() => {
        wx.pageScrollTo({
          scrollTop: 0,
          success() {
            that.updatePosition(keyboardHeight)
            that.editorCtx.scrollIntoView()
          }
        })
      }, duration)

    });





    //初始化导航数据


    var cityinfo = wx.getStorageSync('cityinfo');
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
    var ctoken = wx.getStorageSync('ctoken');
    var params = {city:city,ctoken:ctoken};

    
    company.getCompanyDetailData((data) => {
 
      that.data.companytypeindex = isHasElementOne(that.data.companytype, data.companyinfo.companytype);

      that.data.companytypename = data.companyinfo.companytype;
    
      that.data.companyworkerindex = isHasElementOne(that.data.companyworker, data.companyinfo.companyworker);

      that.data.companyworkername = data.companyinfo.companyworker;

      that.data.areaidindex = isHasElementTwo(data.arealist, data.companyinfo.areaid);
      console.log(that.data.areaidindex);
      that.data.areaid = data.companyinfo.areaid;
      
      const query = wx.createSelectorQuery();

      query.select('#editor')
  
        .context(function (res) {
  
          res.context.setContents({
  
            html: data.companyinfo.content
  
          })
          that.editorCtx = res.context
          wx.pageScrollTo({
            scrollTop: 0,
            success: () => {
              that.editorCtx.scrollIntoView()
            }
          })
  
        }).exec();


      if(data.companyinfo.thumb!="")
      {
       that.data.logoimglist.push(data.companyinfo.thumb);
       that.data.imgs1.push(data.companyinfo.thumb);
       that.data.show1 = 'block';
      }

 
 
      that.data.cardimglist = data.companyinfo.cardimg;
      that.data.companyimglist = data.companyinfo.companyimg;
    
      that.data.imgs2  = that.data.cardimglist ;
      that.data.show2 = 'block';
      that.data.imgs3  = that.data.companyimglist ;
      that.data.show3 = 'block';


     that.setData({
      companyinfo:data.companyinfo,
      arealist:data.arealist,
      address:data.companyinfo.address,
      companytypeindex:that.data.companytypeindex,
      companyworkerindex:that.data.companyworkerindex,
      areaidindex:that.data.areaidindex,
      imgs1:that.data.imgs1,
      show1:that.data.show1,
      imgs2:that.data.imgs2,
      show2:that.data.show2,
      imgs3:that.data.imgs3,
      show3:that.data.show3,

     });
 },params);

  },

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


  updatePosition(keyboardHeight) {
    const toolbarHeight = 50
    const { windowHeight, platform } = wx.getSystemInfoSync()
    let editorHeight = keyboardHeight > 0 ? (windowHeight - keyboardHeight - toolbarHeight) : windowHeight
    this.setData({ editorHeight, keyboardHeight })
  },
  calNavigationBarAndStatusBar() {
    const systemInfo = wx.getSystemInfoSync()
    const { statusBarHeight, platform } = systemInfo
    const isIOS = platform === 'ios'
    const navigationBarHeight = isIOS ? 44 : 48
    return statusBarHeight + navigationBarHeight
  },
  onEditorReady() {
    const that = this;
    console.log('fffffff');
    wx.createSelectorQuery().select('#editor').context(function (res) {
      that.editorCtx = res.context
    }).exec()
  },
  blur() {
    this.editorCtx.blur()
  },
  format(e) {
    let { name, value } = e.target.dataset
    if (!name) return
    // console.log('format', name, value)
    this.editorCtx.format(name, value)

  },
  onStatusChange(e) {
    const formats = e.detail

    console.log(formats);
    this.setData({ formats })
  },
  insertDivider() {
    this.editorCtx.insertDivider({
      success: function () {
        console.log('insert divider success')
      }
    })
  },
  clear() {
    this.editorCtx.clear({
      success: function (res) {
        console.log("clear success")
      }
    })
  },
  removeFormat() {
    this.editorCtx.removeFormat()
  },
  insertDate() {
    const date = new Date()
    const formatDate = `${date.getFullYear()}/${date.getMonth() + 1}/${date.getDate()}`
    this.editorCtx.insertText({
      text: formatDate
    })
  },
  insertImage() {
    const that = this
    wx.chooseImage({
      count: 1,
      success: function (res) {
        that.editorCtx.insertImage({
          src: res.tempFilePaths[0],
          data: {
            id: 'abcd',
            role: 'god'
          },
          width: '80%',
          success: function () {
            console.log('insert image success')
          }
        })
      }
    })
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




  savepubinfo:function(e){

     var that = this;

     var content = '';


    
     that.editorCtx.getContents({
 
       success: (res) => {
 
         console.log(res.html);
 
         content = res.html;
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
     var companycate = e.detail.value.companycate;
     var companytype = that.data.companytypename;
     var companyworker = that.data.companyworkername;
     var mastername = e.detail.value.mastername;
     var tel = e.detail.value.tel;
     var address = e.detail.value.address;


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
 
  
    
   if (content == "") {
    wx.showModal({
      title: '提示',
      content: '请输入个人介绍',
      showCancel: false
    })
    return
  }
 
   var tid = 0;
   if (wx.getStorageSync('tid')) {
     tid = wx.getStorageSync('tid');
   }
   


    var cardimgstr = that.data.cardimglist.join(',');
    var companyimgstr = that.data.companyimglist.join(',');
    var logoimglist = that.data.logoimglist;


    
 
    var cityinfo = wx.getStorageSync('cityinfo');

    var ctoken = wx.getStorageSync('ctoken');
     
     var params = {
      ctoken:ctoken,
       areaid:areaid,
       cityid: cityinfo.id,
                 companyname: companyname,
                 companycate: companycate, 
                 companytype: companytype,
                 companyworker: companyworker,
                 mastername: mastername,
                 tel: tel,
                 address: address,
                 content: content,
                 lat:that.data.lat,
                 lng:that.data.lng,
                 cardimg:cardimgstr,
                 companyimg:companyimgstr,
                 thumb: logoimglist[0],
                 tid:tid
                             };

 
       company.Updatecompany((data) => {

                      wx.showToast({
                        title: '编辑成功',
                        icon: 'none',
                        duration: 2000,
                        success:function(){

                        wx.navigateBack({
                          delta: 1,
                        })

                        }
                      })
                              
                          },params);
 
                        }})
 
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

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})


function isHasElementOne(arr, value) {
  for (var i = 0, vlen = arr.length; i < vlen; i++) {
    if (arr[i] == value) {
      return i;
    }
  } 
  return -1;
} 

function isHasElementTwo(arr, value) {
  for (var i = 0, vlen = arr.length; i < vlen; i++) {
    if (arr[i]['id'] == value) {
      return i;
    }
  }
  return -1;
} 