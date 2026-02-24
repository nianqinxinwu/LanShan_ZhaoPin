import { Job } from '../../model/job-model.js';

var job  = new Job();

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
    worktypelist: [],
    worktypeindexid: -1,
    worktypeid: 0,
    
    special:'',
    speciallist: [{ name: '五险', checked: false }, 
    {name:'五险一金',checked:false}, 
    { name: '补充医疗保险', checked: false }, 
    { name: '员工旅游', checked: false }, 
    { name: '交通补贴', checked: false }, 
    { name: '餐饮补贴', checked: false }, 
    { name: '出国机会', checked: false }, 
    { name: '年终奖金', checked: false }, 
    { name: '定期体检', checked: false },
    { name: '节日福利', checked: false },
    { name: '双休', checked: false },
    { name: '调休', checked: false },
    { name: '年假', checked: false },
    { name: '加班补贴', checked: false },
    { name: '职位晋升', checked: false },
    { name: '包食宿', checked: false }],
  education: ['不限','初中以上', '高中以上', '中技以上', '中专以上', '大专以上', '本科以上', '硕士以上', '博士以上', '博后'],
  educationindex: -1,
  educationname: '',
  express: ['无经验', '1年以下', '1-3年', '3-5年', '5-10年', '10年以上'],
  expressindex:-1,
  expressname:'',
  sex:1,
  type:0,
  money:'',
  settleTypeList: ['面议', '日结', '周结', '月结', '完工结'],
  settleTypeIndex: 0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    wx.setNavigationBarTitle({
      title: '添加职位',
    })

    var city ;
    city =  wx.getStorageSync('city');
    var params = {city:city};

    job.getPubJobInit((data) => {

      that.data.worktypelist = data.worktypelist;

      that.setData({
        jobcate:data.jobcatelist,
        arealist:that.data.arealist,
        jobprice:data.jobpricelist,
        worktypelist:that.data.worktypelist
      }); 

    },params)


/*
    const query = wx.createSelectorQuery();

    var html = `<div class="div_class">
    <h1>Title</h1>
    <p class="p">
      Life is&nbsp;<i>like</i>&nbsp;a box of
      <b>&nbsp;chocolates</b>.
    </p>
  </div>
  `;

    query.select('#editor')

      .context(function (res) {

        res.context.setContents({

          html: html

        })

      }).exec();

      */

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
  },
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


  
  bindJobpriceChange: function (e) {
    var jobprice = this.data.jobprice;

    if (jobprice) {
      this.data.jobpriceindex = e.detail.value;
      this.data.jobpriceid = jobprice[e.detail.value].id;
      this.data.money = jobprice[e.detail.value].name;
    }
    this.setData({
      jobprice: jobprice,
      jobpriceindex: e.detail.value
    })
  }
  ,

  bindSettleTypeChange: function (e) {
    this.setData({
      settleTypeIndex: e.detail.value
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


    var jobtitle = e.detail.value.jobtitle;
    var jobcateid = this.data.jobcateid;
    var jobpriceid = this.data.jobpriceid;
    var money = this.data.money;
    var num = e.detail.value.num;
    var education =  that.data.educationname;
    var express = that.data.expressname;
    var age = e.detail.value.age;
    var sex = that.data.sex;
    var type = that.data.worktypeid;
    var companyid = wx.getStorageSync('companyid');

    if (jobtitle == "") {
      wx.showModal({
        title: '提示',
        content: '请输入工作职位',
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

    if (money == "") {
      wx.showModal({
        title: '提示',
        content: '请输入薪资待遇',
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
    if (education == "") {
      wx.showModal({
        title: '提示',
        content: '请选择学历要求',
        showCancel: false
      })
      return
    }
    if (express == "") {
      wx.showModal({
        title: '提示',
        content: '请输入工作经验',
        showCancel: false
      })
      return
    }
 
    if (age == "") {
      wx.showModal({
        title: '提示',
        content: '请输入年龄要求',
        showCancel: false
      })
      return
    }  
    if (content == "") {
      wx.showModal({
        title: '提示',
        content: '请输入职位说明',
        showCancel: false
      })
      return
    }

    var ctoken = wx.getStorageSync('ctoken');
    
    var params = {
                ctoken:ctoken,
                companyid: companyid,
                jobtitle:jobtitle,
                worktype: jobcateid, 
                jobpriceid:jobpriceid,
                money: money,
                num: num,
                education: education,
                express:express,
                age:age,
                sex: sex,
                type:type,
                special: that.data.special,
                content:content,
                settle_type: that.data.settleTypeIndex
               // videourl:that.data.videourl
                
                            };


      job.Savejob((data) => {

        /*
             wx.navigateTo({
                   url: "/pages/companyjob/index"
                  })

          */
         wx.navigateBack({
           delta: 1,
         })          
                              
       },params);
 


      
    }
  });


  



  },


  radioChange: function (e) {
    this.data.sex = e.detail.value;
  },


  radioTypeChange: function (e) {
    this.data.type = e.detail.value;
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