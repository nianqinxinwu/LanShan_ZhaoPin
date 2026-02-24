import { Job } from '../../model/job-model.js';

var job = new Job();

Page({

  data: {
    worktypelist: [],
    worktypeindexid: -1,
    worktypeid: 0,
    jobcatelist: [
      { name: '餐饮服务' }, { name: '活动执行' }, { name: '安保检票' },
      { name: '教育辅导' }, { name: '影视艺术' }, { name: '临时促销' },
      { name: '电商推广' }, { name: '问卷调查' }, { name: '物流配送' },
      { name: '装卸搬运' }, { name: '家政护理' }, { name: '流水线工' },
      { name: '手工制作' }, { name: '内容创作' }, { name: '校园专属' }
    ],
    jobcateindexid: -1,
    jobcatename: '',
    settleTypeList: ['面议', '日结', '周结', '月结', '完工结'],
    settleTypeIndex: 0,
    sex: 0,
    job_address: '',
    job_lat: '',
    job_lng: '',
    markers: [],
    hasMap: false,
    work_start_date: '',
    work_end_date: '',
    work_start_time: '',
    work_end_time: '',
    benefit_tag1: '',
    benefit_tag2: '',
    money: '',
    num: '',
    jobtitle: '',
    content: '',
    requirements: '',
    tips: ''
  },

  onLoad: function (options) {
    var that = this;
    wx.setNavigationBarTitle({
      title: '发布岗位',
    })

    var city;
    city = wx.getStorageSync('city');
    var params = { city: city };

    job.getPubJobInit((data) => {

      that.data.worktypelist = data.worktypelist;

      that.setData({
        worktypelist: that.data.worktypelist
      });

    }, params)
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
  },

  bindJobcateChange: function (e) {
    var jobcatelist = this.data.jobcatelist;
    if (jobcatelist) {
      this.data.jobcatename = jobcatelist[e.detail.value].name;
      this.data.jobcateindexid = e.detail.value;
    }
    this.setData({
      jobcateindexid: e.detail.value
    })
  },

  bindSettleTypeChange: function (e) {
    this.setData({
      settleTypeIndex: e.detail.value
    })
  },

  radioChange: function (e) {
    this.data.sex = e.detail.value;
  },

  bindStartDateChange: function (e) {
    this.setData({
      work_start_date: e.detail.value
    })
  },

  bindEndDateChange: function (e) {
    this.setData({
      work_end_date: e.detail.value
    })
  },

  bindStartTimeChange: function (e) {
    this.setData({
      work_start_time: e.detail.value
    })
  },

  bindEndTimeChange: function (e) {
    this.setData({
      work_end_time: e.detail.value
    })
  },

  chooseLocation: function () {
    var that = this;
    wx.chooseLocation({
      success: function (res) {
        var markers = [{
          id: 1,
          latitude: res.latitude,
          longitude: res.longitude,
          width: 30,
          height: 30
        }];
        that.setData({
          job_address: res.address || res.name,
          job_lat: res.latitude,
          job_lng: res.longitude,
          markers: markers,
          hasMap: true
        })
      }
    })
  },

  savepubinfo: function (e) {
    var that = this;
    var formData = e.detail.value;

    var jobtitle = formData.jobtitle;
    var num = formData.num;
    var money = formData.money;
    var content = formData.content;
    var requirements = formData.requirements;
    var tips = formData.tips;
    var benefit_tag1 = formData.benefit_tag1;
    var benefit_tag2 = formData.benefit_tag2;
    var type = that.data.worktypeid;
    var jobcatename = that.data.jobcatename;

    if (jobtitle == "") {
      wx.showModal({
        title: '提示',
        content: '请输入岗位名称',
        showCancel: false
      })
      return
    }

    if (type == 0) {
      wx.showModal({
        title: '提示',
        content: '请选择工作性质',
        showCancel: false
      })
      return
    }

    if (!jobcatename) {
      wx.showModal({
        title: '提示',
        content: '请选择工作类型',
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
        content: '请输入工作内容',
        showCancel: false
      })
      return
    }

    var ctoken = wx.getStorageSync('ctoken');
    var companyid = wx.getStorageSync('companyid');

    var params = {
      ctoken: ctoken,
      companyid: companyid,
      jobtitle: jobtitle,
      jobcatename: jobcatename,
      type: type,
      num: num,
      sex: that.data.sex,
      settle_type: that.data.settleTypeIndex,
      content: content,
      requirements: requirements,
      tips: tips,
      benefit_tag1: benefit_tag1,
      benefit_tag2: benefit_tag2,
      money: money ? money + '元/天' : '',
      job_address: that.data.job_address,
      job_lat: that.data.job_lat,
      job_lng: that.data.job_lng,
      work_start_date: that.data.work_start_date,
      work_end_date: that.data.work_end_date,
      work_start_time: that.data.work_start_time,
      work_end_time: that.data.work_end_time
    };

    job.ZwSavejob((data) => {

      if (data.status == 1) {
        wx.showToast({
          title: '发布成功',
          icon: 'success',
          duration: 1500
        })
        setTimeout(function () {
          wx.navigateBack({
            delta: 1,
          })
        }, 1500)
      } else {
        wx.showModal({
          title: '提示',
          content: data.msg || '发布失败',
          showCancel: false
        })
      }

    }, params);

  },

  onReady: function () { },
  onShow: function () { },
  onHide: function () { },
  onUnload: function () { },
  onPullDownRefresh: function () { },
  onReachBottom: function () { },
  onShareAppMessage: function () { }
})
