import { Note } from '../findworker/note-model.js';
var note  = new Note();
import { Job } from '../../model/job-model.js';
var job  = new Job();
import { Company } from '../../model/company-model.js';
var company = new Company();
import { Coin } from '../../model/coin-model.js';
var coin = new Coin();

Page({
  data: {

    TabCur: 1,
    MainCur: 0,
    VerticalNavTop: 0,
    list: [],
    load: true,
    cateid:0,
    priceid:"",
    edu:'',
    express:'',
    sex:"",
    special:'',

    // 报名者列表模式
    jobid: '',
    jobinfo: null,
    currentTab: -1,
    applicantList: [],
    selectAll: false,
    selectedCount: 0,
    page: 1

  },
  onLoad(options) {
    var that = this;

    // 如果传入了jobid，进入报名者列表模式
    if (options.jobid) {
      that.setData({ jobid: options.jobid });
      wx.setNavigationBarTitle({ title: '筛选报名者' });
      that.loadApplicants();
      return;
    }

    wx.setNavigationBarTitle({
      title: '简历筛选',
    })

    wx.showLoading({
      title: '加载中...',
      mask: true
    });
    var params = {};
    job.selectJobInit((data) => {

      that.setData({
        jobcate:data.jobcatelist,
        jobprice:data.jobpricelist
      });

    },params)



    let list = [{}];
    for (let i = 0; i < 26; i++) {
      list[i] = {};
      list[i].name = String.fromCharCode(65 + i);
      list[i].id = i;
    }
    this.setData({
      list: list,
      listCur: list[0],
      notecount:0
    })


  },
  onReady() {
    wx.hideLoading()
  },

  // ========== 报名者列表模式方法 ==========

  loadApplicants: function () {
    var that = this;
    var ctoken = wx.getStorageSync('ctoken');
    var params = {
      ctoken: ctoken,
      jobid: that.data.jobid,
      filterStatus: that.data.currentTab,
      page: that.data.page
    };

    company.companynote((data) => {
      var list = data.notelist || [];
      var sexMap = { 1: '男', 2: '女', 0: '未知' };
      for (var i = 0; i < list.length; i++) {
        // 已处理（status >= 1）的报名者不可勾选
        list[i].disabled = list[i].status >= 1;
        list[i].selected = false;
        // 性别映射
        list[i].sexText = sexMap[list[i].sex] || '未知';
        // 计算年龄（从birthday）
        if (list[i].birthday) {
          var birth = new Date(list[i].birthday);
          var now = new Date();
          var age = now.getFullYear() - birth.getFullYear();
          var m = now.getMonth() - birth.getMonth();
          if (m < 0 || (m === 0 && now.getDate() < birth.getDate())) age--;
          list[i].calcAge = age + '岁';
        } else if (list[i].note_age) {
          list[i].calcAge = list[i].note_age;
        } else {
          list[i].calcAge = '';
        }
        // 学历（优先使用note_education）
        list[i].eduText = list[i].note_education || '';
        // 小红花评分数组
        var rating = list[i].rating || 0;
        var stars = [];
        for (var s = 0; s < 5; s++) {
          if (rating >= (s + 1)) {
            stars.push('full');
          } else if (rating >= (s + 0.5)) {
            stars.push('half');
          } else {
            stars.push('empty');
          }
        }
        list[i].ratingStars = stars;
      }
      // 按评分从高到低排序
      list.sort(function (a, b) {
        return (b.rating || 0) - (a.rating || 0);
      });
      that.setData({
        applicantList: list,
        jobinfo: data.jobinfo || null,
        selectAll: false,
        selectedCount: 0
      });

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, params);
  },

  toggleSelect: function (e) {
    var that = this;
    var index = e.currentTarget.dataset.index;
    var list = that.data.applicantList;
    if (list[index].disabled) return;
    list[index].selected = !list[index].selected;

    var count = 0;
    var selectable = 0;
    for (var i = 0; i < list.length; i++) {
      if (!list[i].disabled) {
        selectable++;
        if (list[i].selected) count++;
      }
    }

    that.setData({
      applicantList: list,
      selectedCount: count,
      selectAll: selectable > 0 && count === selectable
    });
  },

  toggleSelectAll: function () {
    var that = this;
    var newVal = !that.data.selectAll;
    var list = that.data.applicantList;
    var count = 0;
    for (var i = 0; i < list.length; i++) {
      if (!list[i].disabled) {
        list[i].selected = newVal;
        if (newVal) count++;
      }
    }
    that.setData({
      applicantList: list,
      selectAll: newVal,
      selectedCount: count
    });
  },

  getSelectedIds: function () {
    var list = this.data.applicantList;
    var ids = [];
    for (var i = 0; i < list.length; i++) {
      if (list[i].selected) ids.push(list[i].id);
    }
    return ids;
  },

  confirmSelect: function () {
    var that = this;
    var ids = that.getSelectedIds();
    if (ids.length == 0) {
      wx.showToast({ title: '请先选择报名者', icon: 'none' });
      return;
    }

    // 先查询群聊是否已存在，决定扣费方式
    coin.checkServiceStatus(function (serviceData) {
      var groupExists = serviceData.status == 0 && serviceData.group_created;

      if (groupExists) {
        // 群聊已存在：按人数扣费（1章鱼币/人）
        var perPersonCost = 1;
        var totalCost = ids.length * perPersonCost;
        wx.showModal({
          title: '确认选择',
          content: '确认选择 ' + ids.length + ' 人？将消耗 ' + totalCost + ' 章鱼币（' + perPersonCost + '章鱼币/人），新录用的报名者将自动加入群聊',
          success: function (res) {
            if (res.confirm) {
              // 先扣费
              coin.consume(function (coinData) {
                if (coinData.status == 0) {
                  // 扣费成功，执行录用
                  that.doBatchAccept(ids, '录用成功，已加入群聊');
                } else {
                  wx.showToast({ title: coinData.msg || '章鱼币余额不足', icon: 'none', duration: 2000 });
                }
              }, { amount: totalCost, action: 'addmember', related_id: that.data.jobid, remark: '追加录用' + ids.length + '人' });
            }
          }
        });
      } else {
        // 群聊不存在：创建群聊 2 章鱼币
        wx.showModal({
          title: '确认选择',
          content: '确认选择 ' + ids.length + ' 人？选中的报名者将被录用，同时将消耗2章鱼币创建岗位群聊，已录用的报名者将自动加入群聊',
          success: function (res) {
            if (res.confirm) {
              var ctoken = wx.getStorageSync('ctoken');
              var params = { ctoken: ctoken, ids: ids.join(','), action: 'accept' };
              company.batchApplicant((data) => {
                if (data.status == 1) {
                  coin.consume(function (coinData) {
                    if (coinData.status == 0) {
                      wx.showToast({ title: '录用成功，群聊已创建', icon: 'none', duration: 2000 });
                    } else {
                      wx.showModal({
                        title: '录用成功',
                        content: '报名者已录用，但群聊创建失败：' + (coinData.msg || '章鱼币余额不足') + '。可前往"购买服务"页面手动创建群聊',
                        showCancel: false
                      });
                    }
                    that.loadApplicants();
                  }, { amount: 2, action: 'creategroup', related_id: that.data.jobid, remark: '创建群聊服务' });
                } else {
                  wx.showToast({ title: data.msg || '操作失败', icon: 'none', duration: 2000 });
                  that.loadApplicants();
                }
              }, params);
            }
          }
        });
      }
    }, { related_id: that.data.jobid });
  },

  doBatchAccept: function (ids, successMsg) {
    var that = this;
    var ctoken = wx.getStorageSync('ctoken');
    var params = { ctoken: ctoken, ids: ids.join(','), action: 'accept' };
    company.batchApplicant((data) => {
      if (data.status == 1) {
        wx.showToast({ title: successMsg, icon: 'none', duration: 2000 });
      } else {
        wx.showToast({ title: data.msg || '操作失败', icon: 'none', duration: 2000 });
      }
      that.loadApplicants();
    }, params);
  },

  toWorkerDetail: function (e) {
    var uid = e.currentTarget.dataset.uid;
    wx.navigateTo({
      url: "/pages/workerdetail/index?uid=" + uid
    })
  },

  // ========== 原有筛选模式方法 ==========

  toReastJob:function() {

    var that = this;

    that.data.cateid = 0;
    that.data.priceid = 0;
    that.data.edu = '';
    that.data.express = '';
    that.data.sex = -1;
    that.data.special = '';


    that.setData({
      cateid:that.data.cateid,
      priceid:that.data.priceid,
      edu:that.data.edu,
      express:that.data.express,
      sex:that.data.sex,
      special:that.data.special,
      jobcount:0
    });

  },
  getjoblist:function() {

    var that = this;

    var params = {cateid:that.data.cateid,priceid:that.data.priceid,eduid:that.data.edu,express:that.data.express,sex:that.data.sex,special:that.data.special};

    note.getNoteListCount((data) => {


      that.setData({

        notecount:data.notecount

      });

  },params);

  },

  tabSelect(e) {
    this.setData({
      TabCur: e.currentTarget.dataset.id,
      MainCur: e.currentTarget.dataset.id,
      VerticalNavTop: (e.currentTarget.dataset.id - 1) * 50
    })
  },

  toCateType:function(e) {
    var that = this;
    var cateid = e.currentTarget.dataset.id;
    if(that.data.cateid == cateid)
    {
      cateid = 0;
    }
    that.data.cateid = cateid;

    that.setData({
      cateid:cateid
    })
    that.getjoblist();
  },

  toPriceType:function(e) {
    var that = this;
    var priceid = e.currentTarget.dataset.id;
    if(that.data.priceid == priceid)
    {
      priceid = 0;
    }
    that.data.priceid = priceid;
    that.setData({
      priceid:priceid

    })
    that.getjoblist();
  },

  toEdu:function(e) {
    var that = this;

    var edu = e.currentTarget.dataset.id;
    if(that.data.edu == edu)
    {
      edu = '';
    }

    that.data.edu = edu;
    that.setData({
      edu:edu
    })
    that.getjoblist();
  },


  toExpress:function(e) {
    var that = this;
    var express = e.currentTarget.dataset.id;
    if(that.data.express == express)
    {
      express = '';
    }
    that.data.express = express;
    that.setData({
      express:express
    })
    that.getjoblist();
  },

  toSex:function(e) {
    var that =this;
    var sex = e.currentTarget.dataset.id;
    if(that.data.sex == sex)
    {
      sex = '';
    }
    that.data.sex = sex;
    that.setData({
      sex:sex
    })
    that.getjoblist();
  },

  toSpecial:function(e) {
    var that = this;
    var special = e.currentTarget.dataset.id;
    if(that.data.special == special)
    {
      special = '';
    }
    that.data.special = special;
    that.setData({
      special:special
    })
    that.getjoblist();
  },

  toSearchJob:function() {
    var that = this;

    var cateid = that.data.cateid ;
    var priceid = that.data.priceid ;
    var edu = that.data.edu ;
    var express = that.data.express ;
    var sex = that.data.sex ;
    var special = that.data.special ;

    wx.navigateTo({
      url: "/pages/searchnote/index?cateid=" + cateid+"&priceid="+priceid+"&edu="+edu+"&express="+express+"&sex="+sex+"&special="+special
     })
  },

  VerticalMain(e) {
    let that = this;
    let list = this.data.list;
    let tabHeight = 0;
    if (this.data.load) {
      for (let i = 0; i < list.length; i++) {
        let view = wx.createSelectorQuery().select("#main-" + list[i].id);
        view.fields({
          size: true
        }, data => {
          list[i].top = tabHeight;
          tabHeight = tabHeight + data.height;
          list[i].bottom = tabHeight;
        }).exec();
      }
      that.setData({
        load: false,
        list: list
      })
    }
    let scrollTop = e.detail.scrollTop + 20;
    for (let i = 0; i < list.length; i++) {
      if (scrollTop > list[i].top && scrollTop < list[i].bottom) {
        that.setData({
          VerticalNavTop: (list[i].id - 1) * 50,
          TabCur: list[i].id
        })
        return false
      }
    }
  },

  onPullDownRefresh: function () {
    if (this.data.jobid) {
      wx.showNavigationBarLoading();
      this.loadApplicants();
    }
  },

  onReachBottom: function () {
    if (this.data.jobid) {
      this.data.page = this.data.page + 1;
      this.loadApplicants();
    }
  }
})
