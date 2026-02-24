import { Findjob } from 'findjob-model.js';
var findjob = new Findjob(); //实例化 首页 对象
Page({

  /**
   * 页面的初始数据
   */
  data: {
    city: wx.getStorageSync('companyinfo').city,
    loadMore: '',

    page: 1,
    isArea: true,
    areaid: 0,
    areatitle: '',
    jobln: 0,

    // 新筛选项
    isCateNew: true,
    cateKeyword: '',
    cateNewTitle: '',

    isSettle: true,
    settleType: 0,
    settleTitle: '',

    isSex: true,
    sexFilter: -1,
    sexTitle: '',

    isSort: true,
    sortType: 'new',
    sortTitle: '',

    searchKeyword: '',

    userRole: 0,

    categoryList: [
      { name: '餐饮服务', icon: '../../imgs/category/1餐厅服务.png' },
      { name: '活动执行', icon: '../../imgs/category/2活动执行.png' },
      { name: '安保检票', icon: '../../imgs/category/3安保检票.png' },
      { name: '教育辅导', icon: '../../imgs/category/4教育辅导.png' },
      { name: '影视艺术', icon: '../../imgs/category/5群众演员.png' },
      { name: '临时促销', icon: '../../imgs/category/6临时促销.png' },
      { name: '电商推广', icon: '../../imgs/category/7电商推广.png' },
      { name: '问卷调查', icon: '../../imgs/category/8问卷调查.png' },
      { name: '物流配送', icon: '../../imgs/category/9物流配送.png' },
      { name: '装卸搬运', icon: '../../imgs/category/10装卸搬运.png' },
      { name: '家政护理', icon: '../../imgs/category/11家政服务.png' },
      { name: '流水线工', icon: '../../imgs/category/12流水线工.png' },
      { name: '手工制作', icon: '../../imgs/category/13手工制作.png' },
      { name: '内容创作', icon: '../../imgs/category/14内容创作.png' },
      { name: '校园专属', icon: '../../imgs/category/15校园专属.png' }
    ]
  },

  onLoad: function (options) {
    if (options && options.keyword) {
      this.setData({
        cateKeyword: options.keyword,
        cateNewTitle: options.keyword,
        searchKeyword: options.keyword
      });
    }
  },

  toIndex: function () {
    wx.redirectTo({
      url: '/pages/index/index',
    })
  },

  toFindjob: function (e) {
    wx.redirectTo({
      url: "/pages/findjob/index"
    })
  },

  toMyinvate: function (e) {
    wx.redirectTo({
      url: "/pages/switchrole/index"
    })
  },

  toSysmsg: function () {
    wx.redirectTo({
      url: '/pages/sysmsg/index',
    })
  },

  toMyuser: function () {
    wx.redirectTo({
      url: '/pages/user/index',
    })
  },

  // ========== 筛选开关方法 ==========

  closeAllDropdown: function () {
    this.setData({
      isArea: true,
      isCateNew: true,
      isSettle: true,
      isSex: true,
      isSort: true
    });
  },

  toSelectArea: function () {
    var that = this;
    that.setData({
      isArea: !that.data.isArea,
      isCateNew: true,
      isSettle: true,
      isSex: true,
      isSort: true
    });
  },

  toSelectCateNew: function () {
    var that = this;
    that.setData({
      isCateNew: !that.data.isCateNew,
      isArea: true,
      isSettle: true,
      isSex: true,
      isSort: true
    });
  },

  toSelectSettle: function () {
    var that = this;
    that.setData({
      isSettle: !that.data.isSettle,
      isArea: true,
      isCateNew: true,
      isSex: true,
      isSort: true
    });
  },

  toSelectSex: function () {
    var that = this;
    that.setData({
      isSex: !that.data.isSex,
      isArea: true,
      isCateNew: true,
      isSettle: true,
      isSort: true
    });
  },

  toSelectSort: function () {
    var that = this;
    that.setData({
      isSort: !that.data.isSort,
      isArea: true,
      isCateNew: true,
      isSettle: true,
      isSex: true
    });
  },

  // ========== 筛选选择方法 ==========

  SelectAreaItem: function (e) {
    var that = this;
    var areaid = e.currentTarget.dataset.id;
    var title = e.currentTarget.dataset.title;
    that.setData({
      areaid: areaid,
      areatitle: areaid == 0 ? '' : title,
      isArea: true
    });
    that.data.page = 1;
    that.getjoblist();
  },

  SelectCateNewItem: function (e) {
    var that = this;
    var name = e.currentTarget.dataset.name;
    that.setData({
      cateKeyword: name,
      cateNewTitle: name || '',
      isCateNew: true
    });
    that.data.page = 1;
    that.getjoblist();
  },

  SelectSettleItem: function (e) {
    var that = this;
    var id = parseInt(e.currentTarget.dataset.id);
    var title = e.currentTarget.dataset.title;
    that.setData({
      settleType: id,
      settleTitle: id == 0 ? '' : title,
      isSettle: true
    });
    that.data.page = 1;
    that.getjoblist();
  },

  SelectSexItem: function (e) {
    var that = this;
    var id = parseInt(e.currentTarget.dataset.id);
    var title = e.currentTarget.dataset.title;
    that.setData({
      sexFilter: id,
      sexTitle: id == -1 ? '' : title,
      isSex: true
    });
    that.data.page = 1;
    that.getjoblist();
  },

  SelectSortItem: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var title = e.currentTarget.dataset.title;
    that.setData({
      sortType: id,
      sortTitle: id == 'new' ? '' : title,
      isSort: true
    });
    that.data.page = 1;
    that.getjoblist();
  },

  toSelectJob: function () {
    wx.navigateTo({
      url: "/pages/selectjob/index"
    })
  },

  onSearchInput: function (e) {
    this.setData({ searchKeyword: e.detail.value });
  },

  doSearch: function () {
    var keyword = this.data.searchKeyword.trim();
    this.setData({
      cateKeyword: keyword,
      cateNewTitle: keyword || ''
    });
    this.data.page = 1;
    this.getjoblist();
  },

  toApplyJob: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/zwjobdetail/index?id=" + id
    })
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function (options) {
    var that = this;

    var checkFlag = wx.getStorageSync('checkFlag');
    that.setData({ checkFlag: checkFlag || 0, userRole: parseInt(wx.getStorageSync('user_role') || 0) });
    wx.setNavigationBarTitle({
      title: checkFlag == 1 ? '找工作' : '找活',
    })

    wx.showShareMenu({
      withShareTicket: true,
      menus: ['shareAppMessage', 'shareTimeline']
    })

    var cityinfo = wx.getStorageSync('cityinfo');

    if (cityinfo) {
      wx.setStorageSync('city', cityinfo.name);
      that.setData({
        city: wx.getStorageSync('cityinfo').name
      })
    }

    that.data.page = 1;
    that.getjoblist(true);
  },

  getjoblist: function (isInit) {
    var that = this;

    var cityid = wx.getStorageSync('cityinfo').id;
    var latitude = wx.getStorageSync('latitude');
    var longitude = wx.getStorageSync('longitude');

    var params = {
      cityid: cityid,
      page: that.data.page,
      areaid: that.data.areaid,
      latitude: latitude,
      longitude: longitude,
      settle_type: that.data.settleType,
      sex: that.data.sexFilter,
      sortby: that.data.sortType,
      keyword: that.data.cateKeyword
    };

    findjob.getJobListData((data) => {
      var setObj = {
        joblist: data.joblist,
        jobln: data.joblist ? data.joblist.length : 0
      };

      if (isInit) {
        setObj.arealist = data.arealist;
      }

      that.setData(setObj);

      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    }, params);
  },

  toJobDetail: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.navigateTo({
      url: "/pages/zwjobdetail/index?id=" + id
    })
  },

  onReady: function () {
  },

  onHide: function () {
  },

  onUnload: function () {
  },

  onPullDownRefresh: function () {
    wx.showNavigationBarLoading();
    this.onShow();
  },

  onReachBottom: function () {
    this.data.page = this.data.page + 1;
    this.getjoblist();
  },

  onShareAppMessage: function () {
    return {
      title: wx.getStorageSync('checkFlag') == 1 ? '找工作' : '找活',
      path: '/pages/findjob/index'
    }
  }
})
