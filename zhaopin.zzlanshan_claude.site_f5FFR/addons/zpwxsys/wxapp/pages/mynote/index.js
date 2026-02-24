import { Note } from '../findworker/note-model.js';

var note  = new Note();

Page({

  /**
   * 页面的初始数据
   */
  data: {

    /*
    formats: {},
    readOnly: false,
    placeholder: '开始输入...',
    editorHeight: 300,
    keyboardHeight: 0,
    isIOS: false,
*/
    speciallist: [{ name: '五险一金', checked: false },
    { name: '补充医疗保险', checked: false },
    { name: '员工旅游', checked: false },
    { name: '交通补贴', checked: false },
    { name: '餐饮补贴', checked: false },
    { name: '出国机会', checked: false },
    { name: '年终奖金', checked: false },
    { name: '定期体检', checked: false }],
    birthday: ['1960', '1961', '1962', '1963', '1964', '1965', '1966', '1967', '1968', '1969','1970', '1971', '1972', '1973', '1974', '1975', '1976', '1977', '1978', '1979', '1980', '1981', '1982', '1983', '1984', '1985', '1986', '1987', '1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998', '1999', '2000','2001','2002','2003','2004'],
    birthdayindex: -1,
    birthdayname: '',
    education: ['初中以下','初中', '高中', '中技', '中专', '大专', '本科', '硕士', '博士', '博后'],
    educationindex: -1,
    educationname: '',
    express: ['无经验', '1年以下', '1-3年', '3-5年', '5-10年', '10年以上'],
    expressindex: -1,
    expressname: '',
    currentstatus: [],
    currentstatusindex: -1,
    currentid: '',
    worktype: ['全职', '兼职', '实习'],
    worktypeindex: -1,
    worktypename: '',
    worknature: [{id:1,name:'全职'},{id:2,name:'兼职'},{id:3,name:'零工'},{id:4,name:'实习'}],
    worknatureIndex: -1,
    industryIndex: -1,
    money: [ '1千~2千/月', '2千~3千/月', '3千~4千/月', '4千~5千/月', '5千~1万/月', '1万以上/月'],
    moneyindex: -1,
    moneyname: '',
    arealist: [],
    areaindexid: -1,
    areaid: 0,
    imgurl1:false,
    true1:true,
    show1:'none',
    logoimglist:[],
    imgs1:[],

    worktypelist: [],
    worktypeindexid: -1,
    worktypeid: 0,
    ishidden:1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
    wx.setNavigationBarTitle({
      title: '我的简历',
    })
   
    var that = this;

     
/*
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
*/





    var cityinfo = wx.getStorageSync('cityinfo');
          if (cityinfo) {

            wx.setStorageSync('city', cityinfo.name);

            that.initpage();

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

  bindJobpriceChange: function (e) {
    var jobprice = this.data.jobprice;
console.log(jobprice);
    if (jobprice) {
      this.data.jobpriceindex = e.detail.value;
      this.data.jobpriceid = jobprice[e.detail.value].id;
      this.data.money = jobprice[e.detail.value].name;
    }

    console.log(this.data.money);
    this.setData({
      jobprice: jobprice,
      jobpriceindex: e.detail.value
    })
  }
  ,

  initpage:function(){

    var that = this;
    var city ;
    city =  wx.getStorageSync('city');
    var params = {city:city};

    note.getPubNoteInit((data) => {

      console.log(data);
      that.data.arealist = data.arealist;

      that.data.worktypelist = data.worktypelist;
      that.data.currentstatus = data.currentlist;


      that.data.jobprice = data.jobpricelist;
      that.setData({
        jobprice:that.data.jobprice,
        worktypelist:that.data.worktypelist,
        currentstatus:that.data.currentstatus
      }); 



      var noteinfo = data.noteinfo;
      if(noteinfo)
      {

          console.log(data.worktypelist);

   
        that.data.worktypeindexid = isHasElementTwo(data.worktypelist, noteinfo.worktypeid);

        that.data.worktypeid = noteinfo.worktypeid;


        that.data.birthdayindex = isHasElementOne(that.data.birthday, noteinfo.birthday);
        that.data.birthdayname = noteinfo.birthday;

        that.data.industryIndex = isHasElementTwo(data.worktypelist, noteinfo.jobcateid);
        that.data.jobcateid = noteinfo.jobcateid;


        that.data.jobpriceindex = isHasElementTwo(that.data.jobprice, noteinfo.jobpriceid);
      
        that.data.jobpriceid = data.jobpriceid;

         that.data.money = noteinfo.money;





        that.data.areaindexid = isHasElementTwo(data.arealist, noteinfo.areaid);

        that.data.areaid = noteinfo.areaid;

        that.data.educationindex = isHasElementOne(that.data.education, noteinfo.education);
        that.data.educationname = noteinfo.education;
        that.data.expressindex = isHasElementOne(that.data.express, noteinfo.express);
        that.data.expressname = noteinfo.express;

        that.data.areaindexid = isHasElementTwo(data.arealist, noteinfo.areaid);

        that.data.areaid = noteinfo.areaid;



        that.data.currentstatusindex = isHasElementTwo(data.currentlist, noteinfo.currentid);

        that.data.currentid = noteinfo.currentid;



       // that.data.moneyindex = isHasElementOne(that.data.money, noteinfo.money);
       // that.data.moneyname = noteinfo.money;

        that.data.worktypeindex = isHasElementOne(that.data.worktype, noteinfo.worktype);
        that.data.worktypename = noteinfo.worktype;

        that.data.worknatureIndex = isHasElementTwo(that.data.worknature, noteinfo.worktypeid);

        that.data.show1 = false;

        that.data.logoimglist.push(noteinfo.avatarUrl);

        that.data.imgs1.push(noteinfo.avatarUrl);

        console.log(noteinfo.avatarUrl);

        console.log(that.data.worktypeindexid);
        that.setData({
          birthdayindex:that.data.birthdayindex,
          industryIndex: that.data.industryIndex,
          educationindex: that.data.educationindex,
          expressindex: that.data.expressindex,
          moneyindex: that.data.moneyindex,
          currentstatusindex:that.data.currentstatusindex,
          areaindexid: that.data.areaindexid,
          worktypeindex: that.data.worktypeindex,
          worknatureIndex: that.data.worknatureIndex,
          noteinfo:data.noteinfo,
          show1:that.data.show1,
          imgs1:that.data.imgs1,
          jobpriceindex :that.data.jobpriceindex,
          worktypeindexid:that.data.worktypeindexid
          }); 

      }

      that.setData({
        jobcate:data.jobcatelist,
        worktypelist:data.worktypelist,
        arealist:that.data.arealist
      }); 

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
  },params);

  },


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
  savepubinfo: function (e) {
    var that = this;

    var content = '';


    /*
    that.editorCtx.getContents({

      success: (res) => {

        console.log(res.html);

        content = res.html;

      }})
*/

    var title = that.data.title;
    var userinfo = wx.getStorageSync('userInfo');
    var jobtitle = e.detail.value.jobtitle;
    var name = e.detail.value.name;
    var sex = that.data.sex;
    var ishidden = that.data.ishidden;
    var cardnum = e.detail.value.cardnum;
    var birthday = this.data.birthdayname;
    var education = that.data.educationname;
    var express = that.data.expressname;
    var address = e.detail.value.address;
    var email = e.detail.value.email;
    var tel = e.detail.value.tel;
    var currentid = that.data.currentid;
    var worktype = that.data.worktypeid;
    var jobcateid = this.data.jobcateid;
    var money = this.data.money;
    var jobpriceid = this.data.jobpriceid;
    var areaid = this.data.areaid;
    var currentid = that.data.currentid;
    var tid = 0 ;
    var shareid = 0 ;
    var status  = that.data.status;
    if (wx.getStorageSync('tid'))
      {
      tid = wx.getStorageSync('tid');
      }
    
    if (wx.getStorageSync('shareid')) {
      shareid = wx.getStorageSync('shareid');
    }

   
    if (name == "") {
      wx.showModal({
        title: '提示',
        content: '请输入姓名',
        showCancel: false
      })
      return
    }

    if (birthday == "") {
      wx.showModal({
        title: '提示',
        content: '请选择出生年份',
        showCancel: false
      })
      return
    }
   
    if (cardnum == "") {
      wx.showModal({
        title: '提示',
        content: '请输入身份证号',
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
  

    if (education == "") {
      wx.showModal({
        title: '提示',
        content: '请选择最高学历',
        showCancel: false
      })
      return
    }


    if (express == "") {
      wx.showModal({
        title: '提示',
        content: '请选择工作经验',
        showCancel: false
      })
      return
    }

    if (jobcateid == 0) {
      wx.showModal({
        title: '提示',
        content: '请选择期望行业',
        showCancel: false
      })
      return
    }


    if (jobtitle == "") {
      wx.showModal({
        title: '提示',
        content: '请输入意向职位',
        showCancel: false
      })
      return
    }




  


 

 
    
    if (worktype == "") {
      wx.showModal({
        title: '提示',
        content: '请选择工作性质',
        showCancel: false
      })
      return
    }
    

 
    if (money == "") {
      wx.showModal({
        title: '提示',
        content: '请选择工资结算方式',
        showCancel: false
      })
      return
    }


    if (currentid == 0) {
      wx.showModal({
        title: '提示',
        content: '请选择目前状态',
        showCancel: false
      })
      return
    }
   /*

  

    if (content == "") {
      wx.showModal({
        title: '提示',
        content: '请输入个人介绍',
        showCancel: false
      })
      return
    }

*/



    var logoimglist = that.data.logoimglist;



    var cityinfo = wx.getStorageSync('cityinfo');
    var params = {
      cityid: cityinfo.id,
      areaid:areaid,
      jobtitle: jobtitle,
      name:name,
      sex:sex,
      ishidden:ishidden,
      tel:tel,
      address:address,
      cardnum:cardnum,
      birthday:birthday,
      education: education,
      express: express,
      tel:tel,
      currentid: currentid,
      worktypeid:worktype,
      jobcateid: jobcateid,
      avatarUrl: logoimglist[0],
      tid:tid,
      money: money,
      jobpriceid:jobpriceid,
      status:status
    };




    note.Savenote((data) => {


      wx.navigateTo({
        url: "/pages/nextedu/index"
      })
      
      
  },params);



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
  

  radioChange: function (e) {
    this.data.sex = e.detail.value;
  },

  radioHiddenChange: function (e) {
    this.data.ishidden = e.detail.value;
  },

  bindAreaChange: function (e) {
    var arealist = this.data.arealist;

    if (arealist) {
      this.data.areaid = arealist[e.detail.value].id;
      this.data.areaindexid = e.detail.value;
    }
    this.setData({
      arealist: arealist,
      areaindexid: e.detail.value
    })
  }
  ,
  bindJobcateChange: function (e) {
    var jobcate = this.data.jobcate;

    if (jobcate) {
      this.data.jobcateindex = e.detail.value;
      this.data.jobcateid = jobcate[e.detail.value].id;
    }
    this.setData({
      jobcate: jobcate,
      jobcateindex: e.detail.value
    })
  }
  ,

  bindIndustryChange: function (e) {
    var worktypelist = this.data.worktypelist;

    if (worktypelist) {
      this.data.industryIndex = e.detail.value;
      this.data.jobcateid = worktypelist[e.detail.value].id;
    }
    this.setData({
      industryIndex: e.detail.value
    })
  }
  ,

  bindWorknatureChange: function (e) {
    var worknature = this.data.worknature;

    if (worknature) {
      this.data.worknatureIndex = e.detail.value;
      this.data.worktypeid = worknature[e.detail.value].id;
    }
    this.setData({
      worknatureIndex: e.detail.value
    })
  }
  ,

  bindCurrentChange: function (e) {
    var currentstatus = this.data.currentstatus;

    if (currentstatus) {
      this.data.currentstatusindex = e.detail.value;
   this.data.currentid = currentstatus[e.detail.value].id;
    }
    this.setData({
      currentstatus: currentstatus,
      currentstatusindex: e.detail.value
    })
  }
  ,

  bindExpressChange: function (e) {
    var express = this.data.express;

    if (express) {
      this.data.expressindex = e.detail.value;
      this.data.expressname = express[e.detail.value];
    }
    console.log(this.data.expressname);
    this.setData({
      express: express,
      expressindex: e.detail.value
    })
  }
  ,
  bindBirthdayChange: function (e) {
    var birthday = this.data.birthday;

    if (birthday) {
      this.data.birthdayindex = e.detail.value;
      this.data.birthdayname = birthday[e.detail.value];
    }
    this.setData({
      birthday: birthday,
      birthdayindex: e.detail.value
    })
  }
  ,

  bindEducationChange: function (e) {
    var education = this.data.education;

    if (education) {
      this.data.educationindex = e.detail.value;
      this.data.educationname = education[e.detail.value];
    }
    console.log(this.data.educationname);
    this.setData({
      education: education,
      educationindex: e.detail.value
    })
  }
  ,

  bindWorktypeChange2: function (e) {
    var worktype = this.data.worktype;

    if (worktype) {
      this.data.worktypeindex = e.detail.value;
      this.data.worktypename = worktype[e.detail.value];
    }
    console.log(this.data.worktypename);
    this.setData({
      worktype: worktype,
      worktypeindex: e.detail.value
    })
  },

  bindWorktypeChange: function (e) {
    var worktypelist = this.data.worktypelist;

    if (worktypelist) {
      this.data.worktypeid = worktypelist[e.detail.value].id;
      this.data.worktypeindexid = e.detail.value;
    }
    this.setData({
      worktypelist: worktypelist,
      worktypeindexid: e.detail.value
    })
  }
  ,



  bindMoneyChange: function (e) {
    var money = this.data.money;

    if (money) {
      this.data.moneyindex = e.detail.value;
      this.data.moneyname = money[e.detail.value];
    }
    console.log(this.data.moneyname);
    this.setData({
      money: money,
      moneyindex: e.detail.value
    })
  }
  ,
  bindCurrentstatusChange: function (e) {
    var currentstatus = this.data.currentstatus;

    if (currentstatus) {
      this.data.currentstatusindex = e.detail.value;
      this.data.currentstatusname = currentstatus[e.detail.value];
    }
    console.log(this.data.currentstatusname);
    this.setData({
      currentstatus: currentstatus,
      currentstatusindex: e.detail.value
    })
  }
  ,

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
          that.data.imagelist = [];
          that.data.imgs2 = tempFilePaths;
          that.setData({
            imgs2: tempFilePaths,
            show2: 'block'
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
    note.uploadimg((data) => {

      console.log(data);

      if(id == 1)
      {

        console.log(data.imgpath);
        that.data.logoimglist.push(data.imgpath);
        console.log( that.data.logoimglist);

      }else if(id == 2){

        that.data.imagelist.push(data.imgpath);

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
  onPullDownRefresh: function(){
    wx.showNavigationBarLoading();
    this.initpage();
  },

  //分享效果
  onShareAppMessage: function () {
    var that = this;
      return {
          title:'我的简历' ,
          path: '/pages/index/index'
      }
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

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