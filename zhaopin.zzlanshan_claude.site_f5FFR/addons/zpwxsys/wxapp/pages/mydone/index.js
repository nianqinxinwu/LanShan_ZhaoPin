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
    education: ['初中', '高中', '中技', '中专', '大专', '本科', '硕士', '博士', '博后'],
    educationindex: -1,
    educationname: '',
    express: ['无经验', '1年以下', '1-3年', '3-5年', '5-10年', '10年以上'],
    expressindex: -1,
    expressname: '',
    currentstatus: ['我目前已离职,可快速到岗', '我目前在职，但考虑换个新环境', '观望有好的机会再考虑', '目前暂无跳槽打算', '应届毕业生'],
    currentstatusindex: -1,
    currentstatusname: '',
    worktype: ['全职', '兼职', '实习'],
    worktypeindex: -1,
    worktypename: '',
    money: [ '1千~2千/月', '2千~3千/月', '3千~4千/月', '4千~5千/月', '5千~1万/月', '1万以上/月'],
    moneyindex: -1,
    moneyname: '',
    arealist: [],
    areaindexid: -1,
    areaid: 0,

    currentlist: [],
    currentindexid: -1,
    currentid: 0,

    worktypelist: [],
    worktypeindexid: -1,
    worktypeid: 0,

    helplablist: [],
    helplabindexid: -1,
    helplabid: 0,

    imgurl1:false,
    true1:true,
    show1:'none',
    logoimglist:[],
    imgs1:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
    wx.setNavigationBarTitle({
      title: '完善资料',
    })
   
    var that = this;

     





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

  initpage:function(){

    var that = this;
    var city ;
    city =  wx.getStorageSync('city');
    var params = {city:city};

    note.getPubNoteInit((data) => {

      console.log(data);
      that.data.arealist = data.arealist;
      that.data.currentlist = data.currentlist;
      that.data.worktypelist = data.worktypelist;
      that.data.helplablist = data.helplablist;

      var noteinfo = data.noteinfo;
      if(noteinfo)
      {


/*
        const query = wx.createSelectorQuery();

        query.select('#editor')
    
          .context(function (res) {
    
            res.context.setContents({
    
              html: noteinfo.content
    
            })

          
            that.editorCtx = res.context
            wx.pageScrollTo({
              scrollTop: 0,
              success: () => {
                that.editorCtx.scrollIntoView()
              }
            })

    
          }).exec();

*/




        that.data.birthdayindex = isHasElementOne(that.data.birthday, noteinfo.birthday);
        that.data.birthdayname = noteinfo.birthday;

        that.data.jobcateindex = isHasElementTwo(data.jobcatelist, noteinfo.jobcateid);
        that.data.jobcateid = noteinfo.jobcateid;

        that.data.areaindexid = isHasElementTwo(data.arealist, noteinfo.areaid);

        that.data.areaid = noteinfo.areaid;

        that.data.educationindex = isHasElementOne(that.data.education, noteinfo.education);
        that.data.educationname = noteinfo.education;
        that.data.expressindex = isHasElementOne(that.data.express, noteinfo.express);
        that.data.expressname = noteinfo.express;

 


        that.data.currentindexid = isHasElementTwo(data.currentlist, noteinfo.currentid);

        that.data.currentid = noteinfo.currentid;


        that.data.helplabindexid = isHasElementTwo(data.helplablist, noteinfo.helplabid);

        that.data.helplabid = noteinfo.helplabid;

        
        that.data.moneyindex = isHasElementOne(that.data.money, noteinfo.money);
        that.data.moneyname = noteinfo.money;

        that.data.worktypeindex = isHasElementOne(that.data.worktype, noteinfo.worktype);
        that.data.worktypename = noteinfo.worktype;

        that.data.currentstatusindex = isHasElementOne(that.data.currentstatus, noteinfo.currentstatus);
        that.data.currentstatusname = noteinfo.currentstatus;

        that.data.show1 = false;

        that.data.logoimglist.push(noteinfo.avatarUrl);

        that.data.imgs1.push(noteinfo.avatarUrl);

        that.setData({
          birthdayindex:that.data.birthdayindex,
          jobcateindex: that.data.jobcateindex,
          educationindex: that.data.educationindex,
          expressindex: that.data.expressindex,
          moneyindex: that.data.moneyindex,
          currentstatusindex:that.data.currentstatusindex,
          areaindexid: that.data.areaindexid,
          currentindexid: that.data.currentindexid,
          helplabindexid: that.data.helplabindexid,
          worktypeindex: that.data.worktypeindex,
          noteinfo:data.noteinfo,
          show1:that.data.show1,
          imgs1:that.data.imgs1
          }); 

      }

      that.setData({
        jobcate:data.jobcatelist,
        arealist:that.data.arealist,
        currentlist:that.data.currentlist,
        worktypelist:that.data.worktypelist,
        helplablist:that.data.helplablist
      }); 
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
  
    var address = e.detail.value.address;
    var areaid = this.data.areaid;
    var currentid = that.data.currentid;
    var helplabid = that.data.helplabid;
   
    if (address == "") {
      wx.showModal({
        title: '提示',
        content: '请输入现在居住地',
        showCancel: false
      })
      return
    }

    if (areaid == 0) {
      wx.showModal({
        title: '提示',
        content: '请选择希望工作地区',
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


    if (helplabid == 0) {
      wx.showModal({
        title: '提示',
        content: '请选择就业帮扶',
        showCancel: false
      })
      return
    }


    var params = {
      address: address,
      areaid: areaid,
      currentid:currentid,
      helplabid:helplabid
    };




    note.Savenote((data) => {



      wx.switchTab({
        url: '/pages/user/index',
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

  bindCurrentChange: function (e) {
    var currentlist = this.data.currentlist;

    if (currentlist) {
      this.data.currentid = currentlist[e.detail.value].id;
      this.data.currentindexid = e.detail.value;
    }
    this.setData({
      currentlist: currentlist,
      currentindexid: e.detail.value
    })
  }
  ,

  
  bindHelplabChange: function (e) {
    var helplablist = this.data.helplablist;

    if (helplablist) {
      this.data.helplabid = helplablist[e.detail.value].id;
      this.data.helplabindexid = e.detail.value;
    }
    this.setData({
      helplablist: helplablist,
      helplabindexid: e.detail.value
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
  bindWorktypeChange: function (e) {
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