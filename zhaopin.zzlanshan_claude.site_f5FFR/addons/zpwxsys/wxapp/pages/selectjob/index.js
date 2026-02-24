import { Job } from '../../model/job-model.js';
var job  = new Job();

Page({
  data: {
 
    TabCur: 1,
    MainCur: 0,
    VerticalNavTop: 0,
    list: [],
    load: true,
    cateid:0,
    priceid:0,
    edu:'',
    express:'',
    sex:-1,
    special:'',

  },
  onLoad() {
    var that = this;
    wx.setNavigationBarTitle({
      title: '职位筛选',
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
      jobcount:0
    })


  },
  onReady() {
    wx.hideLoading()
  },
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
  //  that.getjoblist();

  },
  getjoblist:function() {

    var that = this;


    var cityid = wx.getStorageSync('cityinfo').id;

    var params = {cityid:cityid,cateid:that.data.cateid,priceid:that.data.priceid,edu:that.data.edu,express:that.data.express,sex:that.data.sex,special:that.data.special};

    job.getJobListCount((data) => {

   
      that.setData({

        jobcount:data.jobcount

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
    console.log(edu);
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
    console.log(express);
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
    console.log(sex);
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
      url: "/pages/searchjob/index?cateid=" + cateid+"&priceid="+priceid+"&edu="+edu+"&express="+express+"&sex="+sex+"&special="+special
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
  }
})