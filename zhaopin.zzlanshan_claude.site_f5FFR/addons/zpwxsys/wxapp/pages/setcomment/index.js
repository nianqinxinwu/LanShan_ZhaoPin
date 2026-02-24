import { Company } from '../../model/company-model.js';
var company  = new Company();
Page({
  data: {
    imagelist: [],
    flag: 0,
    noteMaxLen: 300, // 最多放多少字
    info: "",
    noteNowLen: 0,//备注当前字数
    score:0,
    id:0,
    companyid:0,
    ordertype:0,
    show:'none'
  },
  onLoad:function(e){

    var that = this;
    wx.setNavigationBarTitle({
        title: '我要评价',
      })
    that.data.id = e.id;
    that.data.companyid = e.companyid;

  },
  // 监听字数
  bindTextAreaChange: function (e) {
    var that = this
    var value = e.detail.value,
      len = parseInt(value.length);
    if (len > that.data.noteMaxLen)
      return;
    that.setData({ info: value, noteNowLen: len })

  },
  // 提交清空当前值
  bindSubmit: function (e) {
    var that = this;
    var score = that.data.score;
    var content = e.detail.value.content;
    var pid = that.data.id;
    var companyid =  that.data.companyid;
    if (score == 0) {
      wx.showModal({
        title: '提示',
        content: '请选择评分',
        showCancel: false
      })
      return
    }

    if (content == '') {
      wx.showModal({
        title: '提示',
        content: '请输入评论内容',
        showCancel: false
      })
      return
    }

    var params = {
      id:pid,
      type:1,
      score:score,
      companyid:companyid,
      content: content,
      piclist: that.data.imagelist.join(',')
    };
    company.saveComment((data) => {

        wx.showModal({
          title: '提示',
          content: data.msg,
          showCancel: false,
          success:function(){

            wx.navigateBack({
                delta: 1,
              })
          }
         
        })
        return
        
    },params);




  },
  changeColor1: function () {
    var that = this;
    that.data.score = 1;
    that.setData({
      flag: 1
    });
  },
  changeColor2: function () {
    var that = this;
    that.data.score = 2;
    that.setData({
      flag: 2
    });
  },
  changeColor3: function () {
    var that = this;
    that.data.score = 3;
    that.setData({
      flag: 3
    });
  },
  changeColor4: function () {
    var that = this;
    that.data.score = 4;
    that.setData({
      flag: 4
    });
  },
  changeColor5: function () {
    var that = this;
    that.data.score = 5;
    that.setData({
      flag: 5
    });
  },


  uploadimg: function (path) {
    var uploadurl = app.util.geturl({ 'url': 'entry/wxapp/upload' });
    // var id = id;
    wx.showToast({
      icon: "loading",
      title: "正在上传"
    });

    var that = this;
    wx.uploadFile({
      url: uploadurl,
      filePath: path,
      name: 'file',
      header: { "Content-Type": "multipart/form-data" },
      formData: {
        //和服务器约定的token, 一般也可以放在header中
        'session_token': wx.getStorageSync('session_token')
      },
      success: function (res) {
        var getdata = JSON.parse(res.data);

        if (res.statusCode != 200) {
          wx.showModal({
            title: '提示',
            content: '上传失败',
            showCancel: false
          })
          return;
        } else {

        }
        var imgpath = getdata.data.path;

        that.data.imagelist.push(imgpath);





      },
      fail: function (e) {

        wx.showModal({
          title: '提示',
          content: '上传失败',
          showCancel: false
        })
      },
      complete: function () {
        wx.hideToast();  //隐藏Toast
      }
    })
  },

  chooseImg: function (e) {
    var that = this;

    wx.chooseImage({
      count: 3, // 默认9
      sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        var tempFilePaths = res.tempFilePaths;
        var imgs1 = that.data.imgs1;
        imgs1 = [];
        that.data.imagelist = [];
        for (var i = 0; i < tempFilePaths.length; i++) {
          if (imgs1.length >= 9) {
            that.setData({
              imgs1: imgs1
            });
            // return false;
          } else {
            imgs1.push(tempFilePaths[i]);
          }
        }
        that.setData({
          imgs1: imgs1,
          show: 'block'
        });
        that.setData({
          picture1: []
        })
        var tempFilePaths = that.data.imgs1

        // var uploadurl = app.util.geturl({ 'url': 'entry/wxapp/upload' });

        for (var s = 0; s < tempFilePaths.length; s++) {

          console.log(tempFilePaths[s]);

          that.uploadimg(tempFilePaths[s]);
        }

      },
      fail: function (res) {
      },
      complete: function (res) {
      }
    });
  }


})

