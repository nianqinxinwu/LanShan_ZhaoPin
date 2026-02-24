import { Agent } from '../../model/agent-model.js';

var agent  = new Agent();


import { Note } from '../findworker/note-model.js';

var note  = new Note();

import { Findjob } from '../findjob/findjob-model.js';
var findjob = new Findjob(); 

Page({

  /**
   * 页面的初始数据
   */
  data: {
    multiIndex: [0,0],
    objectMultiArray: [],
    datainfo: '',
    helplablist: [],
    helplabindexid: -1,
    helplabid: 0,
    floor:0,

    notelist:[],
    noteidindex:-1,
    noteid:0,
    jobid:0,
    joblist:[],
    id:0
  },



  doSendjob: function (e) {
    var that = this;
    var companyid = that.data.companyid;


    if(that.data.noteid == 0)
    {
      wx.showModal({
        title: '提示',
        content: '请选择人才',
        showCancel: false
      })
      return
    }



    

   
    var noteid = that.data.noteid;
    var params = { noteid:that.data.noteid,taskid:that.data.id};
 

        findjob.sendCompanyJob((data) => {


   
          if(data.status == 0)
          {

          this.setData({
            loadModal: true
          })
          setTimeout(()=> {
            this.setData({
              loadModal: false
            })
  
        
  
           wx.redirectTo({
            url: '/pages/sendorder/index',
          })
  
          }, 2000)

        }else{


          wx.showModal({
            title: '提示',
            content: '您已邀请过',
            showCancel: false
          })
          return
        }



     },params);

    

   
    



      

   





  },



  bindMultiPickerChange:function(e){

    var that = this;
      console.log(e);
      var value = e.detail.value;

        that.data.cityid = that.data.objectMultiArray[0][value[0]]['id'];

      that.data.areaid = that.data.objectMultiArray[1][value[1]]['id'];
  },


  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

    var that = this;

    wx.setNavigationBarTitle({
      title: '派遣人才' ,
    }) 
    //初始化导航数据

    that.data.id = options.id;
    var params = {};

    note.getAgentNoteListData((data) => {

      console.log(data);
      that.data.notelist = data.notelist;
      that.setData({
          notelist: data.notelist
      });
  },params);


   

  },

  radioChange: function (e) {
    this.data.jobid = e.detail.value;
  },

  checkboxChange: function (e) {
    var areaid = e.detail.value;
    this.data.areaid = areaid.join(',');
  },

  bindCompanyChange: function (e) {
    var that = this;
    var notelist = this.data.notelist;

    if (notelist) {
      this.data.noteid = notelist[e.detail.value].id;
      this.data.noteidindex = e.detail.value;
    //  var datainfo = that.data.datainfo;
      //that.data.joblist = datainfo[that.data.companyid].joblist;

    }

    this.setData({
      notelist:that.data.notelist,
 
      noteidindex: e.detail.value
    })
  }
  ,

  radioFloorChange: function (e) {
    var that = this;
    that.data.floor = e.detail.value;

    that.setData({
      
      floor:that.data.floor

      }); 
  },

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

  bindMultiPickerColumnChange: function (e) {
    var that = this;
    var secondcatelist;
    var data = {
      objectMultiArray: this.data.objectMultiArray,
      multiIndex: this.data.multiIndex
    };
    data.multiIndex[e.detail.column] = e.detail.value;
    var currentdata = this.data.objectMultiArray[e.detail.column][e.detail.value];
    var datainfo = that.data.datainfo;



    if(that.data.cityid == -1)
      {
        
      that.data.cityid = that.data.objectMultiArray[0]['id'] ;

      }



    switch (e.detail.column) {
      case 0:
        that.data.cityid = currentdata['id'];
        data.objectMultiArray[1] = datainfo[currentdata['id']]['arealist'];
        
        secondcatelist = data.objectMultiArray[1];

        data.multiIndex[1] = 0;
        break;
      case 1:
        that.data.areaid = currentdata['id'];
        break;
    }
   // console.log(data);

    that.data.currentdata = data.multiIndex;

    this.setData(data);

    
  },

  
  radioTypeChange: function (e) {
    var that = this;
    that.data.type = e.detail.value;

    console.log( that.data.type);

    that.setData({
      
      type:that.data.type

      }); 
  },

  initpage:function(){

    var that = this;


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
          that.data.imagelist = [];
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
    house.uploadimg((data) => {

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
  toSelectHouse:function(){

    wx.navigateTo({
      url: "/pages/selecthouse/index"
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

    var imagelist = [];
    var imgs2 = [];

    console.log(that.data.imagelist);
    console.log(that.data.imgs2);



    for(var i = 0 ; i < that.data.imagelist.length ; i++ )
    {

      if(i != index)
      {
          imagelist.push(that.data.imagelist[i]);
          imgs2.push(that.data.imgs2[i]);

      }

    }

    that.data.imagelist = imagelist;
    that.data.imgs2 = imgs2;
    that.setData({
      imgs2: imgs2,
     // show2: 'none'
    });

    if(that.data.imagelist.length == 0)
    {

      that.setData({
        show2: 'none'
      });

    }




    console.log(index);

  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示s
   */
  onShow: function () {

    console.log(wx.getStorageSync('houseinfo'));
    var that = this;

    that.setData({
      houseinfo: wx.getStorageSync('houseinfo')
    });
    

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