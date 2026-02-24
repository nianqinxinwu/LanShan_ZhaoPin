import { Task } from '../../model/task-model.js';

var task  = new Task();

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
    sex:1,
    jobid:0,
    joblist:[],
    jobindex:-1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var that = this;
    wx.setNavigationBarTitle({
      title: '编辑任务',
    })

   

    if (that.data.id > 0) {
      var id = that.data.id;
    } else {
      var id = e.id;
      that.data.id = e.id;
    }


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


 
    var ctoken = wx.getStorageSync('ctoken');
    var params = {ctoken:ctoken};

    task.getPubTaskInit((data) => {

      that.data.joblist = data.joblist;
      that.setData({
        joblist:data.joblist,
      }); 

    },params)



    var params = { id:that.data.id};

    task.GetTaskdetail((data) => {


      that.data.jobindex = isHasElementTwo(that.data.joblist, data.jobid);

      console.log(that.data.jobindex);
      
      that.data.jobid = data.jobid;

        const query = wx.createSelectorQuery();

        query.select('#editor')
    
          .context(function (res) {
    
            res.context.setContents({
    
              html: data.content
    
            })

          
            that.editorCtx = res.context
            wx.pageScrollTo({
              scrollTop: 0,
              success: () => {
                that.editorCtx.scrollIntoView()
              }
            })




    
          }).exec();




        
        
    
    


     that.setData({
         taskinfo:data,
         jobindex: that.data.jobindex,
       
     });
 },params);


  },



  bindJobChange: function (e) {
    var joblist = this.data.joblist;

    if (joblist) {
      this.data.jobindex = e.detail.value;
      this.data.jobid = joblist[e.detail.value].id;
    }
    this.setData({
      joblist: joblist,
      jobindex: e.detail.value
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


  savepubinfo:function(e){
     
    
    var that = this;
    var content = '';



    that.editorCtx.getContents({

      success: (res) => {

        console.log(res.html);

        content = res.html;


    var title = e.detail.value.title;
    var jobid = this.data.jobid;
    var money = e.detail.value.money;
    var num = e.detail.value.num;


    var ctoken = wx.getStorageSync('ctoken');


    if (title == "") {
      wx.showModal({
        title: '提示',
        content: '请输入任务名称',
        showCancel: false
      })
      return
    }

    if (jobid == 0) {
      wx.showModal({
        title: '提示',
        content: '请选择所属职位',
        showCancel: false
      })
      return
    }

    if (money == "") {
      wx.showModal({
        title: '提示',
        content: '请输入推荐入职奖',
        showCancel: false
      })
      return
    }
    if (num == "") {
      wx.showModal({
        title: '提示',
        content: '请输入招聘人数',
        showCancel: false
      })
      return
    }

 
    if (content == "") {
      wx.showModal({
        title: '提示',
        content: '请输入任务说明',
        showCancel: false
      })
      return
    }

    
    var params = {
        ctoken:ctoken,
                id:that.data.id,
                title:title,
                jobid: jobid, 
                money: money,
                num: num,
                content:content
                
                            };


         task.Updatetask((data) => {
             wx.navigateTo({
                   url: "/pages/tasklist/index"
                  })                              
       },params);



      
    }
  });


  



  },


  radioChange: function (e) {
    this.data.sex = e.detail.value;
  },

  checkboxChange: function (e) {
    var special = e.detail.value;
    this.data.special = special.join(',');
    //console.log('checkbox发生change事件，携带value值为：', e.detail.value)
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