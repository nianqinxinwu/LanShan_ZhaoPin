import { Baoming } from '../../model/baoming-model.js';
import { Note } from '../findworker/note-model.js';
var baoming  = new Baoming();
var note = new Note();

Page({

  /**
   * 页面的初始数据
   */
  data: {

    jobid:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
    wx.setNavigationBarTitle({
      title: '我的简历',
    })
   
    var that = this;

    that.data.jobid = options.id;

     



  },

  initpage:function(){

    var that = this;
    var city ;
    city =  wx.getStorageSync('city');
    var params = {city:city};


  },


  savepubinfo: function (e) {
    var that = this;

  

    var name = e.detail.value.name;
    var sex = that.data.sex;
    var tel = e.detail.value.tel;

    var jobid = that.data.jobid;



    if (name == '') {
      wx.showModal({
        title: '提示',
        content: '请输入姓名',
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



    var cityinfo = wx.getStorageSync('cityinfo');
    var params = {
      cityid: cityinfo.id,
      name:name,
      sex:sex,
      tel:tel,
      jobid:jobid
    };


    baoming.Savebaoming((data) => {

      wx.showModal({
        title: '提示',
        content: data.msg,
        showCancel: false,
       
      })
      return
      
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

  bindCurrentChange: function (e) {
    var currentstatus = this.data.currentstatus;

    if (currentstatus) {
      this.data.currentstatusindex = e.detail.value;
   //   this.data.currentid = current[e.detail.value].id;
   this.data.currentstatusname = currentstatus[e.detail.value];
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